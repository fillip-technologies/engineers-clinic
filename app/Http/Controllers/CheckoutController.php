<?php

namespace App\Http\Controllers;

use App\Http\Requests\Checkout\StartCheckoutRequest;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutController extends Controller
{
    public function start(StartCheckoutRequest $request, Course $course, CheckoutService $checkout): RedirectResponse
    {
        $selectedCourses = array_filter((array) $request->input('selected_courses', []));

        // If projects were selected in the enrollment form, create/resolve the account
        // without creating a course-specific Razorpay order, then hand off to internship checkout.
        if (!empty($selectedCourses)) {
            try {
                $account = $checkout->createUserAccount($course, $request->validated());
            } catch (ValidationException $exception) {
                throw $exception;
            } catch (Throwable $exception) {
                Log::error('Unable to create user account for internship checkout.', [
                    'message' => $exception->getMessage(),
                ]);
                return back()->withInput()->with('error', 'Unable to start checkout right now. Please try again in a moment.');
            }

            Auth::login($account['user']);
            $request->session()->regenerate();
            $request->session()->put('enrollment_pending_payment', [
                'level'                => $request->input('level'),
                'stream'               => $request->input('stream'),
                'selected_courses'     => $selectedCourses,
                'selected_project_nos' => array_filter((array) $request->input('selected_project_nos', [])),
                'course_title'         => $course->title,
            ]);

            return redirect()->route('enrollment.payment');
        }

        try {
            $result = $checkout->start($course, $request->validated());
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            Log::error('Unable to start course checkout.', [
                'course_id' => $course->id,
                'message' => $exception->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Unable to start checkout right now. Please try again in a moment.');
        }

        Auth::login($result['user']);
        $request->session()->regenerate();

        $request->session()->put('checkout_intent', [
            ...$request->validated(),
            'course_slug' => $course->slug,
            'new_user' => $result['new_user'],
        ]);

        if ($result['already_enrolled']) {
            return redirect()
                ->route('dashboard.enrolled-courses')
                ->with('success', 'Your enrollment is ready.');
        }

        return redirect()->route('payments.checkout', [
            'course' => $course->slug,
            'order' => $result['order']->id,
        ]);
    }

    public function enrollmentPayment(\Illuminate\Http\Request $request): mixed
    {
        $pending = $request->session()->get('enrollment_pending_payment');

        if (empty($pending) || empty($pending['selected_courses'])) {
            return redirect('/')->with('error', 'No pending enrollment found. Please fill the form again.');
        }

        $level = $pending['level'] ?? 'Beginner';
        $fees  = ['Beginner' => 4999, 'Intermediate' => 7999, 'Advanced' => 12999];

        return view('payments.enrollment-payment', [
            'pending'      => $pending,
            'level'        => $level,
            'amount'       => $fees[$level] ?? 4999,
            'razorpayKey'  => config('services.razorpay.key'),
            'startUrl'     => route('student.internship.checkout.start'),
            'verifyUrl'    => route('student.internship.checkout.verify'),
            'dashboardUrl' => route('dashboard'),
        ]);
    }

    public function show(Course $course, ?Order $order = null)
    {
        $student = Auth::user()?->student;

        abort_unless($student, 403, 'A student account is required for checkout.');

        if ($order) {
            abort_unless($order->student_id === $student->id && $order->course_id === $course->id, 403);
        }

        $completedPayment = Payment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        $existingEnrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->first();

        return view('payments.checkout', [
            'course' => $course,
            'student' => $student,
            'order' => $order,
            'checkoutIntent' => session('checkout_intent', []),
            'existingEnrollment' => $existingEnrollment,
            'hasCompletedEnrollment' => (bool) $existingEnrollment,
            'completedPayment' => $completedPayment,
            'razorpayKey' => config('services.razorpay.key'),
        ]);
    }
}
