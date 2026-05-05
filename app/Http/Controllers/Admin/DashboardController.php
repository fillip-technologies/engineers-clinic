<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\College;
use App\Models\Student;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Certificate;
use App\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'totalUsers' => User::count(),
            'totalColleges' => College::count(),
            'totalStudents' => Student::count(),
            'totalCourses' => Course::count(),
            'totalEnrollments' => Enrollment::count(),
            'totalPayments' => Payment::count(),
            'totalCertificates' => Certificate::count(),
            'totalRoles' => Role::count(),
        ];

        return view('admin.dashboard.index', compact('stats'));
    }
}
