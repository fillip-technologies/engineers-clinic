<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuizResult;
use App\Models\Student;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    public function index()
    {
        $quizResults = QuizResult::with('student.user', 'quiz')->get();
        return view('admin.quiz_results.index', compact('quizResults'));
    }

    public function create()
    {
        $students = Student::with('user')->get();
        $quizzes = Quiz::all();
        return view('admin.quiz_results.create', compact('students', 'quizzes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|integer|min:0',
            'passed' => 'required|boolean',
        ]);

        QuizResult::create($request->all());

        return redirect()->route('quiz-results.index')->with('success', 'Quiz result created successfully.');
    }

    public function show(QuizResult $quizResult)
    {
        $quizResult->load('student.user', 'quiz');
        return view('admin.quiz_results.show', compact('quizResult'));
    }

    public function edit(QuizResult $quizResult)
    {
        $students = Student::with('user')->get();
        $quizzes = Quiz::all();
        return view('admin.quiz_results.edit', compact('quizResult', 'students', 'quizzes'));
    }

    public function update(Request $request, QuizResult $quizResult)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'quiz_id' => 'required|exists:quizzes,id',
            'score' => 'required|integer|min:0',
            'passed' => 'required|boolean',
        ]);

        $quizResult->update($request->all());

        return redirect()->route('quiz-results.index')->with('success', 'Quiz result updated successfully.');
    }

    public function destroy(QuizResult $quizResult)
    {
        $quizResult->delete();

        return redirect()->route('quiz-results.index')->with('success', 'Quiz result deleted successfully.');
    }
}
