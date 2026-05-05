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
'accent' => 'from-brand to-brandLight',
'trend' => '+' . \App\Models\User::where('created_at', '>=', $weekStart)->count() . ' this week',
'trendTone' => 'text-emerald-600',
],
[
'label' => 'Students',
'value' => $studentsCount,
'icon' => 'fi-rr-user',
'accent' => 'from-sky-500 to-blue-500',
'trend' => '+' . \App\Models\Student::where('created_at', '>=', $weekStart)->count() . ' this week',
'trendTone' => 'text-emerald-600',
],
[
'label' => 'Courses',
'value' => $coursesCount,
'icon' => 'fi-rr-book-bookmark',
'accent' => 'from-violet-500 to-fuchsia-500',
'trend' => '+' . \App\Models\Course::where('created_at', '>=', $weekStart)->count() . ' this week',
'trendTone' => 'text-emerald-600',
],
[
'label' => 'Enrollments',
'value' => $enrollmentsCount,
'icon' => 'fi-rr-clipboard-list',
'accent' => 'from-orange-500 to-amber-400',
'trend' => '+' . \App\Models\Enrollment::where('created_at', '>=', $weekStart)->count() . ' this week',
'trendTone' => 'text-emerald-600',
],
[
'label' => 'Payments',
'value' => $paymentsCount,
'icon' => 'fi-rr-credit-card',
'accent' => 'from-emerald-500 to-teal-400',
'trend' => '+' . \App\Models\Payment::where('created_at', '>=', $weekStart)->count() . ' this week',
'trendTone' => 'text-emerald-600',
],
[
'label' => 'Certificates',
'value' => $certificatesCount,
'icon' => 'fi-rr-medal',
'accent' => 'from-yellow-500 to-secondary',
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
$barMa = max(collect($barData)->max('value'), 1);
@endphp

<div class="space-y-7">
    <section
        class="relative overflow-hidden rounded-[2rem] border border-white/80 bg-white/75 p-6 shadow-2xl shadow-brand/10 backdrop-blur-2xl sm:p-8">
        <div class="absolute right-0 top-0 h-40 w-40 rounded-full bg-brandSoft blur-3xl"></div>
        <div class="absolute bottom-0 right-36 h-32 w-32 rounded-full bg-secondarySoft blur-3xl"></div>

        <div class="relative flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand">Admin Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Welcome back, {{ Auth::user()->name }}.
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-textSecondary sm:text-base">
                    Track platform activity, learning operations, access workflows, and student progress from one
                    focused workspace.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:w-[24rem]">
                <div class="rounded-2xl border border-glassBorder bg-white/70 p-4 shadow-sm">
                    <p class="text-2xl font-semibold text-textPrimary">{{ $usersCount }}</p>
                    <p class="mt-1 text-xs font-medium text-textMuted">Users</p>
                </div>
                <div class="rounded-2xl border border-glassBorder bg-white/70 p-4 shadow-sm">
                    <p class="text-2xl font-semibold text-textPrimary">{{ $studentsCount }}</p>
                    <p class="mt-1 text-xs font-medium text-textMuted">Students</p>
                </div>
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm">
                    <p class="text-sm font-semibold text-emerald-700">Operational</p>
                    <p class="mt-2 text-xs font-medium text-emerald-600">Live system</p>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach($stats as $stat)
        <div class="group rounded-[1.5rem] border border-white/80 bg-white/75 p-5 shadow-lg shadow-brand/5 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-brand/10">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-textMuted">{{ $stat['label'] }}</p>
                    <p class="mt-3 text-3xl font-semibold tracking-tight text-textPrimary">{{ $stat['value'] }}</p>
                </div>
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br {{ $stat['accent'] }} text-white shadow-lg shadow-brand/20">
                    <i class="fi {{ $stat['icon'] }} text-base leading-none"></i>
                </span>
            </div>
            <div class="mt-5 flex items-center justify-between border-t border-glassBorder pt-4">
                <div class="flex items-center gap-2 text-xs font-semibold {{ $stat['trendTone'] }}">
                    <i class="fi fi-rr-arrow-trend-up leading-none"></i>
                    {{ $stat['trend'] }}
                </div>
                <span class="h-2 w-12 rounded-full bg-gradient-to-r {{ $stat['accent'] }} opacity-70 transition group-hover:w-16"></span>
            </div>
        </div>
        @endforeach
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.35fr_1fr]">
        <div class="rounded-[1.75rem] border border-white/80 bg-white/75 p-6 shadow-xl shadow-brand/5 backdrop-blur-xl">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-textPrimary">Enrollments Over Time</h2>
                    <p class="mt-1 text-sm text-textSecondary">Monthly enrollment activity for the last 6 months.</p>
                </div>
                <span class="rounded-full bg-brandSoft px-3 py-1 text-xs font-semibold text-brand">Live data</span>
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

        <div class="rounded-[1.75rem] border border-white/80 bg-white/75 p-6 shadow-xl shadow-brand/5 backdrop-blur-xl">
            <div>
                <h2 class="text-lg font-semibold text-textPrimary">Platform Distribution</h2>
                <p class="mt-1 text-sm text-textSecondary">Core records across learning operations.</p>
            </div>

            <div class="mt-6 space-y-4">
                @foreach($barData as $item)
                @php($width = max(($item['value'] / $barMa) * 100, $item['value'] > 0 ? 8 : 2))
                <div>
                    <div class="mb-2 flex items-center justify-between text-sm">
                        <span class="font-medium text-textSecondary">{{ $item['label'] }}</span>
                        <span class="text-textMuted">{{ $item['value'] }}</span>
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
        <div class="rounded-[1.75rem] border border-white/80 bg-white/75 p-6 shadow-xl shadow-brand/5 backdrop-blur-xl">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-textPrimary">Quick Actions</h2>
                    <p class="mt-1 text-sm text-textSecondary">Common admin workflows, ready when you need them.</p>
                </div>
                <a href="{{ route('admin.notifications.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-glassBorder bg-white px-4 py-2 text-sm font-semibold text-textSecondary transition hover:border-brand/40 hover:bg-brandSoft hover:text-brand">
                    View notifications
                </a>
            </div>

            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                @foreach($quickActions as $action)
                <a href="{{ route($action['route']) }}"
                    class="flex items-center justify-between rounded-2xl border border-glassBorder bg-white/70 px-4 py-3 text-sm font-semibold text-textPrimary transition hover:-translate-y-0.5 hover:border-brand/40 hover:bg-white hover:text-brand hover:shadow-lg hover:shadow-brand/10">
                    <span class="flex items-center gap-3">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-bgDark text-textMuted">
                            <i class="fi {{ $action['icon'] }} text-base leading-none"></i>
                        </span>
                        {{ $action['label'] }}
                    </span>
                    <i class="fi fi-rr-angle-small-right leading-none text-gray-400"></i>
                </a>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-[1.75rem] border border-white/80 bg-gradient-to-br from-brandDark via-brand to-brandLight text-white shadow-2xl shadow-brand/20">
            <div class="p-6">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/20 text-white">
                    <i class="fi fi-rr-shield-check text-base leading-none"></i>
                </div>
                <h2 class="mt-4 text-lg font-semibold">Access Control</h2>
                <p class="mt-2 text-sm leading-6 text-white/70">
                    Keep roles and permission sets aligned with current admin responsibilities.
                </p>
                <div class="mt-5 grid gap-3">
                    <a href="{{ route('admin.roles.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white/15 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/25">
                        Roles
                    </a>
                    <a href="{{ route('admin.permissions.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-white px-4 py-2.5 text-sm font-semibold text-brand transition hover:bg-bgDark">
                        Permissions
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
