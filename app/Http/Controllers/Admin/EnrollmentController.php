<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with('student.user', 'course')
            ->latest('enrollment_date')
            ->paginate(15);

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('id')->get();
        $courses = Course::orderBy('title')->get();

        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('enrollments')->where(fn ($query) => $query->where('course_id', $request->course_id)),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'enrollment_date' => ['required', 'date'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:ongoing,completed'],
        ], [
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ]);

        Enrollment::create($validated);

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment created successfully.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load('student.user', 'course');
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $students = Student::with('user')->orderBy('id')->get();
        $courses = Course::orderBy('title')->get();

        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    public function update(Request $request, Enrollment $enrollment)
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:students,id',
                Rule::unique('enrollments')
                    ->ignore($enrollment->id)
                    ->where(fn ($query) => $query->where('course_id', $request->course_id)),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'enrollment_date' => ['required', 'date'],
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', 'in:ongoing,completed'],
        ], [
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ]);

        $enrollment->update($validated);

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment updated successfully.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')->with('success', 'Enrollment deleted successfully.');
    }
}
