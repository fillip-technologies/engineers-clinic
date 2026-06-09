<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentTask;
use App\Models\Student;
use App\Models\Task;
use Illuminate\Http\Request;

class StudentTaskController extends Controller
{
    public function index()
    {
        $studentTasks = StudentTask::with('student.user', 'task')->get();
        return view('Admin.student_tasks.index', compact('studentTasks'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $tasks = Task::all();
        return view('Admin.student_tasks.create', compact('students', 'tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:pending,completed',
        ]);

        StudentTask::create($request->all());

        return redirect()->route('student-tasks.index')->with('success', 'Student task created successfully.');
    }

    public function show(StudentTask $studentTask)
    {
        $studentTask->load('student.user', 'task');
        return view('Admin.student_tasks.show', compact('studentTask'));
    }

    public function edit(StudentTask $studentTask)
    {
        $students = Student::with('user')->get();
        $tasks = Task::all();
        return view('Admin.student_tasks.edit', compact('studentTask', 'students', 'tasks'));
    }

    public function update(Request $request, StudentTask $studentTask)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'task_id' => 'required|exists:tasks,id',
            'status' => 'required|in:pending,completed',
        ]);

        $studentTask->update($request->all());

        return redirect()->route('student-tasks.index')->with('success', 'Student task updated successfully.');
    }

    public function destroy(StudentTask $studentTask)
    {
        $studentTask->delete();

        return redirect()->route('student-tasks.index')->with('success', 'Student task deleted successfully.');
    }
}
