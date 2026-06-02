@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Student Management</h1>
                <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">
                    Review students, monitor status, and keep student records organized for your partnered programs.
                </p>
            </div>

            <a href="{{ route('college.students.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Add Student
            </a>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-5 shadow-sm sm:p-6">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex w-full max-w-lg items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
                <i class="fi fi-rr-search text-sm text-slate-400"></i>
                <input type="text" placeholder="Search students..."
                    class="w-full bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" />
            </div>

            <div class="flex items-center gap-3">
                <select class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100">
                    <option>All Courses</option>
                    <option>Full Stack Development</option>
                    <option>Frontend Development</option>
                    <option>UI/UX Design</option>
                    <option>Data Analytics</option>
                </select>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="rounded-l-2xl px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Name</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Email</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Course</th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Status</th>
                        <th class="rounded-r-2xl px-5 py-4 text-xs font-semibold uppercase tracking-[0.15em] text-slate-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach ($students as $student)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="px-5 py-4 font-semibold text-slate-900">{{ $student['name'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $student['email'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $student['course'] }}</td>
                            <td class="px-5 py-4">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $student['status'] === 'Active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-700' }}">
                                    {{ $student['status'] }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('college.students.view') }}"
                                        class="inline-flex rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-primary hover:text-primary">
                                        View
                                    </a>
                                    <a href="{{ route('college.students.edit') }}"
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
