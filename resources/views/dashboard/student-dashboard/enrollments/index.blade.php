@extends('layouts.frontend-admin')

@php
    $allCourses = $enrolledCourses;
    $activeCourses = array_values(array_filter($enrolledCourses, fn ($course) => $course['status'] === 'Active'));
    $completedCourses = array_values(array_filter($enrolledCourses, fn ($course) => $course['status'] === 'Completed'));
    $tabs = [
        ['label' => 'All Courses', 'key' => 'all', 'courses' => $allCourses],
        ['label' => 'Active Courses', 'key' => 'active', 'courses' => $activeCourses],
        ['label' => 'Completed Courses', 'key' => 'completed', 'courses' => $completedCourses],
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-6xl" x-data="{ activeTab: 'all' }">
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">My Enrollments</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    Keep track of every course you have joined, monitor progress, and continue learning from one focused view.
                </p>
            </div>
        </section>

        <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-3 shadow-sm sm:p-4">
            <div class="flex flex-wrap gap-3">
                @foreach ($tabs as $tab)
                    <button type="button"
                        class="inline-flex items-center justify-center rounded-full px-5 py-2.5 text-sm font-semibold transition"
                        :class="activeTab === '{{ $tab['key'] }}'
                            ? 'bg-primary text-white shadow-sm'
                            : 'bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900'"
                        @click="activeTab = '{{ $tab['key'] }}'">
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>

            @foreach ($tabs as $tab)
                <div x-show="activeTab === '{{ $tab['key'] }}'" x-cloak class="mt-6">
                    @if (!empty($tab['courses']))
                        <div class="space-y-3">
                            @foreach ($tab['courses'] as $course)
                                @include('dashboard.student-dashboard.components.enrollment-item', ['course' => $course])
                            @endforeach
                        </div>
                    @else
                        <div class="flex min-h-[280px] flex-col items-center justify-center rounded-[1.5rem] bg-slate-50 px-6 text-center">
                            <h2 class="text-2xl font-semibold text-slate-900">You have not enrolled in any courses yet</h2>
                            <p class="mt-3 max-w-md text-base leading-7 text-slate-500">
                                Explore available programs and start building momentum with a course aligned to your goals.
                            </p>
                            <a href="/"
                                class="mt-6 inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primaryLight">
                                Browse Courses
                            </a>
                        </div>
                    @endif
                </div>
            @endforeach
        </section>
    </div>
@endsection
