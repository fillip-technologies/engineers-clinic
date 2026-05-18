<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\CounsellingController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/college-tieup', [HomeController::class, 'collegeTieup'])->name('college.tieup');
Route::get('/company-branding', [HomeController::class, 'companyBranding'])->name('company.branding');
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
Route::post('/signup/{role}', [HomeController::class, 'signupSubmit'])->name('signup.submit');

// Unified dashboard - redirects based on user role
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
});

Route::post('/counselling-submit', [CounsellingController::class, 'store'])
    ->name('counselling.store');
Route::post('/college-tieup/partnership-discussion', [CounsellingController::class, 'storeCollegePartnershipDiscussion'])->name('college.partnership-discussion.store');




Route::middleware(['auth', CheckRole::class . ':college'])->group(function () {
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
    Route::get('/student/course/workspace', [HomeController::class, 'studentDefaultCourseWorkspace'])->name('student.course.workspace.default');
    Route::get('/student/course/{id}/workspace', [HomeController::class, 'studentCourseWorkspace'])->name('student.course.workspace');
    Route::get('/student-dashboard/course/{id}/workspace', [HomeController::class, 'studentCourseWorkspace']);
    Route::get('/student-dashboard/course/{id}', [HomeController::class, 'studentCourse'])->name('student.course.detail');

    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/create-order', [App\Http\Controllers\PaymentController::class, 'createOrder'])->name('create-order');
        Route::post('/verify', [App\Http\Controllers\PaymentController::class, 'verifyPayment'])->name('verify');
        Route::get('/history', [App\Http\Controllers\PaymentController::class, 'paymentHistory'])->name('history');
        Route::get('/available-courses', [App\Http\Controllers\PaymentController::class, 'availableCourses'])->name('available-courses');
    });
});

// Public course detail route
Route::get('/course/{slug}', [HomeController::class, 'courseDetail'])->name('course.detail');
