@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Add Enrollment</h1>
            <p class="mt-3 text-base leading-8 text-slate-600">
                Enroll an existing college student, or create a new student account during enrollment.
            </p>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        @if ($errors->any())
            <div class="mb-6 rounded-2xl border border-red-100 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('college.enrollments.store') }}" class="grid gap-6">
            @csrf
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700" for="student_id">Select Existing Student</label>
                    <select id="student_id" name="student_id"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option value="">Create a new student instead</option>
                        @foreach ($students as $student)
                            <option value="{{ $student['id'] }}" @selected((string) old('student_id') === (string) $student['id'])>
                                {{ $student['name'] }}{{ $student['email'] ? ' - ' . $student['email'] : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700" for="course_id">Select Course</label>
                    <select id="course_id" name="course_id" required
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option value="">Select course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course['id'] }}" @selected((string) old('course_id') === (string) $course['id'])>{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5">
                <p class="text-sm font-semibold text-slate-800">New student details</p>
                <p class="mt-1 text-xs text-slate-500">Use these fields only when the student is not already in your college list.</p>

                <div class="mt-4 grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_name">Student Name</label>
                        <input id="new_student_name" name="new_student_name" type="text" value="{{ old('new_student_name') }}" placeholder="Enter student name"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_email">Student Email</label>
                        <input id="new_student_email" name="new_student_email" type="email" value="{{ old('new_student_email') }}" placeholder="Enter student email"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                    </div>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-slate-700" for="status">Status</label>
                    <select id="status" name="status" required
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option value="ongoing" @selected(old('status', 'ongoing') === 'ongoing')>Active</option>
                        <option value="completed" @selected(old('status') === 'completed')>Completed</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700" for="start_date">Start Date</label>
                    <input id="start_date" name="enrollment_date" type="date" value="{{ old('enrollment_date', now()->toDateString()) }}" required
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primaryLight">
                    Save
                </button>
                <a href="{{ route('college.enrollments') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    Cancel
                </a>
            </div>
        </form>
    </section>
@endsection
