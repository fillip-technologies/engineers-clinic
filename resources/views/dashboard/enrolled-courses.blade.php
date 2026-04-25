@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-glassBorder bg-white p-6 shadow-sm sm:p-8">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Learning Library</p>
            <h1 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">My Enrolled Courses</h1>
            <p class="mt-4 text-base leading-8 text-textSecondary">
                Pick up where you left off and continue building momentum across your enrolled learning tracks.
            </p>
        </div>
    </section>

    <section class="mt-8 rounded-[1.75rem] border border-glassBorder bg-[#FCFDFE] p-3 shadow-sm sm:p-4">
        @if (!empty($enrolledCourses))
            <div class="divide-y divide-slate-200">
                @foreach ($enrolledCourses as $course)
                    <x-student.enrolled-course-row :course="$course" />
                @endforeach
            </div>
        @else
            <div class="flex min-h-[320px] flex-col items-center justify-center rounded-[1.5rem] bg-white px-6 text-center">
                <h2 class="text-2xl font-semibold text-textPrimary">You are not enrolled in any courses yet</h2>
                <p class="mt-3 max-w-md text-base leading-7 text-textSecondary">
                    Explore the available programs and start your learning journey with a track that fits your goals.
                </p>
                <a href="#"
                    class="mt-6 inline-flex items-center justify-center rounded-full bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Browse Courses
                </a>
            </div>
        @endif
    </section>
@endsection
