<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendances = Attendance::with('student.user', 'course')->get();
        return view('Admin.attendances.index', compact('attendances'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('Admin.attendances.create', compact('students', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        Attendance::create($request->all());

        return redirect()->route('admin.attendances.index')->with('success', 'Attendance created successfully.');
    }

    public function show(Attendance $attendance)
    {
        $attendance->load('student.user', 'course');
        return view('Admin.attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        $students = Student::with('user')->get();
        $courses = Course::all();
        return view('Admin.attendances.edit', compact('attendance', 'students', 'courses'));
    }

    public function update(Request $request, Attendance $attendance)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent',
        ]);

        $attendance->update($request->all());

        return redirect()->route('admin.attendances.index')->with('success', 'Attendance updated successfully.');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()->route('admin.attendances.index')->with('success', 'Attendance deleted successfully.');
    }
}
