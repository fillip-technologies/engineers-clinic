<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::paginate(10);
        return view('Admin.courses.index', compact('courses'));
    }

    public function create()
    {
        return view('Admin.courses.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCourse($request);
        $validated = $this->normalizeCourseData($validated);
        $validated = $this->decodeJsonFields($validated);

        Course::create($validated);

        return redirect()->route('Admin.courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('enrollments', 'tasks', 'quizzes', 'certificates');
        return view('Admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('Admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $this->validateCourse($request, $course);
        $validated = $this->normalizeCourseData($validated);
        $validated = $this->decodeJsonFields($validated);

        $course->update($validated);

        return redirect()->route('Admin.courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('Admin.courses.index')->with('success', 'Course deleted successfully.');
    }

    private function validateCourse(Request $request, ?Course $course = null): array
    {
        $slugRule = Rule::unique('courses', 'slug');

        if ($course) {
            $slugRule->ignore($course);
        }

        return $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                $slugRule,
            ],
            'description' => 'nullable|string',
            'level' => ['required', 'string', Rule::in(['Beginner', 'Intermediate', 'Advanced'])],
            'category' => 'required|string|max:255',
            'image' => 'nullable|string|max:255',
            'hero_badge' => 'nullable|string',
            'career_path' => 'nullable|string',
            'duration_months' => 'required|integer|min:1',
            'fee' => 'nullable|numeric|min:0',
            'program_overview' => 'nullable|json',
            'why_choose' => 'nullable|json',
            'testimonials' => 'nullable|json',
            'faq' => 'nullable|json',
            'curriculum' => 'nullable|json',
            'modules' => 'nullable|json',
            'phases' => 'nullable|json',
            'outcome' => 'nullable|json',
        ]);
    }

    private function normalizeCourseData(array $validated): array
    {
        $validated['fee'] = $validated['fee'] ?? 0;

        return $validated;
    }

    private function decodeJsonFields(array $validated): array
    {
        foreach ($this->jsonFields() as $field) {
            if (!array_key_exists($field, $validated)) {
                continue;
            }

            if ($validated[$field] === null || $validated[$field] === '') {
                $validated[$field] = null;
                continue;
            }

            $validated[$field] = json_decode($validated[$field], true);
        }

        return $validated;
    }

    private function jsonFields(): array
    {
        return [
            'program_overview',
            'why_choose',
            'testimonials',
            'faq',
            'curriculum',
            'modules',
            'phases',
            'outcome',
        ];
    }
}
