@extends('Admin.layouts.layout')

@section('content')
    @php
        $studentsCount = \App\Models\Student::count();
        $coursesCount = \App\Models\Course::count();
        $enrollmentsCount = \App\Models\Enrollment::count();
        $paymentsCount = \App\Models\Payment::count();
        $certificatesCount = \App\Models\Certificate::count();
        $usersCount = \App\Models\User::count();
        $weekStart = now()->subDays(7);

        $stats = [
            [
                'label' => 'Total Users',
                'value' => $usersCount,
                'icon' => 'fi-rr-users',
                'accent' => 'bg-indigo-50 text-indigo-600',
                'trend' => '+' . \App\Models\User::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
            [
                'label' => 'Students',
                'value' => $studentsCount,
                'icon' => 'fi-rr-user',
                'accent' => 'bg-sky-50 text-sky-600',
                'trend' => '+' . \App\Models\Student::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
            [
                'label' => 'Courses',
                'value' => $coursesCount,
                'icon' => 'fi-rr-book-bookmark',
                'accent' => 'bg-violet-50 text-violet-600',
                'trend' => '+' . \App\Models\Course::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
            [
                'label' => 'Enrollments',
                'value' => $enrollmentsCount,
                'icon' => 'fi-rr-clipboard-list',
                'accent' => 'bg-orange-50 text-orange-600',
                'trend' => '+' . \App\Models\Enrollment::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
            [
                'label' => 'Payments',
                'value' => $paymentsCount,
                'icon' => 'fi-rr-credit-card',
                'accent' => 'bg-emerald-50 text-emerald-600',
                'trend' => '+' . \App\Models\Payment::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
            [
                'label' => 'Certificates',
                'value' => $certificatesCount,
                'icon' => 'fi-rr-medal',
                'accent' => 'bg-amber-50 text-amber-600',
                'trend' => '+' . \App\Models\Certificate::where('created_at', '>=', $weekStart)->count() . ' this week',
                'trendTone' => 'text-emerald-600',
            ],
        ];

        $quickActions = [
            ['label' => 'Add Student', 'route' => 'admin.students.create', 'icon' => 'fi-rr-user-add'],
            ['label' => 'Add College', 'route' => 'admin.colleges.create', 'icon' => 'fi-rr-building'],
            ['label' => 'Create Course', 'route' => 'admin.courses.create', 'icon' => 'fi-rr-book-plus'],
            ['label' => 'Assign Permission', 'route' => 'admin.role-permissions.create', 'icon' => 'fi-rr-key'],
        ];

        $lineMonths = collect(range(5, 0))->map(fn ($offset) => now()->subMonths($offset));
        $lineValues = $lineMonths->map(fn ($date) => \App\Models\Enrollment::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count());
        $lineMax = max($lineValues->max(), 1);
        $linePoints = $lineValues->values()->map(function ($value, $index) use ($lineMax) {
            $x = 40 + ($index * 64);
            $y = 170 - (($value / $lineMax) * 120);
            return round($x, 1) . ',' . round($y, 1);
        })->implode(' ');

        $barData = [
            ['label' => 'Students', 'value' => $studentsCount, 'color' => 'bg-sky-500', 'fill' => '#38BDF8'],
            ['label' => 'Courses', 'value' => $coursesCount, 'color' => 'bg-violet-500', 'fill' => '#8B5CF6'],
            ['label' => 'Enrollments', 'value' => $enrollmentsCount, 'color' => 'bg-orange-400', 'fill' => '#FB923C'],
            ['label' => 'Certificates', 'value' => $certificatesCount, 'color' => 'bg-emerald-500', 'fill' => '#10B981'],
        ];
        $barMax = max(collect($barData)->max('value'), 1);
    @endphp

    <div class="space-y-6">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight text-gray-950">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-600">
                    Track platform activity, learning operations, and access workflows.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <span class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-600">
                    <span class="font-semibold text-gray-950">{{ $usersCount }}</span> total users
                </span>
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Operational
                </span>
            </div>
        </section>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($stats as $stat)
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm transition hover:border-gray-300 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-600">{{ $stat['label'] }}</p>
                            <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950">{{ $stat['value'] }}</p>
                        </div>
                        <span class="flex h-10 w-10 items-center justify-center rounded-lg {{ $stat['accent'] }}">
                            <i class="fi {{ $stat['icon'] }} text-base leading-none"></i>
                        </span>
                    </div>
                    <div class="mt-4 flex items-center gap-2 text-xs font-medium {{ $stat['trendTone'] }}">
                        <i class="fi fi-rr-arrow-trend-up leading-none"></i>
                        {{ $stat['trend'] }}
                    </div>
                </div>
            @endforeach
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.35fr_1fr]">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-semibold text-gray-950">Enrollments Over Time</h2>
                        <p class="mt-1 text-sm text-gray-600">Monthly enrollment activity for the last 6 months.</p>
                    </div>
                    <span class="rounded-full bg-brandSoft px-3 py-1 text-xs font-medium text-brand">Live data</span>
                </div>

                <div class="mt-6 overflow-hidden">
                    <svg class="h-64 w-full" viewBox="0 0 390 220" role="img" aria-label="Enrollments over time line chart">
                        <line x1="40" y1="40" x2="360" y2="40" stroke="#F3F4F6" />
                        <line x1="40" y1="80" x2="360" y2="80" stroke="#F3F4F6" />
                        <line x1="40" y1="120" x2="360" y2="120" stroke="#F3F4F6" />
                        <line x1="40" y1="160" x2="360" y2="160" stroke="#F3F4F6" />
                        <polyline points="{{ $linePoints }}" fill="none" stroke="#4F46E5" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="3" />
                        @foreach($lineValues->values() as $value)
                            @php
                                $x = 40 + ($loop->index * 64);
                                $y = 170 - (($value / $lineMax) * 120);
                            @endphp
                            <circle cx="{{ $x }}" cy="{{ $y }}" r="4" fill="#4F46E5" />
                        @endforeach
                        @foreach($lineMonths as $month)
                            <text x="{{ 40 + ($loop->index * 64) }}" y="205" text-anchor="middle" fill="#6B7280"
                                font-size="11">{{ $month->format('M') }}</text>
                        @endforeach
                    </svg>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div>
                    <h2 class="text-base font-semibold text-gray-950">Platform Distribution</h2>
                    <p class="mt-1 text-sm text-gray-600">Core records across learning operations.</p>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach($barData as $item)
                        @php($width = max(($item['value'] / $barMax) * 100, $item['value'] > 0 ? 8 : 2))
                        <div>
                            <div class="mb-2 flex items-center justify-between text-sm">
                                <span class="font-medium text-gray-700">{{ $item['label'] }}</span>
                                <span class="text-gray-500">{{ $item['value'] }}</span>
                            </div>
                            <div class="h-3 overflow-hidden rounded-full bg-gray-100">
                                <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1fr_22rem]">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-semibold text-gray-950">Quick Actions</h2>
                        <p class="mt-1 text-sm text-gray-600">Common admin workflows, ready when you need them.</p>
                    </div>
                    <a href="{{ route('admin.notifications.index') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 transition hover:border-brand hover:bg-brandSoft hover:text-brand">
                        View notifications
                    </a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    @foreach($quickActions as $action)
                        <a href="{{ route($action['route']) }}"
                            class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm font-medium text-gray-800 transition hover:border-brand/40 hover:bg-brandSoft hover:text-brand">
                            <span class="flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-50 text-gray-500">
                                    <i class="fi {{ $action['icon'] }} text-base leading-none"></i>
                                </span>
                                {{ $action['label'] }}
                            </span>
                            <i class="fi fi-rr-angle-small-right leading-none text-gray-400"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="h-1.5 bg-brand"></div>
                <div class="p-6">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-brand">
                        <i class="fi fi-rr-shield-check text-base leading-none"></i>
                    </div>
                    <h2 class="mt-4 text-base font-semibold text-gray-950">Access Control</h2>
                    <p class="mt-2 text-sm leading-6 text-gray-600">
                        Keep roles and permission sets aligned with current admin responsibilities.
                    </p>
                    <div class="mt-5 grid gap-3">
                        <a href="{{ route('admin.roles.index') }}"
                            class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:border-brand hover:bg-brandSoft hover:text-brand">
                            Roles
                        </a>
                        <a href="{{ route('admin.permissions.index') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2.5 text-sm font-medium text-white transition hover:bg-brandDark">
                            Permissions
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
