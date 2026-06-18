<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CollegeController;
use App\Http\Controllers\Api\InternshipController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — v1
|--------------------------------------------------------------------------
| Token auth: pass Authorization: Bearer <token> header.
| Tokens are issued via POST /api/v1/auth/login.
| Roles: admin, college, student (same as web session roles).
*/

Route::prefix('v1')->group(function () {

    // ── Auth ─────────────────────────────────────────────────────────────
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::middleware('auth:sanctum')->post('/auth/logout', [AuthController::class, 'logout']);

    // ── College signup ────────────────────────────────────────────────────
    Route::post('/colleges', [CollegeController::class, 'store']);

    // ── Authenticated routes ──────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // College
        Route::get('/colleges/{college}/payment-status', [CollegeController::class, 'paymentStatus']);
        Route::post('/colleges/{college}/payments', [CollegeController::class, 'submitPayment']);
        Route::get('/colleges/{college}/students', [CollegeController::class, 'students']);
        Route::post('/colleges/{college}/students', [CollegeController::class, 'addStudent']);
        Route::post('/colleges/{college}/students/bulk', [CollegeController::class, 'bulkImportStudents']);
        Route::post('/colleges/{college}/internship-purchases', [CollegeController::class, 'createInternshipPurchase']);

        // Internship catalog
        Route::get('/internships', [InternshipController::class, 'index']);
        Route::get('/internships/{course}', [InternshipController::class, 'show']);

        // Internship purchase seat allocation
        Route::post('/internship-purchases/{purchase}/allocate', [InternshipController::class, 'allocateSeat']);

        // Student enrollments
        Route::get('/students/{student}/enrollments', [StudentController::class, 'enrollments']);
        Route::post('/students/{student}/enrollments', [StudentController::class, 'enroll']);

        // Student payment (self-pay)
        Route::post('/payments/student/create-order', [PaymentController::class, 'createOrder']);
        Route::post('/payments/student/verify', [PaymentController::class, 'verify']);

        // Admin
        Route::middleware('role:admin')->group(function () {
            Route::get('/admin/colleges', [AdminController::class, 'colleges']);
            Route::post('/admin/colleges/{college}/approve', [AdminController::class, 'approveCollege']);
            Route::post('/admin/colleges/{college}/reject', [AdminController::class, 'rejectCollege']);
            Route::get('/admin/reports/revenue', [AdminController::class, 'revenueReport']);
            Route::get('/admin/reports/enrollments', [AdminController::class, 'enrollmentsReport']);
            Route::get('/admin/reports/colleges', [AdminController::class, 'collegesReport']);
            Route::get('/admin/reports/students', [AdminController::class, 'studentsReport']);
        });
    });
});
