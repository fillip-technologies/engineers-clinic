@extends('layouts.frontend-admin')

@section('content')
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">College Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Courses</h1>
                <p class="mt-3 max-w-2xl text-base leading-8 text-slate-600">
                    View available programs and track how your students are progressing across each course.
                </p>
            </div>

            <a href="{{ route('college.enrollments.create') }}"
                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Enroll Student
            </a>
        </div>
    </section>

    <section class="mt-8 grid gap-5 xl:grid-cols-2">
        @forelse ($courses as $course)
            <article class="rounded-[1.5rem] border border-slate-200/70 bg-white p-6 shadow-sm">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <div class="flex flex-wrap gap-2">
                            <span class="rounded-full bg-primarySoft px-3 py-1 text-xs font-semibold text-primaryLight">
                                {{ $course['category'] }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                {{ $course['level'] }}
                            </span>
                        </div>
                        <h2 class="mt-4 text-xl font-semibold text-slate-950">{{ $course['title'] }}</h2>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-600">
                            {{ $course['description'] ?: 'Course details will appear here when added by admin.' }}
                        </p>
                    </div>

                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-right">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Fee</p>
                        <p class="mt-1 text-sm font-bold text-slate-950">{{ $course['fee'] }}</p>
                    </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Duration</p>
                        <p class="mt-2 text-lg font-semibold text-slate-950">{{ $course['duration'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Students</p>
                        <p class="mt-2 text-lg font-semibold text-slate-950">{{ $course['enrollments'] }}</p>
                    </div>
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Completion</p>
                        <p class="mt-2 text-lg font-semibold text-slate-950">{{ $course['completion'] }}</p>
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-[1.5rem] border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm xl:col-span-2">
                <p class="text-lg font-semibold text-slate-950">No courses available yet.</p>
                <p class="mt-2 text-sm text-slate-600">Courses added by admin will appear here.</p>
            </div>
        @endforelse
    </section>
@endsection
