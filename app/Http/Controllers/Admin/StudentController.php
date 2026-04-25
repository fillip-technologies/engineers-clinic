<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\College;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user', 'college')->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $users = User::all();
        $colleges = College::all();
        return view('admin.students.create', compact('users', 'colleges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'college_id' => 'required|exists:colleges,id',
            'course_name' => 'nullable|string|max:255',
        ]);

        Student::create($request->all());

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function show(Student $student)
    {
        $student->load('user', 'college', 'enrollments', 'quizResults', 'certificates');
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $users = User::all();
        $colleges = College::all();
        return view('admin.students.edit', compact('student', 'users', 'colleges'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'college_id' => 'required|exists:colleges,id',
            'course_name' => 'nullable|string|max:255',
        ]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }
}
