@extends('Admin.layouts.layout')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-semibold text-textPrimary">Seat Utilization</h1>
        <p class="text-textSecondary mt-1">Track how colleges are using their purchased internship seats.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.reports.revenue') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Revenue</a>
        <a href="{{ route('admin.reports.enrollments') }}" class="rounded-lg border border-glassBorder px-4 py-2 text-sm font-medium text-textSecondary hover:bg-gray-50 transition">Enrollments</a>
    </div>
</div>

<!-- Summary cards -->
<div class="grid gap-4 sm:grid-cols-4 mb-8">
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">Total Seats Sold</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">{{ number_format($totalPurchased) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">Seats Allocated</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">{{ number_format($totalUsed) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-textMuted">Seats Available</p>
        <p class="mt-3 text-3xl font-bold text-textPrimary">{{ number_format($totalPurchased - $totalUsed) }}</p>
    </div>
    <div class="rounded-xl border border-glassBorder bg-emerald-50 p-5 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-600">Overall Utilization</p>
        <p class="mt-3 text-3xl font-bold text-emerald-700">
            {{ $totalPurchased > 0 ? round($totalUsed / $totalPurchased * 100) : 0 }}%
        </p>
    </div>
</div>

<!-- Per-purchase breakdown -->
<div class="overflow-hidden rounded-xl border border-glassBorder bg-white shadow-sm">
    <div class="border-b border-gray-100 px-5 py-4 flex items-center justify-between">
        <h2 class="font-semibold text-textPrimary">By Purchase</h2>
        <span class="text-sm text-textMuted">{{ $purchases->count() }} purchases</span>
    </div>

    @if($purchases->isEmpty())
        <div class="py-12 text-center text-sm text-textMuted">No approved seat purchases yet.</div>
    @else
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">College</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Internship</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Purchased</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Used</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-textMuted">Available</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Utilization</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($purchases as $row)
                @php
                    $pct = $row['seats_purchased'] > 0
                        ? round($row['seats_used'] / $row['seats_purchased'] * 100)
                        : 0;
                    $barColor = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-amber-400' : 'bg-slate-300');
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4 font-medium text-textPrimary">{{ $row['college'] }}</td>
                    <td class="px-5 py-4 text-sm text-textSecondary">{{ $row['course'] }}</td>
                    <td class="px-5 py-4 text-sm text-right text-textSecondary">{{ $row['seats_purchased'] }}</td>
                    <td class="px-5 py-4 text-sm text-right text-textSecondary">{{ $row['seats_used'] }}</td>
                    <td class="px-5 py-4 text-sm text-right text-textSecondary">{{ $row['seats_purchased'] - $row['seats_used'] }}</td>
                    <td class="px-5 py-4 min-w-[160px]">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="text-xs font-semibold text-textSecondary w-10 text-right">{{ $pct }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
