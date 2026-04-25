@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Student Details</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    Review the selected student profile and course details from the college dashboard.
                </p>
            </div>

            <a href="{{ route('college.students') }}"
                class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                Back
            </a>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="grid gap-5 md:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Name</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $student['name'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Email</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $student['email'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Course</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $student['course'] }}</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Status</p>
                <span class="mt-3 inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $student['status'] === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                    {{ $student['status'] }}
                </span>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 md:col-span-2">
                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Joined Date</p>
                <p class="mt-3 text-lg font-semibold text-slate-900">{{ $student['joined_date'] }}</p>
            </div>
        </div>
    </section>
@endsection
