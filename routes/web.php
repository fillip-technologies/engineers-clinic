<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [HomeController::class, 'login']);
Route::get('/signup/{role}', [HomeController::class, 'signup'])->name('signup');
Route::get('/dashboard', function () {
    return view('pages.student.dashboard');
})->name('dashboard');
Route::get('/college/dashboard', function () {
    return view('pages.college.dashboard');
})->name('college.dashboard');
Route::get('/dashboard/enrolled-courses', [HomeController::class, 'enrolledCourses'])->name('dashboard.enrolled-courses');
Route::get('/course/{slug}', [HomeController::class, 'courseDetail'])->name('course.detail');
