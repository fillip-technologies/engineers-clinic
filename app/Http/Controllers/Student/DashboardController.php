<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get student profile
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            // Create default student object if no profile
            $student = (object) [
                'id' => null,
                'course_name' => 'No course enrolled',
                'user' => $user
            ];
        }
        
        // Get enrollments for this student
        $enrollments = Enrollment::where('student_id', $student->id ?? 0)
            ->with('course')
            ->get();
        
        // Calculate stats
        $totalEnrolled = $enrollments->count();
        $activeCourses = $enrollments->where('status', 'ongoing')->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        
        // Get enrollment ID
        $enrollmentId = $enrollments->first()->id ?? null;
        $enrollmentIdFormatted = $enrollmentId ? 'EC-2026-' . str_pad($enrollmentId, 4, '0', STR_PAD_LEFT) : 'N/A';
        
        // Get current track (first ongoing enrollment's course)
        $currentTrack = $enrollments->where('status', 'ongoing')->first()->course->title ?? 'No active course';
        
        return view('pages.student.dashboard', compact(
            'student',
            'enrollments',
            'totalEnrolled',
            'activeCourses',
            'completedCourses',
            'enrollmentIdFormatted',
            'currentTrack'
        ));
    }
}