<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\College;
use App\Models\CollegePartnershipDiscussion;
use App\Models\Notification;
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

    public function collegeTieup()
    {
        return view('pages.college-tieup');
    }

    public function storeCollegePartnershipDiscussion(Request $request)
    {
        $validated = $request->validateWithBag('partnershipDiscussion', [
            'full_name' => ['required', 'string', 'max:255'],
            'institution_name' => ['required', 'string', 'max:255'],
            'official_email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'regex:/^[0-9+\-\s()]{7,20}$/'],
            'designation' => ['required', 'string', 'max:255'],
            'number_of_students' => ['required', 'integer', 'min:1', 'max:1000000'],
            'department_stream' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ], [
            'institution_name.required' => 'College / institution name is required.',
            'official_email.required' => 'Official email is required.',
            'official_email.email' => 'Enter a valid official email address.',
            'phone.regex' => 'Enter a valid phone number.',
            'department_stream.required' => 'Department / stream is required.',
        ]);

        CollegePartnershipDiscussion::create($validated);

        return back()->with('partnership_discussion_success', 'Your partnership discussion request has been submitted. Our team will contact you shortly.');
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
            'phases' => $this->getCoursePhases($course),
            'module_content' => $this->getCourseModules($course),
        ];

        return view('dashboard.student-dashboard.course.show', [
            'course' => $courseData,
            ...$this->frontendAdminData('student-enrolled-courses'),
        ]);
    }

//     public function studentCourseWorkspace($id)
//     {
//         $user = Auth::user();

//         if (!$user) {
//             return redirect()->route('login');
//         }

//         $student = Student::where('user_id', $user->id)->first();
//         $enrollment = null;

//         if ($student) {
//             $enrollment = Enrollment::with('course')
//                 ->where('student_id', $student->id)
//                 ->where('id', $id)
//                 ->first();

//             if (!$enrollment) {
//                 $enrollment = Enrollment::with('course')
//                     ->where('student_id', $student->id)
//                     ->get()
//                     ->firstWhere('course_id', $id);
//             }
//         }

//         $course = $enrollment?->course;
//         $project = request('project', 'portfolio-platform');
//         $progress = (int) ($enrollment?->progress ?? 42);
//         $title = match ($project) {
//             'task-manager' => 'Team Task Manager',
//             'analytics-dashboard' => 'Learning Analytics Dashboard',
//             default => 'Developer Portfolio Platform',
//         };

//         $workspace = [
//             'title' => $title,
//             'track' => $course?->title ?? 'Full Stack Engineering Bootcamp',
//             'headline' => $title,
//             'summary' => 'Follow the steps one by one. Read the explanation, try the code, check the output, and mark the step complete when you are done.',
//             'progress' => 20,
//             'next_milestone' => 'Continue from Authentication',
//             'student_name' => $user->name ?? 'Student',
//             'student_email' => $user->email ?? 'student@example.com',
//         ];

//         $steps = [
//             [
//                 'number' => 1,
//                 'slug' => 'setup-project',
//                 'nav_label' => 'Setup Project',
//                 'title' => 'Setup Project',
//                 'description' => 'Create a clean Laravel project structure and prepare your first commit.',
//                 'status' => 'Completed',
//                 'state' => 'completed',
//                 'active' => false,
//                 'build' => 'A clean starter project with Git initialized, routes ready, and a short README.',
//                 'why' => 'A clear foundation prevents confusion later when the project grows.',
//                 'lesson' => 'Before building features, make sure your folders, routes, and README explain what the project is about.',
//                 'file' => 'terminal',
//                 'code' => "mkdir portfolio-platform\ncd portfolio-platform\ngit init\nphp artisan serve",
//                 'expected_output' => 'The local Laravel app opens in your browser and your terminal shows the development server URL.',
//                 'preview_title' => 'Your browser should show the Laravel welcome screen or your starter homepage.',
//                 'preview_points' => ['Project opens locally', 'README explains the goal', 'Git repository is ready'],
//                 'task' => 'Create a README.md and write the project goal, features, and setup command.',
//                 'mistakes' => ['Starting feature work before Git is initialized', 'Keeping the README empty', 'Putting all files in one messy folder'],
//                 'tips' => ['Commit early with a simple message like setup project foundation.', 'Keep your first version small and working.'],
//                 'hint' => 'If php artisan serve fails, check that dependencies are installed with composer install.',
//                 'mentor_tip' => 'A clean setup is not wasted time. It makes debugging much easier later.',
//             ],
//             [
//                 'number' => 2,
//                 'slug' => 'authentication',
//                 'nav_label' => 'Authentication',
//                 'title' => 'Authentication',
//                 'description' => 'Build a simple login flow with validation and clear error messages.',
//                 'status' => 'In Progress',
//                 'state' => 'active',
//                 'active' => true,
//                 'build' => 'A login page where students can enter email and password safely.',
//                 'why' => 'Authentication protects private dashboards, student data, and project submissions.',
//                 'lesson' => 'A beginner-friendly login page should be simple: email, password, validation errors, and one clear submit button.',
//                 'file' => 'routes/web.php',
//                 'code' => "Route::get('/login', [AuthController::class, 'showLogin'])->name('login');\nRoute::post('/login', [AuthController::class, 'login'])->name('login.submit');",
//                 'expected_output' => 'The login page opens. Empty fields show helpful validation messages instead of confusing errors.',
//                 'preview_title' => 'Your login page should look like a centered form with email, password, and a blue login button.',
//                 'preview_points' => ['Email input is visible', 'Password input is visible', 'Validation message appears below the field'],
//                 'task' => 'Create the login form and show validation errors for empty email and password fields.',
//                 'mistakes' => ['Hiding validation errors', 'Using unclear button text', 'Redirecting users without feedback'],
//                 'tips' => ['Use labels above inputs.', 'Keep the form narrow so it is easy to scan.', 'Test with empty fields before testing correct login.'],
//                 'hint' => 'Start with the Blade form first, then connect the controller after the layout is clear.',
//                 'mentor_tip' => 'Authentication feels hard because it has several small pieces. Build one piece at a time.',
//             ],
//             [
//                 'number' => 3,
//                 'slug' => 'dashboard-ui',
//                 'nav_label' => 'Dashboard UI',
//                 'title' => 'Dashboard UI',
//                 'description' => 'Create the first student dashboard screen with one clear next action.',
//                 'status' => 'Locked',
//                 'state' => 'locked',
//                 'active' => false,
//                 'build' => 'A simple dashboard that welcomes the student and shows what to do next.',
//                 'why' => 'The dashboard is the student home base. It should reduce confusion, not add more choices.',
//                 'lesson' => 'Keep the first dashboard version focused: welcome text, progress, and one continue button.',
//                 'file' => 'dashboard.blade.php',
//                 'code' => "<section class=\"space-y-4\">\n    <h1>Welcome back</h1>\n    <p>Continue your latest project task.</p>\n    <a href=\"#steps\">Continue learning</a>\n</section>",
//                 'expected_output' => 'A calm dashboard screen with a welcome message and one obvious next step.',
//                 'preview_title' => 'Your dashboard should show a welcome message, current project, and continue button.',
//                 'preview_points' => ['No clutter', 'One primary button', 'Readable spacing on mobile'],
//                 'task' => 'Build a dashboard header with project name, progress bar, and continue button.',
//                 'mistakes' => ['Adding too many stats too early', 'Making every button look primary', 'Using tiny text'],
//                 'tips' => ['Use one main action per section.', 'Check mobile spacing before adding more content.'],
//                 'hint' => 'Sketch the dashboard on paper first: title, progress, button.',
//                 'mentor_tip' => 'Simple screens are not incomplete. Simple screens are easier for users to trust.',
//             ],
//             [
//                 'number' => 4,
//                 'slug' => 'crud',
//                 'nav_label' => 'CRUD',
//                 'title' => 'CRUD Features',
//                 'description' => 'Add create, read, update, and delete actions for project records.',
//                 'status' => 'Locked',
//                 'state' => 'locked',
//                 'active' => false,
//                 'build' => 'A small feature where users can add, edit, view, and delete a project item.',
//                 'why' => 'Most real web apps are built around CRUD. Learning it gives you practical backend confidence.',
//                 'lesson' => 'Start with Create and Read before adding Update and Delete. This keeps the workflow easier.',
//                 'file' => 'ProjectController.php',
//                 'code' => <<<'PHP'
// public function store(Request $request)
// {
//     $data = $request->validate([
//         'title' => ['required', 'max:100'],
//     ]);

//     Project::create($data);

//     return redirect()->back();
// }
// PHP,
//                 'expected_output' => 'Submitting the form creates a new project item and shows it in the list.',
//                 'preview_title' => 'Your CRUD screen should show a form on top and a list of saved items below.',
//                 'preview_points' => ['Create form works', 'Items appear after submit', 'Validation errors are readable'],
//                 'task' => 'Create the add-project form and show saved items in a simple list.',
//                 'mistakes' => ['Skipping validation', 'Deleting records without confirmation', 'Mixing too much logic in Blade'],
//                 'tips' => ['Validate first.', 'Build create/read before update/delete.', 'Use clear empty states.'],
//                 'hint' => 'If data does not save, check fillable fields on the model.',
//                 'mentor_tip' => 'CRUD becomes easier when you treat each action as its own small lesson.',
//             ],
//             [
//                 'number' => 5,
//                 'slug' => 'deployment',
//                 'nav_label' => 'Deployment',
//                 'title' => 'Deployment',
//                 'description' => 'Prepare your project for sharing with a mentor or reviewer.',
//                 'status' => 'Locked',
//                 'state' => 'locked',
//                 'active' => false,
//                 'build' => 'A final project link, screenshots, and notes about what you learned.',
//                 'why' => 'Deployment turns your local work into something other people can review and use.',
//                 'lesson' => 'Before sharing, check environment variables, screenshots, README, and basic mobile layout.',
//                 'file' => '.env.example',
//                 'code' => "APP_NAME=\"Portfolio Platform\"\nAPP_ENV=production\nAPP_DEBUG=false\nAPP_URL=https://your-project-url.com",
//                 'expected_output' => 'Your deployed project opens from a public URL without debug errors.',
//                 'preview_title' => 'Your final project should open from a public link and show the main screen correctly.',
//                 'preview_points' => ['Public link works', 'README has setup steps', 'Screenshots are attached'],
//                 'task' => 'Prepare your GitHub link, screenshot, and short learning summary.',
//                 'mistakes' => ['Leaving APP_DEBUG=true', 'Forgetting screenshots', 'Submitting without testing the link'],
//                 'tips' => ['Open your link in an incognito window.', 'Add screenshots to your README.', 'Write what you learned in plain words.'],
//                 'hint' => 'If the deployed page is blank, check logs and environment variables first.',
//                 'mentor_tip' => 'A clear submission makes your effort easy to review.',
//             ],
//         ];

//         $sidebarItems = array_map(function (array $step) {
//             return [
//                 'label' => $step['nav_label'],
//                 'target' => 'step-' . $step['slug'],
//                 'state' => $step['state'],
//                 'number' => $step['number'],
//             ];
//         }, $steps);

//         $sidebarItems[] = [
//             'label' => 'Submission',
//             'target' => 'submission',
//             'state' => 'locked',
//             'number' => count($steps) + 1,
//         ];

//         $resources = [
//             [
//                 'category' => 'Documentation',
//                 'items' => [
//                     ['label' => 'Laravel Docs', 'description' => 'Routes, controllers, validation, and Blade basics.', 'icon' => 'fi fi-rr-document', 'href' => 'https://laravel.com/docs'],
//                     ['label' => 'Laravel Validation', 'description' => 'Learn how request validation works.', 'icon' => 'fi fi-rr-shield-check', 'href' => 'https://laravel.com/docs/validation'],
//                 ],
//             ],
//             [
//                 'category' => 'Videos',
//                 'items' => [
//                     ['label' => 'YouTube Laravel Basics', 'description' => 'Watch a beginner route/controller walkthrough.', 'icon' => 'fi fi-rr-play-alt', 'href' => 'https://www.youtube.com/results?search_query=laravel+beginner+project+tutorial'],
//                     ['label' => 'Authentication Tutorial', 'description' => 'See how login forms are usually built.', 'icon' => 'fi fi-rr-user-lock', 'href' => 'https://www.youtube.com/results?search_query=laravel+authentication+tutorial'],
//                 ],
//             ],
//             [
//                 'category' => 'Examples',
//                 'items' => [
//                     ['label' => 'GitHub Example', 'description' => 'Review a simple Laravel project structure.', 'icon' => 'fi fi-rr-code-branch', 'href' => 'https://github.com/search?q=laravel+portfolio+project&type=repositories'],
//                     ['label' => 'UI Inspiration', 'description' => 'Look at simple login and dashboard layouts.', 'icon' => 'fi fi-rr-layout-fluid', 'href' => 'https://dribbble.com/search/dashboard-login'],
//                 ],
//             ],
//         ];

//         $mentorTip = [
//             'title' => 'Think in tiny shippable checkpoints',
//             'body' => 'Before adding another feature, open your current screen on mobile and desktop. A polished simple flow beats an unfinished complicated one every time.',
//         ];

//         $todayGoal = [
//             'title' => 'Complete Step 2',
//             'body' => 'Build the login form and check that validation errors are easy to understand.',
//             'time' => '45-60 min',
//         ];

//         return view('dashboard.student-dashboard.course.workspace', compact(
//             'workspace',
//             'sidebarItems',
//             'steps',
//             'mentorTip',
//             'todayGoal',
//             'resources'
//         ));
//     }

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

    if (!$enrollment || !$enrollment->course) {
        abort(404, 'Course not found');
    }

    $course = $enrollment->course;

    $progress = (int) ($enrollment->progress ?? 0);

    $workspace = [
        'title' => $course->title,
        'track' => $course->title,
        'headline' => $course->title,
        'summary' => $course->description,
        'progress' => $progress,
        'next_milestone' => 'Continue Learning',
        'student_name' => $user->name ?? 'Student',
        'student_email' => $user->email ?? 'student@example.com',
    ];

    /*
    |--------------------------------------------------------------------------
    | Curriculum Mapping
    |--------------------------------------------------------------------------
    */

    $curriculumMap = [];

    if (is_array($course->curriculum)) {

        foreach ($course->curriculum as $group) {

            foreach (($group['tasks'] ?? []) as $task) {

                $curriculumMap[$task['title']] = $task;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Dynamic Steps
    |--------------------------------------------------------------------------
    */

    $steps = [];

    $stepNumber = 1;

    if (is_array($course->phases)) {

        foreach ($course->phases as $phaseIndex => $phase) {

            foreach (($phase['modules'] ?? []) as $moduleIndex => $module) {

                foreach (($module['tasks'] ?? []) as $taskIndex => $task) {

                    $curriculumTask = $curriculumMap[$task] ?? [];

                    $steps[] = [

                        'number' => $stepNumber,

                        'slug' => \Illuminate\Support\Str::slug($task),

                        'nav_label' => $task,

                        'title' => $task,

                        'description' => $curriculumTask['assignment']
                            ?? 'Complete the task and submit your implementation.',

                        'status' => $stepNumber == 1
                            ? 'In Progress'
                            : 'Locked',

                        'state' => $stepNumber == 1
                            ? 'active'
                            : 'locked',

                        'active' => $stepNumber == 1,

                        'build' => $task,

                        'why' => 'This task improves practical implementation skills.',

                        'lesson' => $module['title'] ?? '',

                        'file' => 'resources/views/project.blade.php',

                        'code' => '// Complete this task implementation',

                        'expected_output' => $curriculumTask['submission']
                            ?? 'Submit your completed implementation.',

                        'preview_title' => $task,

                        'preview_points' => [
                            'Task completed',
                            'Submission uploaded',
                            'Review ready',
                        ],

                        'task' => $task,

                        'mistakes' => [
                            'Skipping validation',
                            'Incomplete submission',
                            'Not testing before submit',
                        ],

                        'tips' => [
                            'Break the task into smaller parts',
                            'Test before submitting',
                            'Read requirements carefully',
                        ],

                        'hint' => 'Read the task carefully before starting.',

                        'mentor_tip' => $curriculumTask['ai_review']
                            ?? 'Focus on understanding before optimization.',

                        'phase' => $phase['title'] ?? '',

                        'module' => $module['title'] ?? '',
                    ];

                    $stepNumber++;
                }
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Active Step
    |--------------------------------------------------------------------------
    */

    $activeStep = collect($steps)->firstWhere('active', true);

    if (!$activeStep && count($steps)) {
        $activeStep = $steps[0];
    }

    return view(
        'dashboard.student-dashboard.course.workspace',
        compact(
            'workspace',
            'steps',
            'activeStep',
            'course',
            'enrollment'
        )
    );
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

        $course['hero'] = $this->buildCourseHero($course);

        return $course;
    }

    protected function buildCourseHero(array $course): array
    {
        $overview = $course['program_overview'] ?? [];
        $overviewStats = is_array($overview) ? ($overview['stats'] ?? []) : [];
        $heroConfig = $course['hero'] ?? [];
        $primaryCta = is_array($heroConfig['primary_cta'] ?? null) ? $heroConfig['primary_cta'] : [];
        $secondaryCta = is_array($heroConfig['secondary_cta'] ?? null) ? $heroConfig['secondary_cta'] : [];

        $features = $heroConfig['features']
            ?? $course['feature_points']
            ?? $course['features']
            ?? [];

        if (empty($features)) {
            $features = array_values(array_filter([
                'Choose any 3 projects from your selected level',
                ! empty($course['level']) ? "{$course['level']} internship certificate & mentor-guided learning" : 'Internship certificate & mentor-guided learning',
                ! empty($course['modules'][0]) ? "Real-world assignments in {$course['modules'][0]}" : 'Real-world assignments designed for career growth',
            ]));
        }

        $features = collect($features)
            ->map(function ($feature) {
                if (is_array($feature)) {
                    return $feature['title'] ?? $feature['label'] ?? $feature['text'] ?? null;
                }

                return is_string($feature) ? $feature : null;
            })
            ->filter()
            ->take(4)
            ->values()
            ->all();

        $logos = $heroConfig['logos']
            ?? $course['company_logos']
            ?? [];

        $stats = [
            'learners' => $heroConfig['learner_count'] ?? $course['learner_count'] ?? null,
            'mentors' => $heroConfig['mentor_count'] ?? $course['mentor_count'] ?? null,
            'projects' => $heroConfig['projects_count'] ?? $course['projects_count'] ?? $this->countCourseProjects($course),
        ];

        return [
            'title' => $heroConfig['title'] ?? $course['title'] ?? null,
            'subtitle' => $heroConfig['subtitle'] ?? $course['subtitle'] ?? $course['description'] ?? null,
            'trusted_badge' => $heroConfig['trusted_badge'] ?? $course['hero_badge'] ?? $course['menu_group_label'] ?? $course['category'] ?? null,
            'level' => $heroConfig['level'] ?? $course['level'] ?? null,
            'duration' => $heroConfig['duration'] ?? $course['duration'] ?? null,
            'features' => $features,
            'primary_cta' => [
                'label' => $primaryCta['label'] ?? 'Reserve Your Seat',
                'href' => $primaryCta['href'] ?? '#enroll-now',
            ],
            'secondary_cta' => [
                'label' => $secondaryCta['label'] ?? 'View Curriculum',
                'href' => $secondaryCta['href'] ?? (! empty($course['curriculum']) ? '#curriculum' : null),
            ],
            'trust_label' => $heroConfig['trust_label'] ?? (! empty($logos) ? 'Trusted by learners from' : null),
            'logos' => $logos,
            'stats' => array_filter($stats),
            'overview_stats' => is_array($overviewStats) ? $overviewStats : [],
        ];
    }

    protected function countCourseProjects(array $course): ?int
    {
        $count = 0;

        foreach (($course['phases'] ?? []) as $phase) {
            foreach (($phase['modules'] ?? []) as $module) {
                $count += count($module['tasks'] ?? []);
            }
        }

        if ($count > 0) {
            return $count;
        }

        foreach (($course['curriculum'] ?? []) as $section) {
            $count += count($section['tasks'] ?? []);
        }

        return $count > 0 ? $count : null;
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
