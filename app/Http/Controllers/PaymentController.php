<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Course;
use App\Models\Student;
use App\Models\College;
use App\Models\Role;
use App\Models\User;
use App\Services\OnboardingMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct(Api $razorpay = null)
    {
        $this->razorpay = $razorpay;
    }

    public function startCheckout(Request $request, Course $course)
    {
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'location' => ['required', 'string', 'max:255'],
            'college' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
        ]);

        abort_unless((int) $validated['course_id'] === $course->id, 404);

        $checkoutIntent = [
            ...$validated,
            'course_slug' => $course->slug,
        ];

        session(['checkout_intent' => $checkoutIntent]);

        if (! Auth::check()) {
            $user = User::where('email', $validated['email'])->first();

            if ($user) {
                $request->session()->put('url.intended', route('payments.checkout', ['course' => $course->slug]));

                return redirect()
                    ->route('login')
                    ->with('error', 'An account already exists with this email. Please log in to continue your purchase.');
            }

            $plainPassword = Str::password(12);
            $role = Role::where('name', 'student')->firstOrFail();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($plainPassword),
                'role_id' => $role->id,
            ]);

            $college = College::firstOrCreate(
                ['college_name' => $validated['college']],
                [
                    'user_id' => null,
                    'address' => $validated['location'],
                    'contact_number' => $validated['phone'],
                ]
            );

            $user->student()->create([
                'college_id' => $college->id,
                'course_name' => $course->title,
            ]);

            app(OnboardingMailer::class)->send($user, $plainPassword, 'student');

            Auth::login($user);
            $request->session()->regenerate();
            session(['checkout_intent' => $checkoutIntent]);
        } elseif (optional(Auth::user()->role)->name !== 'student') {
            return redirect()
                ->route('course.detail', $course->slug)
                ->with('error', 'Please use a student account to purchase this course.');
        }

        return redirect()->route('payments.checkout', ['course' => $course->slug]);
    }

    public function checkout(Course $course)
    {
        if (! Auth::check()) {
            return redirect()->guest(route('login'));
        }

        $student = $this->currentStudent();
        $existingEnrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->first();

        $completedPayment = Payment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->latest()
            ->first();
        $hasCompletedEnrollment = (bool) $existingEnrollment
            && ((bool) $completedPayment || (float) $course->fee <= 0);

        return view('payments.checkout', [
            'course' => $course,
            'student' => $student,
            'checkoutIntent' => session('checkout_intent', []),
            'existingEnrollment' => $existingEnrollment,
            'hasCompletedEnrollment' => $hasCompletedEnrollment,
            'completedPayment' => $completedPayment,
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }

    /**
     * Create a Razorpay order for course purchase
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = $this->currentStudent();

        $course = Course::findOrFail($request->course_id);

        // Check if payment already exists
        $existingPayment = Payment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return response()->json(['error' => 'Payment already completed for this course'], 400);
        }

        try {
            if ((float) $course->fee <= 0) {
                return response()->json(['error' => 'This course does not require payment.'], 400);
            }

            // Create Razorpay order
            $orderData = [
                'receipt' => 'course_' . $course->id . '_student_' . $student->id,
                'amount' => (int) round((float) $course->fee * 100), // Amount in paisa
                'currency' => 'INR',
                'payment_capture' => 1, // Auto capture
            ];

            $razorpayOrder = $this->razorpay()->order->create($orderData);

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

        $student = $this->currentStudent();

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

            $this->razorpay()->utility->verifyPaymentSignature($attributes);

            // Update payment status
            $payment->update([
                'status' => 'completed',
                'payment_date' => now(),
                'razorpay_payment_id' => $request->razorpay_payment_id,
            ]);

            // Create enrollment
            $student->enrollments()->updateOrCreate(
                ['course_id' => $payment->course_id],
                [
                    'enrollment_date' => now(),
                    'status' => 'ongoing',
                ]
            );

            session()->forget('checkout_intent');

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
        $student = $this->currentStudent();

        $payments = $student->payments()->with('course')->get();

        return response()->json($payments);
    }

    /**
     * Get available courses for purchase
     */
    public function availableCourses()
    {
        $student = $this->currentStudent();

        // Get courses not enrolled in
        $enrolledCourseIds = $student->enrollments()->pluck('course_id');
        $courses = Course::whereNotIn('id', $enrolledCourseIds)->get();

        return response()->json($courses);
    }

    public function completeFreeEnrollment(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $student = $this->currentStudent();
        $course = Course::findOrFail($request->course_id);

        if ((float) $course->fee > 0) {
            return response()->json(['error' => 'This course requires payment.'], 400);
        }

        $payment = Payment::updateOrCreate(
            [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'status' => 'completed',
            ],
            [
                'amount' => 0,
                'payment_date' => now(),
            ]
        );

        $student->enrollments()->updateOrCreate(
            ['course_id' => $course->id],
            [
                'enrollment_date' => now(),
                'status' => 'ongoing',
            ]
        );

        session()->forget('checkout_intent');

        return response()->json([
            'success' => true,
            'payment_id' => $payment->id,
            'message' => 'Enrollment completed.',
        ]);
    }

    protected function currentStudent(): Student
    {
        $user = Auth::user();

        abort_unless($user, 401);
        abort_unless(optional($user->role)->name === 'student', 403, 'Only student accounts can enroll in courses.');

        $student = $user->student()->first();

        if ($student) {
            return $student;
        }

        $intent = session('checkout_intent', []);
        $collegeName = trim($intent['college'] ?? '') ?: 'Direct Enrollment';
        $college = College::firstOrCreate(
            ['college_name' => $collegeName],
            [
                'user_id' => null,
                'address' => $intent['location'] ?? null,
                'contact_number' => $intent['phone'] ?? null,
            ]
        );

        return $user->student()->create([
            'college_id' => $college->id,
            'course_name' => null,
        ]);
    }

    protected function razorpay(): Api
    {
        if ($this->razorpay) {
            return $this->razorpay;
        }

        $key = config('services.razorpay.key');
        $secret = config('services.razorpay.secret');

        if (blank($key) || blank($secret)) {
            throw ValidationException::withMessages([
                'razorpay' => 'Razorpay credentials are not configured.',
            ]);
        }

        return $this->razorpay = new Api($key, $secret);
    }
}
