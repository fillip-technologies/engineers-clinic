<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\VerifyPaymentRequest;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Student;
use App\Services\CheckoutService;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class PaymentController extends Controller
{
    public function createOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        $student = $this->currentStudent();
        $course = Course::findOrFail($validated['course_id']);

        if ((float) $course->fee <= 0) {
            return response()->json(['message' => 'This course does not require payment.'], 422);
        }

        $completedPayment = Payment::query()
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->exists();

        if ($completedPayment) {
            return response()->json(['message' => 'Payment already completed for this course.'], 422);
        }

        $receipt = 'ec_' . $course->id . '_' . $student->id . '_' . Str::lower(Str::random(8));
        $razorpayOrder = $razorpay->createOrder((float) $course->fee, $receipt, [
            'course_id' => (string) $course->id,
            'student_id' => (string) $student->id,
        ]);

        $records = DB::transaction(function () use ($course, $student, $receipt, $razorpayOrder) {
            $order = Order::create([
                'user_id' => Auth::id(),
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

    public function verify(VerifyPaymentRequest $request, CheckoutService $checkout): JsonResponse
    {
        if ($request->filled('payment_id')) {
            return $this->verifyLegacyPayment($request);
        }

        $order = Order::with('student.user', 'payment')->findOrFail($request->integer('order_id'));

        abort_unless(Auth::id() === $order->user_id, 403);

        try {
            $checkout->completePaidOrder($order, $request->validated());
            session()->forget('checkout_intent');

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and enrollment completed.',
                'redirect_url' => route('payments.success', ['order' => $order->id]),
            ]);
        } catch (Throwable $exception) {
            $checkout->failOrder($order);

            return response()->json([
                'success' => false,
                'message' => 'Payment verification failed. Please contact support if money was debited.',
            ], 422);
        }
    }

    public function success(Order $order): RedirectResponse
    {
        abort_unless(Auth::id() === $order->user_id, 403);

        return redirect()
            ->route('dashboard.enrolled-courses')
            ->with('success', 'Payment successful. Your course access is active.');
    }

    public function failure(Order $order): RedirectResponse
    {
        abort_unless(Auth::id() === $order->user_id, 403);

        return redirect()
            ->route('course.detail', $order->course->slug)
            ->with('error', 'Payment was not completed. You can try again anytime.');
    }

    public function paymentHistory(): JsonResponse
    {
        $payments = $this->currentStudent()
            ->payments()
            ->with('course')
            ->latest('payment_date')
            ->get();

        return response()->json($payments);
    }

    public function availableCourses(Request $request)
    {
        $student = $this->currentStudent();
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        $courses = Course::whereNotIn('id', $enrolledCourseIds)->orderBy('title')->get();

        if ($request->expectsJson()) {
            return response()->json($courses);
        }

        return view('dashboard.student-dashboard.available-courses', [
            'courses' => $courses->map(fn (Course $c) => [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'level' => $c->level,
                'category' => $c->category,
                'fee' => $c->fee ? 'Rs. ' . number_format((float) $c->fee, 2) : 'Free',
                'slug' => $c->slug,
                'checkout_url' => route('payments.checkout', ['course' => $c->slug]),
                'is_free' => (float) ($c->fee ?? 0) <= 0,
            ])->toArray(),
        ]);
    }

    public function completeFreeEnrollment(Request $request): JsonResponse
    {
        $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Free enrollments are completed during checkout start.',
            'redirect_url' => route('dashboard.enrolled-courses'),
        ]);
    }

    private function currentStudent(): Student
    {
        $student = Auth::user()?->student;

        abort_unless($student, 403, 'Only student accounts can access payments.');

        return $student;
    }

    private function verifyLegacyPayment(VerifyPaymentRequest $request): JsonResponse
    {
        $student = $this->currentStudent();
        $payment = Payment::where('student_id', $student->id)->findOrFail($request->integer('payment_id'));

        app(RazorpayService::class)->verifyPaymentSignature([
            'razorpay_order_id' => $request->string('razorpay_order_id')->toString(),
            'razorpay_payment_id' => $request->string('razorpay_payment_id')->toString(),
            'razorpay_signature' => $request->string('razorpay_signature')->toString(),
        ]);

        DB::transaction(function () use ($payment, $request, $student) {
            $payment->update([
                'status' => 'success',
                'payment_date' => now(),
                'razorpay_payment_id' => $request->string('razorpay_payment_id')->toString(),
                'razorpay_signature' => $request->string('razorpay_signature')->toString(),
            ]);

            $student->enrollments()->updateOrCreate(
                ['course_id' => $payment->course_id],
                ['enrollment_date' => now(), 'status' => 'active', 'sponsor_type' => 'self']
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Payment verified and enrollment completed.',
            'redirect_url' => route('dashboard.enrolled-courses'),
        ]);
    }
}
