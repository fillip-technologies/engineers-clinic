<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            config('services.razorpay.key'),
            config('services.razorpay.secret')
        );
    }

    /**
     * Create a Razorpay order for course purchase
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $course = Course::findOrFail($request->course_id);

        // Check if already enrolled
        $existingEnrollment = $student->enrollments()->where('course_id', $course->id)->first();
        if ($existingEnrollment) {
            return response()->json(['error' => 'Already enrolled in this course'], 400);
        }

        // Check if payment already exists
        $existingPayment = Payment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return response()->json(['error' => 'Payment already completed for this course'], 400);
        }

        try {
            // Create Razorpay order
            $orderData = [
                'receipt' => 'course_' . $course->id . '_student_' . $student->id,
                'amount' => $course->fee * 100, // Amount in paisa
                'currency' => 'INR',
                'payment_capture' => 1, // Auto capture
            ];

            $razorpayOrder = $this->razorpay->order->create($orderData);

            // Create payment record
            $payment = Payment::create([
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->fee,
                'status' => 'pending',
                'payment_date' => now(),
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            return response()->json([
                'order_id' => $razorpayOrder->id,
                'amount' => $course->fee,
                'currency' => 'INR',
                'key' => config('services.razorpay.key'),
                'payment_id' => $payment->id,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Verify payment and complete enrollment
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'payment_id' => 'required|exists:payments,id',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $payment = Payment::findOrFail($request->payment_id);

        // Verify payment belongs to student
        if ($payment->student_id !== $student->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Verify payment signature
            $attributes = [
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            // Update payment status
            $payment->update([
                'status' => 'completed',
                'payment_date' => now(),
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);

            // Create enrollment
            $student->enrollments()->create([
                'course_id' => $payment->course_id,
                'enrollment_date' => now(),
                'status' => 'active',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment verified and enrollment completed',
            ]);

        } catch (\Exception $e) {
            // Update payment status to failed
            $payment->update(['status' => 'failed']);

            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 400);
        }
    }

    /**
     * Get payment history for student
     */
    public function paymentHistory()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        $payments = $student->payments()->with('course')->get();

        return response()->json($payments);
    }

    /**
     * Get available courses for purchase
     */
    public function availableCourses()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return response()->json(['error' => 'Student not found'], 404);
        }

        // Get courses not enrolled in
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        $courses = Course::whereNotIn('id', $enrolledCourseIds)->get();

        return response()->json($courses);
    }
}
