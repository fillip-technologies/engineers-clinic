@extends('layouts.frontend-admin')

@php
    $activeCourses = array_values(array_filter($enrolledCourses, fn ($course) => $course['status'] === 'Active'));
    $activeCourse = $activeCourses[0] ?? ($enrolledCourses[0] ?? null);
    $otherCourses = array_values(array_filter($enrolledCourses, fn ($course) => ! $activeCourse || $course['id'] !== $activeCourse['id']));
    $workspaceUrl = $activeCourse
        ? route('student.course.workspace', ['id' => $activeCourse['id']])
        : route('student.course.workspace.default');

    $projects = [
        [
            'id' => 'portfolio-platform',
            'title' => 'Developer Portfolio Platform',
            'description' => 'Build a full-stack portfolio with projects, contact form, admin updates, and deployment.',
            'time' => '10-12 hours',
            'points' => 450,
            'recommended' => true,
        ],
        [
            'id' => 'task-manager',
            'title' => 'Team Task Manager',
            'description' => 'Create a collaborative task board with authentication, status flows, and filters.',
            'time' => '8-10 hours',
            'points' => 380,
            'recommended' => false,
        ],
        [
            'id' => 'analytics-dashboard',
            'title' => 'Learning Analytics Dashboard',
            'description' => 'Design and implement charts, KPI cards, and a clean reporting dashboard.',
            'time' => '12-14 hours',
            'points' => 520,
            'recommended' => false,
        ],
    ];

    $tasks = [
        'portfolio-platform' => [
            ['title' => 'Set up project structure', 'meta' => 'Repository, routes, layout', 'status' => 'Done', 'action' => 'Continue Task', 'tone' => 'bg-emerald-50 text-emerald-700 ring-emerald-200'],
            ['title' => 'Build project showcase CRUD', 'meta' => 'Models, forms, validation', 'status' => 'Review', 'action' => 'Submit Task', 'tone' => 'bg-blue-50 text-blue-700 ring-blue-200'],
            ['title' => 'Deploy and document the app', 'meta' => 'Hosting, README, screenshots', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        ],
        'task-manager' => [
            ['title' => 'Create board and task schema', 'meta' => 'Database and relationships', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
            ['title' => 'Implement drag status workflow', 'meta' => 'To do, review, done', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
            ['title' => 'Add team filters', 'meta' => 'Priority, owner, due date', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        ],
        'analytics-dashboard' => [
            ['title' => 'Define dashboard metrics', 'meta' => 'KPIs and data states', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
            ['title' => 'Build chart components', 'meta' => 'Progress and comparison views', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
            ['title' => 'Create final report view', 'meta' => 'Export-ready summary', 'status' => 'Pending', 'action' => 'Start Task', 'tone' => 'bg-amber-50 text-amber-700 ring-amber-200'],
        ],
    ];
@endphp

@section('content')
    <div class="mx-auto max-w-6xl"
        x-data="{
            selectedProject: 'portfolio-platform',
            projects: @js($projects),
            tasks: @js($tasks),
            get currentProject() {
                return this.projects.find((project) => project.id === this.selectedProject) || this.projects[0];
            }
        }">
        <div class="mb-6">
            <p class="text-sm font-semibold text-primary">Student Dashboard</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">My Enrolled Course</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                Continue your course, choose one project, and complete the related tasks from the same workspace.
            </p>
        </div>

        @if ($activeCourse)
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <div class="grid gap-5 lg:grid-cols-[5.5rem_minmax(0,1fr)_auto] lg:items-center">
                    <div class="overflow-hidden rounded-lg bg-slate-100">
                        <img src="{{ $activeCourse['image'] }}" alt="{{ $activeCourse['title'] }}" class="h-24 w-full object-cover" />
                    </div>

                    <div class="min-w-0">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-primary">Active Course</p>
                                <h2 class="mt-1 truncate text-2xl font-semibold text-slate-950">{{ $activeCourse['title'] }}</h2>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700 ring-1 ring-blue-200">
                                {{ $activeCourse['status'] }}
                            </span>
                        </div>

                        <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-500">{{ $activeCourse['description'] }}</p>
                        <div class="mt-4">
                            <div class="flex justify-between text-sm">
                                <span class="font-medium text-slate-600">{{ $activeCourse['completed_lessons'] }}/{{ $activeCourse['total_lessons'] }} lessons completed</span>
                                <span class="font-semibold text-slate-900">{{ $activeCourse['progress'] }}%</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-slate-100">
                                <div class="h-2 rounded-full bg-primary" style="width: {{ $activeCourse['progress'] }}%"></div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('student.course.workspace', ['id' => $activeCourse['id']]) }}"
                        class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                        Continue Learning
                    </a>
                </div>
            </section>
        @endif

        <section class="mt-6 grid gap-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <div class="space-y-6">
                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-primary">Project Selection</p>
                            <h2 class="mt-2 text-xl font-semibold text-slate-950">Choose 1 project to complete</h2>
                        </div>
                        <p class="text-sm text-slate-500">You can change before final submission.</p>
                    </div>

                    <div class="mt-5 grid gap-4 xl:grid-cols-3">
                        @foreach ($projects as $project)
                            <a href="{{ $workspaceUrl }}?project={{ $project['id'] }}"
                                @mouseenter="selectedProject = '{{ $project['id'] }}'"
                                @focus="selectedProject = '{{ $project['id'] }}'"
                                class="flex cursor-pointer flex-col rounded-xl border bg-white p-4 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-primary hover:shadow-xl hover:shadow-blue-100"
                                :class="selectedProject === '{{ $project['id'] }}' ? 'border-primary ring-2 ring-blue-100' : 'border-slate-200'">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="text-base font-semibold leading-6 text-slate-950">{{ $project['title'] }}</h3>
                                    @if ($project['recommended'])
                                        <span class="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-semibold text-primary ring-1 ring-blue-200">Recommended</span>
                                    @endif
                                </div>
                                <p class="mt-3 flex-1 text-sm leading-6 text-slate-500">{{ $project['description'] }}</p>
                                <div class="mt-4 flex items-center justify-between text-sm">
                                    <span class="text-slate-500">{{ $project['time'] }}</span>
                                    <span class="font-semibold text-slate-900">{{ $project['points'] }} pts</span>
                                </div>
                                <span
                                    class="mt-4 inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold transition"
                                    :class="selectedProject === '{{ $project['id'] }}'
                                        ? 'bg-primary text-white'
                                        : 'bg-slate-100 text-slate-700 hover:bg-slate-200'">
                                    <span x-text="selectedProject === '{{ $project['id'] }}' ? 'Continue Project' : 'Start Project'"></span>
                                </span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-primary">Selected Project</p>
                            <h2 class="mt-2 text-xl font-semibold text-slate-950" x-text="currentProject.title"></h2>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500" x-text="currentProject.description"></p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ $workspaceUrl }}?project=portfolio-platform"
                                :href="`{{ $workspaceUrl }}?project=${selectedProject}`"
                                class="inline-flex items-center justify-center rounded-lg bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                                Continue Project
                            </a>
                            <button type="button" @click="$el.closest('section').previousElementSibling.scrollIntoView({ behavior: 'smooth' })"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                                Change Project
                            </button>
                        </div>
                    </div>
                </section>

                <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold text-primary">Project Tasks</p>
                            <h2 class="mt-2 text-xl font-semibold text-slate-950">Task workflow</h2>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">3 tasks</span>
                    </div>

                    <div class="mt-5 divide-y divide-slate-100 rounded-lg border border-slate-200">
                        <template x-for="task in tasks[selectedProject]" :key="task.title">
                            <div class="flex flex-col gap-4 px-4 py-4 transition hover:bg-slate-50 sm:flex-row sm:items-center">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-slate-950" x-text="task.title"></p>
                                    <p class="mt-1 text-sm text-slate-500" x-text="task.meta"></p>
                                </div>
                                <span class="w-fit rounded-full px-2.5 py-1 text-xs font-semibold ring-1" :class="task.tone" x-text="task.status"></span>
                                <a href="{{ $workspaceUrl }}?project=portfolio-platform#tasks"
                                    :href="`{{ $workspaceUrl }}?project=${selectedProject}#tasks`"
                                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-primary hover:text-primary"
                                    x-text="task.action"></a>
                            </div>
                        </template>
                    </div>
                </section>
            </div>

            <aside class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div>
                    <p class="text-sm font-semibold text-primary">Other Courses</p>
                    <h2 class="mt-2 text-xl font-semibold text-slate-950">Remaining enrollments</h2>
                </div>

                <div class="mt-5 space-y-3">
                    @forelse ($otherCourses as $course)
                        <a href="{{ route('student.course.workspace', ['id' => $course['id']]) }}"
                            class="block rounded-lg border border-slate-200 p-4 transition hover:border-primary hover:bg-slate-50">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-sm font-semibold leading-6 text-slate-950">{{ $course['title'] }}</h3>
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $course['status'] === 'Completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $course['status'] }}
                                </span>
                            </div>
                            <div class="mt-3 flex items-center gap-3">
                                <div class="h-1.5 flex-1 rounded-full bg-slate-100">
                                    <div class="h-1.5 rounded-full bg-primary" style="width: {{ $course['progress'] }}%"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">{{ $course['progress'] }}%</span>
                            </div>
                        </a>
                    @empty
                        <p class="rounded-lg bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">
                            No other courses yet.
                        </p>
                    @endforelse
                </div>
            </aside>
        </section>
    </div>
@endsection
