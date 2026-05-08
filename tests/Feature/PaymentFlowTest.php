<?php

namespace Tests\Feature;

use App\Models\College;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Role;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Razorpay\Api\Api;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_order_stores_pending_payment()
    {
        $role = Role::create(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $college = College::create([
            'college_name' => 'Test College',
            'address' => '123 Test Lane',
            'contact_number' => '9999999999',
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'college_id' => $college->id,
        ]);

        $course = Course::create([
            'title' => 'Test Course',
            'description' => 'A test course.',
            'duration_months' => 6,
            'fee' => 1000,
        ]);

        $api = new class extends Api {
            public $order;
            public function __construct() {}
        };
        $api->order = new class {
            public function create($orderData)
            {
                return (object) ['id' => 'order_test_id'];
            }
        };

        app()->instance(Api::class, $api);

        $response = $this->actingAs($user)
            ->postJson('/payments/create-order', ['course_id' => $course->id]);

        $response->assertStatus(200)
            ->assertJson([
                'order_id' => 'order_test_id',
                'currency' => 'INR',
                'payment_id' => 1,
            ]);

        $this->assertDatabaseHas('payments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'razorpay_order_id' => 'order_test_id',
        ]);
    }

    public function test_verify_payment_completes_payment_and_creates_enrollment()
    {
        $role = Role::create(['name' => 'student']);
        $user = User::factory()->create(['role_id' => $role->id]);
        $college = College::create([
            'college_name' => 'Verify College',
            'address' => '456 Verify Road',
            'contact_number' => '8888888888',
        ]);
        $student = Student::create([
            'user_id' => $user->id,
            'college_id' => $college->id,
        ]);

        $course = Course::create([
            'title' => 'Verify Course',
            'description' => 'Verify test course.',
            'duration_months' => 3,
            'fee' => 1500,
        ]);

        $payment = Payment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'amount' => 1500,
            'status' => 'pending',
            'payment_date' => now(),
            'razorpay_order_id' => 'order_verify_test',
        ]);

        $api = new class extends Api {
            public $utility;
            public function __construct() {}
        };
        $api->utility = new class {
            public function verifyPaymentSignature($attributes)
            {
                return true;
            }
        };

        app()->instance(Api::class, $api);

        $response = $this->actingAs($user)
            ->postJson('/payments/verify', [
                'razorpay_payment_id' => 'pay_verify_test',
                'razorpay_order_id' => 'order_verify_test',
                'razorpay_signature' => 'signature_verify_test',
                'payment_id' => $payment->id,
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'completed',
            'razorpay_payment_id' => 'pay_verify_test',
        ]);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);
    }
}
