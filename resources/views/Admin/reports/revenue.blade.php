@extends('Admin.layouts.layout')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-textPrimary">Revenue Report</h1>
        <p class="text-textSecondary mt-1">Self-pay vs. college sponsorship revenue split.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.seat-utilization') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Seat Utilization</a>
        <a href="{{ route('admin.reports.enrollments') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Enrollments</a>
    </div>
</div>

<!-- Summary cards -->
<div class="grid gap-4 sm:grid-cols-3 mb-8">
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">Self-Pay Revenue</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">Rs. {{ number_format((float)$selfPayRevenue, 2) }}</p>
        <p class="mt-1 text-xs text-textMuted">From student Razorpay payments</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">College Revenue</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">Rs. {{ number_format((float)$collegeRevenue, 2) }}</p>
        <p class="mt-1 text-xs text-textMuted">Dashboard access + seat purchases</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-emerald-50 p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Total Revenue</p>
        <p class="mt-3 text-3xl font-bold text-emerald-700">Rs. {{ number_format((float)$selfPayRevenue + (float)$collegeRevenue, 2) }}</p>
        <p class="mt-1 text-xs text-emerald-500">All approved payments</p>
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <!-- Self-pay breakdown -->
    <div class="rounded-xl border border-glassBorder bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-textPrimary">Top Courses by Self-Pay Revenue</h2>
        </div>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Course</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Revenue</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Students</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($selfPayBreakdown as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-sm font-medium text-textPrimary">{{ $row['course'] }}</td>
                    <td class="px-5 py-3 text-sm text-right text-textSecondary">{{ $row['total'] }}</td>
                    <td class="px-5 py-3 text-sm text-right text-textSecondary">{{ $row['count'] }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-6 text-center text-textMuted text-sm">No self-pay revenue yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- College breakdown by purpose -->
    <div class="rounded-xl border border-glassBorder bg-white shadow-sm overflow-hidden">
        <div class="border-b border-gray-100 px-5 py-4">
            <h2 class="font-semibold text-textPrimary">College Revenue by Purpose</h2>
        </div>
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Purpose</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Revenue</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Transactions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($collegePurposeBreakdown as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 text-sm font-medium text-textPrimary">{{ $row['purpose'] }}</td>
                    <td class="px-5 py-3 text-sm text-right text-textSecondary">{{ $row['total'] }}</td>
                    <td class="px-5 py-3 text-sm text-right text-textSecondary">{{ $row['count'] }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-5 py-6 text-center text-textMuted text-sm">No college payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Monthly trend -->
@if($monthly->isNotEmpty())
<div class="mt-6 rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
    <h2 class="font-semibold text-textPrimary mb-4">Monthly Self-Pay Revenue Trend</h2>
    <div class="space-y-2">
        @foreach($monthly as $row)
        @php $max = $monthly->max('total'); $width = $max > 0 ? round($row->total * 100 / $max) : 0; @endphp
        <div class="flex items-center gap-3">
            <span class="w-16 text-xs text-textMuted font-medium">{{ $row->month }}</span>
            <div class="flex-1 h-5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-primary rounded-full" style="width: {{ $width }}%"></div>
            </div>
            <span class="w-28 text-right text-xs font-semibold text-textSecondary">Rs. {{ number_format((float)$row->total, 0) }}</span>
        </div>
        @endforeach
    </div>
</div>
@endif
@endsection
