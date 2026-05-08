<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(10);
        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:courses|max:255',
            'description' => 'nullable|string',
            'level' => 'required|string|in:Beginner,Intermediate,Advanced',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'hero_badge' => 'nullable|string',
            'career_path' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'program_overview' => 'nullable|json',
            'why_choose' => 'nullable|json',
            'testimonials' => 'nullable|json',
            'faq' => 'nullable|json',
            'curriculum' => 'nullable|json',
            'modules' => 'nullable|json',
            'phases' => 'nullable|json',
            'outcome' => 'nullable|json',
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('enrollments', 'tasks', 'quizzes', 'certificates');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:courses,slug,' . $course->id . '|max:255',
            'description' => 'nullable|string',
            'level' => 'required|string|in:Beginner,Intermediate,Advanced',
            'category' => 'required|string|max:255',
            'image' => 'nullable|string|url',
            'hero_badge' => 'nullable|string',
            'career_path' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'required|numeric|min:0',
            'program_overview' => 'nullable|json',
            'why_choose' => 'nullable|json',
            'testimonials' => 'nullable|json',
            'faq' => 'nullable|json',
            'curriculum' => 'nullable|json',
            'modules' => 'nullable|json',
            'phases' => 'nullable|json',
            'outcome' => 'nullable|json',
        ]);

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}
