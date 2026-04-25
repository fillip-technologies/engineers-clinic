<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $activePage = request()->routeIs('college.dashboard')
            ? 'college-dashboard'
            : 'student-dashboard';

        return view('dashboard.home', $this->frontendAdminData($activePage));
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
        $enrolledCourses = [
            [
                'id' => 1,
                'title' => 'Full Stack Development Internship',
                'image' => '/images/courses/full-stack-development.svg',
                'description' => 'Build production-ready applications with frontend, backend, and deployment workflows.',
                'completed_lessons' => 2,
                'total_lessons' => 16,
                'progress' => 13,
                'status' => 'Active',
            ],
            [
                'id' => 2,
                'title' => 'Frontend Development Internship',
                'image' => '/images/courses/frontend-development.svg',
                'description' => 'Create polished, responsive interfaces using modern frontend engineering practices.',
                'completed_lessons' => 8,
                'total_lessons' => 16,
                'progress' => 50,
                'status' => 'Active',
            ],
            [
                'id' => 3,
                'title' => 'UI/UX Design Internship',
                'image' => '/images/courses/ui-ux-design.svg',
                'description' => 'Design intuitive product flows, wireframes, and high-fidelity interface systems.',
                'completed_lessons' => 16,
                'total_lessons' => 16,
                'progress' => 100,
                'status' => 'Completed',
            ],
        ];

        return view('dashboard.student-dashboard.enrollments.index', [
            'enrolledCourses' => $enrolledCourses,
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }

    public function studentCourse($id)
    {
        return view('dashboard.student-dashboard.course.show', [
            'course' => $this->studentCourseWorkspaceData((int) $id),
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }

    public function studentProfile()
    {
        return view('dashboard.student-dashboard.profile.index', [
            'profile' => $this->studentProfileData(),
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function studentProfileEdit()
    {
        return view('dashboard.student-dashboard.profile.edit', [
            'profile' => $this->studentProfileData(),
            ...$this->frontendAdminData('student-profile'),
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
        $course['hero_badge'] = $meta['hero_badge'];
        $course['career_path'] = $meta['career_path'];

        return $course;
    }

    protected function frontendAdminData(string $activePage): array
    {
        return [
            'sidebarSections' => $this->dashboardSidebarSections(),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => 'Aman Kumar',
            'sidebarUserMeta' => 'Unified Dashboard',
            'navbarUserName' => 'Aman',
            'collegeStudents' => $this->dashboardCollegeStudents(),
        ];
    }

    protected function dashboardSidebarSections(): array
    {
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
                        'icon' => 'fi fi-rr-document-signed',
                        'href' => '#',
                    ],
                    [
                        'key' => 'student-order-history',
                        'label' => 'Order History',
                        'icon' => 'fi fi-rr-receipt',
                        'href' => '#',
                    ],
                    [
                        'key' => 'student-question-answer',
                        'label' => 'Question & Answer',
                        'icon' => 'fi fi-rr-interrogation',
                        'href' => '#',
                    ],
                    [
                        'key' => 'student-settings',
                        'label' => 'Settings',
                        'icon' => 'fi fi-rr-settings',
                        'href' => '#',
                    ],
                    [
                        'key' => 'student-logout',
                        'label' => 'Logout',
                        'icon' => 'fi fi-rr-exit',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'For College',
                'items' => [],
            ],
            [
                'label' => 'Main',
                'items' => [
                    [
                        'key' => 'college-dashboard',
                        'label' => 'Dashboard',
                        'icon' => 'fi fi-rr-apps',
                        'href' => route('college.dashboard'),
                    ],
                ],
            ],
            [
                'label' => 'Students',
                'items' => [
                    [
                        'key' => 'college-students',
                        'label' => 'Student Management',
                        'icon' => 'fi fi-rr-users',
                        'href' => route('college.students'),
                        ],
                    [
                        'key' => 'college-enrollments',
                        'label' => 'Enrollments',
                        'icon' => 'fi fi-rr-book-alt',
                        'href' => route('college.enrollments'),
                    ],
                ],
            ],
            [
                'label' => 'Courses',
                'items' => [
                    [
                        'key' => 'college-courses',
                        'label' => 'Courses / Programs',
                        'icon' => 'fi fi-rr-book-alt',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Internship & Placement',
                'items' => [
                    [
                        'key' => 'college-internships',
                        'label' => 'Internships',
                        'icon' => 'fi fi-rr-briefcase',
                        'href' => '#',
                    ],
                    [
                        'key' => 'college-placements',
                        'label' => 'Placements',
                        'icon' => 'fi fi-rr-briefcase',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Analytics',
                'items' => [
                    [
                        'key' => 'college-progress',
                        'label' => 'Analytics & Reports',
                        'icon' => 'fi fi-rr-chart-line-up',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Communication',
                'items' => [
                    [
                        'key' => 'college-announcements',
                        'label' => 'Announcements / Notices',
                        'icon' => 'fi fi-rr-megaphone',
                        'href' => '#',
                    ],
                    [
                        'key' => 'college-support',
                        'label' => 'Q&A / Support',
                        'icon' => 'fi fi-rr-interrogation',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Finance',
                'items' => [
                    [
                        'key' => 'college-payments',
                        'label' => 'Payments / Revenue',
                        'icon' => 'fi fi-rr-receipt',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Management',
                'items' => [
                    [
                        'key' => 'college-certificates',
                        'label' => 'Certificates / Completion',
                        'icon' => 'fi fi-rr-diploma',
                        'href' => '#',
                    ],
                    [
                        'key' => 'college-attendance',
                        'label' => 'Attendance / Activity',
                        'icon' => 'fi fi-rr-chart-line-up',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Account',
                'items' => [
                    [
                        'key' => 'college-profile',
                        'label' => 'College Profile',
                        'icon' => 'fi fi-rr-user',
                        'href' => '#',
                    ],
                    [
                        'key' => 'college-admin-users',
                        'label' => 'Admin Users',
                        'icon' => 'fi fi-rr-users',
                        'href' => '#',
                    ],
                ],
            ],
            [
                'label' => 'Settings',
                'items' => [
                    [
                        'key' => 'college-settings',
                        'label' => 'Settings',
                        'icon' => 'fi fi-rr-settings',
                        'href' => '#',
                    ],
                    [
                        'key' => 'college-logout',
                        'label' => 'Logout',
                        'icon' => 'fi fi-rr-exit',
                        'href' => '#',
                    ],
                ],
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
