<?php

namespace App\Http\Controllers;

use App\Helpers\CourseDataHelper;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\College;
use App\Models\Notification;
use App\Models\QuizResult;
use App\Models\Quiz;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Models\CourseWorkspace;
use App\Services\OnboardingMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    public function index()
    {
        $courses = CourseDataHelper::loadAllCourses();

        return view('pages.home', compact('courses'));
    }

    public function collegeTieup()
    {
        return view('pages.college-tieup');
    }

    

    public function companyBranding()
    {
        return view('pages.company-branding');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // Determine role and set appropriate values
        $roleName = $user?->role?->name ?? 'student';
        $routeName = $request->route()?->getName();

        // Redirect users away from college routes if they are not college users
        if (str_starts_with($routeName ?? '', 'college.') && $roleName !== 'college') {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access the college dashboard.');
        }

        $activePage = $roleName . '-dashboard';

        // Return role-specific view and sidebar
        $view = match ($roleName) {
            'college' => 'dashboard.home',
            'admin' => 'dashboard.admin-dashboard.home',
            default => 'dashboard.student-dashboard.home',
        };

        return view($view, $this->frontendAdminData($activePage, $roleName));
    }

    public function redirectDashboardByRole($role)
    {
        return redirect()->route('dashboard');
    }

    public function signup($role)
    {
        $roles = [
            'student' => [
                'label' => 'Student',
                'eyebrow' => 'Learning Access',
                'title' => 'Create your student account',
                'description' => 'Join Engineers Clinic to explore practical learning, internship-ready modules, and guided progress built for engineering students.',
                'button' => 'Sign up as Student',
                'fields' => [
                    ['label' => 'Full Name', 'type' => 'text', 'name' => 'student_name', 'placeholder' => 'Enter your full name'],
                    ['label' => 'Student Email', 'type' => 'email', 'name' => 'student_email', 'placeholder' => 'Enter your student email'],
                    ['label' => 'College Name', 'type' => 'text', 'name' => 'student_college', 'placeholder' => 'Enter your college name'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'student_password', 'placeholder' => 'Create a password'],
                    ['label' => 'Confirm Password', 'type' => 'password', 'name' => 'student_password_confirmation', 'placeholder' => 'Confirm your password'],
                ],
            ],
            'college' => [
                'label' => 'College',
                'eyebrow' => 'Partnership Access',
                'title' => 'Create your college account',
                'description' => 'Register your institution to coordinate partnerships, track student engagement, and connect with Engineers Clinic programs.',
                'button' => 'Sign up as College',
                'fields' => [
                    ['label' => 'College Name', 'type' => 'text', 'name' => 'college_name', 'placeholder' => 'Enter your college name'],
                    ['label' => 'Official Email', 'type' => 'email', 'name' => 'college_email', 'placeholder' => 'Enter your official email'],
                    ['label' => 'Contact Person', 'type' => 'text', 'name' => 'college_contact', 'placeholder' => 'Enter contact person name'],
                    ['label' => 'Password', 'type' => 'password', 'name' => 'college_password', 'placeholder' => 'Create a password'],
                    ['label' => 'Confirm Password', 'type' => 'password', 'name' => 'college_password_confirmation', 'placeholder' => 'Confirm your password'],
                ],
            ],
        ];

        abort_unless(isset($roles[$role]), 404);

        $data = [
            'role' => $role,
            'signup' => $roles[$role],
        ];

        if ($role === 'student') {
            $colleges = College::all()->pluck('college_name', 'id')->toArray();
            $data['colleges'] = $colleges;
            $data['signup']['fields'][2]['options'] = array_merge(['other' => 'Other'], $colleges); // Add 'Other' option
        }

        return view('pages.signup', $data);
    }

    public function signupSubmit(Request $request, string $role)
    {
        $roles = ['student', 'college'];
        abort_unless(in_array($role, $roles, true), 404);

        if ($role === 'student') {
            $rules = [
                'student_name' => 'required|string|max:255',
                'student_email' => 'required|string|email|max:255|unique:users,email',
                'student_college' => 'required|string',
                'student_password' => 'required|string|min:8|confirmed',
            ];

            if ($request->student_college === 'other') {
                $rules['student_college_other'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            $roleRecord = Role::where('name', 'student')->firstOrFail();

            $user = User::create([
                'name' => $validated['student_name'],
                'email' => $validated['student_email'],
                'password' => Hash::make($validated['student_password']),
                'role_id' => $roleRecord->id,
            ]);

            // Handle college
            if ($validated['student_college'] === 'other') {
                $college = College::create([
                    'user_id' => null, // No user associated yet
                    'college_name' => $validated['student_college_other'],
                    'address' => null,
                    'contact_number' => null,
                ]);
            } else {
                $college = College::findOrFail($validated['student_college']);
            }

            $user->student()->create([
                'college_id' => $college->id,
                'course_name' => null,
            ]);

            app(OnboardingMailer::class)->send($user, $validated['student_password'], 'student');
        } else {
            $validated = $request->validate([
                'college_name' => 'required|string|max:255',
                'college_email' => 'required|string|email|max:255|unique:users,email',
                'college_contact' => 'required|string|max:255',
                'college_password' => 'required|string|min:8|confirmed',
            ]);

            $roleRecord = Role::where('name', 'college')->firstOrFail();

            $user = User::create([
                'name' => $validated['college_contact'],
                'email' => $validated['college_email'],
                'password' => Hash::make($validated['college_password']),
                'role_id' => $roleRecord->id,
            ]);

            $user->college()->create([
                'college_name' => $validated['college_name'],
                'address' => null,
                'contact_number' => null,
            ]);

            app(OnboardingMailer::class)->send($user, $validated['college_password'], 'college');
        }

        Auth::login($user);

        return match ($role) {
            'college' => redirect('/college/dashboard'),
            'student' => redirect('/dashboard'),
        };
    }

    public function enrolledCourses()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            // If no student record, show empty or demo data
            $enrolledCourses = [];
        } else {
            // Fetch enrollments with course details
            $enrollments = Enrollment::with('course')
                ->where('student_id', $student->id)
                ->orderBy('enrollment_date', 'desc')
                ->get();

            $enrolledCourses = $enrollments->map(function ($enrollment) {
                $course = $enrollment->course;
                return [
                    'id' => $enrollment->id,
                    'course_id' => $course?->id,
                    'title' => $course?->title ?? 'Unknown Course',
                    'image' => '/images/courses/' . ($course?->slug ?? 'default') . '.svg',
                    'description' => $course?->description ?? '',
                    'completed_lessons' => $enrollment->progress ?? 0,
                    'total_lessons' => 100, // Default total
                    'progress' => $enrollment->progress ?? 0,
                    'status' => $enrollment->status ?? 'Active',
                    'enrollment_date' => $enrollment->enrollment_date?->format('M d, Y'),
                ];
            })->toArray();
        }

        return view('dashboard.student-dashboard.enrollments.index', [
            'enrolledCourses' => $enrolledCourses,
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }

    public function studentCourse($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard.enrolled-courses');
        }

        // Fetch the enrollment with course details
        $enrollment = Enrollment::with('course')
            ->where('student_id', $student->id)
            ->where('id', $id)
            ->first();

        if (!$enrollment) {
            // Try to find by course ID as fallback
            $enrollment = Enrollment::with('course')
                ->where('student_id', $student->id)
                ->get()
                ->firstWhere('course_id', $id);
        }

        if (!$enrollment) {
            abort(404, 'Course not found');
        }

        $course = $enrollment->course;

        // Build course data from database
        $courseData = [
            'id' => $enrollment->id,
            'course_id' => $course?->id,
            'title' => $course?->title ?? 'Unknown Course',
            'completed_lessons' => $enrollment->progress ?? 0,
            'total_lessons' => 100,
            'progress' => $enrollment->progress ?? 0,
            'current_module' => 'module-1',
            'description' => $course?->description ?? '',
            'status' => $enrollment->status ?? 'Active',
            'enrollment_date' => $enrollment->enrollment_date?->format('M d, Y'),
            'phases' => CourseDataHelper::getCoursePhases($course),
            'module_content' => CourseDataHelper::getCourseModules($course),
        ];

        return view('dashboard.student-dashboard.course.show', [
            'course' => $courseData,
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }

    public function studentCourseWorkspace($id)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();
        $enrollment = null;

        if ($student) {
            $enrollment = Enrollment::with('course')
                ->where('student_id', $student->id)
                ->where('id', $id)
                ->first();

            if (!$enrollment) {
                $enrollment = Enrollment::with('course')
                    ->where('student_id', $student->id)
                    ->get()
                    ->firstWhere('course_id', $id);
            }
        }

        abort_if($student && ! $enrollment && $id !== 'demo', 404, 'Course enrollment not found.');

        $course = $enrollment?->course;
        $courseWorkspace = CourseWorkspace::with([
            'course',
            'steps' => fn ($query) => $query->orderBy('sort_order')->orderBy('step_no'),
            'steps.taskProgress' => fn ($query) => $query->where('student_id', $user->id),
            'resources' => fn ($query) => $query->orderBy('sort_order'),
            'goals',
        ])
            ->when($course, fn ($query) => $query->where('course_id', $course->id))
            ->where('status', true)
            ->orderByDesc('updated_at')
            ->first();

        abort_unless($courseWorkspace, 404, 'No active workspace found for this course.');

        $course = $course ?: $courseWorkspace->course;
        $steps = $this->studentWorkspaceSteps($courseWorkspace);

        abort_if(empty($steps), 404, 'This workspace does not have any steps yet.');

        $workspace = $this->studentWorkspaceData($courseWorkspace, $course, $user, $steps, $enrollment);
        $sidebarItems = $this->studentWorkspaceSidebarItems($steps);
        $resources = $this->studentWorkspaceResources($courseWorkspace);
        $mentorTip = $this->studentWorkspaceMentorTip($steps);
        $todayGoal = $this->studentWorkspaceTodayGoal($courseWorkspace, $steps);

        return view('dashboard.student-dashboard.course.workspace', compact(
            'workspace',
            'sidebarItems',
            'steps',
            'mentorTip',
            'todayGoal',
            'resources'
        ));
    }

    protected function studentWorkspaceData(CourseWorkspace $courseWorkspace, $course, $user, array $steps, $enrollment = null): array
    {
        $stepsCollection = collect($steps);
        $completedSteps = $stepsCollection->where('state', 'completed')->count();
        $progress = $stepsCollection->isNotEmpty()
            ? (int) round(($completedSteps / $stepsCollection->count()) * 100)
            : (int) ($enrollment?->progress ?? $courseWorkspace->progress);
        $currentStep = $stepsCollection->firstWhere('active', true)
            ?? $stepsCollection->first(fn (array $step) => $step['state'] !== 'completed')
            ?? $stepsCollection->first();

        return [
            'title' => $courseWorkspace->title,
            'track' => $courseWorkspace->track ?: ($course?->title ?? 'Student Project Workspace'),
            'headline' => $courseWorkspace->headline ?: $courseWorkspace->title,
            'summary' => $courseWorkspace->summary ?: 'Follow the steps one by one and complete your project checkpoints.',
            'progress' => $progress,
            'next_milestone' => $courseWorkspace->next_milestone ?: ('Continue ' . ($currentStep['title'] ?? 'your current workspace step')),
            'current_step_slug' => $currentStep['slug'] ?? null,
            'current_step_number' => $currentStep['number'] ?? null,
            'current_step_label' => isset($currentStep['number']) ? 'Continue Step ' . $currentStep['number'] : 'Continue',
            'student_name' => $user->name ?? 'Student',
            'student_email' => $user->email ?? 'student@example.com',
        ];
    }

    protected function studentWorkspaceSteps(CourseWorkspace $courseWorkspace): array
    {
        $steps = $courseWorkspace->steps->map(function ($step, $index) {
            $number = $step->step_no ?: $index + 1;
            $progress = $step->taskProgress->first();
            $isCompleted = (bool) ($progress?->completed ?? false);

            return [
                'number' => $number,
                'slug' => $step->slug ?: 'step-' . $number,
                'nav_label' => $step->nav_label ?: $step->title,
                'title' => $step->title,
                'description' => $step->description ?: '',
                'status' => $isCompleted ? 'Completed' : $step->status,
                'state' => $isCompleted ? 'completed' : $step->state,
                'active' => $isCompleted ? false : $step->active,
                'build' => $step->build_goal ?: '',
                'why' => $step->why_text ?: '',
                'lesson' => $step->lesson ?: '',
                'file' => $step->file_name ?: 'workspace',
                'code' => $step->code_snippet ?: '',
                'expected_output' => $step->expected_output ?: '',
                'preview_title' => $step->preview_title ?: '',
                'preview_points' => $step->preview_points ?: [],
                'task' => $step->task ?: '',
                'mistakes' => $step->mistakes ?: [],
                'tips' => $step->tips ?: [],
                'hint' => $step->hint ?: '',
                'mentor_tip' => $step->mentor_tip ?: '',
            ];
        })->values()->all();

        if (! collect($steps)->contains(fn (array $step) => $step['active'])) {
            $firstAvailableIndex = collect($steps)->search(fn (array $step) => $step['state'] !== 'completed');

            if ($firstAvailableIndex !== false) {
                $steps[$firstAvailableIndex]['state'] = 'active';
                $steps[$firstAvailableIndex]['active'] = true;
                $steps[$firstAvailableIndex]['status'] = 'In Progress';
            }
        }

        return $steps;
    }

    protected function studentWorkspaceSidebarItems(array $steps): array
    {
        $sidebarItems = array_map(function (array $step) {
            return [
                'label' => $step['nav_label'],
                'target' => 'step-' . $step['slug'],
                'state' => $step['state'],
                'number' => $step['number'],
            ];
        }, $steps);

        $sidebarItems[] = [
            'label' => 'Submission',
            'target' => 'submission',
            'state' => 'locked',
            'number' => count($steps) + 1,
        ];

        return $sidebarItems;
    }

    protected function studentWorkspaceResources(CourseWorkspace $courseWorkspace): array
    {
        return $courseWorkspace->resources
            ->groupBy(fn ($resource) => $resource->category ?: 'Resources')
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'items' => $items->map(fn ($resource) => [
                        'label' => $resource->label,
                        'description' => $resource->description ?: '',
                        'icon' => $resource->icon ?: 'fi fi-rr-link',
                        'href' => $resource->href ?: '#',
                    ])->values()->all(),
                ];
            })
            ->values()
            ->all();
    }

    protected function studentWorkspaceMentorTip(array $steps): array
    {
        $activeStep = collect($steps)->firstWhere('active', true) ?? $steps[0] ?? [];

        return [
            'title' => 'Mentor tip',
            'body' => $activeStep['mentor_tip'] ?? 'Complete one small checkpoint at a time and keep your project easy to review.',
        ];
    }

    protected function studentWorkspaceTodayGoal(CourseWorkspace $courseWorkspace, array $steps): array
    {
        $goal = $courseWorkspace->goals->firstWhere('type', 'daily') ?? $courseWorkspace->goals->first();
        $activeStep = collect($steps)->firstWhere('active', true) ?? $steps[0] ?? [];

        return [
            'title' => $goal?->title ?? ($activeStep['title'] ?? 'Continue your workspace'),
            'body' => $goal?->body ?? ($activeStep['task'] ?? 'Complete the next available step.'),
            'time' => $goal?->duration ?? '45-60 min',
        ];
    }



    public function studentDefaultCourseWorkspace()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        $enrollment = $student
            ? Enrollment::where('student_id', $student->id)
                ->orderBy('enrollment_date', 'desc')
                ->first()
            : null;

        if (!$enrollment) {
            return $this->studentCourseWorkspace('demo');
        }

        return redirect()->route('student.course.workspace', ['id' => $enrollment->id]);
    }

    public function studentProfile()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        // Build profile data from user and student records
        $profile = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'avatar' => $user->avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            'student_id' => $student?->id,
            'college_id' => $student?->college_id,
            'course_name' => $student?->course_name ?? '',
            'created_at' => $user->created_at?->format('M d, Y'),
        ];

        // If no student record exists, create basic profile from user
        if (!$student) {
            $profile['course_name'] = 'Not enrolled';
        }

        return view('dashboard.student-dashboard.profile.index', [
            'profile' => $profile,
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function studentProfileEdit(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Handle form submission for profile update
        if ($request->isMethod('post')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
            ]);

            // Update user profile
            $user->name = $validated['name'];
            if (isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }
            $user->save();

            return redirect()->route('dashboard.student.profile')
                ->with('success', 'Profile updated successfully!');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        // Build profile data for editing
        $profile = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'avatar' => $user->avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            'student_id' => $student?->id,
            'college_id' => $student?->college_id,
            'course_name' => $student?->course_name ?? '',
        ];

        return view('dashboard.student-dashboard.profile.edit', [
            'profile' => $profile,
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function quizAttempts()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            $quizAttempts = [];
        } else {
            // Fetch quiz results with quiz and course details
            $quizResults = QuizResult::with(['quiz', 'quiz.course'])
                ->where('student_id', $student->id)
                ->orderBy('created_at', 'desc')
                ->get();

            $quizAttempts = $quizResults->map(function ($result, $index) {
                $quiz = $result->quiz;
                return [
                    'id' => $result->id,
                    'title' => $quiz?->title ?? 'Unknown Quiz',
                    'course' => $quiz?->course?->title ?? 'Unknown Course',
                    'attempt' => 'Attempt ' . ($index + 1),
                    'score' => $result->score ? $result->score . '%' : 'Pending',
                    'status' => $result->passed ? 'Passed' : ($result->score !== null ? 'Failed' : 'Upcoming'),
                    'updated_at' => $result->created_at?->format('F j, Y'),
                    'action' => $result->passed ? 'View Summary' : 'Review Attempt',
                ];
            })->toArray();
        }

        return view('dashboard.student-dashboard.quiz-attempts.index', [
            'quizAttempts' => $quizAttempts,
            ...$this->frontendAdminData('student-quiz-attempts'),
        ]);
    }

    public function orderHistory()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Get the student record for this user
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            $orders = [];
        } else {
            // Fetch payments with course details
            $payments = Payment::with('course')
                ->where('student_id', $student->id)
                ->orderBy('payment_date', 'desc')
                ->get();

            $orders = $payments->map(function ($payment) {
                $course = $payment->course;
                return [
                    'id' => $payment->id,
                    'title' => $course?->title ?? 'Unknown Course',
                    'order_id' => 'ORD-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                    'purchase_date' => $payment->payment_date?->format('F j, Y') ?? 'N/A',
                    'price' => $payment->amount ? 'Rs. ' . number_format($payment->amount, 0) : 'N/A',
                    'payment_status' => ucfirst($payment->status ?? 'Pending'),
                    'access_status' => $payment->status === 'completed' ? 'Active' : 'Pending',
                ];
            })->toArray();
        }

        return view('dashboard.student-dashboard.order-history.index', [
            'orders' => $orders,
            ...$this->frontendAdminData('student-order-history'),
        ]);
    }

    public function studentManagement()
    {
        return view('dashboard.college.student-management', [
            'students' => $this->collegeStudentManagementData(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentCreate()
    {
        return view('dashboard.college.student-create', [
            'courseOptions' => $this->collegeStudentCourseOptions(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentEdit()
    {
        $students = $this->collegeStudentManagementData();

        return view('dashboard.college.student-edit', [
            'student' => $students[1] ?? $students[0] ?? null,
            'courseOptions' => $this->collegeStudentCourseOptions(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentShow()
    {
        $students = $this->collegeStudentManagementData();

        return view('dashboard.college.student-show', [
            'student' => $students[0] ?? null,
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function enrollments()
    {
        return view('dashboard.college.enrollments.index', [
            'enrollments' => $this->collegeEnrollmentsData(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentCreate()
    {
        return view('dashboard.college.enrollments.create', [
            'students' => $this->collegeEnrollmentStudentOptions(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentEdit()
    {
        $enrollments = $this->collegeEnrollmentsData();

        return view('dashboard.college.enrollments.edit', [
            'enrollment' => $enrollments[1] ?? $enrollments[0] ?? null,
            'students' => $this->collegeEnrollmentStudentOptions(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentShow()
    {
        $enrollments = $this->collegeEnrollmentsData();

        return view('dashboard.college.enrollments.show', [
            'enrollment' => $enrollments[0] ?? null,
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function courseDetail($slug)
    {
        return $this->show($slug);
    }

    public function show($slug)
    {
        $course = CourseDataHelper::loadCourseBySlug($slug);

        abort_unless($course, 404);

        return view('course.detail', compact('course'));
    }

    protected function frontendAdminData(string $activePage, string $role = 'student'): array
    {
        $user = Auth::user();
        $role = Auth::check() ? Auth::user()->role->name : 'student';
        // dd($role);

        $data = [
            'sidebarSections' => $this->dashboardSidebarSections($role),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => $user ? $user->name : 'Guest',
            'sidebarUserMeta' => $user && $user->email ? $user->email : 'Unified Dashboard',
            'navbarUserName' => $user ? explode(' ', $user->name)[0] : 'Guest',
            'collegeStudents' => $this->dashboardCollegeStudents(),
        ];

        if ($role === 'college') {
            $data = array_merge($data, $this->dashboardCollegeOverviewData());
        }

        if ($role === 'student') {
            $data = array_merge($data, $this->dashboardStudentOverviewData());
        }

        return $data;
    }

    protected function dashboardSidebarSections(string $role = 'student'): array
    {
        // Common items for all roles
        $commonItems = [
            [
                'key' => 'common-settings',
                'label' => 'Settings',
                'icon' => 'fi fi-rr-settings',
                'href' => '#',
            ],
            [
                'key' => 'common-logout',
                'label' => 'Logout',
                'icon' => 'fi fi-rr-exit',
                'href' => route('logout'),
                'method' => 'POST',
            ],
        ];

        // Student sidebar
        if ($role === 'student') {
            return [
                [
                    'label' => 'For Students',
                    'items' => [
                        [
                            'key' => 'student-dashboard',
                            'label' => 'Dashboard',
                            'icon' => 'fi fi-rr-apps',
                            'href' => route('dashboard'),
                        ],
                        [
                            'key' => 'student-profile',
                            'label' => 'My Profile',
                            'icon' => 'fi fi-rr-user',
                            'href' => route('dashboard.student.profile'),
                        ],
                        [
                            'key' => 'student-enrolled-courses',
                            'label' => 'Enrolled Courses',
                            'icon' => 'fi fi-rr-book-alt',
                            'href' => route('dashboard.enrolled-courses'),
                        ],
                        [
                            'key' => 'student-quiz-attempts',
                            'label' => 'My Quiz Attempts',
                            'icon' => 'fi fi-rr-document',
                            'href' => route('dashboard.quiz-attempts'),
                        ],
                        [
                            'key' => 'student-order-history',
                            'label' => 'Billing & Purchases',
                            'icon' => 'fi fi-rr-shopping-cart',
                            'href' => route('dashboard.orders'),
                        ],
                    ],
                ],
                [
                    'label' => 'Account',
                    'items' => $commonItems,
                ],
            ];
        }

        // College sidebar
        if ($role === 'college') {
            return [
                [
                    'label' => 'For College',
                    'items' => [
                        [
                            'key' => 'college-dashboard',
                            'label' => 'Dashboard',
                            'icon' => 'fi fi-rr-apps',
                            'href' => route('college.dashboard'),
                        ],
                        [
                            'key' => 'college-students',
                            'label' => 'Manage Students',
                            'icon' => 'fi fi-rr-users',
                            'href' => route('college.students'),
                        ],
                        [
                            'key' => 'college-enrollments',
                            'label' => 'Enrollments',
                            'icon' => 'fi fi-rr-user-plus',
                            'href' => route('college.enrollments'),
                        ],
                        [
                            'key' => 'college-courses',
                            'label' => 'Courses',
                            'icon' => 'fi fi-rr-book-alt',
                            'href' => '#',
                        ],
                    ],
                ],
                [
                    'label' => 'Account',
                    'items' => $commonItems,
                ],
            ];
        }

        // Admin sidebar
        if ($role === 'admin') {
            return [
                [
                    'label' => 'Administration',
                    'items' => [
                        [
                            'key' => 'admin-dashboard',
                            'label' => 'Dashboard',
                            'icon' => 'fi fi-rr-apps',
                            'href' => route('admin.dashboard'),
                        ],
                        [
                            'key' => 'admin-users',
                            'label' => 'Manage Users',
                            'icon' => 'fi fi-rr-users',
                            'href' => '#',
                        ],
                        [
                            'key' => 'admin-colleges',
                            'label' => 'Colleges',
                            'icon' => 'fi fi-rr-building',
                            'href' => '#',
                        ],
                        [
                            'key' => 'admin-courses',
                            'label' => 'Courses',
                            'icon' => 'fi fi-rr-book-alt',
                            'href' => '#',
                        ],
                    ],
                ],
                [
                    'label' => 'Account',
                    'items' => $commonItems,
                ],
            ];
        }

        // Default student sidebar
        return [
            [
                'label' => 'For Students',
                'items' => [
                    [
                        'key' => 'student-dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'fi fi-rr-apps',
                        'href' => route('dashboard'),
                    ],
                ],
            ],
            [
                'label' => 'Account',
                'items' => $commonItems,
            ],
        ];
    }

    protected function dashboardCollegeStudents(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        if (Auth::check() && Auth::user()->role?->name === 'college') {
            $college = College::where('user_id', Auth::id())->first();
            if ($college) {
                $query->where('college_id', $college->id);
            }
        }

        return $query->limit(5)->get()->map(function (Student $student) {
            $latestEnrollment = $student->enrollments->first();
            $progress = $latestEnrollment?->progress;

            return [
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email ?? '',
                'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                'progress' => $progress !== null ? $progress . '%' : '0%',
                'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                'joined' => $student->created_at?->diffForHumans() ?? 'Just now',
            ];
        })->toArray();
    }

    protected function dashboardCollegeOverviewData(): array
    {
        $college = Auth::check() && Auth::user()->role?->name === 'college'
            ? College::where('user_id', Auth::id())->first()
            : null;

        if (! $college) {
            return [
                'recentStudents' => [],
                'topCourses' => [],
                'activities' => [],
                'announcements' => [],
                'statCards' => [],
            ];
        }

        $studentIds = Student::where('college_id', $college->id)->pluck('id');

        $totalStudents = $studentIds->count();
        $totalEnrollments = Enrollment::whereIn('student_id', $studentIds)->count();
        $completedEnrollments = Enrollment::whereIn('student_id', $studentIds)
            ->where('status', 'completed')
            ->count();
        $activeStudents = Student::where('college_id', $college->id)
            ->whereHas('enrollments', fn ($query) => $query->where('status', 'active'))
            ->count();
        $placementRate = $totalEnrollments ? round($completedEnrollments * 100 / $totalEnrollments) : 0;

        $recentStudents = Student::with(['user', 'enrollments.course'])
            ->whereIn('id', $studentIds)
            ->latest('created_at')
            ->limit(4)
            ->get()
            ->map(function (Student $student) {
                $latestEnrollment = $student->enrollments->sortByDesc('enrollment_date')->first();

                return [
                    'name' => $student->user?->name ?? 'Unknown Student',
                    'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                    'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                    'joined' => $student->created_at?->diffForHumans() ?? 'Just now',
                ];
            })->toArray();

        $enrollments = Enrollment::with(['student.user', 'course'])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('updated_at')
            ->get();

        $topCourses = $enrollments
            ->groupBy(fn (Enrollment $enrollment) => $enrollment->course?->title ?? 'Unknown')
            ->map(function ($group, $courseName) {
                $count = $group->count();
                $completed = $group->where('status', 'completed')->count();

                return [
                    'name' => $courseName,
                    'enrollments' => $count,
                    'completion' => $count ? round($completed * 100 / $count) . '%' : '0%',
                ];
            })
            ->sortByDesc(fn ($course) => $course['enrollments'])
            ->take(4)
            ->values()
            ->toArray();

        if (empty($topCourses)) {
            $topCourses = Course::orderBy('title')
                ->limit(4)
                ->get()
                ->map(fn (Course $course) => [
                    'name' => $course->title,
                    'enrollments' => 0,
                    'completion' => '0%',
                ])->toArray();
        }

        $activities = $enrollments->take(4)->map(function (Enrollment $enrollment) {
            $studentName = $enrollment->student->user?->name ?? 'Student';
            $courseTitle = $enrollment->course?->title ?? 'course';
            $isCompleted = $enrollment->status === 'completed';

            return [
                'title' => $isCompleted
                    ? "{$studentName} completed {$courseTitle}"
                    : "{$studentName} enrolled in {$courseTitle}",
                'time' => $enrollment->updated_at?->diffForHumans() ?? 'Just now',
                'tone' => $isCompleted ? 'green' : 'blue',
            ];
        })->toArray();

        $announcements = Notification::where('user_id', Auth::id())
            ->latest()
            ->limit(3)
            ->get()
            ->map(function (Notification $notification) {
                return [
                    'title' => $notification->message,
                    'meta' => 'College update',
                ];
            })->toArray();

        if (empty($announcements)) {
            $announcements = [
                ['title' => 'Placement readiness review scheduled for Friday', 'meta' => 'Academic coordination'],
                ['title' => 'Q3 student engagement report is now available', 'meta' => 'Analytics update'],
                ['title' => 'Internship mentor session opens next week', 'meta' => 'Program notice'],
            ];
        }

        $statCards = [
            [
                'label' => 'Total Students',
                'value' => number_format($totalStudents),
                'change' => '+0%',
                'icon' => 'fi fi-rr-users',
                'classes' => 'from-blue-500/15 to-cyan-400/10 text-blue-700',
            ],
            [
                'label' => 'Active Students',
                'value' => number_format($activeStudents),
                'change' => '+0%',
                'icon' => 'fi fi-rr-chart-line-up',
                'classes' => 'from-violet-500/15 to-indigo-400/10 text-violet-700',
            ],
            [
                'label' => 'Total Enrollments',
                'value' => number_format($totalEnrollments),
                'change' => '+0%',
                'icon' => 'fi fi-rr-book-alt',
                'classes' => 'from-emerald-500/15 to-lime-400/10 text-emerald-700',
            ],
            [
                'label' => 'Placement Rate',
                'value' => "{$placementRate}%",
                'change' => '+0%',
                'icon' => 'fi fi-rr-briefcase',
                'classes' => 'from-orange-500/15 to-amber-400/10 text-orange-700',
            ],
        ];

        return compact('recentStudents', 'topCourses', 'activities', 'announcements', 'statCards');
    }

    protected function dashboardStudentOverviewData(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [
                'currentTrack' => 'Full Stack Development Internship',
                'totalEnrolled' => 0,
                'activeCourses' => 0,
                'completedCourses' => 0,
                'tasks' => [],
            ];
        }

        $student = Student::with(['enrollments.course.tasks'])
            ->where('user_id', $user->id)
            ->first();

        $enrollments = $student?->enrollments ?? collect();
        $totalEnrolled = $enrollments->count();
        $activeCourses = $enrollments->where('status', 'active')->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();

        $latestEnrollment = $enrollments->sortByDesc('enrollment_date')->first();
        $currentTrack = $latestEnrollment?->course?->title ?? $student?->course_name ?? 'Learning Track';

        $courseTasks = $latestEnrollment?->course?->tasks ?? collect();
        $statuses = ['Pending', 'Review', 'Done'];
        $tones = [
            'bg-amber-50 text-amber-700 ring-amber-200',
            'bg-blue-50 text-blue-700 ring-blue-200',
            'bg-emerald-50 text-emerald-700 ring-emerald-200',
        ];

        $tasks = $courseTasks->take(3)->values()->map(function ($task, $index) use ($statuses, $tones) {
            return [
                'title' => $task->title,
                'deadline' => ['Due today', 'Mentor review', 'Submitted'][$index] ?? 'Soon',
                'status' => $statuses[$index] ?? 'Pending',
                'tone' => $tones[$index] ?? 'bg-slate-100 text-slate-700 ring-slate-200',
            ];
        })->toArray();

        if (empty($tasks)) {
            $tasks = [
                ['title' => 'Build portfolio API', 'deadline' => 'Due today', 'status' => 'Pending', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
                ['title' => 'React dashboard checkpoint', 'deadline' => 'Mentor review', 'status' => 'Review', 'tone' => 'bg-blue-50 text-blue-700 ring-blue-200'],
                ['title' => 'Git deployment lab', 'deadline' => 'Submitted', 'status' => 'Done', 'tone' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
            ];
        }

        return compact('currentTrack', 'totalEnrolled', 'activeCourses', 'completedCourses', 'tasks');
    }

    protected function collegeStudentManagementData(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        if (Auth::check() && Auth::user()->role?->name === 'college') {
            $college = College::where('user_id', Auth::id())->first();
            if ($college) {
                $query->where('college_id', $college->id);
            }
        }

        return $query->get()->map(function (Student $student) {
            $latestEnrollment = $student->enrollments->first();

            return [
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email ?? '',
                'course' => $student->course_name ?? $latestEnrollment?->course?->title ?? 'Not enrolled',
                'status' => $latestEnrollment?->status === 'completed' ? 'Completed' : 'Active',
                'joined_date' => $student->created_at?->format('F d, Y') ?? 'N/A',
            ];
        })->toArray();
    }

    protected function collegeStudentCourseOptions(): array
    {
        return Course::orderBy('title')->pluck('title')->toArray();
    }

    protected function collegeEnrollmentsData(): array
    {
        return Enrollment::with(['student.user', 'course'])
            ->orderBy('enrollment_date', 'desc')
            ->get()
            ->map(function (Enrollment $enrollment) {
                return [
                    'student_name' => $enrollment->student->user?->name ?? 'Unknown Student',
                    'course_name' => $enrollment->course?->title ?? 'Unknown Course',
                    'enrollment_date' => $enrollment->enrollment_date?->format('F d, Y') ?? 'N/A',
                    'progress' => $enrollment->progress,
                    'status' => $enrollment->status === 'completed' ? 'Completed' : 'Active',
                    'last_activity' => $enrollment->updated_at?->diffForHumans() ?? 'No activity yet',
                ];
            })->toArray();
    }

    protected function collegeEnrollmentStudentOptions(): array
    {
        return Student::with('user')
            ->get()
            ->map(fn (Student $student) => $student->user?->name ?? 'Unknown Student')
            ->toArray();
    }

    protected function collegeEnrollmentCourseOptions(): array
    {
        return Course::orderBy('title')->pluck('title')->toArray();
    }

    protected function studentCourseWorkspaceData(int $id): array
    {
        $course = Course::with('tasks')->find($id);

        if (!$course) {
            // Return default if course not found
            return [
                'id' => $id,
                'title' => 'Unknown Course',
                'completed_lessons' => 0,
                'total_lessons' => 16,
                'progress' => 0,
                'current_module' => 'module-1',
                'description' => 'Course not found.',
                'phases' => [],
                'module_content' => [],
                'tasks' => [],
            ];
        }

        $tasks = $course->tasks()->orderBy('created_at')->get();

        // Build phases and modules from tasks
        $phases = [];
        $modules = [];
        $moduleContent = [];
        $phaseIndex = 1;

        foreach ($tasks as $index => $task) {
            $moduleId = 'module-' . ($index + 1);
            $modules[] = [
                'id' => $moduleId,
                'title' => $task->title,
                'state' => $index === 0 ? 'active' : 'locked',
            ];
            $moduleContent[$moduleId] = [
                'title' => $task->title,
                'description' => $task->description ?? 'Complete this module to progress.',
            ];

            // Create a new phase every 3 modules
            if (($index + 1) % 3 === 0 || $index === $tasks->count() - 1) {
                $phases[] = [
                    'title' => 'Month ' . $phaseIndex . ': Learning',
                    'modules' => $modules,
                ];
                $modules = [];
                $phaseIndex++;
            }
        }

        // Get student progress if available
        $user = Auth::user();
        $student = $user ? Student::where('user_id', $user->id)->first() : null;
        $enrollment = $student ? Enrollment::where('student_id', $student->id)->where('course_id', $course->id)->first() : null;
        $progress = $enrollment ? $enrollment->progress : 0;
        $currentModule = 'module-' . max(1, min($tasks->count(), ceil($progress / 10) + 1));

        // Default tasks (assuming Task model has tasks, but here it's static)
        $defaultTasks = [
            ['id' => 'task-1', 'title' => 'Digital Persona Card'],
            ['id' => 'task-2', 'title' => 'Product Waitlist Landing Page'],
            ['id' => 'task-3', 'title' => 'Local Cafe Menu'],
            ['id' => 'task-4', 'title' => 'Portfolio Hero Section'],
            ['id' => 'task-5', 'title' => 'Course Enrollment Flow'],
            ['id' => 'task-6', 'title' => 'Freelancer Service Page'],
            ['id' => 'task-7', 'title' => 'Student Dashboard Summary'],
            ['id' => 'task-8', 'title' => 'Mobile Travel Planner'],
            ['id' => 'task-9', 'title' => 'Job Application Tracker'],
            ['id' => 'task-10', 'title' => 'AI Tool Comparison Table'],
        ];

        $tasksWithDetails = array_map(function (array $task) use ($course) {
            return [
                ...$task,
                'instructions' => "Create a polished {$task['title']} solution that fits the tone and workflow of {$course->title}.",
                'requirements' => [
                    'Use clear information hierarchy and intentional spacing.',
                    'Include responsive states for desktop and mobile.',
                    'Show practical thinking, not just visual styling.',
                ],
            ];
        }, $defaultTasks);

        return [
            'id' => $course->id,
            'title' => $course->title,
            'completed_lessons' => $progress,
            'total_lessons' => 16, // Default, could be calculated
            'progress' => $progress,
            'current_module' => $currentModule,
            'description' => $course->description ?? 'Build practical skills.',
            'phases' => $phases,
            'module_content' => $moduleContent,
            'tasks' => $tasksWithDetails,
        ];
    }

    public function about()
    {
        $about = [
            'eyebrow' => 'About Engineers Clinic',
            'title' => 'We build internship-first learning that feels practical from day one.',
            'description' => 'Engineers Clinic helps students and early professionals move from theory to execution with guided programs, clear learning paths, and industry-shaped skill tracks.',
            'pillars' => [
                [
                    'title' => 'Structured tracks',
                    'description' => 'Programs are grouped like our mega menu so learners can move quickly toward the domain that fits their goals.',
                ],
                [
                    'title' => 'Project-led learning',
                    'description' => 'Every path is designed around implementation, output, and confidence building rather than passive theory.',
                ],
                [
                    'title' => 'Career clarity',
                    'description' => 'From enrollment to guidance, we focus on helping learners understand where each skill track can take them.',
                ],
            ],
            'stats' => [
                ['value' => '10+', 'label' => 'Career-focused learning tracks'],
                ['value' => 'Project-led', 'label' => 'Hands-on delivery model'],
                ['value' => 'Student-first', 'label' => 'Built for accessible guidance'],
            ],
        ];

        return view('pages.about', compact('about'));
    }
}
