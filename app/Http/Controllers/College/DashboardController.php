<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Student;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get college profile
        $college = College::where('user_id', $user->id)->first();
        
        // Get students for this college (or empty collection if no college)
        $students = $college 
            ? Student::where('college_id', $college->id)->with('user')->get()
            : collect();
        
        // Calculate stats with defaults
        $totalStudents = $students->count();
        $activeInternships = $students->filter(function($student) {
            return $student->enrollments()->where('status', 'active')->exists();
        })->count();
        $completed = $students->filter(function($student) {
            return $student->enrollments()->where('status', 'completed')->exists();
        })->count();
        
        // Get monthly enrollment data for chart
        $monthlyData = Enrollment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');
        
        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData->get($i, 0);
        }
        
        // Provide default college object if none exists
        if (!$college) {
            $college = (object) ['college_name' => $user->name];
        }
        
        return view('pages.college.dashboard', compact(
            'college',
            'students',
            'totalStudents',
            'activeInternships',
            'completed',
            'chartData'
        ));
    }
}