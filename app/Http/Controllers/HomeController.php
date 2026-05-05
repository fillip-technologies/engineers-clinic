<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\QuizResult;
use App\Models\Quiz;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    protected array $courseFamilies = [
        'ui-ux-product-design-professional' => [
            'menu_group' => 'Design & Product',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Design product experiences end to end',
            'career_path' => 'UI, UX, and product systems track',
        ],
        'data-science-analytics-expert' => [
            'menu_group' => 'Data & Analytics',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Turn data into practical decisions',
            'career_path' => 'Analytics, dashboards, and insight track',
        ],
        'b2b-digital-marketing-automation-mba-bba' => [
            'menu_group' => 'Business & Marketing',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build growth systems for B2B brands',
            'career_path' => 'Digital funnels and automation track',
        ],
        'aws-cloud-solutions-architect' => [
            'menu_group' => 'Cloud & Infrastructure',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Architect and scale cloud systems',
            'career_path' => 'AWS infrastructure and deployment track',
        ],
        'btech-civil-engineering-smart-city-bim-infrastructure' => [
            'menu_group' => 'Civil Engineering',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Plan smarter infrastructure systems',
            'career_path' => 'BIM and smart city engineering track',
        ],
        'btech-mechanical-engineering-digital-twin-automation' => [
            'menu_group' => 'Mechanical Engineering',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Connect machines with digital intelligence',
            'career_path' => 'Automation and digital twin track',
        ],
        'btech-electrical-electronics-iot-power-grids' => [
            'menu_group' => 'Electrical & Electronics',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Work with IoT-enabled power systems',
            'career_path' => 'Connected devices and smart grids track',
        ],
        'llb-corporate-law-legal-tech-tech-law' => [
            'menu_group' => 'Law & Legal Tech',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Navigate legal work in digital contexts',
            'career_path' => 'Corporate law and legal tech track',
        ],
        'mass-communication-journalism-digital-media-pr-tech' => [
            'menu_group' => 'Media & Communication',
            'menu_group_label' => 'AI Remote Internships',
            'hero_badge' => 'Build digital-first media communication skills',
            'career_path' => 'Journalism, media, and PR tech track',
        ],
    ];

    public function index()
    {
        $courses = $this->loadAllCourses();

        return view('pages.home', compact('courses'));
    }

    public function login()
    {
        return view('pages.login');
    }

    public function dashboard()
    {
        $user = Auth::user();

        // Determine role and set appropriate values
        $roleName = $user?->role?->name ?? 'student';

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
                ],
            ],
        ];

        abort_unless(isset($roles[$role]), 404);

        return view('pages.signup', [
            'role' => $role,
            'signup' => $roles[$role],
        ]);
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
            'phases' => $this->getCoursePhases($course),
            'module_content' => $this->getCourseModules($course),
        ];

        return view('dashboard.student-dashboard.course.show', [
            'course' => $courseData,
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }
    
    /**
     * Get course phases/modules structure
     */
    protected function getCoursePhases($course): array
    {
        if (!$course) {
            return [];
        }
        
        // Get tasks for this course as modules
        $tasks = $course->tasks()->orderBy('created_at')->get();
        
        if ($tasks->isEmpty()) {
            // Return default phases if no tasks
            return [
                [
                    'title' => 'Month 1: Getting Started',
                    'modules' => [
                        ['id' => 'module-1', 'title' => 'Introduction', 'state' => 'active'],
                        ['id' => 'module-2', 'title' => 'Basics', 'state' => 'locked'],
                    ],
                ],
            ];
        }
        
        $phases = [];
        $phaseIndex = 1;
        $modules = [];
        
        foreach ($tasks as $index => $task) {
            $modules[] = [
                'id' => 'module-' . ($index + 1),
                'title' => $task->title,
                'state' => $index === 0 ? 'active' : 'locked',
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
        
        return $phases;
    }
    
    /**
     * Get course module content
     */
    protected function getCourseModules($course): array
    {
        if (!$course) {
            return [];
        }
        
        $tasks = $course->tasks()->orderBy('created_at')->get();
        
        $modules = [];
        foreach ($tasks as $index => $task) {
            $modules['module-' . ($index + 1)] = [
                'title' => $task->title,
                'description' => $task->description ?? 'Complete this module to progress.',
            ];
        }
        
        return $modules;
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
        return view('dashboard.college.student-edit', [
            'student' => $this->collegeStudentManagementData()[1],
            'courseOptions' => $this->collegeStudentCourseOptions(),
            ...$this->frontendAdminData('college-students'),
        ]);
    }

    public function studentShow()
    {
        return view('dashboard.college.student-show', [
            'student' => $this->collegeStudentManagementData()[0],
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
        return view('dashboard.college.enrollments.edit', [
            'enrollment' => $this->collegeEnrollmentsData()[1],
            'students' => $this->collegeEnrollmentStudentOptions(),
            'courses' => $this->collegeEnrollmentCourseOptions(),
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function enrollmentShow()
    {
        return view('dashboard.college.enrollments.show', [
            'enrollment' => $this->collegeEnrollmentsData()[0],
            ...$this->frontendAdminData('college-enrollments'),
        ]);
    }

    public function courseDetail($slug)
    {
        return $this->show($slug);
    }

    public function show($slug)
    {
        $course = $this->loadCourseBySlug($slug);

        abort_unless($course, 404);

        return view('course.detail', compact('course'));
    }

    protected function loadAllCourses(): array
    {
        $courseFiles = glob(resource_path('data/courses/*.json')) ?: [];

        $courses = [];

        foreach ($courseFiles as $courseFile) {
            $course = json_decode(file_get_contents($courseFile), true);

            if (! is_array($course) || ! isset($course['slug'])) {
                continue;
            }

            $courses[] = $this->attachCourseMeta($course);
        }

        usort($courses, fn (array $first, array $second) => strcmp($first['title'], $second['title']));

        return $courses;
    }

    protected function loadCourseBySlug(string $slug): ?array
    {
        $courseFile = resource_path("data/courses/{$slug}.json");

        if (! is_file($courseFile)) {
            return null;
        }

        $course = json_decode(file_get_contents($courseFile), true);

        if (! is_array($course)) {
            return null;
        }

        return $this->attachCourseMeta($course);
    }

    protected function attachCourseMeta(array $course): array
    {
        $slug = $course['slug'] ?? '';

        $meta = $this->courseFamilies[$slug] ?? [
            'menu_group' => 'AI Remote Internships',
            'menu_group_label' => 'Our Programs',
            'hero_badge' => 'Structured practical learning',
            'career_path' => 'Career-focused guided track',
        ];

        $course['menu_group'] = $meta['menu_group'];
        $course['menu_group_label'] = $meta['menu_group_label'];
        $course['hero_badge'] = $course['hero_badge'] ?? $meta['hero_badge'];
        $course['career_path'] = $course['career_path'] ?? $meta['career_path'];

        return $course;
    }

    protected function frontendAdminData(string $activePage, string $role = 'student'): array
    {
        $user = Auth::user();
        $role = Auth::check() ? Auth::user()->role->name : 'student';
        // dd($role);

        return [
            'sidebarSections' => $this->dashboardSidebarSections($role),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => $user ? $user->name : 'Guest',
            'sidebarUserMeta' => $user && $user->email ? $user->email : 'Unified Dashboard',
            'navbarUserName' => $user ? explode(' ', $user->name)[0] : 'Guest',
            'collegeStudents' => $this->dashboardCollegeStudents(),
        ];
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
                            'label' => 'Order History',
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
        return [
            ['name' => 'Aarav Sharma', 'email' => 'aarav.sharma@abccollege.edu', 'course' => 'Full Stack Development', 'progress' => '78%', 'status' => 'Active'],
            ['name' => 'Priya Verma', 'email' => 'priya.verma@abccollege.edu', 'course' => 'Frontend Development', 'progress' => '64%', 'status' => 'Active'],
            ['name' => 'Rohan Mehta', 'email' => 'rohan.mehta@abccollege.edu', 'course' => 'UI/UX Design', 'progress' => '100%', 'status' => 'Completed'],
            ['name' => 'Sneha Iyer', 'email' => 'sneha.iyer@abccollege.edu', 'course' => 'Data Analytics', 'progress' => '52%', 'status' => 'Active'],
        ];
    }

    protected function collegeStudentManagementData(): array
    {
        return [
            [
                'name' => 'Aarav Sharma',
                'email' => 'aarav.sharma@abccollege.edu',
                'course' => 'Full Stack Development',
                'status' => 'Active',
                'joined_date' => 'April 12, 2026',
            ],
            [
                'name' => 'Priya Verma',
                'email' => 'priya.verma@abccollege.edu',
                'course' => 'Frontend Development',
                'status' => 'Active',
                'joined_date' => 'April 08, 2026',
            ],
            [
                'name' => 'Rohan Mehta',
                'email' => 'rohan.mehta@abccollege.edu',
                'course' => 'UI/UX Design',
                'status' => 'Inactive',
                'joined_date' => 'March 30, 2026',
            ],
            [
                'name' => 'Sneha Iyer',
                'email' => 'sneha.iyer@abccollege.edu',
                'course' => 'Data Analytics',
                'status' => 'Active',
                'joined_date' => 'March 18, 2026',
            ],
            [
                'name' => 'Karan Malhotra',
                'email' => 'karan.malhotra@abccollege.edu',
                'course' => 'Cloud Computing',
                'status' => 'Inactive',
                'joined_date' => 'February 27, 2026',
            ],
        ];
    }

    protected function collegeStudentCourseOptions(): array
    {
        return [
            'Full Stack Development',
            'Frontend Development',
            'UI/UX Design',
            'Data Analytics',
            'Cloud Computing',
        ];
    }

    protected function collegeEnrollmentsData(): array
    {
        return [
            [
                'student_name' => 'Aarav Sharma',
                'course_name' => 'Full Stack Development',
                'enrollment_date' => 'April 12, 2026',
                'progress' => 78,
                'status' => 'Active',
                'last_activity' => 'Completed Module 5 yesterday',
            ],
            [
                'student_name' => 'Priya Verma',
                'course_name' => 'Frontend Development',
                'enrollment_date' => 'April 08, 2026',
                'progress' => 64,
                'status' => 'Active',
                'last_activity' => 'Submitted assignment 2 hours ago',
            ],
            [
                'student_name' => 'Rohan Mehta',
                'course_name' => 'UI/UX Design',
                'enrollment_date' => 'March 30, 2026',
                'progress' => 100,
                'status' => 'Completed',
                'last_activity' => 'Course completed on April 20, 2026',
            ],
            [
                'student_name' => 'Sneha Iyer',
                'course_name' => 'Data Analytics',
                'enrollment_date' => 'March 18, 2026',
                'progress' => 52,
                'status' => 'Active',
                'last_activity' => 'Joined live session 1 day ago',
            ],
            [
                'student_name' => 'Karan Malhotra',
                'course_name' => 'Cloud Computing',
                'enrollment_date' => 'February 27, 2026',
                'progress' => 24,
                'status' => 'Dropped',
                'last_activity' => 'No activity for 3 weeks',
            ],
        ];
    }

    protected function collegeEnrollmentStudentOptions(): array
    {
        return [
            'Aarav Sharma',
            'Priya Verma',
            'Rohan Mehta',
            'Sneha Iyer',
            'Karan Malhotra',
        ];
    }

    protected function collegeEnrollmentCourseOptions(): array
    {
        return [
            'Full Stack Development',
            'Frontend Development',
            'UI/UX Design',
            'Data Analytics',
            'Cloud Computing',
        ];
    }

    protected function studentProfileData(): array
    {
        return [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'age' => '22',
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
        ];
    }

    protected function studentQuizAttemptsData(): array
    {
        return [
            [
                'title' => 'Frontend Foundations Quiz',
                'course' => 'Frontend Development Internship',
                'attempt' => 'Attempt 2 of 3',
                'score' => '82%',
                'status' => 'Passed',
                'updated_at' => 'Last attempted on April 26, 2026',
                'action' => 'Review Attempt',
            ],
            [
                'title' => 'API Basics Assessment',
                'course' => 'Full Stack Development Internship',
                'attempt' => 'Attempt 1 of 2',
                'score' => 'Pending',
                'status' => 'Upcoming',
                'updated_at' => 'Available until April 30, 2026',
                'action' => 'Start Quiz',
            ],
            [
                'title' => 'User Research Checkpoint',
                'course' => 'UI/UX Design Internship',
                'attempt' => 'Attempt 1 of 1',
                'score' => '91%',
                'status' => 'Completed',
                'updated_at' => 'Submitted on April 18, 2026',
                'action' => 'View Summary',
            ],
        ];
    }

    protected function studentOrderHistoryData(): array
    {
        return [
            [
                'title' => 'Full Stack Internship',
                'order_id' => 'ORD-1023',
                'purchase_date' => 'April 21, 2026',
                'price' => 'Rs. 4,999',
                'payment_status' => 'Paid',
                'access_status' => 'Active',
            ],
            [
                'title' => 'AI Automation Program',
                'order_id' => 'ORD-1041',
                'purchase_date' => 'April 10, 2026',
                'price' => 'Rs. 6,499',
                'payment_status' => 'Pending',
                'access_status' => 'Active',
            ],
            [
                'title' => 'UI/UX Design Course',
                'order_id' => 'ORD-0988',
                'purchase_date' => 'March 28, 2026',
                'price' => 'Rs. 3,799',
                'payment_status' => 'Failed',
                'access_status' => 'Expired',
            ],
        ];
    }

    protected function studentCourseWorkspaceData(int $id): array
    {
        $courses = [
            1 => [
                'id' => 1,
                'title' => 'Full Stack Development Internship',
                'completed_lessons' => 2,
                'total_lessons' => 16,
                'progress' => 13,
                'current_module' => 'module-2',
                'description' => 'Build practical full-stack delivery skills across frontend, backend, APIs, and deployment.',
                'phases' => [
                    [
                        'title' => 'Month 1: Learning & Frameworks',
                        'modules' => [
                            ['id' => 'module-1', 'title' => 'Module 1: Foundations', 'state' => 'completed'],
                            ['id' => 'module-2', 'title' => 'Module 2: UI Planning', 'state' => 'active'],
                            ['id' => 'module-3', 'title' => 'Module 3: Frontend Build', 'state' => 'locked'],
                        ],
                    ],
                    [
                        'title' => 'Month 2: Product Buildout',
                        'modules' => [
                            ['id' => 'module-4', 'title' => 'Module 4: Backend Workflows', 'state' => 'locked'],
                            ['id' => 'module-5', 'title' => 'Module 5: Deployment Flow', 'state' => 'locked'],
                        ],
                    ],
                ],
                'module_content' => [
                    'module-1' => [
                        'title' => 'Module 1: Foundations',
                        'description' => 'Understand the program workflow, project expectations, and how product tasks map to real-world outcomes.',
                    ],
                    'module-2' => [
                        'title' => 'Module 2: UI Planning',
                        'description' => 'Choose one focused practical task and complete it end to end with structured deliverables and submission steps.',
                    ],
                    'module-3' => [
                        'title' => 'Module 3: Frontend Build',
                        'description' => 'Move from planning into implementation using reusable components, layouts, and real interface states.',
                    ],
                ],
            ],
            2 => [
                'id' => 2,
                'title' => 'Frontend Development Internship',
                'completed_lessons' => 8,
                'total_lessons' => 16,
                'progress' => 50,
                'current_module' => 'module-2',
                'description' => 'Learn how to ship polished, responsive UI systems with real workflows and feedback loops.',
                'phases' => [
                    [
                        'title' => 'Month 1: Design to UI',
                        'modules' => [
                            ['id' => 'module-1', 'title' => 'Module 1: Visual Systems', 'state' => 'completed'],
                            ['id' => 'module-2', 'title' => 'Module 2: Landing Page Tasks', 'state' => 'active'],
                            ['id' => 'module-3', 'title' => 'Module 3: Interaction States', 'state' => 'locked'],
                        ],
                    ],
                ],
                'module_content' => [
                    'module-1' => [
                        'title' => 'Module 1: Visual Systems',
                        'description' => 'Build clarity around typography, spacing, alignment, and repeatable UI structure.',
                    ],
                    'module-2' => [
                        'title' => 'Module 2: Landing Page Tasks',
                        'description' => 'Select one frontend build task and take it from brief to clean implementation.',
                    ],
                    'module-3' => [
                        'title' => 'Module 3: Interaction States',
                        'description' => 'Focus on hover states, tabs, selection patterns, and polished product motion.',
                    ],
                ],
            ],
            3 => [
                'id' => 3,
                'title' => 'UI/UX Design Internship',
                'completed_lessons' => 16,
                'total_lessons' => 16,
                'progress' => 100,
                'current_module' => 'module-1',
                'description' => 'Create research-backed flows, product wireframes, and delivery-ready UI systems.',
                'phases' => [
                    [
                        'title' => 'Month 1: Product Thinking',
                        'modules' => [
                            ['id' => 'module-1', 'title' => 'Module 1: Research and Framing', 'state' => 'completed'],
                            ['id' => 'module-2', 'title' => 'Module 2: Wireframe Practice', 'state' => 'completed'],
                        ],
                    ],
                ],
                'module_content' => [
                    'module-1' => [
                        'title' => 'Module 1: Research and Framing',
                        'description' => 'Explore user context, task framing, and how to translate problem statements into design decisions.',
                    ],
                    'module-2' => [
                        'title' => 'Module 2: Wireframe Practice',
                        'description' => 'Build and iterate simple, useful wireframes with clear content hierarchy and flow.',
                    ],
                ],
            ],
        ];

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

        $selectedCourse = $courses[$id] ?? $courses[1];
        $selectedCourse['tasks'] = array_map(function (array $task) use ($selectedCourse) {
            return [
                ...$task,
                'instructions' => "Create a polished {$task['title']} solution that fits the tone and workflow of {$selectedCourse['title']}.",
                'requirements' => [
                    'Use clear information hierarchy and intentional spacing.',
                    'Include responsive states for desktop and mobile.',
                    'Show practical thinking, not just visual styling.',
                ],
            ];
        }, $defaultTasks);

        return $selectedCourse;
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
