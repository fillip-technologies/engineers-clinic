@extends('layouts.dashboard')

@section('content')
    <section class="rounded-[1.75rem] border border-glassBorder bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Enrollment Summary</p>
                <h1 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Enrollment ID // {{ $enrollmentIdFormatted }}
                </h1>
                <p class="mt-4 max-w-2xl text-base leading-8 text-textSecondary">
                    Welcome back to your learning space. Keep an eye on your courses, ongoing progress, and next milestones.
                </p>
            </div>

            <div class="rounded-[1.25rem] border border-glassBorder bg-slate-50 px-5 py-4">
                <p class="text-sm font-semibold text-textMuted">Current Track</p>
                <p class="mt-2 text-xl font-semibold text-textPrimary">{{ $currentTrack }}</p>
            </div>
        </div>
    </section>

    <section class="mt-8 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
        <x-common.stat-card label="Enrolled Internship Courses" value="{{ $totalEnrolled }}" accent="primary" />
        <x-common.stat-card label="Active Internship Courses" value="{{ $activeCourses }}" accent="secondary" />
        <x-common.stat-card label="Completed Internship Courses" value="{{ $completedCourses }}" accent="glass" />
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-glassBorder bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">In Progress Courses</p>
                <h2 class="mt-3 text-2xl font-semibold text-textPrimary sm:text-3xl">Keep moving through your active roadmap</h2>
            </div>
            <a href="#" class="text-sm font-semibold text-primaryLight transition hover:text-primary">View all courses</a>
        </div>

        <div class="mt-8 grid gap-5 xl:grid-cols-3">
            @forelse ($enrollments->where('status', 'ongoing') as $enrollment)
                <x-student.course-card 
                    title="{{ $enrollment->course->title ?? 'N/A' }}" 
                    duration="{{ $enrollment->course->duration ?? 'N/A' }}" 
                    progress="{{ $enrollment->progress ?? rand(30, 90) }}" 
                    lessonCount="{{ $enrollment->course->lessons_count ?? 0 }}" 
                />
            @empty
                <div class="col-span-3 text-center py-8 text-textMuted">
                    No active courses. Enroll in a course to get started.
                </div>
            @endforelse
@endsection
