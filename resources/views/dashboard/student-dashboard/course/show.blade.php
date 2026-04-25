@extends('layouts.frontend-admin')

@php
    $moduleLookup = $course['module_content'];
    $defaultModuleId = $course['current_module'];
    $defaultModule = $moduleLookup[$defaultModuleId] ?? reset($moduleLookup);
    $defaultTask = $course['tasks'][0] ?? null;
@endphp

@section('content')
    <div
        class="mx-auto max-w-7xl"
        x-data='{
            openPhases: @json(collect($course["phases"])->mapWithKeys(fn ($phase, $index) => ["phase-" . $index => true])->all()),
            activeModule: @json($defaultModuleId),
            selectedTask: null,
            moduleContent: @json($moduleLookup),
            tasks: @json($course["tasks"]),
            get currentModule() {
                return this.moduleContent[this.activeModule] ?? this.moduleContent[@json($defaultModuleId)];
            },
            get currentTask() {
                return this.tasks.find(task => task.id === this.selectedTask) ?? null;
            }
        }'>
        <div class="overflow-hidden rounded-[1.75rem] border border-slate-200/70 bg-white shadow-sm">
            <div class="grid min-h-[calc(100vh-12rem)] lg:grid-cols-[20rem_minmax(0,1fr)]">
                <aside class="border-b border-slate-200 bg-slate-50/70 lg:border-b-0 lg:border-r">
                    <div class="h-full overflow-y-auto px-5 py-6">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Learning Path</p>
                        <p class="mt-3 text-lg font-semibold text-slate-900">{{ $course['title'] }}</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ $course['description'] }}</p>

                        <div class="mt-6 space-y-5">
                            @foreach ($course['phases'] as $phaseIndex => $phase)
                                @php $phaseKey = 'phase-' . $phaseIndex; @endphp
                                <section class="border-t border-slate-200 pt-4 first:border-t-0 first:pt-0">
                                    <button type="button"
                                        class="flex w-full items-center justify-between gap-4 text-left"
                                        @click="openPhases['{{ $phaseKey }}'] = !openPhases['{{ $phaseKey }}']">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900">{{ $phase['title'] }}</p>
                                        </div>
                                        <i class="fi fi-rr-angle-small-down text-sm text-slate-400 transition"
                                            :class="openPhases['{{ $phaseKey }}'] ? 'rotate-180' : ''"></i>
                                    </button>

                                    <div x-show="openPhases['{{ $phaseKey }}']" x-cloak class="mt-4 space-y-2">
                                        @foreach ($phase['modules'] as $module)
                                            @php
                                                $isLocked = $module['state'] === 'locked';
                                                $isCompleted = $module['state'] === 'completed';
                                            @endphp
                                            <button type="button"
                                                class="flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm transition"
                                                @if (! $isLocked) @click="activeModule = '{{ $module['id'] }}'; selectedTask = null" @endif
                                                :class="activeModule === '{{ $module['id'] }}'
                                                    ? 'bg-blue-50 text-slate-900'
                                                    : '{{ $isLocked ? 'cursor-not-allowed text-slate-400' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}'">
                                                <span class="inline-flex h-7 w-7 items-center justify-center rounded-full {{ $isCompleted ? 'bg-emerald-100 text-emerald-700' : ($isLocked ? 'bg-slate-200 text-slate-400' : 'bg-blue-100 text-blue-700') }}">
                                                    @if ($isCompleted)
                                                        <i class="fi fi-rr-check text-xs"></i>
                                                    @elseif ($isLocked)
                                                        <i class="fi fi-rr-lock text-xs"></i>
                                                    @else
                                                        <i class="fi fi-rr-play text-xs"></i>
                                                    @endif
                                                </span>
                                                <span class="font-medium">{{ $module['title'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </section>
                            @endforeach
                        </div>
                    </div>
                </aside>

                <section class="min-w-0 bg-white">
                    <header class="sticky top-0 z-10 border-b border-slate-200 bg-white/95 backdrop-blur">
                        <div class="flex flex-col gap-4 px-6 py-5 sm:px-8 xl:flex-row xl:items-center xl:justify-between">
                            <div class="min-w-0">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('dashboard.enrolled-courses') }}"
                                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-slate-900">
                                        <i class="fi fi-rr-arrow-left text-sm"></i>
                                        <span>Back</span>
                                    </a>
                                </div>
                                <h1 class="mt-3 truncate text-2xl font-semibold text-slate-900 sm:text-3xl">{{ $course['title'] }}</h1>
                                <p class="mt-2 text-sm text-slate-500">{{ $course['completed_lessons'] }} of {{ $course['total_lessons'] }} completed - {{ $course['progress'] }}%</p>
                            </div>

                            <div class="flex min-w-0 flex-col gap-3 sm:flex-row sm:items-center">
                                <div class="w-full min-w-[12rem] sm:w-56">
                                    <div class="h-2 rounded-full bg-slate-200">
                                        <div class="h-2 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600" style="width: {{ $course['progress'] }}%"></div>
                                    </div>
                                </div>
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    Mark as Complete
                                </button>
                            </div>
                        </div>
                    </header>

                    <div class="px-6 py-8 sm:px-8">
                        <div class="max-w-4xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Module Workspace</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900" x-text="currentModule.title"></h2>
                            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600" x-text="currentModule.description"></p>
                        </div>

                        <div class="mt-10" x-show="!selectedTask">
                            <div class="flex items-end justify-between gap-4 border-b border-slate-200 pb-4">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Task Selection</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-slate-900">Choose one task to start working on</h3>
                                </div>
                                <p class="text-sm text-slate-500">One task at a time</p>
                            </div>

                            <div class="mt-6 divide-y divide-slate-200">
                                <template x-for="task in tasks" :key="task.id">
                                    <button type="button"
                                        class="flex w-full items-center justify-between gap-6 py-5 text-left transition"
                                        @click="selectedTask = task.id">
                                        <div>
                                            <p class="text-base font-semibold text-slate-900" x-text="task.title"></p>
                                            <p class="mt-2 text-sm leading-6 text-slate-500" x-text="task.instructions"></p>
                                        </div>
                                        <span class="whitespace-nowrap text-sm font-semibold text-blue-700">Select Task</span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div class="mt-10" x-show="selectedTask" x-cloak>
                            <div class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Task Workspace</p>
                                    <h3 class="mt-2 text-2xl font-semibold text-slate-900" x-text="currentTask?.title"></h3>
                                </div>
                                <button type="button"
                                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                    @click="selectedTask = null">
                                    Change Task
                                </button>
                            </div>

                            <div class="mt-8 space-y-8">
                                <section>
                                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Instructions</p>
                                    <p class="mt-3 max-w-3xl text-base leading-8 text-slate-600" x-text="currentTask?.instructions"></p>
                                </section>

                                <section>
                                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-slate-500">Requirements</p>
                                    <div class="mt-4 space-y-3">
                                        <template x-for="requirement in currentTask?.requirements ?? []" :key="requirement">
                                            <div class="flex items-start gap-3">
                                                <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                                                    <i class="fi fi-rr-check text-xs"></i>
                                                </span>
                                                <p class="text-base leading-7 text-slate-600" x-text="requirement"></p>
                                            </div>
                                        </template>
                                    </div>
                                </section>

                                <section class="border-t border-slate-200 pt-6">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <button type="button"
                                            class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:bg-primaryLight">
                                            Submit Task
                                        </button>
                                        <button type="button"
                                            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                            Mark as Complete
                                        </button>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
