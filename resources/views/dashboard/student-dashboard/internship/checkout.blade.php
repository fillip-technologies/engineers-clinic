@extends('layouts.frontend-admin')

@section('content')
@php
    $levelMeta = [
        'Beginner'     => ['icon' => 'fi fi-rr-seedling',      'color' => 'emerald', 'duration' => '45 days',  'desc' => 'Foundational projects. Build real skills from scratch.'],
        'Intermediate' => ['icon' => 'fi fi-rr-chart-line-up', 'color' => 'blue',    'duration' => '75 days',  'desc' => 'Industry-level builds for full-stack development.'],
        'Advanced'     => ['icon' => 'fi fi-rr-rocket',        'color' => 'violet',  'duration' => '90 days',  'desc' => 'AI, cloud deployment, and production-grade systems.'],
    ];
    $steps = [
        ['num' => 1, 'label' => 'Level'],
        ['num' => 2, 'label' => 'Topic'],
        ['num' => 3, 'label' => 'Track'],
        ['num' => 4, 'label' => 'Project'],
        ['num' => 5, 'label' => 'Payment'],
    ];
@endphp

<div class="mx-auto max-w-5xl"
    x-data="internshipCheckout({
        step:                 {{ $initialStep }},
        selectedLevel:        @js($currentLevel),
        selectedStream:       @js($currentStream),
        canSelfAssignLevel:   @js($canSelfAssignLevel),
        allCourses:           @js($allCourses),
        enrolledCourseIds:    @js($enrolledCourseIds),
        levelFees:            @js($levelFees),
        razorpayKey:          @js($razorpayKey),
        userName:             @js(Auth::user()?->name ?? ''),
        userEmail:            @js(Auth::user()?->email ?? ''),
        csrfToken:            @js(csrf_token()),
        startUrl:             @js(route('student.internship.checkout.start')),
        verifyUrl:            @js(route('student.internship.checkout.verify')),
        dashboardUrl:         @js(route('dashboard')),
        preSelectedCourseIds: @js($preSelectedCourseIds ?? []),
    })"
>
    {{-- Page Header --}}
    <div class="mb-6">
        <p class="text-sm font-semibold text-primary">Internship Enrollment</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Get Started with Your Internship</h1>
        <p class="mt-2 text-sm text-slate-500">Choose your level, topic, track, and up to 3 projects — then pay once to unlock everything.</p>
    </div>

    {{-- Step Progress Indicator --}}
    <div class="mb-8 flex items-center">
        @foreach($steps as $s)
            <div class="flex flex-1 flex-col items-center gap-1.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold transition-all duration-300"
                    :class="step > {{ $s['num'] }}
                        ? 'bg-emerald-500 text-white shadow-sm'
                        : (step === {{ $s['num'] }}
                            ? 'bg-primary text-white shadow-md ring-4 ring-primary/20'
                            : 'bg-slate-100 text-slate-400')">
                    <template x-if="step > {{ $s['num'] }}">
                        <i class="fi fi-rr-check text-xs leading-none"></i>
                    </template>
                    <template x-if="step <= {{ $s['num'] }}">
                        <span>{{ $s['num'] }}</span>
                    </template>
                </div>
                <span class="hidden text-xs font-semibold sm:block transition-colors duration-300"
                    :class="step === {{ $s['num'] }}
                        ? 'text-primary'
                        : (step > {{ $s['num'] }} ? 'text-emerald-600' : 'text-slate-400')">
                    {{ $s['label'] }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="mb-5 h-px flex-1 transition-all duration-500"
                    :class="step > {{ $s['num'] }} ? 'bg-emerald-400' : 'bg-slate-200'"></div>
            @endif
        @endforeach
    </div>

    {{-- Global Error --}}
    <template x-if="error">
        <div class="mb-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
            <i class="fi fi-rr-cross-circle mt-0.5 shrink-0 text-red-400 leading-none"></i>
            <span x-text="error"></span>
        </div>
    </template>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- STEP 1 · Choose Level                                  --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div x-show="step === 1" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <h2 class="text-xl font-semibold text-slate-950">Choose your internship level</h2>
            <p class="mt-1 text-sm text-slate-500">The level sets your project difficulty and the one-time program fee.</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                @foreach(['Beginner', 'Intermediate', 'Advanced'] as $level)
                    @php $meta = $levelMeta[$level] @endphp
                    <button type="button"
                        @click="{{ $canSelfAssignLevel ? "selectLevel('$level')" : '' }}"
                        class="group flex flex-col rounded-xl border p-5 text-left transition-all duration-200 {{ $canSelfAssignLevel ? 'cursor-pointer hover:border-primary hover:shadow-md' : 'cursor-default' }}"
                        :class="selectedLevel === '{{ $level }}'
                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20 shadow-md'
                            : 'border-slate-200 bg-white'">

                        <div class="flex items-center justify-between">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-{{ $meta['color'] }}-50">
                                <i class="{{ $meta['icon'] }} text-xl text-{{ $meta['color'] }}-500 leading-none"></i>
                            </div>
                            <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 transition-all duration-200"
                                :class="selectedLevel === '{{ $level }}'
                                    ? 'border-primary bg-primary text-white'
                                    : 'border-slate-300 bg-white'">
                                <i x-show="selectedLevel === '{{ $level }}'" class="fi fi-rr-check text-[10px] leading-none"></i>
                            </span>
                        </div>

                        <p class="mt-4 text-base font-semibold text-slate-950">{{ $level }}</p>
                        <p class="mt-1 text-xs leading-relaxed text-slate-500">{{ $meta['desc'] }}</p>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3">
                            <span class="flex items-center gap-1 text-xs text-slate-400">
                                <i class="fi fi-rr-clock text-[10px] leading-none"></i>
                                {{ $meta['duration'] }}
                            </span>
                            <span class="text-base font-bold text-slate-900">₹{{ number_format($levelFees[$level], 0) }}</span>
                        </div>
                    </button>
                @endforeach
            </div>

            @if(!$canSelfAssignLevel)
                <div class="mt-4 flex items-start gap-2 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-xs text-amber-800">
                    <i class="fi fi-rr-info mt-0.5 shrink-0 leading-none text-amber-500"></i>
                    Your level is assigned by your college administrator and cannot be changed here.
                </div>
            @endif

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-sm font-semibold text-slate-400 hover:text-slate-700">
                    ← Back to Dashboard
                </a>
                <button type="button"
                    @click="step = 2"
                    :disabled="!selectedLevel"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-50">
                    Continue to Topic
                    <i class="fi fi-rr-arrow-right text-xs leading-none"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- STEP 2 · Choose Topic                                  --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div x-show="step === 2" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-start gap-3">
                <button type="button" @click="step = 1" class="mt-0.5 shrink-0 text-slate-400 transition hover:text-slate-700">
                    <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
                </button>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Choose your internship topic</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Pick the domain that aligns with your career goals.
                        Showing topics for the <strong x-text="selectedLevel"></strong> level.
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2 lg:grid-cols-3"
                x-show="streamsForLevel.length > 0">
                <template x-for="stream in streamsForLevel" :key="stream">
                    <button type="button"
                        @click="selectStream(stream)"
                        class="flex items-center justify-between rounded-xl border p-4 text-left transition-all duration-200 hover:border-primary hover:shadow-md"
                        :class="selectedStream === stream
                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                            : 'border-slate-200 bg-white'">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100">
                                <i class="fi fi-rr-folder text-sm text-slate-500 leading-none"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="truncate text-sm font-semibold text-slate-900" x-text="stream"></p>
                                <p class="mt-0.5 text-xs text-slate-400" x-text="trackCountForStream(stream) + ' track' + (trackCountForStream(stream) !== 1 ? 's' : '')"></p>
                            </div>
                        </div>
                        <span class="ml-3 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-200"
                            :class="selectedStream === stream ? 'border-primary bg-primary text-white' : 'border-slate-300 bg-white'">
                            <i x-show="selectedStream === stream" class="fi fi-rr-check text-[8px] leading-none"></i>
                        </span>
                    </button>
                </template>
            </div>

            <div x-show="streamsForLevel.length === 0" class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 py-14 text-center">
                <i class="fi fi-rr-folder-open text-3xl text-slate-300 leading-none"></i>
                <p class="mt-3 text-sm font-semibold text-slate-500">No topics available for this level yet.</p>
                <p class="mt-1 text-xs text-slate-400">Try selecting a different level, or check back later.</p>
                <button type="button" @click="step = 1" class="mt-4 text-sm font-semibold text-primary hover:underline">
                    ← Change Level
                </button>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" @click="step = 1" class="text-sm font-semibold text-slate-400 hover:text-slate-700">
                    ← Back
                </button>
                <button type="button"
                    @click="step = 3"
                    :disabled="!selectedStream"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-50">
                    Browse Tracks
                    <i class="fi fi-rr-arrow-right text-xs leading-none"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- STEP 3 · Choose Track                                  --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div x-show="step === 3" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex items-start gap-3">
                <button type="button" @click="step = 2" class="mt-0.5 shrink-0 text-slate-400 transition hover:text-slate-700">
                    <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
                </button>
                <div>
                    <h2 class="text-xl font-semibold text-slate-950">Choose your internship track</h2>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Select a skill track within <strong x-text="selectedStream"></strong> (<strong x-text="selectedLevel"></strong>).
                    </p>
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2"
                x-show="tracksForLevelAndStream.length > 0">
                <template x-for="track in tracksForLevelAndStream" :key="track.id">
                    <button type="button"
                        @click="selectTrack(track)"
                        class="flex items-start gap-3 rounded-xl border p-4 text-left transition-all duration-200 hover:border-primary hover:shadow-md"
                        :class="selectedTrack && selectedTrack.id === track.id
                            ? 'border-primary bg-primary/5 ring-2 ring-primary/20'
                            : 'border-slate-200 bg-white'">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 mt-0.5">
                            <i class="fi fi-rr-book-alt text-sm text-slate-500 leading-none"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <p class="text-sm font-semibold leading-snug text-slate-900" x-text="track.title"></p>
                                <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 transition-all duration-200"
                                    :class="selectedTrack && selectedTrack.id === track.id ? 'border-primary bg-primary text-white' : 'border-slate-300 bg-white'">
                                    <i x-show="selectedTrack && selectedTrack.id === track.id" class="fi fi-rr-check text-[8px] leading-none"></i>
                                </span>
                            </div>
                            <div class="mt-1.5 flex items-center gap-2 text-xs text-slate-400">
                                <span x-text="(track.projects ? track.projects.length : 0) + ' projects'"></span>
                                <template x-if="enrolledCourseIds.includes(track.id)">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                                        <i class="fi fi-rr-check text-[8px] leading-none"></i> Enrolled
                                    </span>
                                </template>
                            </div>
                        </div>
                    </button>
                </template>
            </div>

            <div x-show="tracksForLevelAndStream.length === 0" class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 py-14 text-center">
                <i class="fi fi-rr-folder-open text-3xl text-slate-300 leading-none"></i>
                <p class="mt-3 text-sm font-semibold text-slate-500">No tracks available for this topic yet.</p>
                <button type="button" @click="step = 2" class="mt-4 text-sm font-semibold text-primary hover:underline">
                    ← Change Topic
                </button>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" @click="step = 2" class="text-sm font-semibold text-slate-400 hover:text-slate-700">
                    ← Back
                </button>
                <button type="button"
                    @click="step = 4"
                    :disabled="!selectedTrack"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-50">
                    Browse Projects
                    <i class="fi fi-rr-arrow-right text-xs leading-none"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- STEP 4 · Choose Projects                               --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div x-show="step === 4" x-cloak>
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-start gap-3">
                    <button type="button" @click="step = 3" class="mt-0.5 shrink-0 text-slate-400 transition hover:text-slate-700">
                        <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Choose your projects</h2>
                        <p class="mt-0.5 text-sm text-slate-500">
                            Select up to 3 projects from <strong x-text="selectedTrack ? selectedTrack.title : ''"></strong>.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2">
                    <span class="text-xs font-medium text-slate-500">Selected</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full text-xs font-bold text-white transition-colors duration-200"
                        :class="selectedProjects.length >= 3 ? 'bg-emerald-500' : 'bg-slate-800'"
                        x-text="selectedProjects.length"></span>
                    <span class="text-xs font-medium text-slate-500">/ 3</span>
                </div>
            </div>

            {{-- All-3-selected banner --}}
            <template x-if="selectedProjects.length >= 3">
                <div class="mt-4 flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3 text-sm text-emerald-800">
                    <i class="fi fi-rr-check-circle shrink-0 text-emerald-500 leading-none"></i>
                    <span>You've selected 3 projects — you're ready to pay! Deselect one if you want to swap.</span>
                </div>
            </template>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3"
                x-show="projectsForTrack.length > 0">
                <template x-for="project in projectsForTrack" :key="project.id">
                    <div
                        @click="toggleProject(project)"
                        class="relative flex flex-col rounded-xl border p-5 transition-all duration-200"
                        :class="isProjectSelected(project.id)
                            ? 'cursor-pointer border-primary bg-primary/5 ring-2 ring-primary/20 shadow-md'
                            : (selectedProjects.length >= 3
                                ? 'cursor-not-allowed border-slate-200 opacity-50'
                                : 'cursor-pointer border-slate-200 hover:border-primary hover:shadow-md')">

                        <div class="absolute right-4 top-4">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full border-2 transition-all duration-200"
                                :class="isProjectSelected(project.id)
                                    ? 'border-primary bg-primary text-white'
                                    : 'border-slate-300 bg-white'">
                                <i x-show="isProjectSelected(project.id)" class="fi fi-rr-check text-[9px] leading-none"></i>
                            </span>
                        </div>

                        <div class="pr-8">
                            <p class="text-sm font-semibold leading-snug text-slate-900" x-text="project.title"></p>
                            <p class="mt-2 text-xs leading-relaxed text-slate-500"
                                x-text="project.description && project.description.length > 110 ? project.description.substring(0, 110) + '…' : (project.description || '')"></p>
                        </div>

                        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-3 text-xs text-slate-400">
                            <span x-text="project.category"></span>
                            <span class="flex items-center gap-1">
                                <i class="fi fi-rr-clock text-[10px] leading-none"></i>
                                <span x-text="project.duration"></span>
                            </span>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="projectsForTrack.length === 0"
                class="mt-6 rounded-xl border border-dashed border-slate-200 bg-slate-50 py-14 text-center">
                <i class="fi fi-rr-folder-open text-3xl text-slate-300 leading-none"></i>
                <p class="mt-3 text-sm font-semibold text-slate-500">No projects available for this track.</p>
                <button type="button" @click="step = 3" class="mt-4 text-sm font-semibold text-primary hover:underline">
                    ← Change Track
                </button>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <button type="button" @click="step = 3" class="text-sm font-semibold text-slate-400 hover:text-slate-700">
                    ← Back
                </button>
                <button type="button"
                    @click="step = 5"
                    :disabled="selectedProjects.length === 0"
                    class="inline-flex items-center gap-2 rounded-xl bg-primary px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-50">
                    Review & Pay
                    <i class="fi fi-rr-arrow-right text-xs leading-none"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────── --}}
    {{-- STEP 5 · Review & Payment                              --}}
    {{-- ─────────────────────────────────────────────────────── --}}
    <div x-show="step === 5" x-cloak>
        <div class="grid gap-6 lg:grid-cols-[1fr_340px]">

            {{-- Plan Summary --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <div class="flex items-start gap-3">
                    <button type="button" @click="step = 4" class="mt-0.5 shrink-0 text-slate-400 transition hover:text-slate-700">
                        <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
                    </button>
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">Review your internship plan</h2>
                        <p class="mt-0.5 text-sm text-slate-500">Confirm your selections before payment.</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    {{-- Level row --}}
                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white shadow-sm">
                            <i class="fi fi-rr-seedling text-emerald-500 leading-none"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Level</p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-900" x-text="selectedLevel"></p>
                        </div>
                        @if($canSelfAssignLevel)
                        <button @click="step = 1" class="shrink-0 text-xs font-semibold text-primary hover:underline">Change</button>
                        @endif
                    </div>

                    {{-- Topic row --}}
                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white shadow-sm">
                            <i class="fi fi-rr-folder text-blue-500 leading-none"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Topic</p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-900" x-text="selectedStream"></p>
                        </div>
                        <button @click="step = 2" class="shrink-0 text-xs font-semibold text-primary hover:underline">Change</button>
                    </div>

                    {{-- Track row --}}
                    <div class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50 p-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white shadow-sm">
                            <i class="fi fi-rr-book-alt text-violet-500 leading-none"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Track</p>
                            <p class="mt-0.5 text-sm font-semibold text-slate-900" x-text="selectedTrack ? selectedTrack.title : ''"></p>
                        </div>
                        <button @click="step = 3" class="shrink-0 text-xs font-semibold text-primary hover:underline">Change</button>
                    </div>

                    {{-- Projects list --}}
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
                                Selected Projects (<span x-text="selectedProjects.length"></span>/3)
                            </p>
                            <button x-show="selectedProjects.length < 3" @click="step = 4"
                                class="text-xs font-semibold text-primary hover:underline">
                                + Add more
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(project, idx) in selectedProjects" :key="project.id">
                                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-3.5">
                                    <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xs font-bold text-emerald-700" x-text="idx + 1"></div>
                                    <p class="flex-1 min-w-0 truncate text-sm font-medium text-slate-900" x-text="project.title"></p>
                                    <button @click="toggleProject(project)"
                                        class="shrink-0 text-xs text-slate-300 transition hover:text-red-500">
                                        <i class="fi fi-rr-cross text-xs leading-none"></i>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <template x-if="selectedProjects.length === 0">
                            <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50 py-6 text-center text-sm text-slate-400">
                                No projects selected.
                                <button @click="step = 4" class="ml-1 font-semibold text-primary hover:underline">Browse projects</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            {{-- Payment Card --}}
            <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Order Summary</p>

                <div class="mt-4 border-b border-slate-100 pb-4">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold text-slate-900" x-text="selectedLevel + ' Internship Program'"></p>
                            <p class="mt-0.5 text-xs text-slate-400">One-time access fee · <span x-text="selectedProjects.length"></span> project<span x-show="selectedProjects.length !== 1">s</span></p>
                        </div>
                        <p class="shrink-0 text-sm font-bold text-slate-900"
                            x-text="'₹' + (levelFees[selectedLevel] || 0).toLocaleString('en-IN')"></p>
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <p class="text-sm font-medium text-slate-500">Total</p>
                    <p class="text-2xl font-bold text-slate-900"
                        x-text="'₹' + (levelFees[selectedLevel] || 0).toLocaleString('en-IN')"></p>
                </div>

                <ul class="mt-5 space-y-2.5">
                    @foreach(['Full workspace access for all projects','Guided step-by-step checkpoints','Build & submit your portfolio','Internship completion certificate'] as $feat)
                    <li class="flex items-center gap-2.5 text-xs text-slate-600">
                        <i class="fi fi-rr-check-circle shrink-0 text-emerald-500 leading-none"></i>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                <template x-if="error">
                    <div class="mt-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-600" x-text="error"></div>
                </template>

                @if(blank($razorpayKey))
                    <div class="mt-5 rounded-xl border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">
                        Razorpay is not configured. Add RAZORPAY_KEY and RAZORPAY_SECRET to .env.
                    </div>
                @else
                    <button
                        type="button"
                        @click="pay()"
                        :disabled="processing || (!selectedTrack)"
                        class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span x-show="!processing" class="flex items-center gap-2">
                            <i class="fi fi-rr-lock-open-alt leading-none"></i>
                            Pay & Unlock Internship
                        </span>
                        <span x-show="processing" class="flex items-center gap-2">
                            <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                @endif

                <p class="mt-4 text-center text-xs text-slate-400">
                    <i class="fi fi-rr-shield-check mr-1 text-slate-300 leading-none"></i>
                    Secured by Razorpay · One-time payment
                </p>
            </aside>
        </div>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function internshipCheckout(cfg) {
    return {
        ...cfg,
        processing: false,
        error: '',
        selectedTrack: null,
        selectedProjects: [],

        init() {
            // Auto-select track when coming from the public enrollment form (step 5 pre-selected)
            if (this.preSelectedCourseIds && this.preSelectedCourseIds.length > 0 && this.step === 5) {
                const primaryId = this.preSelectedCourseIds[0];
                const track = this.allCourses.find(c => c.id === primaryId);
                if (track) {
                    this.selectedTrack = track;
                    if (!this.selectedStream) this.selectedStream = track.category;
                    if (!this.selectedLevel) this.selectedLevel = track.level;
                }
            }
        },

        get streamsForLevel() {
            if (!this.selectedLevel) return [];
            const seen = new Set();
            return this.allCourses
                .filter(c => c.level === this.selectedLevel && c.category)
                .map(c => c.category)
                .filter(c => { if (seen.has(c)) return false; seen.add(c); return true; })
                .sort();
        },

        get tracksForLevelAndStream() {
            if (!this.selectedLevel || !this.selectedStream) return [];
            return this.allCourses.filter(c =>
                c.level === this.selectedLevel && c.category === this.selectedStream
            );
        },

        get projectsForTrack() {
            return this.selectedTrack ? (this.selectedTrack.projects || []) : [];
        },

        selectLevel(level) {
            if (this.selectedLevel !== level) {
                this.selectedStream = null;
                this.selectedTrack = null;
                this.selectedProjects = [];
            }
            this.selectedLevel = level;
        },

        selectStream(stream) {
            if (this.selectedStream !== stream) {
                this.selectedTrack = null;
                this.selectedProjects = [];
            }
            this.selectedStream = stream;
        },

        selectTrack(track) {
            if (!this.selectedTrack || this.selectedTrack.id !== track.id) {
                this.selectedProjects = [];
            }
            this.selectedTrack = track;
        },

        trackCountForStream(stream) {
            return this.allCourses.filter(c =>
                c.level === this.selectedLevel && c.category === stream
            ).length;
        },

        isProjectSelected(id) {
            return this.selectedProjects.some(p => p.id === id);
        },

        toggleProject(project) {
            const idx = this.selectedProjects.findIndex(p => p.id === project.id);
            if (idx >= 0) {
                this.selectedProjects.splice(idx, 1);
            } else if (this.selectedProjects.length < 3) {
                this.selectedProjects.push(project);
            }
        },

        async pay() {
            if (this.processing) return;
            this.error = '';
            this.processing = true;

            if (typeof Razorpay === 'undefined') {
                this.error = 'Razorpay could not be loaded. Please refresh and try again.';
                this.processing = false;
                return;
            }

            if (!this.selectedTrack) {
                this.error = 'Please select a track first.';
                this.processing = false;
                return;
            }

            let orderData;
            try {
                const courseIds = (this.preSelectedCourseIds && this.preSelectedCourseIds.length > 0)
                    ? this.preSelectedCourseIds
                    : [this.selectedTrack.id];
                orderData = await this.postJson(this.startUrl, {
                    level:            this.selectedLevel,
                    stream:           this.selectedStream || (this.selectedTrack ? this.selectedTrack.category : ''),
                    selected_courses: courseIds,
                });
            } catch (e) {
                this.error = e.message || 'Could not create payment order. Please try again.';
                this.processing = false;
                return;
            }

            const rzp = new Razorpay({
                key:         this.razorpayKey,
                amount:      orderData.amount_paise,
                currency:    'INR',
                name:        'Engineers Clinic',
                description: this.selectedLevel + ' Internship Program',
                order_id:    orderData.order_id,
                prefill: {
                    name:  this.userName,
                    email: this.userEmail,
                },
                handler: async (response) => {
                    try {
                        const result = await this.postJson(this.verifyUrl, {
                            internship_payment_id: orderData.internship_payment_id,
                            razorpay_payment_id:   response.razorpay_payment_id,
                            razorpay_order_id:     response.razorpay_order_id,
                            razorpay_signature:    response.razorpay_signature,
                        });
                        window.location.href = result.redirect_url || this.dashboardUrl;
                    } catch (e) {
                        this.error = e.message || 'Payment verification failed. Contact support if money was debited.';
                        this.processing = false;
                    }
                },
                modal: {
                    ondismiss: () => { this.processing = false; },
                },
                theme: { color: '#5B5BF6' },
            });

            rzp.open();
        },

        async postJson(url, payload) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify(payload),
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw new Error(data.message || 'Request failed.');
            return data;
        },
    };
}
</script>
@endsection
