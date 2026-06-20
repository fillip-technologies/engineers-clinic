@extends('layouts.frontend-admin')

@section('content')
@php
    $levelMeta = [
        'Beginner'     => ['icon' => 'fi fi-rr-seedling',      'color' => 'emerald', 'badge' => 'bg-emerald-50 text-emerald-700 ring-emerald-200', 'border' => 'border-emerald-200', 'bar' => 'bg-emerald-500', 'btn' => 'bg-emerald-600 hover:bg-emerald-700 text-white', 'check' => 'text-emerald-500'],
        'Intermediate' => ['icon' => 'fi fi-rr-chart-line-up', 'color' => 'blue',    'badge' => 'bg-blue-50 text-blue-700 ring-blue-200',         'border' => 'border-blue-200',    'bar' => 'bg-blue-500',    'btn' => 'bg-blue-600 hover:bg-blue-700 text-white',    'check' => 'text-blue-500'],
        'Advanced'     => ['icon' => 'fi fi-rr-rocket',        'color' => 'violet',  'badge' => 'bg-violet-50 text-violet-700 ring-violet-200',   'border' => 'border-violet-200',  'bar' => 'bg-violet-500',  'btn' => 'bg-violet-600 hover:bg-violet-700 text-white', 'check' => 'text-violet-500'],
    ];
@endphp

<div class="mx-auto max-w-6xl" x-data="{
    selectedLevel:         @js($studentLevel),
    selectedCategory:      @js($studentStream),
    selectedTitle:         null,
    categoriesByLevel:     @js($categoriesByLevel),
    titlesByLevelCategory: @js($titlesByLevelCategory),

    get categories() {
        return this.selectedLevel ? (this.categoriesByLevel[this.selectedLevel] || []) : [];
    },
    get titles() {
        if (!this.selectedLevel || !this.selectedCategory) return [];
        return this.titlesByLevelCategory[this.selectedLevel + '|' + this.selectedCategory] || [];
    },
    selectLevel(level) {
        if (this.selectedLevel !== level) {
            this.selectedCategory = null;
            this.selectedTitle    = null;
        }
        this.selectedLevel = level;
    },
    selectCategory(cat) {
        if (this.selectedCategory !== cat) {
            this.selectedTitle = null;
        }
        this.selectedCategory = cat;
    },
}">

    {{-- Page header --}}
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Browse Projects</h1>
                <p class="mt-3 text-base leading-8 text-slate-600">
                    Select your level, topic, and track below to see matching projects.
                </p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if ($studentStream)
                    <div class="flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2.5">
                        <i class="fi fi-rr-bookmark text-xs text-violet-500"></i>
                        <span class="text-sm font-semibold text-violet-800">{{ $studentStream }}</span>
                    </div>
                @endif
                <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5">
                    <span class="text-sm font-medium text-slate-500">Selected</span>
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $totalSelected >= 3 ? 'bg-emerald-600' : 'bg-slate-900' }} text-xs font-bold text-white">{{ $totalSelected }}</span>
                    <span class="text-sm font-medium text-slate-500">/ 3</span>
                </div>
                @if ($totalSelected > 0)
                    <a href="{{ route('dashboard') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">
                        <i class="fi fi-rr-apps text-sm leading-none"></i>
                        My Dashboard
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Level not assigned banner --}}
    @if (! $studentLevel)
        @if ($canSelfAssignLevel)
            <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-5 py-5">
                <p class="text-sm font-semibold text-blue-900">Choose your internship level to get started</p>
                <p class="mt-1 text-sm text-blue-700">Since you're not from a registered college, you can set your own level.</p>
                <form method="POST" action="{{ route('student.level.set') }}" class="mt-4 flex flex-wrap items-center gap-3">
                    @csrf
                    @foreach (['Beginner', 'Intermediate', 'Advanced'] as $lvl)
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white">
                            <input type="radio" name="level" value="{{ $lvl }}" class="sr-only" required />
                            {{ $lvl }}
                        </label>
                    @endforeach
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                        Confirm Level
                    </button>
                </form>
            </div>
        @else
            <div class="mt-4 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                <i class="fi fi-rr-info mt-0.5 shrink-0 text-amber-500"></i>
                <div>
                    <p class="font-semibold">No internship level assigned yet.</p>
                    <p class="mt-0.5 text-amber-700">Contact your college administrator to get your level assigned before selecting projects.</p>
                </div>
            </div>
        @endif
    @endif

    {{-- Not paid banner --}}
    @if ($studentLevel && ! $internshipPaid)
        <div class="mt-4 rounded-xl border border-primary/20 bg-primary/5 px-5 py-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-semibold text-slate-900">
                        <i class="fi fi-rr-lock mr-1.5 text-primary"></i>
                        Pay for your <strong>{{ $studentLevel }}</strong> internship to select projects
                    </p>
                    <p class="mt-1 text-sm text-slate-600">One-time payment unlocks the full program — choose any 3 projects from your topic.</p>
                </div>
                <a href="{{ route('student.internship.checkout') }}"
                    class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-primaryLight">
                    <i class="fi fi-rr-lock-open-alt text-sm leading-none"></i>
                    Pay & Unlock Internship
                </a>
            </div>
        </div>
    @endif

    {{-- All 3 selected banner --}}
    @if ($internshipPaid && $totalSelected >= 3)
        <div class="mt-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
            <i class="fi fi-rr-check-circle mt-0.5 shrink-0 text-emerald-500"></i>
            <div>
                <p class="font-semibold">All 3 projects selected — you're ready to build!</p>
                <p class="mt-0.5 text-emerald-700">Work through your projects from the dashboard. You can select more after completing one.</p>
                <a href="{{ route('dashboard') }}" class="mt-2 inline-flex items-center gap-1.5 font-semibold text-emerald-900 underline underline-offset-2">
                    Go to dashboard <i class="fi fi-rr-arrow-right text-xs"></i>
                </a>
            </div>
        </div>
    @endif

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="mt-4 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
            <i class="fi fi-rr-check-circle mt-0.5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mt-4 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-800">
            <i class="fi fi-rr-cross-circle mt-0.5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Step 1: Select Level --}}
    <section class="mt-6">
        <div class="mb-3 flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">1</span>
            <h2 class="text-sm font-semibold text-slate-700">Select Level</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            @foreach ($levels as $level)
                @php $meta = $levelMeta[$level] @endphp
                <button @click="selectLevel('{{ $level }}')"
                    :class="selectedLevel === '{{ $level }}'
                        ? 'bg-slate-900 text-white shadow-sm'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-400 hover:text-slate-900'"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold transition">
                    <i class="{{ $meta['icon'] }} text-sm leading-none"></i>
                    {{ $level }}
                    <span class="ml-0.5 rounded-full px-1.5 py-0.5 text-xs tabular-nums"
                          :class="selectedLevel === '{{ $level }}' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">
                        {{ count($categoriesByLevel[$level] ?? []) }} topics
                    </span>
                </button>
            @endforeach
        </div>
    </section>

    {{-- Step 2: Select Topic/Category --}}
    <section class="mt-6" x-show="selectedLevel" x-cloak>
        <div class="mb-3 flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">2</span>
            <h2 class="text-sm font-semibold text-slate-700">Select Topic</h2>
            <span class="text-sm text-slate-400">— <span x-text="selectedLevel"></span> level</span>
        </div>
        <div class="flex flex-wrap gap-3">
            <template x-for="cat in categories" :key="cat">
                <button @click="selectCategory(cat)"
                    :class="selectedCategory === cat
                        ? 'bg-violet-600 text-white shadow-sm border-violet-600'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-violet-400 hover:text-violet-700'"
                    class="inline-flex items-center gap-2 rounded-xl border px-5 py-3 text-sm font-semibold transition">
                    <i class="fi fi-rr-bookmark text-sm leading-none"></i>
                    <span x-text="cat"></span>
                </button>
            </template>
        </div>
    </section>

    {{-- Step 3: Select Track/Title --}}
    <section class="mt-6" x-show="selectedCategory" x-cloak>
        <div class="mb-3 flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">3</span>
            <h2 class="text-sm font-semibold text-slate-700">Select Track</h2>
            <span class="text-sm text-slate-400">— <span x-text="selectedCategory"></span></span>
        </div>
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <template x-for="t in titles" :key="t.title">
                <button @click="selectedTitle = t.title"
                    :class="selectedTitle === t.title
                        ? 'border-primary bg-blue-50 shadow-sm'
                        : 'bg-white border-slate-200 hover:border-primary/50 hover:bg-slate-50'"
                    class="flex flex-col items-start rounded-xl border p-4 text-left transition">
                    <p class="text-sm font-semibold leading-snug text-slate-900" x-text="t.title"></p>
                    <p class="mt-1.5 text-xs text-slate-500" x-text="t.count + ' projects'"></p>
                    <span x-show="selectedTitle === t.title"
                          class="mt-2 inline-flex items-center gap-1 rounded-full bg-primary/10 px-2 py-0.5 text-xs font-semibold text-primary">
                        <i class="fi fi-rr-check text-[10px]"></i> Selected
                    </span>
                </button>
            </template>
        </div>
    </section>

    {{-- Step 4: Projects --}}
    <section class="mt-6" x-show="selectedTitle" x-cloak>
        <div class="mb-4 flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-900 text-xs font-bold text-white">4</span>
            <h2 class="text-sm font-semibold text-slate-700">Projects</h2>
            <span class="text-sm text-slate-400">— <span x-text="selectedTitle"></span></span>
        </div>

        @foreach ($projectsByLevelCategoryTitle as $level => $byCategory)
            @foreach ($byCategory as $category => $byTrack)
                @foreach ($byTrack as $trackTitle => $projects)
                    @php $meta = $levelMeta[$level] ?? $levelMeta['Beginner'] @endphp
                    <div x-show="selectedLevel === '{{ $level }}' && selectedCategory === {{ json_encode($category) }} && selectedTitle === {{ json_encode($trackTitle) }}"
                         x-cloak>

                        @if (empty($projects))
                            <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                                <i class="fi fi-rr-inbox text-4xl text-slate-300"></i>
                                <p class="mt-4 text-sm font-semibold text-slate-900">No projects in this track</p>
                            </div>
                        @else
                            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                                @foreach ($projects as $project)
                                    <div class="flex flex-col rounded-2xl border {{ $project['is_enrolled'] ? $meta['border'] : 'border-slate-200' }} bg-white p-5 shadow-sm transition hover:shadow-md">

                                        {{-- Enrolled highlight banner --}}
                                        @if ($project['is_enrolled'])
                                        <div class="-mx-5 -mt-5 mb-4 flex items-center gap-2 rounded-t-2xl px-5 py-2.5 {{ $meta['badge'] }}">
                                            <i class="fi fi-rr-check-circle text-xs leading-none"></i>
                                            <span class="text-xs font-semibold">Your Current Project</span>
                                        </div>
                                        @endif

                                        {{-- Level badge + duration --}}
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $meta['badge'] }}">
                                                <i class="{{ $meta['icon'] }} text-[10px] leading-none"></i>
                                                {{ $project['level'] }}
                                            </span>
                                            <span class="text-xs text-slate-400">{{ $project['duration'] }}</span>
                                        </div>

                                        <p class="mt-3 text-xs font-medium uppercase tracking-wide text-slate-400">{{ $project['category'] }}</p>
                                        <h3 class="mt-1 text-base font-semibold leading-snug text-slate-900">{{ $project['title'] }}</h3>
                                        <p class="mt-2 text-sm leading-6 text-slate-500">{{ Str::limit($project['description'], 120) }}</p>

                                        @if (! empty($project['tasks']))
                                            <div class="mt-4 space-y-1.5">
                                                @foreach ($project['tasks'] as $task)
                                                    <div class="flex items-start gap-2">
                                                        <i class="fi fi-rr-check mt-0.5 shrink-0 text-xs {{ $meta['check'] }}"></i>
                                                        <span class="text-xs leading-snug text-slate-600">{{ $task }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (! empty($project['submission']))
                                            <div class="mt-3 flex items-start gap-2 rounded-lg bg-slate-50 px-3 py-2">
                                                <i class="fi fi-rr-file-upload mt-0.5 shrink-0 text-xs text-slate-400"></i>
                                                <span class="text-xs leading-snug text-slate-500">
                                                    <span class="font-medium text-slate-700">Submit:</span>
                                                    {{ Str::limit($project['submission'], 80) }}
                                                </span>
                                            </div>
                                        @endif

                                        @if ($project['is_enrolled'])
                                            @if ($project['workspace_locked'] ?? false)
                                                <div class="mt-4 flex items-center gap-2 rounded-lg border border-amber-100 bg-amber-50 px-3 py-2">
                                                    <i class="fi fi-rr-lock text-xs text-amber-400 shrink-0"></i>
                                                    <span class="text-xs font-medium text-amber-700">Pay for internship to unlock workspace</span>
                                                </div>
                                            @else
                                                <div class="mt-4">
                                                    <div class="flex justify-between text-xs">
                                                        <span class="font-medium text-slate-600">Progress</span>
                                                        <span class="font-semibold text-slate-900">{{ $project['progress'] }}%</span>
                                                    </div>
                                                    <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                                        <div class="h-full rounded-full {{ $meta['bar'] }} transition-all"
                                                             style="width: {{ $project['progress'] }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        {{-- Action buttons --}}
                                        <div class="mt-5 flex-none">
                                            @if ($project['is_enrolled'])
                                                @if ($project['workspace_locked'] ?? false)
                                                    <a href="{{ route('student.internship.checkout') }}"
                                                        class="flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                                                        <i class="fi fi-rr-lock-open-alt text-sm leading-none"></i>
                                                        Unlock Internship to Start
                                                    </a>
                                                @else
                                                    <a href="{{ $project['workspace_url'] }}"
                                                        class="flex w-full items-center justify-center gap-2 rounded-xl {{ $meta['btn'] }} px-4 py-2.5 text-sm font-semibold shadow-sm transition">
                                                        <i class="fi fi-rr-play text-sm leading-none"></i>
                                                        {{ $project['progress'] > 0 ? 'Continue Project' : 'Start Project' }}
                                                    </a>
                                                @endif
                                                @if (strtolower((string) $project['status']) !== 'completed' && ($project['sponsor_type'] ?? 'self') !== 'college' && $project['progress'] === 0)
                                                    @php
                                                        $swapCandidates = [];
                                                        $seenSwapIds    = [];
                                                        foreach ($projectsByLevelCategoryTitle[$level][$category] ?? [] as $altTrackTitle => $altProjects) {
                                                            foreach ($altProjects as $altProject) {
                                                                if (! $altProject['is_enrolled'] && ! in_array($altProject['id'], $seenSwapIds)) {
                                                                    $swapCandidates[] = ['id' => $altProject['id'], 'title' => $altTrackTitle];
                                                                    $seenSwapIds[]    = $altProject['id'];
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    @if (count($swapCandidates) > 0)
                                                        <div x-data="{ swapOpen: false }" class="mt-2">
                                                            <button @click.stop="swapOpen = true" type="button"
                                                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-500 transition hover:border-amber-200 hover:text-amber-700">
                                                                <i class="fi fi-rr-replace text-xs leading-none"></i>
                                                                Change Project
                                                            </button>

                                                            {{-- Swap modal --}}
                                                            <div x-show="swapOpen" x-cloak
                                                                 class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                                                 style="background: rgba(0,0,0,0.5);">
                                                                <div @click.outside="swapOpen = false"
                                                                     class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                                                    <div class="flex items-start justify-between gap-4">
                                                                        <div>
                                                                            <h3 class="text-base font-semibold text-slate-900">Change Project</h3>
                                                                            <p class="mt-0.5 text-sm text-slate-500">Replacing: <span class="font-medium text-slate-800">{{ $project['title'] }}</span></p>
                                                                        </div>
                                                                        <button @click="swapOpen = false" type="button"
                                                                            class="shrink-0 rounded-lg p-1.5 text-slate-400 hover:bg-slate-100 hover:text-slate-600">
                                                                            <i class="fi fi-rr-cross text-xs leading-none"></i>
                                                                        </button>
                                                                    </div>

                                                                    <p class="mt-3 text-sm text-slate-600">Choose a project from the same topic to switch to:</p>

                                                                    <div class="mt-3 max-h-60 space-y-2 overflow-y-auto pr-1">
                                                                        @foreach ($swapCandidates as $candidate)
                                                                            <form method="POST"
                                                                                  action="{{ route('student.projects.swap', ['enrollment' => $project['enrollment_id']]) }}"
                                                                                  onsubmit="return confirm('Switch to &quot;{{ addslashes($candidate['title']) }}&quot;? Your current project will be removed.')">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $candidate['id'] }}">
                                                                                <button type="submit"
                                                                                    class="flex w-full items-center gap-3 rounded-xl border border-slate-200 px-4 py-3 text-left transition hover:border-primary hover:bg-blue-50">
                                                                                    <i class="fi fi-rr-shuffle text-xs text-slate-400 shrink-0"></i>
                                                                                    <span class="text-sm font-medium text-slate-900">{{ $candidate['title'] }}</span>
                                                                                </button>
                                                                            </form>
                                                                        @endforeach
                                                                    </div>

                                                                    <button @click="swapOpen = false" type="button"
                                                                        class="mt-4 flex w-full items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                                                                        Cancel
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @elseif (! $internshipPaid && $studentLevel && ! ($project['level_locked'] ?? false))
                                                <a href="{{ route('student.internship.checkout') }}"
                                                    class="flex w-full items-center justify-center gap-2 rounded-xl border border-primary/30 bg-primary/5 px-4 py-2.5 text-sm font-semibold text-primary transition hover:bg-primary hover:text-white">
                                                    <i class="fi fi-rr-lock text-sm leading-none"></i>
                                                    Pay to Select Projects
                                                </a>
                                            @elseif ($project['can_select'])
                                                <form method="POST" action="{{ $project['select_url'] }}">
                                                    @csrf
                                                    <button type="submit"
                                                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                                                        <i class="fi fi-rr-plus text-sm leading-none"></i>
                                                        Select This Project
                                                    </button>
                                                </form>
                                            @elseif ($project['level_locked'])
                                                <div class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-400 cursor-not-allowed select-none">
                                                    <i class="fi fi-rr-lock text-sm leading-none"></i>
                                                    Not Your Level
                                                </div>
                                            @elseif (! $studentLevel)
                                                <div class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-400 cursor-not-allowed select-none">
                                                    <i class="fi fi-rr-lock text-sm leading-none"></i>
                                                    Level Not Assigned
                                                </div>
                                            @else
                                                <div class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-100 bg-slate-50 px-4 py-2.5 text-sm font-semibold text-slate-400 cursor-not-allowed select-none">
                                                    <i class="fi fi-rr-lock text-sm leading-none"></i>
                                                    Limit Reached (3/3)
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endforeach
        @endforeach
    </section>

</div>
@endsection
