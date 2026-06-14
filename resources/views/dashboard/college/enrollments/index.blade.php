@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Enrollments</h1>
                <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">
                    Track course enrollments, progress status, and current engagement across your student programs.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <a href="{{ route('college.enrollments.bulk-upload') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                    <i class="fi fi-rr-cloud-upload text-sm leading-none"></i>
                    Bulk Upload
                </a>
                <a href="{{ route('college.enrollments.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    <i class="fi fi-rr-plus text-sm leading-none"></i>
                    Add Enrollment
                </a>
            </div>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex w-full max-w-xl items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <i class="fi fi-rr-search text-sm text-slate-400"></i>
                <input type="text" placeholder="Search student or course..."
                    class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" />
            </div>

            <div class="flex flex-col gap-3 sm:flex-row">
                <select class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100">
                    <option>All Courses</option>
                    @foreach ($courses as $course)
                        <option>{{ $course['title'] }}</option>
                    @endforeach
                </select>

                <select class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Completed</option>
                    <option>Dropped</option>
                </select>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="rounded-l-2xl px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Student Name</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Course Name</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Enrollment Date</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Progress</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Status</th>
                        <th class="rounded-r-2xl px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($enrollments as $enrollment)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $enrollment['student_name'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $enrollment['course_name'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $enrollment['enrollment_date'] }}</td>
                            <td class="px-5 py-4">
                                <div class="w-full max-w-[160px]">
                                    <div class="h-2 rounded-full bg-slate-200">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $enrollment['progress'] }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs font-semibold text-slate-500">{{ $enrollment['progress'] }}%</p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $enrollment['status'] === 'Active' ? 'bg-blue-100 text-blue-700' : ($enrollment['status'] === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700') }}">
                                    {{ $enrollment['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('college.enrollments.view') }}"
                                        class="inline-flex rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-primary hover:text-primary">
                                        View
                                    </a>
                                    <a href="{{ route('college.enrollments.edit') }}"
                                        class="inline-flex rounded-lg bg-primarySoft px-3 py-2 text-xs font-semibold text-primaryLight transition hover:bg-blue-100">
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endsection
