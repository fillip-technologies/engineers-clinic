@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Enrollment Details</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    View the selected enrollment record, progress level, and latest engagement details.
                </p>
            </div>

            <a href="{{ route('college.enrollments') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                Back
            </a>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="grid gap-5 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Student Name</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $enrollment['student_name'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Course Name</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $enrollment['course_name'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Status</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $enrollment['status'] === 'Active' ? 'bg-blue-100 text-blue-700' : ($enrollment['status'] === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700') }}">
                    {{ $enrollment['status'] }}
                </span>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Enrollment Date</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $enrollment['enrollment_date'] }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 md:col-span-2">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Progress</p>
                    <p class="text-sm font-semibold text-slate-700">{{ $enrollment['progress'] }}%</p>
                </div>
                <div class="mt-4 h-3 rounded-full bg-slate-200">
                    <div class="h-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $enrollment['progress'] }}%"></div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Last Activity</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $enrollment['last_activity'] }}</p>
            </div>
        </div>
    </section>
@endsection
