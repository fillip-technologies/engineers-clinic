@php
    $studentName     = Auth::user()->name ?? 'Student';
    $currentTrack    = $currentTrack ?? 'Learning Track';
    $totalEnrolled   = $totalEnrolled ?? 0;
    $activeCourses   = $activeCourses ?? 0;
    $completedCourses = $completedCourses ?? 0;
    $leaderboard     = $leaderboard ?? [];
    $tasks           = $tasks ?? [];
    $currentProgress = $currentProgress ?? 0;
    $completedSteps  = $completedSteps ?? 0;
    $totalSteps      = $totalSteps ?? 0;
    $nextLesson      = $nextLesson ?? 'Select a project to begin.';
    $resumeUrl       = $resumeUrl ?? route('student.projects');
    $rank            = $rank ?? null;
    $percentile      = $percentile ?? 0;
    $points          = $points ?? 0;
    $pendingTasks    = $pendingTasks ?? 0;
    $completedTasks  = $completedTasks ?? 0;
    $enrolledProjects = $enrolledProjects ?? [];

    $levelColors = [
        'Beginner'     => ['color' => 'emerald', 'icon' => 'fi fi-rr-seedling'],
        'Intermediate' => ['color' => 'blue',    'icon' => 'fi fi-rr-chart-line-up'],
        'Advanced'     => ['color' => 'violet',  'icon' => 'fi fi-rr-rocket'],
    ];
@endphp

<div class="space-y-6">

    {{-- Flash messages from project selection --}}
    @if (session('success'))
        <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
            <i class="fi fi-rr-check-circle mt-0.5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- My Projects section --}}
    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-sm font-semibold text-primary">My Projects</p>
                <h2 class="mt-1 text-xl font-semibold text-slate-950">Selected Projects ({{ count($enrolledProjects) }}/3)</h2>
            </div>
            @if (count($enrolledProjects) < 3)
                <a href="{{ route('student.projects') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-primary hover:text-primary">
                    <i class="fi fi-rr-plus text-sm leading-none"></i>
                    Browse Projects
                </a>
            @endif
        </div>

        @if (empty($enrolledProjects))
            <div class="mt-5 flex flex-col items-center rounded-xl border border-dashed border-slate-300 bg-slate-50 py-12 text-center">
                <i class="fi fi-rr-rocket text-3xl text-slate-300"></i>
                <p class="mt-3 text-sm font-semibold text-slate-700">No projects selected yet</p>
                <p class="mt-1 text-sm text-slate-500">Choose up to 3 projects to start your internship journey.</p>
                <div class="mt-4 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('student.projects') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                        <i class="fi fi-rr-search text-sm leading-none"></i>
                        Browse Projects
                    </a>
                    <a href="{{ route('payments.available-courses') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-primary hover:text-primary">
                        <i class="fi fi-rr-credit-card text-sm leading-none"></i>
                        Enroll with Payment
                    </a>
                </div>
            </div>
        @else
            <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($enrolledProjects as $ep)
                    @php
                        $epMeta = $levelColors[$ep['level']] ?? $levelColors['Beginner'];
                        $colorMap = [
                            'emerald' => ['badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'bar' => 'bg-emerald-500', 'btn' => 'bg-emerald-600 hover:bg-emerald-700'],
                            'blue'    => ['badge' => 'bg-blue-50 text-blue-700 ring-blue-200',         'bar' => 'bg-blue-500',    'btn' => 'bg-blue-600 hover:bg-blue-700'],
                            'violet'  => ['badge' => 'bg-violet-50 text-violet-700 ring-violet-200',   'bar' => 'bg-violet-500',  'btn' => 'bg-violet-600 hover:bg-violet-700'],
                        ];
                        $epColors = $colorMap[$epMeta['color']] ?? $colorMap['blue'];
                    @endphp
                    <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-4 shadow-sm transition hover:shadow-md">
                        {{-- Level badge --}}
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $epColors['badge'] }}">
                                <i class="{{ $epMeta['icon'] }} text-[10px] leading-none"></i>
                                {{ $ep['level'] }}
                            </span>
                            <span class="text-xs font-semibold {{ $ep['progress'] === 100 ? 'text-emerald-600' : 'text-slate-500' }}">
                                {{ $ep['progress'] }}%
                            </span>
                        </div>

                        {{-- Title --}}
                        <h3 class="mt-3 text-sm font-semibold leading-snug text-slate-900">{{ $ep['title'] }}</h3>
                        <p class="mt-1 flex-1 text-xs text-slate-500">{{ $ep['category'] }}</p>

                        {{-- Progress bar --}}
                        <div class="mt-3 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                            <div class="h-full rounded-full {{ $epColors['bar'] }} transition-all"
                                style="width: {{ $ep['progress'] }}%"></div>
                        </div>

                        {{-- Status + enrolled date --}}
                        <div class="mt-2 flex items-center justify-between text-xs text-slate-400">
                            <span>{{ $ep['status'] }}</span>
                            <span>Since {{ $ep['enrollment_date'] }}</span>
                        </div>

                        {{-- Continue button --}}
                        <a href="{{ $ep['workspace_url'] }}"
                            class="mt-4 flex w-full items-center justify-center gap-2 rounded-xl {{ $epColors['btn'] }} px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition">
                            <i class="fi fi-rr-play text-xs leading-none"></i>
                            {{ $ep['progress'] > 0 ? 'Continue' : 'Start Project' }}
                        </a>
                    </div>
                @endforeach

                {{-- Empty slot cards when < 3 selected --}}
                @for ($i = count($enrolledProjects); $i < 3; $i++)
                    <a href="{{ route('student.projects') }}"
                        class="flex flex-col items-center justify-center rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4 text-center transition hover:border-primary hover:bg-blue-50">
                        <i class="fi fi-rr-plus-small text-2xl text-slate-300"></i>
                        <p class="mt-2 text-xs font-semibold text-slate-500">Add a project</p>
                        <p class="mt-0.5 text-xs text-slate-400">Slot {{ $i + 1 }} of 3</p>
                    </a>
                @endfor
            </div>
        @endif
    </section>

    <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
            <div class="min-w-0">
                <p class="text-sm font-medium text-slate-500">Welcome back, {{ $studentName }}</p>
                <h1 class="mt-1 truncate text-2xl font-semibold tracking-tight text-slate-950 sm:text-3xl">
                    {{ $currentTrack }}
                </h1>
            </div>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                <div class="grid grid-cols-3 divide-x divide-slate-200 rounded-lg border border-slate-200 bg-slate-50">
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Rank</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950">{{ $rank ? '#' . $rank : '-' }}</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Percentile</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950">{{ $percentile }}%</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Points</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950">{{ number_format($points) }}</p>
                    </div>
                </div>

                <a href="{{ $resumeUrl }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    <i class="fi fi-rr-play-alt text-base"></i>
                    <span>Continue Learning</span>
                </a>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="space-y-6">
            <section class="rounded-xl border border-blue-200 bg-blue-50/40 p-5 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-primary">Continue Learning</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">{{ $currentTrack }}</h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $nextLesson }}</p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">{{ $currentProgress }}%</span>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-600">Course progress</span>
                        <span class="font-semibold text-slate-900">{{ $completedSteps }} of {{ $totalSteps }}</span>
                    </div>
                    <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-primary" style="width: {{ $currentProgress }}%"></div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">Next: {{ $nextLesson }}</p>
                    <a href="{{ $resumeUrl }}"
                        class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                        Resume
                    </a>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-primary">Internship Tasks</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">Current workload</h2>
                    </div>
                    <div class="hidden gap-2 sm:flex">
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">{{ $pendingTasks }} pending</span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">{{ $completedTasks }} done</span>
                    </div>
                </div>

                <div class="mt-5 divide-y divide-slate-100 rounded-lg border border-slate-200">
                    @forelse ($tasks as $task)
                        <button type="button" class="group flex w-full items-center gap-4 px-4 py-4 text-left transition hover:bg-slate-50">
                            <span class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                                <i class="fi fi-rr-clipboard-check text-sm"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block truncate text-sm font-medium text-slate-900">{{ $task['title'] }}</span>
                                <span class="mt-1 block text-xs text-slate-500">{{ $task['deadline'] }}</span>
                            </span>
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $task['tone'] }}">{{ $task['status'] }}</span>
                            <i class="fi fi-rr-arrow-small-right text-base text-slate-300 transition group-hover:translate-x-0.5 group-hover:text-primary"></i>
                        </button>
                    @empty
                        <div class="px-4 py-8 text-center text-sm text-slate-500">
                            No project tasks are available for your current course yet.
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <aside class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-primary">Leaderboard</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">This week</h2>
                    </div>
                    <i class="fi fi-rr-trophy text-xl text-slate-400"></i>
                </div>

                <div class="mt-5 space-y-2">
                    @forelse ($leaderboard as $player)
                        <div class="flex items-center gap-3 rounded-lg border px-3 py-3 {{ ! empty($player['current']) ? 'border-primary bg-blue-50' : 'border-slate-200 bg-white' }}">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ ! empty($player['current']) ? 'bg-primary text-white' : 'bg-slate-100 text-slate-700' }} text-sm font-semibold">
                                {{ $player['rank'] }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-semibold text-slate-950">{{ $player['name'] }}</p>
                                <p class="text-xs text-slate-500">{{ $player['points'] }} points</p>
                            </div>
                            <span class="rounded-full px-2 py-1 text-xs font-semibold ring-1 {{ $player['tone'] }}">{{ $player['badge'] }}</span>
                        </div>
                    @empty
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-6 text-center text-sm text-slate-500">
                            Leaderboard appears after students start making progress.
                        </div>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-primary">Progress Chart</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">Rank improvement</h2>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">{{ $currentProgress }}% complete</span>
                </div>

                <div class="mt-5 h-44 rounded-lg border border-slate-200 bg-slate-50 p-4">
                    <svg class="h-full w-full" viewBox="0 0 320 150" fill="none" role="img" aria-label="Simple rank progress line chart">
                        <path d="M0 118H320" stroke="#E2E8F0" stroke-width="1" />
                        <path d="M0 78H320" stroke="#E2E8F0" stroke-width="1" />
                        <path d="M0 38H320" stroke="#E2E8F0" stroke-width="1" />
                        <path d="M4 118C42 110 54 93 80 96C121 101 127 68 160 70C195 72 203 47 238 50C276 53 290 31 316 28" stroke="#2563EB" stroke-width="3" stroke-linecap="round" />
                        <g fill="#FFFFFF" stroke="#2563EB" stroke-width="3">
                            <circle cx="80" cy="96" r="5" />
                            <circle cx="160" cy="70" r="5" />
                            <circle cx="238" cy="50" r="5" />
                            <circle cx="316" cy="28" r="5" />
                        </g>
                    </svg>
                </div>
                <div class="mt-3 flex justify-between text-xs font-medium text-slate-500">
                    <span>Week 1</span>
                    <span>Week 2</span>
                    <span>Week 3</span>
                    <span>Now</span>
                </div>
                <p class="mt-4 rounded-lg bg-slate-50 px-3 py-2 text-sm font-medium text-slate-700">
                    Next goal: {{ $nextLesson }}
                </p>
            </section>
        </aside>
    </section>

    <section class="grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Enrolled courses</p>
            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ str_pad($totalEnrolled, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Active courses</p>
            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ str_pad($activeCourses, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm font-medium text-slate-500">Completed courses</p>
            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ str_pad($completedCourses, 2, '0', STR_PAD_LEFT) }}</p>
        </div>
    </section>
</div>
