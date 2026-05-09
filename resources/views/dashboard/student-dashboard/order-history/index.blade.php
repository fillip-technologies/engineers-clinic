@extends('layouts.frontend-admin')

@php
    $activePaidOrders = array_values(array_filter($orders ?? [], fn ($order) => ($order['payment_status'] ?? '') === 'Paid' && ($order['access_status'] ?? '') === 'Active'));
@endphp

@section('content')
    <div class="mx-auto max-w-6xl">
        <div class="mb-6">
            <p class="text-sm font-semibold text-primary">Student Dashboard</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Billing & Purchases</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Manage paid course access, pending payments, renewals, and purchase records from one place.
            </p>
        </div>

        <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-primary">Active Courses</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">Paid courses ready to continue</h2>
                </div>
                <p class="text-sm text-slate-500">{{ count($activePaidOrders) }} active</p>
            </div>

            <div class="mt-5 grid gap-3 lg:grid-cols-2">
                @forelse ($activePaidOrders as $order)
                    <div class="flex flex-col gap-4 rounded-lg border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <h3 class="truncate text-base font-semibold text-slate-950">{{ $order['title'] }}</h3>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-sm text-slate-500">
                                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">Active</span>
                                <span>{{ $order['price'] }}</span>
                            </div>
                        </div>
                        <a href="{{ route('student.course.workspace.default') }}"
                            class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                            Continue Learning
                        </a>
                    </div>
                @empty
                    <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 px-4 py-6 text-sm text-slate-500 lg:col-span-2">
                        No active paid courses found.
                    </div>
                @endforelse
            </div>
        </section>

        <section class="mt-6 rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div>
                    <p class="text-sm font-semibold text-primary">Purchase History</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-950">All transactions</h2>
                </div>
                <p class="text-sm text-slate-500">
                    Review payment status and take the next action.
                </p>
            </div>

            <div>
                @if (!empty($orders))
                    @include('dashboard.student-dashboard.components.order-list', ['orders' => $orders])
                @else
                    @include('dashboard.student-dashboard.components.order-empty')
                @endif
            </div>
        </section>
    </div>
@endsection
