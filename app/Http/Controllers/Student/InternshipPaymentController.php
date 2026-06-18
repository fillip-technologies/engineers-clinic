<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\InternshipPayment;
use App\Models\Student;
use App\Services\RazorpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class InternshipPaymentController extends Controller
{
    // Fees per level (in INR). Adjust as needed.
    protected const LEVEL_FEES = [
        'Beginner'     => 4999,
        'Intermediate' => 7999,
        'Advanced'     => 12999,
    ];

    public function show(): mixed
    {
        $student = $this->currentStudent();

        if (! $student->level) {
            return redirect()->route('dashboard.student.profile')
                ->with('error', 'Set your internship level before proceeding to payment.');
        }

        if ($student->internship_paid) {
            return redirect()->route('student.projects')
                ->with('success', 'Your internship is already unlocked. Browse and select your projects.');
        }

        $level   = $student->level;
        $amount  = self::LEVEL_FEES[$level] ?? 4999;

        return view('dashboard.student-dashboard.internship.pay', [
            'student'     => $student,
            'level'       => $level,
            'amount'      => $amount,
            'razorpayKey' => config('services.razorpay.key'),
            'createOrderUrl' => route('student.internship.pay.order'),
            'verifyUrl'      => route('student.internship.pay.verify'),
            'successUrl'     => route('student.internship.pay.success'),
            'cancelUrl'      => route('student.projects'),
        ]);
    }

    public function createOrder(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $student = $this->currentStudent();

        if ($student->internship_paid) {
            return response()->json(['message' => 'Internship already paid.'], 422);
        }

        if (! $student->level) {
            return response()->json(['message' => 'Set your internship level first.'], 422);
        }

        $level  = $student->level;
        $amount = (float) (self::LEVEL_FEES[$level] ?? 4999);

        $receipt = 'inp_' . $student->id . '_' . Str::lower(Str::random(8));

        try {
            $razorpayOrder = $razorpay->createOrder($amount, $receipt, [
                'type'       => 'internship_access',
                'level'      => $level,
                'student_id' => (string) $student->id,
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Could not create payment order. Please try again.'], 500);
        }

        $internshipPayment = InternshipPayment::create([
            'student_id'       => $student->id,
            'level'            => $level,
            'amount'           => $amount,
            'status'           => 'pending',
            'razorpay_order_id' => $razorpayOrder->id,
            'receipt'          => $receipt,
        ]);

        return response()->json([
            'order_id'           => $razorpayOrder->id,
            'internship_payment_id' => $internshipPayment->id,
            'amount'             => $amount,
            'amount_paise'       => (int) round($amount * 100),
            'currency'           => 'INR',
            'key'                => config('services.razorpay.key'),
            'level'              => $level,
        ]);
    }

    public function verify(Request $request, RazorpayService $razorpay): JsonResponse
    {
        $validated = $request->validate([
            'internship_payment_id' => ['required', 'integer'],
            'razorpay_order_id'     => ['required', 'string'],
            'razorpay_payment_id'   => ['required', 'string'],
            'razorpay_signature'    => ['required', 'string'],
        ]);

        $student = $this->currentStudent();

        $internshipPayment = InternshipPayment::where('student_id', $student->id)
            ->where('id', $validated['internship_payment_id'])
            ->where('status', 'pending')
            ->firstOrFail();

        try {
            $razorpay->verifyPaymentSignature([
                'razorpay_order_id'   => $validated['razorpay_order_id'],
                'razorpay_payment_id' => $validated['razorpay_payment_id'],
                'razorpay_signature'  => $validated['razorpay_signature'],
            ]);
        } catch (Throwable $e) {
            $internshipPayment->update(['status' => 'failed']);
            return response()->json(['message' => 'Payment verification failed. Contact support if money was debited.'], 422);
        }

        DB::transaction(function () use ($internshipPayment, $validated, $student) {
            $internshipPayment->update([
                'status'               => 'success',
                'razorpay_payment_id'  => $validated['razorpay_payment_id'],
                'razorpay_signature'   => $validated['razorpay_signature'],
                'paid_at'              => now(),
            ]);

            $student->update(['internship_paid' => true]);
        });

        return response()->json([
            'success'      => true,
            'message'      => 'Payment successful. Your internship is now unlocked.',
            'redirect_url' => route('student.internship.pay.success'),
        ]);
    }

    public function success(): RedirectResponse
    {
        return redirect()->route('student.projects')
            ->with('success', 'Payment successful! Your internship is unlocked. Now select up to 3 projects to start building.');
    }

    private function currentStudent(): Student
    {
        $student = Auth::user()?->student;
        abort_unless($student, 403, 'Student account required.');
        return $student;
    }
}
