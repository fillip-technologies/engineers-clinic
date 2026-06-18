<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Student;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function createOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $user = $request->user();
        $student = Student::where('user_id', $user->id)->firstOrFail();
        $course = Course::findOrFail($validated['course_id']);

        abort_if((float) $course->fee <= 0, 422, 'This course does not require payment.');

        $alreadyPaid = Payment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->exists();

        abort_if($alreadyPaid, 422, 'Payment already completed.');

        $receipt = 'ec_' . $course->id . '_' . $student->id . '_' . Str::lower(Str::random(8));
        $razorpayOrder = $razorpay->createOrder((float) $course->fee, $receipt, [
            'course_id' => (string) $course->id,
            'student_id' => (string) $student->id,
        ]);

        $records = DB::transaction(function () use ($course, $student, $receipt, $razorpayOrder, $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->fee,
                'currency' => 'INR',
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder->id,
                'receipt' => $receipt,
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->fee,
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            return compact('order', 'payment');
        });

        return response()->json([
            'order_id' => $razorpayOrder->id,
            'internal_order_id' => $records['order']->id,
            'payment_id' => $records['payment']->id,
            'amount' => $course->fee,
            'currency' => 'INR',
            'key' => config('services.razorpay.key'),
        ]);
    }

    public function verify(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
            'internal_order_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $order = Order::with('student')->findOrFail($validated['internal_order_id']);

        abort_unless($order->user_id === $user->id, 403);

        $razorpay->verifyPaymentSignature([
            'razorpay_order_id' => $validated['razorpay_order_id'],
            'razorpay_payment_id' => $validated['razorpay_payment_id'],
            'razorpay_signature' => $validated['razorpay_signature'],
        ]);

        DB::transaction(function () use ($order, $validated) {
            $order->update(['status' => 'success']);

            Payment::where('order_id', $order->id)->update([
                'status' => 'success',
                'payment_date' => now(),
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature' => $validated['razorpay_signature'],
            ]);

            Enrollment::updateOrCreate(
                ['student_id' => $order->student_id, 'course_id' => $order->course_id],
                ['enrollment_date' => now(), 'status' => 'active', 'sponsor_type' => 'self']
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment verified and enrollment active.',
        ]);
    }
}
