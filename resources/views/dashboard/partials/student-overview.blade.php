@php
    $studentName = Auth::user()->name ?? 'Student';
    $currentTrack = $currentTrack ?? 'Full Stack Development Internship';
    $totalEnrolled = $totalEnrolled ?? 5;
    $activeCourses = $activeCourses ?? 3;
    $completedCourses = $completedCourses ?? 2;

    $leaderboard = [
        ['rank' => 1, 'name' => 'Aarav Mehta', 'points' => '18,420', 'badge' => 'Gold', 'tone' => 'bg-amber-100 text-amber-700 ring-amber-200'],
        ['rank' => 2, 'name' => 'Diya Sharma', 'points' => '17,860', 'badge' => 'Silver', 'tone' => 'bg-slate-100 text-slate-700 ring-slate-200'],
        ['rank' => 3, 'name' => 'Kabir Rao', 'points' => '16,940', 'badge' => 'Bronze', 'tone' => 'bg-orange-100 text-orange-700 ring-orange-200'],
        ['rank' => 27, 'name' => $studentName, 'points' => '12,780', 'badge' => 'You', 'tone' => 'bg-blue-100 text-primary ring-blue-200', 'current' => true],
    ];

    $tasks = [
        ['title' => 'Build portfolio API', 'deadline' => 'Due today', 'status' => 'Pending', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        ['title' => 'React dashboard checkpoint', 'deadline' => 'Mentor review', 'status' => 'Review', 'tone' => 'bg-blue-50 text-blue-700 ring-blue-200'],
        ['title' => 'Git deployment lab', 'deadline' => 'Submitted', 'status' => 'Done', 'tone' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
    ];
@endphp

<div class="space-y-6">
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
                        <p class="mt-1 text-lg font-semibold text-slate-950">#27</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Percentile</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950">82%</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Points</p>
                        <p class="mt-1 text-lg font-semibold text-slate-950">12.8k</p>
                    </div>
                </div>

                <a href="{{ route('dashboard.enrolled-courses') }}"
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
                        <p class="mt-2 text-sm leading-6 text-slate-500">Module 08: Authentication and APIs</p>
                    </div>
                    <span class="inline-flex w-fit rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700">72%</span>
                </div>

                <div class="mt-6">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-slate-600">Course progress</span>
                        <span class="font-semibold text-slate-900">72 of 100</span>
                    </div>
                    <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full w-[72%] rounded-full bg-primary"></div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-slate-500">Next lesson: Secure login flow and API guards</p>
                    <a href="{{ route('dashboard.enrolled-courses') }}"
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
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700 ring-1 ring-amber-200">1 pending</span>
                        <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">1 done</span>
                    </div>
                </div>

                <div class="mt-5 divide-y divide-slate-100 rounded-lg border border-slate-200">
                    @foreach ($tasks as $task)
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
                    @endforeach
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
                    @foreach ($leaderboard as $player)
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
                    @endforeach
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-primary">Progress Chart</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950">Rank improvement</h2>
                    </div>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">+14 rank improvement</span>
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
                    Next goal: Top 20
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
