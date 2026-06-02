@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Edit Enrollment</h1>
            <p class="mt-3 text-base leading-8 text-slate-600">
                Update the selected demo enrollment while preserving the same clean dashboard form structure.
            </p>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <form class="grid gap-6">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700" for="student_name">Select Student</label>
                    <select id="student_name"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        @foreach ($students as $student)
                            <option {{ $enrollment['student_name'] === $student['name'] ? 'selected' : '' }}>
                                {{ $student['name'] }}{{ $student['email'] ? ' - ' . $student['email'] : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700" for="course_name">Select Course</label>
                    <select id="course_name"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        @foreach ($courses as $course)
                            <option {{ $enrollment['course_name'] === $course['title'] ? 'selected' : '' }}>{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                    <select id="status"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option {{ $enrollment['status'] === 'Active' ? 'selected' : '' }}>Active</option>
                        <option {{ $enrollment['status'] === 'Completed' ? 'selected' : '' }}>Completed</option>
                        <option {{ $enrollment['status'] === 'Dropped' ? 'selected' : '' }}>Dropped</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700" for="start_date">Start Date</label>
                    <input id="start_date" type="text" value="{{ $enrollment['enrollment_date'] }}"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="button"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primaryLight">
                    Update
                </button>
                <a href="{{ route('college.enrollments') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Cancel
                </a>
            </div>
        </form>
    </section>
@endsection
