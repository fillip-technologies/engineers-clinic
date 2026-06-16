<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with('student.user', 'course')->get();
        return view('Admin.payments.index', compact('payments'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('Admin.payments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed',
        ]);

        Payment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully.');
    }

    public function show(Payment $payment)
    {
        $payment->load('student.user', 'course');
        return view('Admin.payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('Admin.payments.edit', compact('payment', 'students', 'courses'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment->update($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }
}
