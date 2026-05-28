@php
    $recentStudents = $recentStudents ?? [];
    $topCourses = $topCourses ?? [];
    $activities = $activities ?? [];
    $announcements = $announcements ?? [];
    $statCards = $statCards ?? [];
    $collegeChartData = $collegeChartData ?? [
        'studentGrowth' => ['labels' => [], 'data' => []],
        'enrollmentDistribution' => ['labels' => [], 'data' => []],
        'placementStats' => ['labels' => ['Completed', 'In progress'], 'data' => [0, 0]],
        'engagement' => ['labels' => ['Active', 'Inactive'], 'active' => [0], 'inactive' => [0]],
    ];
@endphp

<!-- <section class="relative overflow-hidden rounded-[2rem] border border-white/60 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 px-6 py-8 shadow-[0_24px_60px_rgba(15,23,42,0.18)] sm:px-8">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(96,165,250,0.18),_transparent_28%),radial-gradient(circle_at_80%_20%,_rgba(168,85,247,0.18),_transparent_22%),radial-gradient(circle_at_bottom_right,_rgba(34,197,94,0.12),_transparent_24%)]"></div>
    <div class="absolute -right-16 top-6 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
    <div class="absolute bottom-0 left-0 h-32 w-32 rounded-full bg-cyan-400/10 blur-3xl"></div>

    <div class="relative flex flex-col gap-8 xl:flex-row xl:items-end xl:justify-between">
        <div class="max-w-3xl">
            <p class="text-xs font-semibold uppercase tracking-[0.28em] text-blue-200">College Dashboard</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-5xl">ABC College Performance Hub</h1>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200 sm:text-base">
                Monitor student growth, enrollment momentum, engagement health, and placement outcomes from one
                institution-grade command center.
            </p>
        </div>

        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-300">Partner Health</p>
                <p class="mt-3 text-2xl font-semibold text-white">Excellent</p>
                <p class="mt-2 text-sm text-slate-300">Engagement and completion are trending above target.</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-300">Next Review</p>
                <p class="mt-3 text-2xl font-semibold text-white">Apr 30</p>
                <p class="mt-2 text-sm text-slate-300">Institution performance review with Engineers Clinic.</p>
            </div>
        </div>
    </div>
</section> -->

<section class="mt-8 grid gap-5 md:grid-cols-2 2xl:grid-cols-4">
    @foreach ($statCards as $card)
        <article class="group rounded-[1.5rem] border border-slate-200/70 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-0.5 hover:shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $card['label'] }}</p>
                    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-900">{{ $card['value'] }}</p>
                    <div class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                        <span class="text-emerald-600">{{ $card['change'] }}</span>
                        <span>vs last month</span>
                    </div>
                </div>
                <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $card['classes'] }}">
                    <i class="{{ $card['icon'] }} mt-1 text-xl"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

<section class="mt-8 grid gap-6 2xl:grid-cols-[1.35fr_1fr]">
    <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Student Growth</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Registrations over time</h2>
            </div>
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">+12% this quarter</span>
        </div>
        <div class="mt-6 h-[320px]">
            <canvas id="collegeStudentGrowthChart"></canvas>
        </div>
    </article>

    <div class="grid gap-6">
        <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Enrollment Mix</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Course enrollment distribution</h2>
                </div>
                <span class="text-xs font-semibold text-slate-500">Top programs</span>
            </div>
            <div class="mt-6 h-[220px]">
                <canvas id="collegeEnrollmentDistributionChart"></canvas>
            </div>
        </article>

        <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Placement Stats</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-900">Placed vs not placed</h2>
                </div>
                <span class="text-xs font-semibold text-emerald-600">78% success</span>
            </div>
            <div class="mt-6 h-[220px]">
                <canvas id="collegePlacementStatsChart"></canvas>
            </div>
        </article>
    </div>
</section>

<section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Engagement</p>
            <h2 class="mt-2 text-2xl font-semibold text-slate-900">Active vs inactive student activity</h2>
        </div>
        <span class="inline-flex rounded-full bg-violet-50 px-3 py-1 text-xs font-semibold text-violet-700">Live engagement trend</span>
    </div>
    <div class="mt-6 h-[320px]">
        <canvas id="collegeEngagementChart"></canvas>
    </div>
</section>

<section class="mt-8 grid gap-6 xl:grid-cols-2">
    <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recent Students</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">New and active learners</h2>
            </div>
            <a href="#" class="text-sm font-semibold text-blue-700 transition hover:text-blue-800">View all</a>
        </div>

        <div class="mt-6 space-y-4">
            @foreach ($recentStudents as $student)
                <div class="flex items-center justify-between gap-4 rounded-2xl border border-slate-200/70 bg-slate-50/70 px-4 py-4 transition hover:border-blue-200 hover:bg-blue-50/40">
                    <div class="flex min-w-0 items-center gap-4">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-semibold text-white">
                            {{ strtoupper(substr($student['name'], 0, 1)) }}
                        </span>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $student['name'] }}</p>
                            <p class="truncate text-sm text-slate-500">{{ $student['course'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $student['status'] === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $student['status'] }}
                        </span>
                        <p class="mt-2 text-xs text-slate-500">{{ $student['joined'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </article>

    <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Top Performing Courses</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Programs with the strongest outcomes</h2>
            </div>
            <span class="text-sm font-semibold text-slate-500">Completion leaders</span>
        </div>

        <div class="mt-6 space-y-4">
            @foreach ($topCourses as $course)
                <div class="rounded-2xl border border-slate-200/70 bg-slate-50/70 px-4 py-4 transition hover:border-violet-200 hover:bg-violet-50/40">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-slate-900">{{ $course['name'] }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ $course['enrollments'] }} enrollments</p>
                        </div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                            {{ $course['completion'] }} completion
                        </span>
                    </div>
                    <div class="mt-4 h-2 rounded-full bg-slate-200">
                        <div class="h-2 rounded-full bg-gradient-to-r from-violet-500 to-blue-500" style="width: {{ $course['completion'] }}"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </article>
</section>

<section class="mt-8 grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
    <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recent Activity</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">What changed across the institution</h2>
            </div>
            <span class="text-sm font-semibold text-slate-500">Live timeline</span>
        </div>

        <div class="mt-6 space-y-5">
            @foreach ($activities as $activity)
                <div class="flex gap-4">
                    <div class="flex flex-col items-center">
                        <span class="mt-1 h-3 w-3 rounded-full {{ $activity['tone'] === 'green' ? 'bg-emerald-500' : ($activity['tone'] === 'orange' ? 'bg-orange-500' : ($activity['tone'] === 'purple' ? 'bg-violet-500' : 'bg-blue-500')) }}"></span>
                        @if (! $loop->last)
                            <span class="mt-2 w-px flex-1 bg-slate-200"></span>
                        @endif
                    </div>
                    <div class="flex-1 rounded-2xl border border-slate-200/70 bg-slate-50/70 px-4 py-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $activity['title'] }}</p>
                        <p class="mt-2 text-sm text-slate-500">{{ $activity['time'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </article>

    <article class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-7">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Announcements</p>
                <h2 class="mt-2 text-2xl font-semibold text-slate-900">Institution notices</h2>
            </div>
            <a href="#" class="text-sm font-semibold text-blue-700 transition hover:text-blue-800">Create notice</a>
        </div>

        <div class="mt-6 space-y-4">
            @foreach ($announcements as $announcement)
                <div class="rounded-2xl border border-slate-200/70 bg-gradient-to-r from-slate-50 to-white px-4 py-4 transition hover:border-blue-200 hover:shadow-sm">
                    <p class="text-sm font-semibold text-slate-900">{{ $announcement['title'] }}</p>
                    <p class="mt-2 text-sm text-slate-500">{{ $announcement['meta'] }}</p>
                </div>
            @endforeach
        </div>
    </article>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (() => {
        const chartData = @js($collegeChartData);
        const makeGradient = (context, colors) => {
            const gradient = context.createLinearGradient(0, 0, 0, 260);
            gradient.addColorStop(0, colors[0]);
            gradient.addColorStop(1, colors[1]);
            return gradient;
        };

        const studentGrowthCanvas = document.getElementById('collegeStudentGrowthChart');
        if (studentGrowthCanvas) {
            const ctx = studentGrowthCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.studentGrowth.labels,
                    datasets: [{
                        label: 'Registrations',
                        data: chartData.studentGrowth.data,
                        borderColor: '#4f46e5',
                        backgroundColor: makeGradient(ctx, ['rgba(79,70,229,0.28)', 'rgba(79,70,229,0.03)']),
                        fill: true,
                        borderWidth: 3,
                        pointRadius: 0,
                        tension: 0.42
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        },
                        y: {
                            grid: { color: 'rgba(148,163,184,0.16)' },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }

        const enrollmentCanvas = document.getElementById('collegeEnrollmentDistributionChart');
        if (enrollmentCanvas) {
            new Chart(enrollmentCanvas, {
                type: 'bar',
                data: {
                    labels: chartData.enrollmentDistribution.labels,
                    datasets: [{
                        data: chartData.enrollmentDistribution.data,
                        borderRadius: 10,
                        backgroundColor: ['#4f46e5', '#06b6d4', '#8b5cf6', '#22c55e']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        },
                        y: {
                            grid: { color: 'rgba(148,163,184,0.16)' },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }

        const placementCanvas = document.getElementById('collegePlacementStatsChart');
        if (placementCanvas) {
            new Chart(placementCanvas, {
                type: 'pie',
                data: {
                    labels: chartData.placementStats.labels,
                    datasets: [{
                        data: chartData.placementStats.data,
                        backgroundColor: ['#22c55e', '#e2e8f0'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#475569',
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    }
                }
            });
        }

        const engagementCanvas = document.getElementById('collegeEngagementChart');
        if (engagementCanvas) {
            const ctx = engagementCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: chartData.engagement.labels,
                    datasets: [
                        {
                            label: 'Active Students',
                            data: chartData.engagement.active,
                            borderColor: '#06b6d4',
                            backgroundColor: makeGradient(ctx, ['rgba(6,182,212,0.18)', 'rgba(6,182,212,0.02)']),
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            borderWidth: 3
                        },
                        {
                            label: 'Inactive Students',
                            data: chartData.engagement.inactive,
                            borderColor: '#a855f7',
                            backgroundColor: makeGradient(ctx, ['rgba(168,85,247,0.14)', 'rgba(168,85,247,0.02)']),
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            borderWidth: 3
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: '#475569',
                                usePointStyle: true,
                                boxWidth: 8
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b' }
                        },
                        y: {
                            grid: { color: 'rgba(148,163,184,0.16)' },
                            ticks: { color: '#64748b' }
                        }
                    }
                }
            });
        }
    })();
</script>
