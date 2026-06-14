<?php

namespace App\Http\Controllers\Student;

use App\Helpers\CourseDataHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseWorkspace;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Student;
use App\Models\TaskProgress;
use App\Models\User;
use App\Models\WorkspaceStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();

        $roleName = $user?->role?->name ?? 'student';

        if ($roleName === 'college') {
            if ($user->college?->payment_status !== 'approved') {
                return redirect()->route('college.payment');
            }

            return redirect()->route('college.dashboard');
        }

        $activePage = $roleName . '-dashboard';

        $view = match ($roleName) {
            'admin' => 'dashboard.admin-dashboard.home',
            default => 'dashboard.student-dashboard.home',
        };

        return view($view, $this->frontendAdminData($activePage, $roleName));
    }

    public function enrolledCourses()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            $enrolledCourses = [];
        } else {
            $enrollments = Enrollment::with([
                'course.workspaces' => fn ($query) => $query
                    ->where('status', true)
                    ->with([
                        'steps' => fn ($query) => $query->orderBy('sort_order')->orderBy('step_no'),
                        'steps.taskProgress' => fn ($query) => $query->where('student_id', $user->id),
                    ])
                    ->orderByDesc('updated_at'),
            ])
                ->where('student_id', $student->id)
                ->orderBy('enrollment_date', 'desc')
                ->get();

            $enrolledCourses = $enrollments->map(function ($enrollment) {
                $course = $enrollment->course;
                $workspaces = $course?->workspaces ?? collect();
                $projects = $workspaces->values()->map(function (CourseWorkspace $workspace, int $index) use ($enrollment) {
                    $steps = $this->studentWorkspaceSteps($workspace);
                    $completedSteps = collect($steps)->where('state', 'completed')->count();
                    $totalSteps = count($steps);

                    return [
                        'id' => (string) $workspace->id,
                        'title' => $workspace->title,
                        'description' => $workspace->summary ?: $workspace->headline ?: 'Complete the workspace checkpoints for this course.',
                        'time' => $totalSteps > 0 ? $totalSteps . ' steps' : 'Ready to start',
                        'points' => max($totalSteps, 1) * 150,
                        'recommended' => $index === 0,
                        'progress' => $totalSteps > 0 ? (int) round(($completedSteps / $totalSteps) * 100) : (int) ($enrollment->progress ?? 0),
                    ];
                })->all();

                $tasks = $workspaces->mapWithKeys(function (CourseWorkspace $workspace) {
                    $steps = collect($this->studentWorkspaceSteps($workspace))->map(function (array $step) {
                        $state = $step['state'] ?? 'pending';
                        $isCompleted = $state === 'completed';
                        $isSubmitted = ($step['status'] ?? '') === 'Submitted';

                        return [
                            'title' => $step['title'],
                            'meta' => $step['description'] ?: ($step['build'] ?: 'Project checkpoint'),
                            'status' => $step['status'] ?: ($isCompleted ? 'Completed' : 'Pending'),
                            'action' => $isCompleted ? 'Review Task' : ($isSubmitted ? 'View Submission' : 'Continue Task'),
                            'tone' => $isCompleted
                                ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                                : (($step['active'] ?? false)
                                    ? 'bg-blue-50 text-blue-700 ring-blue-200'
                                    : 'bg-amber-50 text-amber-700 ring-amber-200'),
                        ];
                    })->values()->all();

                    return [(string) $workspace->id => $steps];
                })->all();

                $activeWorkspace = $workspaces->first();
                $activeSteps = $activeWorkspace ? $this->studentWorkspaceSteps($activeWorkspace) : [];
                $totalSteps = count($activeSteps) ?: 100;
                $completedSteps = count($activeSteps)
                    ? collect($activeSteps)->where('state', 'completed')->count()
                    : (int) ($enrollment->progress ?? 0);
                $progress = count($activeSteps)
                    ? (int) round(($completedSteps / $totalSteps) * 100)
                    : (int) ($enrollment->progress ?? 0);
                $status = match (strtolower((string) $enrollment->status)) {
                    'completed' => 'Completed',
                    'ongoing', 'active', 'in progress' => 'Active',
                    default => Str::headline((string) ($enrollment->status ?: 'Active')),
                };

                return [
                    'id' => $enrollment->id,
                    'course_id' => $course?->id,
                    'title' => $course?->title ?? 'Unknown Course',
                    'image' => $course?->image ?: '/images/courses/default.svg',
                    'description' => $course?->description ?? '',
                    'completed_lessons' => $completedSteps,
                    'total_lessons' => $totalSteps,
                    'progress' => $progress,
                    'status' => $status,
                    'enrollment_date' => $enrollment->enrollment_date?->format('M d, Y'),
                    'projects' => $projects,
                    'tasks' => $tasks,
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

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard.enrolled-courses');
        }

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

        if (!$enrollment) {
            abort(404, 'Course not found');
        }

        $course = $enrollment->course;

        $phases = CourseDataHelper::getCoursePhases($course);
        $modules = CourseDataHelper::getCourseModules($course);

        $tasks = collect($phases)
            ->flatMap(fn (array $phase) => $phase['modules'] ?? [])
            ->values()
            ->map(fn (array $module, int $i) => [
                'id' => 'task-' . ($i + 1),
                'title' => $module['title'],
                'instructions' => 'Complete the ' . $module['title'] . ' module to advance your learning track.',
                'requirements' => [
                    'Follow the module guidelines and complete all checkpoints.',
                    'Document your work clearly with a reviewable output.',
                    'Submit your artifact before moving to the next module.',
                ],
            ])
            ->all();

        $courseData = [
            'id' => $enrollment->id,
            'course_id' => $course?->id,
            'title' => $course?->title ?? 'Unknown Course',
            'completed_lessons' => $enrollment->progress ?? 0,
            'total_lessons' => max(count($tasks), 1),
            'progress' => $enrollment->progress ?? 0,
            'current_module' => 'module-1',
            'description' => $course?->description ?? '',
            'status' => $enrollment->status ?? 'Active',
            'enrollment_date' => $enrollment->enrollment_date?->format('M d, Y'),
            'phases' => $phases,
            'module_content' => $modules,
            'tasks' => $tasks,
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
        $courseWorkspace = $this->activeCourseWorkspace($course?->id, request('project'), [
            'course',
            'steps' => fn ($query) => $query->orderBy('sort_order')->orderBy('step_no'),
            'steps.taskProgress' => fn ($query) => $query->where('student_id', $user->id),
            'resources' => fn ($query) => $query->orderBy('sort_order'),
            'goals',
        ]);

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

    public function studentWorkspaceCompleteStep(Request $request, $id, WorkspaceStep $step)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        [$student, $enrollment] = $this->studentEnrollmentForWorkspace($user, $id);
        abort_if(! $student || ! $enrollment, 404, 'Course enrollment not found.');

        $workspace = $step->workspace()->first();
        abort_if(! $workspace || (int) $workspace->course_id !== (int) $enrollment->course_id, 403, 'This step does not belong to your enrolled course.');

        $orderedStepIds = $workspace->steps()->pluck('id')->values();
        $stepIndex = $orderedStepIds->search($step->id);
        $previousStepIds = $stepIndex > 0 ? $orderedStepIds->slice(0, $stepIndex) : collect();
        $completedPreviousSteps = $previousStepIds->isEmpty()
            ? 0
            : TaskProgress::where('student_id', $user->id)
                ->where('course_id', $enrollment->course_id)
                ->whereIn('step_id', $previousStepIds)
                ->where('completed', true)
                ->count();

        if ($previousStepIds->count() !== $completedPreviousSteps) {
            return response()->json([
                'message' => 'Complete the previous task before this one.',
            ], 422);
        }

        TaskProgress::updateOrCreate(
            [
                'student_id' => $user->id,
                'course_id' => $enrollment->course_id,
                'step_id' => $step->id,
            ],
            [
                'completed' => true,
                'completed_at' => now(),
            ]
        );

        $progress = $this->syncEnrollmentProgress($enrollment, $workspace);

        return response()->json([
            'message' => 'Step completed.',
            'completed_steps' => $this->completedStepNumbers($workspace, $user->id),
            'progress' => $progress,
            'all_complete' => $progress === 100,
        ]);
    }

    public function studentWorkspaceSubmitProject(Request $request, $id)
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        [$student, $enrollment] = $this->studentEnrollmentForWorkspace($user, $id);
        abort_if(! $student || ! $enrollment, 404, 'Course enrollment not found.');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'github_url' => ['required', 'url', 'max:500'],
            'stream' => ['required', 'string', 'max:50'],
            'learning_note' => ['nullable', 'string', 'max:5000'],
        ]);

        $workspace = $this->activeCourseWorkspace($enrollment->course_id, $request->input('project'), [
            'steps' => fn ($query) => $query->orderBy('sort_order')->orderBy('step_no'),
            'steps.taskProgress' => fn ($query) => $query->where('student_id', $user->id),
        ]);

        abort_unless($workspace, 404, 'No active workspace found for this course.');

        $progress = $this->syncEnrollmentProgress($enrollment, $workspace);
        abort_if($progress < 100, 422, 'Complete every task before final submission.');

        $lastStep = $workspace->steps->last();
        abort_unless($lastStep, 422, 'This workspace does not have any steps yet.');

        TaskProgress::updateOrCreate(
            [
                'student_id' => $user->id,
                'course_id' => $enrollment->course_id,
                'step_id' => $lastStep->id,
            ],
            [
                'completed' => true,
                'submitted' => true,
                'github_link' => $validated['github_url'],
                'notes' => trim(($validated['learning_note'] ?? '') . "\n\nStream: " . $validated['stream']),
                'completed_at' => now(),
            ]
        );

        return response()->json([
            'message' => 'Project submitted successfully.',
        ]);
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

        return view('dashboard.student-dashboard.profile.index', [
            'profile' => $this->studentProfileData($user),
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function studentProfileEdit(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (! $request->isMethod('get')) {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|image|max:2048',
            ]);

            $user->name = $validated['name'];
            if (isset($validated['phone'])) {
                $user->phone = $validated['phone'];
            }

            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('avatars', 'public');
                $user->avatar = Storage::url($path);
            }

            $user->save();

            return redirect()->route('dashboard.student.profile')
                ->with('success', 'Profile updated successfully!');
        }

        return view('dashboard.student-dashboard.profile.edit', [
            'profile' => $this->studentProfileData($user),
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function quizAttempts()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::with('enrollments.course')
            ->where('user_id', $user->id)
            ->first();

        $quizAttempts = [];
        $quizStats = [
            'total' => 0,
            'attempted' => 0,
            'passed' => 0,
            'upcoming' => 0,
            'averageScore' => '0%',
        ];

        if ($student) {
            $enrollments = $student->enrollments;
            $enrollmentByCourse = $enrollments->keyBy('course_id');
            $courseIds = $enrollments->pluck('course_id')->filter()->unique()->values();
            $quizzes = Quiz::with('course')
                ->withCount('questions')
                ->whereIn('course_id', $courseIds)
                ->orderBy('created_at')
                ->get();
            $quizResults = QuizResult::with(['quiz.course'])
                ->where('student_id', $student->id)
                ->orderBy('created_at')
                ->get();
            $attemptNumbers = [];

            $completedAttempts = $quizResults->map(function (QuizResult $result) use (&$attemptNumbers, $enrollmentByCourse) {
                $quiz = $result->quiz;
                $course = $quiz?->course;
                $attemptNumbers[$result->quiz_id] = ($attemptNumbers[$result->quiz_id] ?? 0) + 1;
                $score = $result->score;
                $totalMarks = $quiz?->total_marks;
                $scoreLabel = $score !== null
                    ? ($totalMarks && $score <= $totalMarks
                        ? $score . '/' . $totalMarks . ' marks (' . (int) round(($score / max($totalMarks, 1)) * 100) . '%)'
                        : $score . '%')
                    : 'Pending';
                $enrollment = $course ? $enrollmentByCourse->get($course->id) : null;

                return [
                    'id' => 'result-' . $result->id,
                    'title' => $quiz?->title ?? 'Unknown Quiz',
                    'course' => $course?->title ?? 'Unknown Course',
                    'attempt' => 'Attempt ' . $attemptNumbers[$result->quiz_id],
                    'score' => $scoreLabel,
                    'status' => $result->passed ? 'Passed' : 'Failed',
                    'updated_at' => $result->created_at?->format('F j, Y') ?? 'Recently',
                    'action' => $result->passed ? 'View Summary' : 'Review Attempt',
                    'href' => $enrollment ? route('student.course.workspace', ['id' => $enrollment->id]) : route('dashboard.enrolled-courses'),
                    'sort_at' => $result->created_at,
                ];
            });

            $attemptedQuizIds = $quizResults->pluck('quiz_id')->unique();
            $upcomingAttempts = $quizzes
                ->whereNotIn('id', $attemptedQuizIds)
                ->map(function (Quiz $quiz) use ($enrollmentByCourse) {
                    $enrollment = $enrollmentByCourse->get($quiz->course_id);

                    return [
                        'id' => 'quiz-' . $quiz->id,
                        'title' => $quiz->title,
                        'course' => $quiz->course?->title ?? 'Unknown Course',
                        'attempt' => ($quiz->questions_count ?? 0) . ' questions',
                        'score' => 'Pending',
                        'status' => 'Upcoming',
                        'updated_at' => $quiz->created_at?->format('F j, Y') ?? 'Available now',
                        'action' => 'Open Course',
                        'href' => $enrollment ? route('student.course.workspace', ['id' => $enrollment->id]) : route('dashboard.enrolled-courses'),
                        'sort_at' => $quiz->created_at,
                    ];
                });

            $quizAttempts = $completedAttempts
                ->concat($upcomingAttempts)
                ->sortByDesc('sort_at')
                ->map(fn (array $attempt) => collect($attempt)->except('sort_at')->all())
                ->values()
                ->toArray();

            $scores = $quizResults->pluck('score')->filter(fn ($score) => $score !== null);
            $quizStats = [
                'total' => $quizzes->count(),
                'attempted' => $quizResults->count(),
                'passed' => $quizResults->where('passed', true)->count(),
                'upcoming' => $upcomingAttempts->count(),
                'averageScore' => $scores->count() ? (int) round($scores->avg()) . '%' : '0%',
            ];
        }

        return view('dashboard.student-dashboard.quiz-attempts.index', [
            'quizAttempts' => $quizAttempts,
            'quizStats' => $quizStats,
            ...$this->frontendAdminData('student-quiz-attempts'),
        ]);
    }

    public function studentProjects()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        $enrolledCourseIds = $student
            ? Enrollment::where('student_id', $student->id)->pluck('course_id')->toArray()
            : [];

        $enrollmentByCourse = $student
            ? Enrollment::where('student_id', $student->id)->get()->keyBy('course_id')
            : collect();

        $courses = Course::orderBy('title')->get();

        $levels = ['Beginner', 'Intermediate', 'Advanced'];

        $projectsByLevel = [];

        foreach ($levels as $level) {
            $levelCourses = $courses->where('level', $level)->values();

            $projectsByLevel[$level] = $levelCourses->map(function (Course $course) use ($enrolledCourseIds, $enrollmentByCourse) {
                $isEnrolled = in_array($course->id, $enrolledCourseIds, true);
                $enrollment = $isEnrolled ? $enrollmentByCourse->get($course->id) : null;

                return [
                    'id'           => $course->id,
                    'title'        => $course->title,
                    'description'  => $course->description ?? 'Complete this project to advance your skills.',
                    'level'        => $course->level ?? 'Beginner',
                    'category'     => $course->category ?? 'Project',
                    'duration'     => $course->duration_months ? $course->duration_months . ' month' . ($course->duration_months > 1 ? 's' : '') : 'Self-paced',
                    'is_enrolled'  => $isEnrolled,
                    'enrollment_id' => $enrollment?->id,
                    'progress'     => (int) ($enrollment?->progress ?? 0),
                    'status'       => $enrollment?->status,
                    'workspace_url' => $enrollment ? route('student.course.workspace', ['id' => $enrollment->id]) : null,
                    'select_url'   => route('student.projects.select', ['course' => $course->id]),
                ];
            })->values()->all();
        }

        return view('dashboard.student-dashboard.projects.index', [
            'projectsByLevel' => $projectsByLevel,
            'levels' => $levels,
            ...$this->frontendAdminData('student-projects'),
        ]);
    }

    public function studentSelectProject(Course $course)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (! $student) {
            return redirect()->route('student.projects')->with('error', 'Student profile not found. Please contact your college administrator.');
        }

        $existing = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existing) {
            return redirect()->route('student.course.workspace', ['id' => $existing->id])
                ->with('info', 'You are already enrolled in this project.');
        }

        $enrollment = Enrollment::create([
            'student_id'      => $student->id,
            'course_id'       => $course->id,
            'enrollment_date' => now(),
            'progress'        => 0,
            'status'          => 'ongoing',
        ]);

        return redirect()->route('student.course.workspace', ['id' => $enrollment->id])
            ->with('success', 'Project selected! Work through the steps in your workspace.');
    }

    public function orderHistory()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            $orders = [];
        } else {
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

    protected function studentEnrollmentForWorkspace(User $user, $id): array
    {
        $student = Student::where('user_id', $user->id)->first();
        $enrollment = null;

        if ($student) {
            $enrollment = Enrollment::where('student_id', $student->id)
                ->where('id', $id)
                ->first();

            if (! $enrollment) {
                $enrollment = Enrollment::where('student_id', $student->id)
                    ->where('course_id', $id)
                    ->first();
            }
        }

        return [$student, $enrollment];
    }

    protected function activeCourseWorkspace(?int $courseId, $projectId, array $with = []): ?CourseWorkspace
    {
        $query = CourseWorkspace::with($with)->where('status', true);

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        if (filled($projectId)) {
            $workspace = (clone $query)
                ->whereKey($projectId)
                ->first();

            if ($workspace) {
                return $workspace;
            }
        }

        return $query
            ->orderByDesc('updated_at')
            ->first();
    }

    protected function syncEnrollmentProgress(Enrollment $enrollment, CourseWorkspace $workspace): int
    {
        $stepIds = $workspace->steps()->pluck('id');
        $totalSteps = $stepIds->count();
        $completedSteps = $totalSteps
            ? TaskProgress::where('student_id', Auth::id())
                ->where('course_id', $enrollment->course_id)
                ->whereIn('step_id', $stepIds)
                ->where('completed', true)
                ->count()
            : 0;
        $progress = $totalSteps ? (int) round(($completedSteps / $totalSteps) * 100) : 0;

        $enrollment->forceFill([
            'progress' => $progress,
            'status' => $progress === 100 ? 'completed' : 'ongoing',
        ])->save();

        return $progress;
    }

    protected function completedStepNumbers(CourseWorkspace $workspace, int $userId): array
    {
        return $workspace->steps()
            ->with(['taskProgress' => fn ($query) => $query->where('student_id', $userId)->where('completed', true)])
            ->get()
            ->filter(fn (WorkspaceStep $step) => $step->taskProgress->isNotEmpty())
            ->map(fn (WorkspaceStep $step, int $index) => $step->step_no ?: $index + 1)
            ->values()
            ->all();
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
            'submission_url' => $enrollment
                ? route('student.course.workspace.submit', ['id' => $enrollment->id, 'project' => $courseWorkspace->id])
                : '#',
            'submission_unlocked' => $stepsCollection->isNotEmpty() && $completedSteps === $stepsCollection->count(),
            'submission_submitted' => TaskProgress::where('student_id', $user->id)
                ->where('course_id', $course?->id)
                ->where('submitted', true)
                ->whereIn('step_id', $courseWorkspace->steps->pluck('id'))
                ->exists(),
        ];
    }

    protected function studentWorkspaceSteps(CourseWorkspace $courseWorkspace): array
    {
        $steps = $courseWorkspace->steps->map(function ($step, $index) {
            $number = $step->step_no ?: $index + 1;
            $progress = $step->taskProgress->first();
            $isCompleted = (bool) ($progress?->completed ?? false);

            return [
                'id' => $step->id,
                'number' => $number,
                'slug' => $step->slug ?: 'step-' . $number,
                'nav_label' => $step->nav_label ?: $step->title,
                'title' => $step->title,
                'description' => $step->description ?: '',
                'status' => $isCompleted ? 'Completed' : 'Locked',
                'state' => $isCompleted ? 'completed' : 'locked',
                'active' => false,
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
                'complete_url' => request()->route('id')
                    ? route('student.course.workspace.steps.complete', ['id' => request()->route('id'), 'step' => $step->id])
                    : '#',
            ];
        })->values()->all();

        $firstAvailableIndex = collect($steps)->search(fn (array $step) => $step['state'] !== 'completed');

        if ($firstAvailableIndex !== false) {
            $steps[$firstAvailableIndex]['state'] = 'active';
            $steps[$firstAvailableIndex]['active'] = true;
            $steps[$firstAvailableIndex]['status'] = 'In Progress';
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

        $allComplete = ! empty($steps) && collect($steps)->every(fn (array $step) => $step['state'] === 'completed');

        $sidebarItems[] = [
            'label' => 'Submission',
            'target' => 'submission',
            'state' => $allComplete ? 'active' : 'locked',
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

    protected function studentProfileData(User $user): array
    {
        $student = Student::with([
            'college',
            'enrollments.course',
            'payments.course',
        ])
            ->where('user_id', $user->id)
            ->first();

        $enrollments = $student?->enrollments ?? collect();
        $latestEnrollment = $enrollments->sortByDesc('enrollment_date')->first();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $activeCourses = $enrollments
            ->filter(fn (Enrollment $enrollment) => in_array(strtolower((string) $enrollment->status), ['active', 'ongoing', 'in progress'], true))
            ->count();
        $averageProgress = $enrollments->count() ? (int) round($enrollments->avg('progress')) : 0;
        $payments = $student?->payments ?? collect();
        $paidAmount = $payments
            ->whereIn('status', ['completed', 'paid', 'success'])
            ->sum(fn (Payment $payment) => (float) $payment->amount);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'avatar' => $user->avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            'student_id' => $student?->id,
            'college_id' => $student?->college_id,
            'college_name' => $student?->college?->college_name ?? 'Not linked',
            'course_name' => $latestEnrollment?->course?->title ?? $student?->course_name ?? 'Not enrolled',
            'enrollment_status' => $latestEnrollment
                ? Str::headline((string) $latestEnrollment->status)
                : 'Not enrolled',
            'enrollment_date' => $latestEnrollment?->enrollment_date?->format('M d, Y') ?? 'Not enrolled',
            'progress' => $latestEnrollment?->progress ?? 0,
            'total_enrolled' => $enrollments->count(),
            'active_courses' => $activeCourses,
            'completed_courses' => $completedCourses,
            'average_progress' => $averageProgress,
            'paid_amount' => 'Rs. ' . number_format($paidAmount, 0),
            'latest_payment' => $payments->sortByDesc('payment_date')->first()?->payment_date?->format('M d, Y') ?? 'No payment yet',
            'created_at' => $user->created_at?->format('M d, Y'),
        ];
    }

    protected function frontendAdminData(string $activePage, string $role = 'student'): array
    {
        $user = Auth::user();
        $role = Auth::check() ? Auth::user()->role->name : 'student';

        $data = [
            'sidebarSections' => $this->dashboardSidebarSections($role),
            'activeDashboardPage' => $activePage,
            'sidebarUserName' => $user ? $user->name : 'Guest',
            'sidebarUserMeta' => $user && $user->email ? $user->email : 'Unified Dashboard',
            'navbarUserName' => $user ? explode(' ', $user->name)[0] : 'Guest',
        ];

        if ($role === 'student') {
            $data = array_merge($data, $this->dashboardStudentOverviewData());
        }

        return $data;
    }

    protected function dashboardSidebarSections(string $role = 'student'): array
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
                            'key' => 'student-projects',
                            'label' => 'Browse Projects',
                            'icon' => 'fi fi-rr-rocket',
                            'href' => route('student.projects'),
                        ],
                        [
                            'key' => 'student-enrolled-courses',
                            'label' => 'My Projects',
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

    protected function dashboardStudentOverviewData(): array
    {
        $user = Auth::user();

        if (! $user) {
            return [
                'currentTrack' => 'Learning Track',
                'totalEnrolled' => 0,
                'activeCourses' => 0,
                'completedCourses' => 0,
                'tasks' => [],
                'leaderboard' => [],
                'currentProgress' => 0,
                'completedSteps' => 0,
                'totalSteps' => 0,
                'nextLesson' => 'Enroll in a course to begin.',
                'resumeUrl' => route('dashboard.enrolled-courses'),
                'rank' => null,
                'percentile' => 0,
                'points' => 0,
                'pendingTasks' => 0,
                'completedTasks' => 0,
            ];
        }

        $student = Student::with([
            'enrollments.course.workspaces' => fn ($query) => $query
                ->where('status', true)
                ->with([
                    'steps' => fn ($query) => $query->orderBy('sort_order')->orderBy('step_no'),
                    'steps.taskProgress' => fn ($query) => $query->where('student_id', $user->id),
                ])
                ->orderByDesc('updated_at'),
        ])
            ->where('user_id', $user->id)
            ->first();

        $enrollments = $student?->enrollments ?? collect();
        $totalEnrolled = $enrollments->count();
        $activeCourses = $enrollments
            ->filter(fn (Enrollment $enrollment) => in_array(strtolower((string) $enrollment->status), ['active', 'ongoing', 'in progress'], true))
            ->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();

        $latestEnrollment = $enrollments->sortByDesc('enrollment_date')->first();
        $currentTrack = $latestEnrollment?->course?->title ?? $student?->course_name ?? 'Learning Track';

        $workspace = $latestEnrollment?->course?->workspaces?->first();
        $steps = $workspace ? collect($this->studentWorkspaceSteps($workspace)) : collect();
        $totalSteps = $steps->count();
        $completedSteps = $steps->where('state', 'completed')->count();
        $currentProgress = $totalSteps > 0
            ? (int) round(($completedSteps / $totalSteps) * 100)
            : (int) ($latestEnrollment?->progress ?? 0);

        $activeStep = $steps->firstWhere('active', true)
            ?? $steps->first(fn (array $step) => ($step['state'] ?? null) !== 'completed')
            ?? $steps->first();

        $nextLesson = $activeStep['title'] ?? ($workspace?->next_milestone ?: 'No active task yet.');
        $resumeUrl = $latestEnrollment && $workspace
            ? route('student.course.workspace', ['id' => $latestEnrollment->id]) . '?project=' . $workspace->id
            : route('dashboard.enrolled-courses');

        $tasks = $steps->take(4)->values()->map(function (array $step) {
            $isCompleted = ($step['state'] ?? null) === 'completed';
            $isActive = (bool) ($step['active'] ?? false);

            return [
                'title' => $step['title'],
                'deadline' => $isCompleted ? 'Completed' : ($isActive ? 'Current step' : 'Upcoming'),
                'status' => $isCompleted ? 'Done' : ($isActive ? 'In Progress' : 'Pending'),
                'tone' => $isCompleted
                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-200'
                    : ($isActive
                        ? 'bg-blue-50 text-blue-700 ring-blue-200'
                        : 'bg-amber-50 text-amber-700 ring-amber-200'),
            ];
        })->toArray();

        $pendingTasks = collect($tasks)->where('status', '!=', 'Done')->count();
        $completedTasks = collect($tasks)->where('status', 'Done')->count();
        $points = ($completedSteps * 150) + ($completedCourses * 500);
        $rankings = Student::with('user')
            ->withSum('enrollments as progress_points', 'progress')
            ->withCount([
                'enrollments as completed_enrollments_count' => fn ($query) => $query->where('status', 'completed'),
            ])
            ->get()
            ->map(function (Student $rankedStudent) {
                $completed = (int) ($rankedStudent->completed_enrollments_count ?? 0);
                $progress = (int) ($rankedStudent->progress_points ?? 0);

                return [
                    'student_id' => $rankedStudent->id,
                    'name' => $rankedStudent->user?->name ?? 'Student',
                    'points' => ($completed * 500) + $progress,
                ];
            })
            ->sortByDesc('points')
            ->values();

        $rankIndex = $student
            ? $rankings->search(fn (array $row) => $row['student_id'] === $student->id)
            : false;
        $rank = $rankIndex === false ? null : $rankIndex + 1;
        $percentile = $rank && $rankings->count() > 0
            ? max(1, (int) round((1 - (($rank - 1) / $rankings->count())) * 100))
            : 0;

        $leaderboard = $rankings->take(3)->map(function (array $row, int $index) use ($student) {
            $badges = ['Gold', 'Silver', 'Bronze'];
            $tones = [
                'bg-amber-100 text-amber-700 ring-amber-200',
                'bg-slate-100 text-slate-700 ring-slate-200',
                'bg-orange-100 text-orange-700 ring-orange-200',
            ];

            return [
                'rank' => $index + 1,
                'name' => $row['name'],
                'points' => number_format($row['points']),
                'badge' => $badges[$index] ?? 'Top',
                'tone' => $tones[$index] ?? 'bg-blue-100 text-primary ring-blue-200',
                'current' => $student && $row['student_id'] === $student->id,
            ];
        })->values()->toArray();

        if ($student && $rank && ! collect($leaderboard)->contains(fn (array $row) => ! empty($row['current']))) {
            $leaderboard[] = [
                'rank' => $rank,
                'name' => $user->name ?? 'Student',
                'points' => number_format($points),
                'badge' => 'You',
                'tone' => 'bg-blue-100 text-primary ring-blue-200',
                'current' => true,
            ];
        }

        return compact(
            'currentTrack',
            'totalEnrolled',
            'activeCourses',
            'completedCourses',
            'tasks',
            'leaderboard',
            'currentProgress',
            'completedSteps',
            'totalSteps',
            'nextLesson',
            'resumeUrl',
            'rank',
            'percentile',
            'points',
            'pendingTasks',
            'completedTasks',
        );
    }
}
