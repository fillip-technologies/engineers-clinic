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
Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

Route::get('/college/dashboard', [App\Http\Controllers\College\DashboardController::class, 'index'])->name('college.dashboard')->middleware('auth');
Route::get('/dashboard/enrolled-courses', [HomeController::class, 'enrolledCourses'])->name('dashboard.enrolled-courses');
Route::get('/course/{slug}', [HomeController::class, 'courseDetail'])->name('course.detail');

