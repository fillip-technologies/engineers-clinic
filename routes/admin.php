<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CollegeController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CourseBulkUploadController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\StudentTaskController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizResultController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Admin\CourseWorkspaceController;
use App\Http\Controllers\CounsellingController;

// CRUD Routes

Route::resource('roles', RoleController::class);
Route::resource('permissions', PermissionController::class);
Route::resource('colleges', CollegeController::class);
Route::resource('students', StudentController::class);
Route::resource('courses', CourseController::class);
Route::get('courses-bulk-upload', [CourseBulkUploadController::class, 'index'])->name('courses.bulk-upload.index');
Route::post('courses-bulk-upload', [CourseBulkUploadController::class, 'store'])->name('courses.bulk-upload.store');
Route::resource('course-workspaces', CourseWorkspaceController::class);
Route::resource('enrollments', EnrollmentController::class);
Route::resource('tasks', TaskController::class);
Route::resource('student-tasks', StudentTaskController::class);
Route::resource('quizzes', QuizController::class);
Route::resource('quiz-results', QuizResultController::class);
Route::resource('certificates', CertificateController::class);
Route::resource('payments', PaymentController::class);
Route::resource('role-permissions', RolePermissionController::class);
Route::resource('attendances', AttendanceController::class);
Route::resource('notifications', NotificationController::class);
Route::get('/counselling-leads', [CounsellingController::class, 'index'])
    ->name('counselling.index');
Route::get('/college-partner', [CounsellingController::class, 'index_college'])
    ->name('counselling.partner');
Route::get('/course-enquiries', [CounsellingController::class, 'courseEnquiries'])
    ->name('course-enquiries.index');
