<?php

namespace App\Http\Controllers\College;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Course;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Services\OnboardingMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        return view('dashboard.home', $this->frontendAdminData('college-dashboard', 'college'));
    }

    public function payment()
    {
        $college = $this->currentCollegeOrFail();

        return view('dashboard.college.payment', [
            'college' => $college,
            ...$this->frontendAdminData('college-payment'),
        ]);
    }

    public function paymentStore(Request $request)
    {
        $validated = $request->validate([
            'payment_mode' => ['required', Rule::in(['online', 'offline'])],
            'utr_number' => [
                'nullable',
                Rule::requiredIf(fn () => $request->input('payment_mode') === 'offline'),
                'string',
                'max:100',
            ],
        ], [
            'utr_number.required' => 'Please enter the UTR number for offline payment.',
        ]);

        $college = $this->currentCollegeOrFail();

        $college->update([
            'payment_mode' => $validated['payment_mode'],
            'utr_number' => $validated['payment_mode'] === 'offline' ? $validated['utr_number'] : null,
            'payment_status' => 'pending',
            'payment_submitted_at' => now(),
            'payment_reviewed_by' => null,
            'payment_reviewed_at' => null,
            'payment_rejection_reason' => null,
        ]);

        $message = $validated['payment_mode'] === 'offline'
            ? 'Offline payment details submitted successfully. Your UTR number is pending admin approval.'
            : 'Online payment mode selected. Please complete the payment when the gateway opens.';

        return redirect()->route('college.payment')->with('success', $message);
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get college profile
        $college = College::where('user_id', $user->id)->first();
        
        // Get students for this college (or empty collection if no college)
        $students = $college 
            ? Student::where('college_id', $college->id)->with('user')->get()
            : collect();
        
        // Calculate stats with defaults
        $totalStudents = $students->count();
        $activeInternships = $students->filter(function($student) {
            return $student->enrollments()->where('status', 'active')->exists();
        })->count();
        $completed = $students->filter(function($student) {
            return $student->enrollments()->where('status', 'completed')->exists();
        })->count();
        
        // Get monthly enrollment data for chart
        $monthlyData = Enrollment::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('count', 'month');
        
        // Fill missing months with 0
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyData->get($i, 0);
        }
        
        // Provide default college object if none exists
        if (!$college) {
            $college = (object) ['college_name' => $user->name];
        }
        
        return view('pages.college.dashboard', compact(
            'college',
            'students',
            'totalStudents',
            'activeInternships',
            'completed',
            'chartData'
        ));
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

    public function studentStore(Request $request)
    {
        $college = $this->currentCollegeOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'course_name' => ['nullable', 'string', 'max:255'],
        ]);

        $password = Str::random(12);

        DB::transaction(function () use ($validated, $college, $password) {
            $role = Role::firstOrCreate(['name' => 'student']);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role_id' => $role->id,
            ]);

            $user->student()->create([
                'college_id' => $college->id,
                'course_name' => $validated['course_name'] ?? null,
            ]);

            app(OnboardingMailer::class)->send($user, $password, 'student');
        });

        return redirect()->route('college.students')->with('success', 'Student account created successfully.');
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

    public function enrollmentStore(Request $request)
    {
        $college = $this->currentCollegeOrFail();
        $studentIds = $college->students()->pluck('id')->all();

        $validated = $request->validate([
            'student_id' => [
                'nullable',
                Rule::requiredIf(fn () => blank($request->input('new_student_name')) && blank($request->input('new_student_email'))),
                Rule::in($studentIds),
                Rule::unique('enrollments')->where(fn ($query) => $query->where('course_id', $request->course_id)),
            ],
            'new_student_name' => ['nullable', 'required_without:student_id', 'string', 'max:255'],
            'new_student_email' => ['nullable', 'required_without:student_id', 'email', 'max:255', 'unique:users,email'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrollment_date' => ['required', 'date'],
            'status' => ['required', 'in:ongoing,completed'],
        ], [
            'student_id.required' => 'Select an existing student or enter new student details.',
            'student_id.in' => 'You can only enroll students from your college.',
            'student_id.unique' => 'This student is already enrolled in the selected course.',
        ]);

        $password = Str::random(12);

        DB::transaction(function () use ($validated, $college, $password) {
            $student = null;

            if (!empty($validated['student_id'])) {
                $student = $college->students()->findOrFail($validated['student_id']);
            } else {
                $role = Role::firstOrCreate(['name' => 'student']);

                $user = User::create([
                    'name' => $validated['new_student_name'],
                    'email' => $validated['new_student_email'],
                    'password' => Hash::make($password),
                    'role_id' => $role->id,
                ]);

                $student = $user->student()->create([
                    'college_id' => $college->id,
                    'course_name' => Course::find($validated['course_id'])?->title,
                ]);

                app(OnboardingMailer::class)->send($user, $password, 'student');
            }

            Enrollment::create([
                'student_id' => $student->id,
                'course_id' => $validated['course_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'progress' => 0,
                'status' => $validated['status'],
            ]);
        });

        return redirect()->route('college.enrollments')->with('success', 'Enrollment created successfully.');
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

    protected function frontendAdminData(string $activePage): array
    {
        $user = Auth::user();

        return [
            'sidebarSections' => $this->dashboardSidebarSections(),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => $user ? $user->name : 'Guest',
            'sidebarUserMeta' => $user && $user->email ? $user->email : 'Unified Dashboard',
            'navbarUserName' => $user ? explode(' ', $user->name)[0] : 'Guest',
            'collegeStudents' => $this->dashboardCollegeStudents(),
            ...$this->dashboardCollegeOverviewData(),
        ];
    }

    protected function currentCollegeOrFail(): College
    {
        $college = Auth::user()?->college;

        abort_unless($college, 403, 'Your college account is not linked to a college profile.');

        return $college;
    }

    protected function dashboardSidebarSections(): array
    {
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
                        'key' => 'college-payment',
                        'label' => 'Payment',
                        'icon' => 'fi fi-rr-credit-card',
                        'href' => route('college.payment'),
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

    protected function dashboardCollegeStudents(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
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
        $college = College::where('user_id', Auth::id())->first();

        if (! $college) {
            return [
                'recentStudents' => [],
                'topCourses' => [],
                'activities' => [],
                'announcements' => [],
                'statCards' => [],
                'collegeChartData' => [
                    'studentGrowth' => ['labels' => [], 'data' => []],
                    'enrollmentDistribution' => ['labels' => [], 'data' => []],
                    'placementStats' => ['labels' => ['Completed', 'In progress'], 'data' => [0, 0]],
                    'engagement' => ['labels' => [], 'active' => [], 'inactive' => []],
                ],
            ];
        }

        $studentIds = Student::where('college_id', $college->id)->pluck('id');
        $totalStudents = $studentIds->count();
        $totalEnrollments = Enrollment::whereIn('student_id', $studentIds)->count();
        $completedEnrollments = Enrollment::whereIn('student_id', $studentIds)
            ->where('status', 'completed')
            ->count();
        $activeStudents = Student::where('college_id', $college->id)
            ->whereHas('enrollments', fn ($query) => $query->whereIn('status', ['active', 'ongoing', 'in progress']))
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
            ->map(fn (Notification $notification) => [
                'title' => $notification->message,
                'meta' => 'College update',
            ])->toArray();

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

        $growthRows = Student::where('college_id', $college->id)
            ->selectRaw("DATE_FORMAT(created_at, '%b') as month_label, DATE_FORMAT(created_at, '%Y-%m') as month_key, COUNT(*) as total")
            ->groupBy('month_key', 'month_label')
            ->orderBy('month_key')
            ->limit(7)
            ->get();

        $collegeChartData = [
            'studentGrowth' => [
                'labels' => $growthRows->pluck('month_label')->values()->all(),
                'data' => $growthRows->pluck('total')->map(fn ($value) => (int) $value)->values()->all(),
            ],
            'enrollmentDistribution' => [
                'labels' => collect($topCourses)->pluck('name')->values()->all(),
                'data' => collect($topCourses)->pluck('enrollments')->map(fn ($value) => (int) $value)->values()->all(),
            ],
            'placementStats' => [
                'labels' => ['Completed', 'In progress'],
                'data' => [$completedEnrollments, max($totalEnrollments - $completedEnrollments, 0)],
            ],
            'engagement' => [
                'labels' => ['Active', 'Inactive'],
                'active' => [$activeStudents],
                'inactive' => [max($totalStudents - $activeStudents, 0)],
            ],
        ];

        return compact('recentStudents', 'topCourses', 'activities', 'announcements', 'statCards', 'collegeChartData');
    }

    protected function collegeStudentManagementData(): array
    {
        $query = Student::with([
            'user',
            'enrollments' => function ($query) {
                $query->latest('enrollment_date')->limit(1)->with('course');
            },
        ]);

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
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
        $query = Enrollment::with(['student.user', 'course'])
            ->orderBy('enrollment_date', 'desc');

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->whereHas('student', fn ($query) => $query->where('college_id', $college->id));
        }

        return $query->get()
            ->map(fn (Enrollment $enrollment) => [
                'student_name' => $enrollment->student->user?->name ?? 'Unknown Student',
                'course_name' => $enrollment->course?->title ?? 'Unknown Course',
                'enrollment_date' => $enrollment->enrollment_date?->format('F d, Y') ?? 'N/A',
                'progress' => $enrollment->progress,
                'status' => $enrollment->status === 'completed' ? 'Completed' : 'Active',
                'last_activity' => $enrollment->updated_at?->diffForHumans() ?? 'No activity yet',
            ])->toArray();
    }

    protected function collegeEnrollmentStudentOptions(): array
    {
        $query = Student::with('user')->orderBy('id');

        $college = College::where('user_id', Auth::id())->first();
        if ($college) {
            $query->where('college_id', $college->id);
        }

        return $query->get()
            ->map(fn (Student $student) => [
                'id' => $student->id,
                'name' => $student->user?->name ?? 'Unknown Student',
                'email' => $student->user?->email,
            ])
            ->toArray();
    }

    protected function collegeEnrollmentCourseOptions(): array
    {
        return Course::orderBy('title')
            ->get(['id', 'title'])
            ->map(fn (Course $course) => [
                'id' => $course->id,
                'title' => $course->title,
            ])
            ->toArray();
    }
}
