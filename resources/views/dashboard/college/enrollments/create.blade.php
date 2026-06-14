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

    <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8"
        x-data="{ existingStudent: '{{ old('student_id') ? 'yes' : 'no' }}' }">
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
                        x-model="existingStudent"
                        @change="existingStudent = $event.target.value ? 'yes' : 'no'"
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option value="">— Create a new student instead —</option>
                        @foreach ($students as $student)
                            <option value="{{ $student['id'] }}" @selected((string) old('student_id') === (string) $student['id'])>
                                {{ $student['name'] }}{{ $student['email'] ? ' · ' . $student['email'] : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-medium text-slate-700" for="course_id">Select Course / Project</label>
                    <select id="course_id" name="course_id" required
                        class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:bg-white focus:ring-4 focus:ring-blue-100">
                        <option value="">Select course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course['id'] }}" @selected((string) old('course_id') === (string) $course['id'])>{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- New student section — hidden when an existing student is selected --}}
            <div x-show="existingStudent === 'no'" x-cloak
                class="rounded-2xl border border-dashed border-slate-200 bg-slate-50 p-5">
                <p class="text-sm font-semibold text-slate-800">New student details</p>
                <p class="mt-1 text-xs text-slate-500">
                    Fill in these fields to create a new login account for the student. Login credentials will be sent to their email automatically.
                </p>

                <div class="mt-4 grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_name">Full Name <span class="text-red-500">*</span></label>
                        <input id="new_student_name" name="new_student_name" type="text"
                            value="{{ old('new_student_name') }}" placeholder="Enter student full name"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_email">Email Address <span class="text-red-500">*</span></label>
                        <input id="new_student_email" name="new_student_email" type="email"
                            value="{{ old('new_student_email') }}" placeholder="Enter student email"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_password">Password <span class="text-red-500">*</span></label>
                        <input id="new_student_password" name="new_student_password" type="password"
                            placeholder="Set a login password"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                        <p class="mt-1.5 text-xs text-slate-400">Minimum 8 characters. This will be emailed to the student.</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-700" for="new_student_password_confirmation">Confirm Password <span class="text-red-500">*</span></label>
                        <input id="new_student_password_confirmation" name="new_student_password_confirmation" type="password"
                            placeholder="Re-enter the password"
                            class="mt-2 w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                    </div>
                </div>

                <div class="mt-4 flex items-start gap-2.5 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3">
                    <i class="fi fi-rr-info mt-0.5 shrink-0 text-sm text-blue-600"></i>
                    <p class="text-xs text-blue-700">
                        A welcome email with the student's login email and password will be sent automatically after the account is created.
                    </p>
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
