@extends('layouts.frontend-admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-primary">Internship Seats</p>
            <h1 class="mt-1 text-2xl font-semibold text-slate-950">My Purchases</h1>
        </div>
        <a href="{{ route('college.internships') }}"
            class="inline-flex items-center gap-2 rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
            <i class="fi fi-rr-add text-base leading-none"></i>
            Buy More Seats
        </a>
    </div>

    @if(session('success'))
        <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if(collect($purchases)->isEmpty())
        <div class="mt-10 rounded-xl border border-dashed border-slate-300 py-16 text-center">
            <i class="fi fi-rr-shopping-cart text-4xl text-slate-300"></i>
            <p class="mt-4 text-sm font-medium text-slate-500">No purchases yet.</p>
            <a href="{{ route('college.internships') }}" class="mt-3 inline-flex items-center text-sm font-semibold text-primary hover:underline">
                Browse internship catalog
            </a>
        </div>
    @else
        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <table class="min-w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Internship</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Seats</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Amount</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Submitted</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($purchases as $purchase)
                    @php
                        $statusClasses = [
                            'approved' => 'bg-emerald-100 text-emerald-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'verification_pending' => 'bg-blue-100 text-blue-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="px-5 py-4 font-medium text-slate-900">{{ $purchase['course_title'] }}</td>
                        <td class="px-5 py-4 text-sm text-slate-700">
                            {{ $purchase['seats_used'] }}/{{ $purchase['seats_purchased'] }} used
                            <span class="ml-1.5 text-xs text-slate-400">({{ $purchase['seats_remaining'] }} left)</span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-700">{{ $purchase['total_amount'] }}</td>
                        <td class="px-5 py-4">
                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses[$purchase['payment_status']] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $purchase['payment_status'])) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-sm text-slate-500">{{ $purchase['submitted_at'] }}</td>
                        <td class="px-5 py-4">
                            @if($purchase['is_active'])
                                <a href="{{ $purchase['allocate_url'] }}"
                                    class="rounded-lg bg-primary px-3 py-2 text-xs font-semibold text-white transition hover:bg-primaryLight">
                                    Manage Seats
                                </a>
                            @else
                                <span class="text-xs text-slate-400">Pending approval</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
