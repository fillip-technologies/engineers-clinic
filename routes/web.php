<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('loginpost');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
});
Route::get('/signup/{role}', [HomeController::class, 'signup'])->name('signup');

// Unified dashboard - redirects based on user role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/college/dashboard', [HomeController::class, 'dashboard'])->name('college.dashboard');
    Route::get('/college/students', [HomeController::class, 'studentManagement'])->name('college.students');
    Route::get('/college/students/create', [HomeController::class, 'studentCreate'])->name('college.students.create');
    Route::get('/college/students/edit', [HomeController::class, 'studentEdit'])->name('college.students.edit');
    Route::get('/college/students/view', [HomeController::class, 'studentShow'])->name('college.students.view');
    Route::get('/college/enrollments', [HomeController::class, 'enrollments'])->name('college.enrollments');
    Route::get('/college/enrollments/create', [HomeController::class, 'enrollmentCreate'])->name('college.enrollments.create');
    Route::get('/college/enrollments/edit', [HomeController::class, 'enrollmentEdit'])->name('college.enrollments.edit');
    Route::get('/college/enrollments/view', [HomeController::class, 'enrollmentShow'])->name('college.enrollments.view');
});

// Student Dashboard Routes (protected with auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard/enrolled-courses', [HomeController::class, 'enrolledCourses'])->name('dashboard.enrolled-courses');
    Route::get('/dashboard/student/profile', [HomeController::class, 'studentProfile'])->name('dashboard.student.profile');
    Route::put('/dashboard/student/profile/edit', [HomeController::class, 'studentProfileEdit'])->name('dashboard.student.profile.edit');
    Route::patch('/dashboard/student/profile/edit', [HomeController::class, 'studentProfileEdit'])->name('dashboard.student.profile.update');
    Route::get('/student-dashboard/quiz-attempts', [HomeController::class, 'quizAttempts'])->name('dashboard.quiz-attempts');
    Route::get('/student-dashboard/orders', [HomeController::class, 'orderHistory'])->name('dashboard.orders');
    Route::get('/student-dashboard/course/{id}', [HomeController::class, 'studentCourse'])->name('student.course.detail');
});

// Public course detail route
Route::get('/course/{slug}', [HomeController::class, 'courseDetail'])->name('course.detail');
