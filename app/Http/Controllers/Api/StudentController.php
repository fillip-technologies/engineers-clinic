<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function enrollments(Student $student): JsonResponse
    {
        $this->authorizeStudentAccess($student);

        $enrollments = Enrollment::with('course')
            ->where('student_id', $student->id)
            ->latest('enrollment_date')
            ->get()
            ->map(fn (Enrollment $e) => [
                'id' => $e->id,
                'course_id' => $e->course_id,
                'course_title' => $e->course?->title,
                'status' => $e->status,
                'progress' => $e->progress,
                'sponsor_type' => $e->sponsor_type,
                'enrollment_date' => $e->enrollment_date?->toDateString(),
            ]);

        return response()->json(['enrollments' => $enrollments]);
    }

    public function enroll(Request $request, Student $student): JsonResponse
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
        ]);

        $course = Course::findOrFail($validated['course_id']);

        $existing = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Already enrolled.',
                'enrollment_id' => $existing->id,
            ], 422);
        }

        if ((float) ($course->fee ?? 0) > 0) {
            return response()->json([
                'message' => 'This course requires payment. Use the checkout flow.',
                'checkout_required' => true,
                'fee' => $course->fee,
            ], 402);
        }

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrollment_date' => now(),
            'progress' => 0,
            'status' => 'active',
            'sponsor_type' => 'self',
        ]);

        return response()->json([
            'message' => 'Enrolled successfully.',
            'enrollment_id' => $enrollment->id,
        ], 201);
    }

    private function authorizeStudentAccess(Student $student): void
    {
        $user = request()->user();
        $isAdmin = $user?->role?->name === 'admin';
        $isOwner = $user?->student?->id === $student->id;
        $isCollegeOfStudent = $user?->college?->id === $student->college_id;

        abort_unless($isAdmin || $isOwner || $isCollegeOfStudent, 403, 'Access denied.');
    }
}
