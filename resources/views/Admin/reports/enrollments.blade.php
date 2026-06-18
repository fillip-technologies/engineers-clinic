@extends('Admin.layouts.layout')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-textPrimary">Enrollment Funnel</h1>
        <p class="text-textSecondary mt-1">Breakdown of enrollments by status, sponsor, and monthly trends.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.revenue') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Revenue</a>
        <a href="{{ route('admin.reports.seat-utilization') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Seat Utilization</a>
    </div>
</div>

<!-- Summary cards -->
<div class="grid gap-4 sm:grid-cols-4 mb-8">
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">Total Enrollments</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">{{ number_format($total) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-emerald-50 p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Active</p>
        <p class="mt-3 text-3xl font-bold text-emerald-700">{{ number_format($byStatus['active'] ?? 0) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-blue-50 p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-blue-600">Completed</p>
        <p class="mt-3 text-3xl font-bold text-blue-700">{{ number_format($byStatus['completed'] ?? 0) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-amber-50 p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-amber-600">Pending</p>
        <p class="mt-3 text-3xl font-bold text-amber-700">{{ number_format($byStatus['pending'] ?? 0) }}</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2 mb-8">
    <!-- By status -->
    <div class="rounded-xl border border-glassBorder bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-textPrimary">By Status</h2>
        </div>
        <div class="p-5 space-y-3">
            @foreach($byStatus as $status => $count)
            @php
                $pct = $total > 0 ? round($count / $total * 100) : 0;
                $colors = ['active' => 'bg-emerald-500', 'completed' => 'bg-blue-500', 'pending' => 'bg-amber-400', 'cancelled' => 'bg-red-400'];
                $color = $colors[$status] ?? 'bg-slate-400';
            @endphp
            <div class="flex items-center gap-3">
                <span class="w-24 text-sm font-medium text-textSecondary capitalize">{{ $status }}</span>
                <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <span class="w-16 text-right text-xs font-semibold text-textMuted">{{ number_format($count) }} ({{ $pct }}%)</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- By sponsor type -->
    <div class="rounded-xl border border-glassBorder bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-textPrimary">By Sponsor Type</h2>
        </div>
        <div class="p-5 space-y-3">
            @foreach($bySponsor as $sponsor => $count)
            @php
                $pct = $total > 0 ? round($count / $total * 100) : 0;
                $sponsorColors = ['self' => 'bg-violet-500', 'college' => 'bg-sky-500'];
                $color = $sponsorColors[$sponsor] ?? 'bg-slate-400';
                $label = $sponsor === 'self' ? 'Self-Pay' : ucfirst($sponsor);
            @endphp
            <div class="flex items-center gap-3">
                <span class="w-24 text-sm font-medium text-textSecondary">{{ $label }}</span>
                <div class="flex-1 h-3 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                </div>
                <span class="w-16 text-right text-xs font-semibold text-textMuted">{{ number_format($count) }} ({{ $pct }}%)</span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Top courses -->
<div class="mb-8 overflow-hidden rounded-xl border border-glassBorder bg-white shadow-sm">
    <div class="border-b border-gray-100 px-5 py-4">
        <h2 class="font-semibold text-textPrimary">Top Courses by Enrollment</h2>
    </div>
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Course</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Total</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Active</th>
                <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Completed</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($byCourse as $row)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-4 font-medium text-textPrimary">{{ $row['course'] }}</td>
                <td class="px-5 py-4 text-sm text-right text-textSecondary">{{ $row['total'] }}</td>
                <td class="px-5 py-4 text-sm text-right text-emerald-600">{{ $row['active'] }}</td>
                <td class="px-5 py-4 text-sm text-right text-blue-600">{{ $row['completed'] }}</td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-5 py-8 text-center text-textMuted">No enrollments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Monthly trend -->
@if(collect($monthly)->isNotEmpty())
<div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
    <h2 class="font-semibold text-textPrimary mb-4">Monthly Enrollment Trend</h2>
    <div class="space-y-2">
        @php $maxMonthly = collect($monthly)->max('count'); @endphp
        @foreach($monthly as $row)
        @php $width = $maxMonthly > 0 ? round($row['count'] * 100 / $maxMonthly) : 0; @endphp
        <div class="flex items-center gap-3">
            <span class="w-16 text-xs text-textMuted font-medium">{{ $row['month'] }}</span>
            <div class="flex-1 h-5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $width }}%"></div>
            </div>
            <span class="w-12 text-right text-xs font-semibold text-textSecondary">{{ $row['count'] }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
