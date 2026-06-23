<?php

namespace App\Http\Controllers\Student;

use App\Helpers\CourseDataHelper;
use App\Http\Controllers\Controller;
use App\Models\Certificate;
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
                    'completed'  => 'Completed',
                    'cancelled'  => 'Cancelled',
                    'pending'    => 'Pending',
                    'ongoing', 'active' => 'Active',
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
        // dd( $enrollment);

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

        if ($enrollment && $this->enrollmentNeedsPayment($enrollment)) {
            return redirect()->route('student.internship.pay')
                ->with('error', 'Complete payment for your internship to access the workspace.');
        }

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

        if ($this->enrollmentNeedsPayment($enrollment)) {
            return response()->json(['message' => 'Payment required to access this workspace.'], 403);
        }

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

        if ($this->enrollmentNeedsPayment($enrollment)) {
            return response()->json(['message' => 'Payment required to access this workspace.'], 403);
        }

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
            $student = Student::with('college')->where('user_id', $user->id)->first();
            $canSelfAssignLevel = $student && $student->college && $student->college->user_id === null;

            $availableStreams = Course::select('category')
                ->distinct()
                ->orderBy('category')
                ->pluck('category')
                ->filter()
                ->values()
                ->toArray();

            $validated = $request->validate([
                'name'   => 'required|string|max:255',
                'phone'  => 'nullable|string|max:20',
                'avatar' => 'nullable|image|max:2048',
                'level'  => $canSelfAssignLevel ? 'nullable|in:Beginner,Intermediate,Advanced' : 'sometimes',
                'internship_stream' => $canSelfAssignLevel
                    ? 'nullable|string|in:' . implode(',', $availableStreams)
                    : 'sometimes',
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

            if ($canSelfAssignLevel) {
                $studentUpdates = [];
                if (! empty($validated['level'])) {
                    $studentUpdates['level'] = $validated['level'];
                }
                if (array_key_exists('internship_stream', $validated)) {
                    $studentUpdates['internship_stream'] = $validated['internship_stream'] ?: null;
                }
                if (! empty($studentUpdates)) {
                    $student->update($studentUpdates);
                }
            }

            return redirect()->route('dashboard.student.profile')
                ->with('success', 'Profile updated successfully!');
        }

        return view('dashboard.student-dashboard.profile.edit', [
            'profile' => $this->studentProfileData($user),
            ...$this->frontendAdminData('student-profile'),
        ]);
    }

    public function studentSettings()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        return view('dashboard.student-dashboard.settings.index', [
            'user' => $user,
            ...$this->frontendAdminData('common-settings'),
        ]);
    }

    public function studentSettingsUpdate(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'] ?? $user->phone;

        if (filled($validated['password'] ?? null)) {
            $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('student.settings')
            ->with('success', 'Settings saved successfully.');
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

        $student = Student::with('college')->where('user_id', $user->id)->first();

        $enrollments = $student
            ? Enrollment::where('student_id', $student->id)->get()
            : collect();

        $enrolledCourseIds    = $enrollments->pluck('course_id')->toArray();
        $enrollmentByCourse   = $enrollments->keyBy('course_id');
        $totalSelected        = $enrollments->count();
        $studentLevel         = $student?->level;
        $studentStream        = $student?->internship_stream;
        $canSelfAssignLevel   = $student && $student->college && $student->college->user_id === null;
        $internshipPaid       = (bool) ($student?->internship_paid);

        $workspaceIdByCourse = $enrolledCourseIds
            ? CourseWorkspace::where('status', true)
                ->whereIn('course_id', $enrolledCourseIds)
                ->orderByDesc('updated_at')
                ->get()
                ->groupBy('course_id')
                ->map(fn ($g) => $g->first()->id)
            : collect();

        // Load ALL courses — cascade filter handles visibility
        $courses = Course::orderBy('level')->orderBy('category')->orderBy('title')->get();

        $levels               = ['Beginner', 'Intermediate', 'Advanced'];
        $categoriesByLevel    = array_fill_keys($levels, []);
        $titlesByLevelCategory = [];
        $projectsByLevelCategoryTitle = [];

        foreach ($courses as $course) {
            $level    = $course->level    ?? 'Beginner';
            $category = $course->category ?? 'General';
            $track    = $course->title;
            $items    = $course->curriculum ?? [];

            if (empty($items)) {
                continue;
            }

            // Build cascade selector data
            if (! in_array($category, $categoriesByLevel[$level] ?? [])) {
                $categoriesByLevel[$level][] = $category;
            }
            $key = $level . '|' . $category;
            $existingTracks = array_column($titlesByLevelCategory[$key] ?? [], 'title');
            if (! in_array($track, $existingTracks)) {
                $titlesByLevelCategory[$key][] = ['title' => $track, 'count' => count($items)];
            }

            // Build project items
            $isEnrolled      = in_array($course->id, $enrolledCourseIds, true);
            $enrollment      = $isEnrolled ? $enrollmentByCourse->get($course->id) : null;
            $sponsorType     = $enrollment?->sponsor_type ?? 'self';
            $workspaceLocked = $isEnrolled && $sponsorType === 'self' && ! $internshipPaid;

            foreach ($items as $idx => $item) {
                $firstTask   = $item['tasks'][0] ?? [];
                $description = $firstTask['assignment'] ?? $course->description ?? 'Complete this project to advance your skills.';
                $taskTitles  = collect($item['tasks'] ?? [])->pluck('title')->filter()->values()->toArray();

                $projectsByLevelCategoryTitle[$level][$category][$track][] = [
                    'id'               => $course->id,
                    'item_index'       => $idx,
                    'title'            => $item['title'] ?? $course->title,
                    'description'      => $description,
                    'submission'       => $firstTask['submission'] ?? '',
                    'tasks'            => $taskTitles,
                    'level'            => $level,
                    'category'         => $category,
                    'duration'         => $course->duration_months
                        ? $course->duration_months . ' month' . ($course->duration_months > 1 ? 's' : '')
                        : 'Self-paced',
                    'is_enrolled'      => $isEnrolled,
                    'enrollment_id'    => $enrollment?->id,
                    'progress'         => strtolower((string) $enrollment?->status) === 'completed' ? 100 : (int) ($enrollment?->progress ?? 0),
                    'status'           => $enrollment?->status,
                    'sponsor_type'     => $sponsorType,
                    'workspace_locked' => $workspaceLocked,
                    'workspace_url'    => $enrollment
                        ? route('student.course.workspace', ['id' => $enrollment->id])
                            . ($workspaceIdByCourse->has($course->id) ? '?project=' . $workspaceIdByCourse[$course->id] : '')
                        : null,
                    'select_url'       => route('student.projects.select', ['course' => $course->id]),
                    'can_select'       => ! $isEnrolled && $totalSelected < 3 && $studentLevel === $level
                        && (! $studentStream || $studentStream === $category) && $internshipPaid,
                    'level_locked'     => $studentLevel !== null && $studentLevel !== $level,
                ];
            }
        }

        return view('dashboard.student-dashboard.projects.index', [
            'projectsByLevelCategoryTitle' => $projectsByLevelCategoryTitle,
            'categoriesByLevel'            => $categoriesByLevel,
            'titlesByLevelCategory'        => $titlesByLevelCategory,
            'levels'                       => $levels,
            'totalSelected'                => $totalSelected,
            'studentLevel'                 => $studentLevel,
            'studentStream'                => $studentStream,
            'canSelfAssignLevel'           => $canSelfAssignLevel,
            'internshipPaid'               => $internshipPaid,
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

        if (blank($student->level)) {
            return redirect()->route('student.projects')
                ->with('error', 'Your internship level hasn\'t been assigned yet. Set it from your profile to get started.');
        }

        if (blank($student->internship_stream)) {
            return redirect()->route('dashboard.student.profile')
                ->with('error', 'Choose your internship topic in your profile before selecting a project.');
        }

        if ($course->level !== $student->level) {
            return redirect()->route('student.projects')
                ->with('error', "This project is part of the {$course->level} track. You are assigned to the {$student->level} track.");
        }

        if ($course->category && $student->internship_stream && $course->category !== $student->internship_stream) {
            return redirect()->route('student.projects')
                ->with('error', "This project is from the {$course->category} stream. Your chosen topic is {$student->internship_stream}.");
        }

        $totalSelected = Enrollment::where('student_id', $student->id)->count();

        if ($totalSelected >= 3) {
            return redirect()->route('student.projects')
                ->with('error', 'You can select a maximum of 3 projects. Go to your dashboard to review your current selections.');
        }

        $enrollment = Enrollment::create([
            'student_id'      => $student->id,
            'course_id'       => $course->id,
            'enrollment_date' => now(),
            'progress'        => 0,
            'status'          => 'active',
            'sponsor_type'    => 'self',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Project selected! You can continue from your dashboard.');
    }

    public function studentRemoveProject(Enrollment $enrollment)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->firstOrFail();

        abort_unless($enrollment->student_id === $student->id, 403, 'You can only remove your own projects.');
        abort_if(strtolower((string) $enrollment->status) === 'completed', 422, 'Completed projects cannot be removed.');
        abort_if($enrollment->sponsor_type === 'college', 422, 'College-sponsored projects cannot be removed. Contact your college administrator.');

        $enrollment->delete();

        return redirect()->route('student.projects')
            ->with('success', 'Project removed. You can now select a different project.');
    }

    public function studentSwapProject(Request $request, Enrollment $enrollment)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->firstOrFail();

        abort_unless($enrollment->student_id === $student->id, 403, 'You can only change your own projects.');
        abort_if((int) $enrollment->progress > 0, 422, 'Cannot change a project you have already started.');
        abort_if(strtolower((string) $enrollment->status) === 'completed', 422, 'Completed projects cannot be changed.');
        abort_if($enrollment->sponsor_type === 'college', 422, 'College-sponsored projects cannot be changed. Contact your college administrator.');

        $newCourse = Course::findOrFail($request->input('course_id'));
        $oldCourse = $enrollment->course;

        if ($newCourse->level !== $oldCourse->level) {
            return redirect()->route('student.projects')
                ->with('error', 'You can only swap to a project within the same level.');
        }

        if ($newCourse->category !== $oldCourse->category) {
            return redirect()->route('student.projects')
                ->with('error', 'You can only swap to a project within the same topic.');
        }

        $alreadyEnrolled = Enrollment::where('student_id', $student->id)
            ->where('course_id', $newCourse->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()->route('student.projects')
                ->with('error', 'You are already enrolled in that project.');
        }

        $enrollment->delete();

        Enrollment::create([
            'student_id'      => $student->id,
            'course_id'       => $newCourse->id,
            'enrollment_date' => now(),
            'progress'        => 0,
            'status'          => 'active',
            'sponsor_type'    => 'self',
        ]);

        return redirect()->route('student.projects')
            ->with('success', "Project changed to \"{$newCourse->title}\" successfully.");
    }

    public function studentSetLevel(Request $request)
    {
        $validated = $request->validate([
            'level' => ['required', 'in:Beginner,Intermediate,Advanced'],
        ]);

        $student = Student::with('college')->where('user_id', Auth::id())->firstOrFail();

        abort_unless(
            $student->college && $student->college->user_id === null,
            403,
            'Your internship level is assigned by your college administrator.'
        );

        $student->update(['level' => $validated['level']]);

        if (blank($student->internship_stream)) {
            return redirect()->route('dashboard.student.profile')
                ->with('success', 'Level set to ' . $validated['level'] . '. Now choose your internship topic to start selecting projects.');
        }

        return redirect()->route('student.projects')
            ->with('success', 'Internship level set to ' . $validated['level'] . '. You can now select projects.');
    }

    public function internshipCheckout(): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::with('college')->where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('dashboard')
                ->with('error', 'Student account not found.');
        }

        // if ($student->internship_paid) {
        //     return redirect()->route('student.projects')
        //         ->with('success', 'Your internship is already unlocked. Browse and select your projects.');
        // }

        $canSelfAssignLevel = $student->college && $student->college->user_id === null;

        // if (!$canSelfAssignLevel && !$student->level) {
        //     return redirect()->route('dashboard')
        //         ->with('error', 'Your internship level hasn\'t been assigned yet. Contact your college administrator.');
        // }

        // Pre-selections passed from the public enrollment form
        $enrollmentData = session()->pull('enrollment_internship_checkout', []);
        $preSelectedCourseIds = $enrollmentData['selected_courses'] ?? [];
        $preSelectedLevel  = $enrollmentData['level']  ?? null;
        $preSelectedStream = $enrollmentData['stream']  ?? null;

        $enrolledCourseIds = Enrollment::where('student_id', $student->id)
            ->pluck('course_id')
            ->toArray();

        $allCourses = Course::orderBy('level')->orderBy('category')->orderBy('title')->get()
            ->map(function ($c) {
                $rawCurriculum = $c->curriculum ?? [];
                $projects = collect($rawCurriculum)->values()->map(function ($item, $index) {
                    return [
                        'id'          => $item['project_no'] ?? ($index + 1),
                        'title'       => $item['title'] ?? '',
                        'description' => $item['description'] ?? '',
                        'category'    => $item['category'] ?? '',
                        'duration'    => isset($item['estimated_hours'])
                            ? $item['estimated_hours'] . ' hrs'
                            : 'Self-paced',
                    ];
                })->all();

                return [
                    'id'          => $c->id,
                    'title'       => $c->title,
                    'level'       => $c->level ?? 'Beginner',
                    'category'    => $c->category ?? 'General',
                    'description' => $c->description ?? '',
                    'projects'    => $projects,
                ];
            })
            ->values();

        $levelFees = [
            'Beginner'     => 4999,
            'Intermediate' => 7999,
            'Advanced'     => 12999,
        ];

        $initialStep = 1;
        if (!$canSelfAssignLevel && $student->level) {
            $initialStep = $student->internship_stream ? 3 : 2;
        }

        // If coming from the public enrollment form, override level/stream and jump to payment
        $currentLevel  = $student->level;
        $currentStream = $student->internship_stream;
        if (!empty($preSelectedCourseIds)) {
            $initialStep   = 5;
            $currentLevel  = $preSelectedLevel  ?? $student->level;
            $currentStream = $preSelectedStream ?? $student->internship_stream;
        }

        return view('dashboard.student-dashboard.internship.checkout', [
            'student'               => $student,
            'currentLevel'          => $currentLevel,
            'currentStream'         => $currentStream,
            'canSelfAssignLevel'    => $canSelfAssignLevel,
            'enrolledCourseIds'     => $enrolledCourseIds,
            'allCourses'            => $allCourses,
            'levelFees'             => $levelFees,
            'initialStep'           => $initialStep,
            'preSelectedCourseIds'  => $preSelectedCourseIds,
            'razorpayKey'           => config('services.razorpay.key'),
            ...$this->frontendAdminData('internship-checkout'),
        ]);
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
            $payments = Payment::with(['course.workspaces' => fn ($q) => $q->where('status', true)->orderByDesc('updated_at')])
                ->where('student_id', $student->id)
                ->orderBy('payment_date', 'desc')
                ->get();

            $enrollmentByCourse = Enrollment::where('student_id', $student->id)
                ->get()
                ->keyBy('course_id');

            $orders = $payments->map(function ($payment) use ($enrollmentByCourse) {
                $course     = $payment->course;
                $enrollment = $course ? $enrollmentByCourse->get($course->id) : null;
                $workspace  = $course?->workspaces?->first();
                $workspaceUrl = $enrollment
                    ? route('student.course.workspace', ['id' => $enrollment->id])
                        . ($workspace?->id ? '?project=' . $workspace->id : '')
                    : route('student.course.workspace.default');

                return [
                    'id' => $payment->id,
                    'title' => $course?->title ?? 'Unknown Course',
                    'order_id' => 'ORD-' . str_pad($payment->id, 4, '0', STR_PAD_LEFT),
                    'purchase_date' => $payment->payment_date?->format('F j, Y') ?? 'N/A',
                    'price' => $payment->amount ? 'Rs. ' . number_format($payment->amount, 0) : 'N/A',
                    'payment_status' => match ($payment->status) {
                        'success'  => 'Paid',
                        'refunded' => 'Refunded',
                        'failed'   => 'Failed',
                        default    => 'Pending',
                    },
                    'access_status' => $payment->status === 'success' ? 'Active' : 'Pending',
                    'workspace_url' => $workspaceUrl,
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

    public function downloadCertificate(Certificate $certificate)
    {
        $student = Student::where('user_id', Auth::id())->firstOrFail();
        abort_if($certificate->student_id !== $student->id, 403);

        $completedEnrollments = Enrollment::with('course')
            ->where('student_id', $student->id)
            ->where('status', 'completed')
            ->get();

        return view('student.certificate', [
            'certificate'          => $certificate,
            'student'              => $student,
            'user'                 => Auth::user(),
            'completedEnrollments' => $completedEnrollments,
        ]);
    }

    protected function enrollmentNeedsPayment(Enrollment $enrollment): bool
    {
        if ($enrollment->sponsor_type !== 'self') {
            return false;
        }

        $student = Student::find($enrollment->student_id);

        return ! ($student?->internship_paid ?? false);
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
            'status' => $progress === 100 ? 'completed' : 'active',
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
            ->filter(fn (Enrollment $enrollment) => in_array(strtolower((string) $enrollment->status), ['active', 'pending'], true))
            ->count();
        $averageProgress = $enrollments->count() ? (int) round($enrollments->avg('progress')) : 0;
        $payments = $student?->payments ?? collect();
        $paidAmount = $payments
            ->whereIn('status', ['success', 'paid', 'completed'])
            ->sum(fn (Payment $payment) => (float) $payment->amount);

        $canSelfAssignLevel = $student && $student->college && $student->college->user_id === null;

        $availableStreams = Course::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->filter()
            ->values()
            ->toArray();

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'avatar' => $user->avatar ?? 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
            'student_id' => $student?->id,
            'college_id' => $student?->college_id,
            'college_name' => $student?->college?->college_name ?? 'Not linked',
            'level' => $student?->level,
            'internship_stream' => $student?->internship_stream,
            'internship_paid' => (bool) ($student?->internship_paid),
            'available_streams' => $availableStreams,
            'can_self_assign_level' => $canSelfAssignLevel,
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
            'payment_history' => $payments->sortByDesc('payment_date')->map(fn (Payment $p) => [
                'course_title' => $p->course?->title ?? 'Unknown Course',
                'amount' => 'Rs. ' . number_format((float) $p->amount, 2),
                'status' => ucfirst($p->status ?? 'pending'),
                'date' => $p->payment_date?->format('M d, Y') ?? 'Pending',
                'razorpay_id' => $p->razorpay_payment_id,
            ])->values()->toArray(),
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
                'href' => $role === 'student' ? route('student.settings') : '#',
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
                        // [
                        //     'key' => 'internship-checkout',
                        //     'label' => 'Internship Checkout',
                        //     'icon' => 'fi fi-rr-shopping-bag',
                        //     'href' => route('student.internship.checkout'),
                        // ],
                        // [
                        //     'key' => 'student-enrolled-courses',
                        //     'label' => 'My Projects',
                        //     'icon' => 'fi fi-rr-book-alt',
                        //     'href' => route('dashboard.enrolled-courses'),
                        // ],
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
                'currentTrack'    => null,
                'totalEnrolled'   => 0,
                'activeCourses'   => 0,
                'completedCourses' => 0,
                'tasks'           => [],
                'leaderboard'     => [],
                'currentProgress' => 0,
                'completedSteps'  => 0,
                'totalSteps'      => 0,
                'nextLesson'      => null,
                'resumeUrl'       => route('student.projects'),
                'rank'            => null,
                'percentile'      => 0,
                'points'          => 0,
                'pendingTasks'    => 0,
                'completedTasks'  => 0,
                'enrolledProjects' => [],
                'internshipPaid'           => false,
                'studentLevel'             => null,
                'studentStream'            => null,
                'internshipCertificate'    => null,
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

          

        $allEnrollments = $student?->enrollments ?? collect();

        $internshipPaid = (bool) ($student?->internship_paid ?? false);

        // Only show student-initiated enrollments (no seat_allocation_id) AND only after payment is confirmed
        $enrollments = $internshipPaid
            ? $allEnrollments->filter(fn (Enrollment $e) => $e->seat_allocation_id === null)
            : collect();
        $totalEnrolled    = $enrollments->count();
        $activeCourses    = $enrollments
            ->filter(fn (Enrollment $enrollment) => in_array(strtolower((string) $enrollment->status), ['active', 'pending'], true))
            ->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();

        $latestEnrollment = $enrollments->sortByDesc('enrollment_date')->first();
        $latestCurriculum = $latestEnrollment?->course?->curriculum ?? [];
        $currentTrack = filled($latestCurriculum[0]['title'] ?? '')
            ? $latestCurriculum[0]['title']
            : ($latestEnrollment?->course?->title ?? $student?->course_name ?? null);

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

        $nextLesson = $activeStep['title'] ?? ($workspace?->next_milestone ?: null);
        $resumeUrl = $latestEnrollment && $workspace
            ? route('student.course.workspace', ['id' => $latestEnrollment->id]) . '?project=' . $workspace->id
            : route('student.projects');

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

        $levelColors = [
            'Beginner'     => ['color' => 'emerald', 'icon' => 'fi fi-rr-seedling'],
            'Intermediate' => ['color' => 'blue',    'icon' => 'fi fi-rr-chart-line-up'],
            'Advanced'     => ['color' => 'violet',  'icon' => 'fi fi-rr-rocket'],
        ];

        $enrolledProjects = $enrollments->map(function (Enrollment $enrollment) use ($user, $levelColors, $internshipPaid) {
            $course = $enrollment->course;
            $workspace = $course?->workspaces?->first();
            $steps = $workspace ? collect($this->studentWorkspaceSteps($workspace)) : collect();
            $totalStepsForProject = $steps->count();
            $completedStepsForProject = $steps->where('state', 'completed')->count();
            $projectProgress = $totalStepsForProject > 0
                ? (int) round(($completedStepsForProject / $totalStepsForProject) * 100)
                : (int) ($enrollment->progress ?? 0);

            $level = $course?->level ?? 'Not Selected Yet';
            $meta = $levelColors[$level] ?? $levelColors['Beginner'];

            // Use curriculum item title as the project title, not the course title
            $curriculum  = $course?->curriculum ?? [];
            $firstItem   = $curriculum[0] ?? [];
            $projectTitle = filled($firstItem['title'] ?? '')
                ? $firstItem['title']
                : ($course?->title ?? 'Unknown Project');

            $firstTask   = ($firstItem['tasks'][0] ?? []);
            $description = $firstTask['assignment'] ?? $course?->description ?? '';

            return [
                'id'              => $enrollment->id,
                'title'           => $projectTitle,
                'level'           => $level,
                'category'        => $course?->category ?? 'Project',
                'description'     => $description,
                'progress'        => $projectProgress,
                'status'          => Str::headline((string) ($enrollment->status ?: 'Active')),
                'color'           => $meta['color'],
                'icon'            => $meta['icon'],
                'workspace_locked' => ! $internshipPaid,
                'workspace_url'   => $internshipPaid
                    ? route('student.course.workspace', ['id' => $enrollment->id])
                        . ($workspace?->id ? '?project=' . $workspace->id : '')
                    : null,
                'enrollment_date' => $enrollment->enrollment_date?->format('M d, Y'),
            ];
        })->values()->toArray();

        $studentLevel   = $student?->level;
        $studentStream  = $student?->internship_stream;

        // Auto-issue internship completion certificate when all 3 projects are done
        $internshipCertificate = null;
        if ($student && $completedCourses >= 3 && $totalEnrolled >= 3) {
            $internshipCertificate = Certificate::where('student_id', $student->id)
                ->whereNull('course_id')
                ->first();
            if (! $internshipCertificate) {
                $certNumber = 'IEC-' . now()->format('Ym') . '-' . str_pad($student->id, 5, '0', STR_PAD_LEFT);
                $internshipCertificate = Certificate::create([
                    'student_id'         => $student->id,
                    'course_id'          => null,
                    'issued_date'        => now()->toDateString(),
                    'certificate_number' => $certNumber,
                ]);
            }
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
            'enrolledProjects',
            'internshipPaid',
            'studentLevel',
            'studentStream',
            'internshipCertificate',
        );
    }
}
