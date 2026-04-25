<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with('student.user', 'course')->get();
        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('admin.certificates.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'issued_date' => 'required|date',
            'certificate_url' => 'nullable|string',
        ]);

        Certificate::create($request->all());

        return redirect()->route('certificates.index')->with('success', 'Certificate created successfully.');
    }

    public function show(Certificate $certificate)
    {
        $certificate->load('student.user', 'course');
        return view('admin.certificates.show', compact('certificate'));
    }

    public function edit(Certificate $certificate)
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('admin.certificates.edit', compact('certificate', 'students', 'courses'));
    }

    public function update(Request $request, Certificate $certificate)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'issued_date' => 'required|date',
            'certificate_url' => 'nullable|string',
        ]);

        $certificate->update($request->all());

        return redirect()->route('certificates.index')->with('success', 'Certificate updated successfully.');
    }

    public function destroy(Certificate $certificate)
    {
        $certificate->delete();

        return redirect()->route('certificates.index')->with('success', 'Certificate deleted successfully.');
    }
}
