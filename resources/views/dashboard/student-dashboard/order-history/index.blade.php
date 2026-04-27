@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-6xl">
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Order History</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    View all your purchased courses and internships
                </p>
            </div>
        </section>

        <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-3 shadow-sm sm:p-4">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-3 pb-4 pt-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Purchase Activity</p>
                    <!-- <h2 class="mt-2 text-2xl font-semibold text-slate-900">All orders in one clean view</h2> -->
                </div>
                <p class="text-sm leading-6 text-slate-500">Track payment outcomes, course access, and invoice actions from here.</p>
            </div>

            <div class="mt-4">
                @if (!empty($orders))
                    @include('dashboard.student-dashboard.components.order-list', ['orders' => $orders])
                @else
                    @include('dashboard.student-dashboard.components.order-empty')
                @endif
            </div>
        </section>
    </div>
@endsection
