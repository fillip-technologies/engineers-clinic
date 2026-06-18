<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CollegeInternshipPurchase;
use App\Models\CollegeInternshipSeatAllocation;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Services\OnboardingMailer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InternshipController extends Controller
{
    public function index(): JsonResponse
    {
        $internships = Course::where('type', 'internship')
            ->where('is_sponsorable', true)
            ->orderBy('title')
            ->get()
            ->map(fn (Course $c) => [
                'id' => $c->id,
                'title' => $c->title,
                'description' => $c->description,
                'level' => $c->level,
                'category' => $c->category,
                'fee' => $c->fee,
                'duration_months' => $c->duration_months,
                'slug' => $c->slug,
            ]);

        return response()->json(['internships' => $internships]);
    }

    public function show(Course $course): JsonResponse
    {
        abort_unless($course->type === 'internship', 404, 'Not an internship.');

        return response()->json([
            'id' => $course->id,
            'title' => $course->title,
            'description' => $course->description,
            'level' => $course->level,
            'category' => $course->category,
            'fee' => $course->fee,
            'duration_months' => $course->duration_months,
            'is_sponsorable' => (bool) $course->is_sponsorable,
            'curriculum' => $course->curriculum,
        ]);
    }

    public function allocateSeat(Request $request, CollegeInternshipPurchase $purchase): JsonResponse
    {
        $user = $request->user();
        $college = $user->college;

        abort_unless($college && $purchase->college_id === $college->id, 403);

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
        ]);

        $student = Student::where('id', $validated['student_id'])
            ->where('college_id', $college->id)
            ->firstOrFail();

        DB::transaction(function () use ($purchase, $student, $user) {
            $freshPurchase = CollegeInternshipPurchase::lockForUpdate()->findOrFail($purchase->id);

            abort_if($freshPurchase->transaction?->status !== 'approved', 422, 'Payment not approved.');
            abort_if($freshPurchase->seats_used >= $freshPurchase->seats_purchased, 422, 'No seats remaining.');

            $alreadyAllocated = CollegeInternshipSeatAllocation::where('purchase_id', $purchase->id)
                ->where('student_id', $student->id)
                ->exists();
            abort_if($alreadyAllocated, 422, 'Student already has a seat for this purchase.');

            $enrollment = Enrollment::firstOrCreate(
                ['student_id' => $student->id, 'course_id' => $freshPurchase->course_id],
                ['enrollment_date' => now(), 'progress' => 0, 'status' => 'active', 'sponsor_type' => 'college']
            );

            $allocation = CollegeInternshipSeatAllocation::create([
                'purchase_id' => $freshPurchase->id,
                'student_id' => $student->id,
                'enrollment_id' => $enrollment->id,
                'allocated_by' => $user->id,
                'allocated_at' => now(),
            ]);

            $enrollment->update(['seat_allocation_id' => $allocation->id]);
            $freshPurchase->increment('seats_used');
        });

        return response()->json(['message' => 'Seat allocated and student enrolled.'], 201);
    }
}
