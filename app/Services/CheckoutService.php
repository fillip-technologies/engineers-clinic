<?php

namespace App\Services;

use App\Jobs\SendWelcomeCredentialsEmail;
use App\Models\College;
use App\Models\Course;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Throwable;

class CheckoutService
{
    public function __construct(private RazorpayService $razorpay)
    {
    }

    public function start(Course $course, array $data): array
    {
        abort_unless((int) $data['course_id'] === $course->id, 404);

        $account = $this->resolveStudentAccount($course, $data);
        $student = $account['student'];
        $user = $account['user'];

        $completedPayment = Payment::query()
            ->where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->latest()
            ->first();

        if ($completedPayment || $student->enrollments()->where('course_id', $course->id)->exists()) {
            return [
                'user' => $user,
                'student' => $student,
                'order' => null,
                'payment' => $completedPayment,
                'already_enrolled' => true,
                'new_user' => $account['new_user'],
            ];
        }

        if ((float) $course->fee <= 0) {
            return DB::transaction(function () use ($course, $student, $user, $account) {
                $payment = Payment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'amount' => 0,
                    'status' => 'success',
                    'payment_date' => now(),
                ]);

                $student->enrollments()->updateOrCreate(
                    ['course_id' => $course->id],
                    ['enrollment_date' => now(), 'status' => 'active', 'sponsor_type' => 'self']
                );

                return [
                    'user' => $user,
                    'student' => $student,
                    'order' => null,
                    'payment' => $payment,
                    'already_enrolled' => true,
                    'new_user' => $account['new_user'],
                ];
            });
        }

        $receipt = 'ec_' . $course->id . '_' . $student->id . '_' . Str::lower(Str::random(8));
        $razorpayOrder = $this->razorpay->createOrder((float) $course->fee, $receipt, [
            'course_id' => (string) $course->id,
            'student_id' => (string) $student->id,
            'level' => $data['level'],
        ]);

        return DB::transaction(function () use ($course, $student, $user, $data, $account, $receipt, $razorpayOrder) {
            $order = Order::create([
                'user_id' => $user->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->fee,
                'currency' => 'INR',
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder->id,
                'receipt' => $receipt,
                'notes' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'location' => $data['location'],
                    'college' => $data['college'],
                    'level' => $data['level'],
                ],
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'student_id' => $student->id,
                'course_id' => $course->id,
                'amount' => $course->fee,
                'status' => 'pending',
                'razorpay_order_id' => $razorpayOrder->id,
            ]);

            return [
                'user' => $user,
                'student' => $student,
                'order' => $order,
                'payment' => $payment,
                'already_enrolled' => false,
                'new_user' => $account['new_user'],
            ];
        });
    }

    public function completePaidOrder(Order $order, array $attributes): Payment
    {
        if ($order->razorpay_order_id !== $attributes['razorpay_order_id']) {
            throw ValidationException::withMessages([
                'razorpay_order_id' => 'The payment order does not match this checkout.',
            ]);
        }

        $this->razorpay->verifyPaymentSignature([
            'razorpay_order_id' => $attributes['razorpay_order_id'],
            'razorpay_payment_id' => $attributes['razorpay_payment_id'],
            'razorpay_signature' => $attributes['razorpay_signature'],
        ]);

        return DB::transaction(function () use ($order, $attributes) {
            $order->update([
                'status' => 'paid',
                'razorpay_payment_id' => $attributes['razorpay_payment_id'],
            ]);

            $payment = $order->payment()->firstOrFail();
            $payment->update([
                'status' => 'success',
                'payment_date' => now(),
                'razorpay_payment_id' => $attributes['razorpay_payment_id'],
                'razorpay_signature' => $attributes['razorpay_signature'],
            ]);

            $order->student->enrollments()->updateOrCreate(
                ['course_id' => $order->course_id],
                ['enrollment_date' => now(), 'status' => 'active', 'sponsor_type' => 'self']
            );

            return $payment;
        });
    }

    public function failOrder(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->update(['status' => 'failed']);
            $order->payment?->update(['status' => 'failed']);
        });
    }

    public function createUserAccount(Course $course, array $data): array
    {
        return $this->resolveStudentAccount($course, $data);
    }

    private function resolveStudentAccount(Course $course, array $data): array
    {
        return DB::transaction(function () use ($course, $data) {
            $user = User::with('role', 'student')->where('email', $data['email'])->first();
            $plainPassword = null;
            $newUser = false;

            if (! $user) {
                $plainPassword = Str::password(12);
                $role = Role::firstOrCreate(['name' => 'student']);

                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($plainPassword),
                    'role_id' => $role->id,
                ]);

                $newUser = true;
            }

            $user->loadMissing('role');

            if ($user->role?->name !== 'student') {
                throw ValidationException::withMessages([
                    'email' => 'This email belongs to a non-student account. Please use a student email address.',
                ]);
            }

            $college = College::firstOrCreate(
                ['college_name' => $data['college']],
                [
                    'user_id' => null,
                    'address' => $data['location'],
                    'contact_number' => $data['phone'],
                ]
            );

            $student = Student::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'college_id' => $college->id,
                    'course_name' => $course->title,
                ]
            );

            $student->update([
                'college_id' => $student->college_id ?: $college->id,
                'course_name' => $course->title,
            ]);

            if ($newUser && $plainPassword) {
                try {
                    SendWelcomeCredentialsEmail::dispatchSync($user->id, $plainPassword, 'student');
                } catch (Throwable $exception) {
                    Log::warning('Unable to send checkout welcome credentials email.', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'message' => $exception->getMessage(),
                    ]);
                }
            }

            return [
                'user' => $user->fresh(['role', 'student']),
                'student' => $student->fresh(),
                'new_user' => $newUser,
            ];
        });
    }
}
