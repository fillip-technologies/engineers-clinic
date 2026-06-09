<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\College\DashboardController as CollegeDashboardController;
use App\Http\Middleware\CheckRole;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CounsellingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;



Route::get('/', [HomeController::class, 'index']);
Route::get('/college-tieup', [HomeController::class, 'collegeTieup'])->name('college.tieup');
Route::get('/company-branding', [HomeController::class, 'companyBranding'])->name('company.branding');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/coming-soon/enterprise-services', [HomeController::class, 'comingSoonEnterpriseServices'])->name('coming-soon.enterprise-services');
Route::get('/coming-soon/ai-tools', [HomeController::class, 'comingSoonAiTools'])->name('coming-soon.ai-tools');
Route::get('/coming-soon/blog', [HomeController::class, 'comingSoonBlog'])->name('coming-soon.blog');
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
Route::post('/course-enquiry-submit', [CounsellingController::class, 'storeCourseEnquiry'])->name('course-enquiries.store');
Route::post('/course/{course:slug}/reserve', [CheckoutController::class, 'start'])->name('payments.checkout.start');




Route::middleware(['auth', CheckRole::class . ':college'])->group(function () {
    Route::get('/college/dashboard', [CollegeDashboardController::class, 'dashboard'])->name('college.dashboard');
    Route::get('/college/payment', [CollegeDashboardController::class, 'payment'])->name('college.payment');
    Route::post('/college/payment', [CollegeDashboardController::class, 'paymentStore'])->name('college.payment.store');
    Route::get('/college/students', [CollegeDashboardController::class, 'studentManagement'])->name('college.students');
    Route::get('/college/students/create', [CollegeDashboardController::class, 'studentCreate'])->name('college.students.create');
    Route::post('/college/students', [CollegeDashboardController::class, 'studentStore'])->name('college.students.store');
    Route::get('/college/students/edit', [CollegeDashboardController::class, 'studentEdit'])->name('college.students.edit');
    Route::get('/college/students/view', [CollegeDashboardController::class, 'studentShow'])->name('college.students.view');
    Route::get('/college/enrollments', [CollegeDashboardController::class, 'enrollments'])->name('college.enrollments');
    Route::get('/college/enrollments/create', [CollegeDashboardController::class, 'enrollmentCreate'])->name('college.enrollments.create');
    Route::post('/college/enrollments', [CollegeDashboardController::class, 'enrollmentStore'])->name('college.enrollments.store');
    Route::get('/college/enrollments/edit', [CollegeDashboardController::class, 'enrollmentEdit'])->name('college.enrollments.edit');
    Route::get('/college/enrollments/view', [CollegeDashboardController::class, 'enrollmentShow'])->name('college.enrollments.view');
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
    Route::post('/student/course/{id}/workspace/steps/{step}/complete', [HomeController::class, 'studentWorkspaceCompleteStep'])->name('student.course.workspace.steps.complete');
    Route::post('/student/course/{id}/workspace/submit', [HomeController::class, 'studentWorkspaceSubmitProject'])->name('student.course.workspace.submit');
    Route::get('/student-dashboard/course/{id}/workspace', [HomeController::class, 'studentCourseWorkspace']);
    Route::get('/student-dashboard/course/{id}', [HomeController::class, 'studentCourse'])->name('student.course.detail');
    Route::get('/course/{course:slug}/checkout/{order?}', [CheckoutController::class, 'show'])->name('payments.checkout');

    // Payment Routes
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/create-order', [PaymentController::class, 'createOrder'])->name('create-order');
        Route::post('/verify', [PaymentController::class, 'verify'])->name('verify');
        Route::post('/free-enroll', [PaymentController::class, 'completeFreeEnrollment'])->name('free-enroll');
        Route::get('/success/{order}', [PaymentController::class, 'success'])->name('success');
        Route::get('/failure/{order}', [PaymentController::class, 'failure'])->name('failure');
        Route::get('/history', [PaymentController::class, 'paymentHistory'])->name('history');
        Route::get('/available-courses', [PaymentController::class, 'availableCourses'])->name('available-courses');
    });
});

// Public course detail route
Route::get('/course/{slug}', [HomeController::class, 'courseDetail'])->name('course.detail');

// Chatbot (support assistant)
Route::prefix('chatbot')->name('chatbot.')->middleware('throttle:30,1')->group(function () {
    Route::post('/message', [ChatbotController::class, 'message'])->name('message');
    Route::post('/handoff', [ChatbotController::class, 'handoff'])->name('handoff');
});
