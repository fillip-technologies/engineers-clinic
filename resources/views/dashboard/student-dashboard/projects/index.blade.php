@extends('layouts.frontend-admin')

@section('content')
    @php
        $levels             = $levels ?? ['Beginner', 'Intermediate', 'Advanced'];
        $projectsByLevel    = $projectsByLevel ?? [];
        $totalSelected      = $totalSelected ?? 0;
        $studentLevel       = $studentLevel ?? null;
        $studentStream      = $studentStream ?? null;
        $streamRequired     = $streamRequired ?? false;
        $availableStreams    = $availableStreams ?? [];
        $canSelfAssignLevel = $canSelfAssignLevel ?? false;
        $internshipPaid     = $internshipPaid ?? false;

        $levelMeta = [
            'Beginner' => [
                'icon'   => 'fi fi-rr-seedling',
                'color'  => 'emerald',
                'badge'  => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'border' => 'border-emerald-200',
                'bar'    => 'bg-emerald-500',
                'btn'    => 'bg-emerald-600 hover:bg-emerald-700 text-white',
                'check'  => 'text-emerald-500',
                'desc'   => 'Foundational projects for students new to building real work. Designed to be completed in 45 days.',
            ],
            'Intermediate' => [
                'icon'   => 'fi fi-rr-chart-line-up',
                'color'  => 'blue',
                'badge'  => 'bg-blue-50 text-blue-700 ring-blue-200',
                'border' => 'border-blue-200',
                'bar'    => 'bg-blue-500',
                'btn'    => 'bg-blue-600 hover:bg-blue-700 text-white',
                'check'  => 'text-blue-500',
                'desc'   => 'Industry-level execution projects for students ready for full-stack and multi-feature builds. 75 days.',
            ],
            'Advanced' => [
                'icon'   => 'fi fi-rr-rocket',
                'color'  => 'violet',
                'badge'  => 'bg-violet-50 text-violet-700 ring-violet-200',
                'border' => 'border-violet-200',
                'bar'    => 'bg-violet-500',
                'btn'    => 'bg-violet-600 hover:bg-violet-700 text-white',
                'check'  => 'text-violet-500',
                'desc'   => 'Production-grade projects covering AI, cloud deployment, and real-time systems. 90 days.',
            ],
        ];
    @endphp

    <div class="mx-auto max-w-6xl" x-data="{ activeLevel: '{{ $studentLevel ?? $levels[0] ?? 'Beginner' }}' }">

        {{-- Page header --}}
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Browse Projects</h1>
                    <p class="mt-3 text-base leading-8 text-slate-600">
                        @if ($studentLevel && $studentStream)
                            Pick up to 3 <strong>{{ $studentLevel }}</strong> projects from your <strong>{{ $studentStream }}</strong> topic. Pay once per project to unlock the workspace and start building.
                        @elseif ($studentLevel && ! $studentStream)
                            You're at the <strong>{{ $studentLevel }}</strong> level. Choose your internship topic below to see the right projects for you.
                        @else
                            Your internship level hasn't been assigned yet. Set it from your profile to get started.
                        @endif
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    @if ($studentStream)
                        <div class="flex items-center gap-2 rounded-xl border border-violet-200 bg-violet-50 px-4 py-2.5">
                            <i class="fi fi-rr-bookmark text-xs text-violet-500"></i>
                            <span class="text-sm font-semibold text-violet-800">{{ $studentStream }}</span>
                            <a href="{{ route('dashboard.student.profile') }}" class="text-xs text-violet-500 hover:text-violet-700 hover:underline">Change</a>
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

        {{-- Unassigned level banner --}}
        @if (! $studentLevel)
            @if ($canSelfAssignLevel)
                <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 px-5 py-5">
                    <p class="text-sm font-semibold text-blue-900">Choose your internship level to get started</p>
                    <p class="mt-1 text-sm text-blue-700">Since you're not from a registered college, you can set your own level. Pick the one that best matches your current skill set.</p>
                    <form method="POST" action="{{ route('student.level.set') }}" class="mt-4 flex flex-wrap items-center gap-3">
                        @csrf
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white">
                            <input type="radio" name="level" value="Beginner" class="sr-only" required />
                            <i class="fi fi-rr-seedling text-sm leading-none"></i> Beginner
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white">
                            <input type="radio" name="level" value="Intermediate" class="sr-only" required />
                            <i class="fi fi-rr-chart-line-up text-sm leading-none"></i> Intermediate
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-blue-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-600 has-[:checked]:text-white">
                            <input type="radio" name="level" value="Advanced" class="sr-only" required />
                            <i class="fi fi-rr-rocket text-sm leading-none"></i> Advanced
                        </label>
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
                        <p class="mt-0.5 text-amber-700">Contact your college administrator to get your Beginner / Intermediate / Advanced level set before you can select a project.</p>
                    </div>
                </div>
            @endif
        @endif

        {{-- Topic/stream selection banner --}}
        @if ($streamRequired && $studentLevel)
            <div class="mt-4 rounded-xl border border-violet-200 bg-violet-50 px-5 py-5">
                <p class="text-sm font-semibold text-violet-900">Choose your internship topic to see matching projects</p>
                <p class="mt-1 text-sm text-violet-700">You are at the <strong>{{ $studentLevel }}</strong> level. Pick the domain you want to build your internship projects in — you can change this any time from your profile.</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($availableStreams as $stream)
                        <a href="{{ route('dashboard.student.profile') }}"
                            class="inline-flex items-center gap-2 rounded-xl border border-violet-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-violet-500 hover:bg-violet-600 hover:text-white">
                            {{ $stream }}
                        </a>
                    @endforeach
                </div>
                <p class="mt-3 text-xs text-violet-600">
                    <i class="fi fi-rr-info mr-1"></i>
                    Set your internship topic in <a href="{{ route('dashboard.student.profile') }}" class="font-semibold underline underline-offset-2">My Profile → Edit → Internship Topic</a>.
                </p>
            </div>
        @endif

        {{-- Internship payment gate banner --}}
        @if ($studentLevel && ! $internshipPaid)
            <div class="mt-4 rounded-xl border border-primary/20 bg-primary/5 px-5 py-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">
                            <i class="fi fi-rr-lock mr-1.5 text-primary"></i>
                            Pay for your <strong>{{ $studentLevel }}</strong> internship to select projects and access workspaces
                        </p>
                        <p class="mt-1 text-sm text-slate-600">One-time payment unlocks the full program — choose any 3 projects from your topic and start building.</p>
                    </div>
                    <a href="{{ route('student.internship.pay') }}"
                        class="shrink-0 inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-2.5 text-sm font-bold text-white shadow-sm transition hover:bg-primaryLight">
                        <i class="fi fi-rr-lock-open-alt text-sm leading-none"></i>
                        Pay & Unlock Internship
                    </a>
                </div>
            </div>
        @endif

        {{-- 3-project limit banner (only shown when paid) --}}
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

        {{-- Level tabs --}}
        <div class="mt-6 flex gap-2 overflow-x-auto pb-1">
            @foreach ($levels as $level)
                @php
                    $meta = $levelMeta[$level] ?? $levelMeta['Beginner'];
                    $count = count($projectsByLevel[$level] ?? []);
                    $isLocked = $studentLevel !== null && $studentLevel !== $level;
                @endphp
                <button
                    @click="activeLevel = '{{ $level }}'"
                    :class="activeLevel === '{{ $level }}'
                        ? 'bg-slate-900 text-white shadow-sm'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-400 hover:text-slate-900'"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition">
                    <i class="{{ $meta['icon'] }} text-sm leading-none"></i>
                    {{ $level }}
                    @if ($isLocked)
                        <i class="fi fi-rr-lock text-xs leading-none opacity-60"></i>
                    @endif
                    <span class="ml-0.5 rounded-full bg-white/20 px-1.5 py-0.5 text-xs tabular-nums">{{ $count }}</span>
                </button>
            @endforeach
        </div>

        {{-- Level panels --}}
        @foreach ($levels as $level)
            @php
                $meta     = $levelMeta[$level] ?? $levelMeta['Beginner'];
                $projects = $projectsByLevel[$level] ?? [];
            @endphp

            <div x-show="activeLevel === '{{ $level }}'" x-cloak class="mt-6">

                {{-- Level description --}}
                <div class="mb-5 flex items-center gap-3 rounded-2xl border {{ $meta['border'] }} bg-white px-5 py-4">
                    <i class="{{ $meta['icon'] }} text-xl leading-none text-{{ $meta['color'] }}-600"></i>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $level }} Track</p>
                        <p class="text-sm text-slate-500">{{ $meta['desc'] }}</p>
                    </div>
                </div>

                @if (empty($projects))
                    <div class="rounded-2xl border border-slate-200 bg-white px-6 py-16 text-center shadow-sm">
                        <i class="fi fi-rr-inbox text-4xl text-slate-300"></i>
                        <p class="mt-4 text-sm font-semibold text-slate-900">No {{ $level }} projects yet</p>
                        <p class="mt-1 text-sm text-slate-500">Projects for this level will appear here once they are added by the admin.</p>
                    </div>
                @else
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($projects as $project)
                            <div class="flex flex-col rounded-2xl border {{ $project['is_enrolled'] ? $meta['border'] : 'border-slate-200' }} bg-white p-5 shadow-sm transition hover:shadow-md">

                                {{-- Top row: level badge + duration --}}
                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $meta['badge'] }}">
                                        <i class="{{ $meta['icon'] }} text-[10px] leading-none"></i>
                                        {{ $project['level'] }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $project['duration'] }}</span>
                                </div>

                                {{-- Category --}}
                                <p class="mt-3 text-xs font-medium uppercase tracking-wide text-slate-400">{{ $project['category'] }}</p>

                                {{-- Project title (curriculum item title) --}}
                                <h3 class="mt-1 text-base font-semibold leading-snug text-slate-900">{{ $project['title'] }}</h3>

                                {{-- Assignment description --}}
                                <p class="mt-2 text-sm leading-6 text-slate-500">{{ Str::limit($project['description'], 120) }}</p>

                                {{-- Task list --}}
                                @if (!empty($project['tasks']))
                                    <div class="mt-4 space-y-1.5">
                                        @foreach ($project['tasks'] as $task)
                                            <div class="flex items-start gap-2">
                                                <i class="fi fi-rr-check mt-0.5 shrink-0 text-xs {{ $meta['check'] }}"></i>
                                                <span class="text-xs leading-snug text-slate-600">{{ $task }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Submission format --}}
                                @if (!empty($project['submission']))
                                    <div class="mt-3 flex items-start gap-2 rounded-lg bg-slate-50 px-3 py-2">
                                        <i class="fi fi-rr-file-upload mt-0.5 shrink-0 text-xs text-slate-400"></i>
                                        <span class="text-xs leading-snug text-slate-500"><span class="font-medium text-slate-700">Submit:</span> {{ Str::limit($project['submission'], 80) }}</span>
                                    </div>
                                @endif

                                {{-- Progress bar (enrolled only) --}}
                                @if ($project['is_enrolled'])
                                    @if ($project['workspace_locked'] ?? false)
                                        <div class="mt-4 flex items-center gap-2 rounded-lg border border-amber-100 bg-amber-50 px-3 py-2">
                                            <i class="fi fi-rr-lock text-xs text-amber-400 shrink-0"></i>
                                            <span class="text-xs text-amber-700 font-medium">Pay for internship to unlock workspace</span>
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

                                {{-- Spacer + Action button --}}
                                <div class="mt-5 flex-none">
                                    @if ($project['is_enrolled'])
                                        @if ($project['workspace_locked'] ?? false)
                                            <a href="{{ route('student.internship.pay') }}"
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
                                        @if(strtolower((string) $project['status']) !== 'completed' && ($project['sponsor_type'] ?? 'self') !== 'college')
                                        <form method="POST" action="{{ route('student.projects.remove', ['enrollment' => $project['enrollment_id']]) }}"
                                            class="mt-2"
                                            onsubmit="return confirm('Remove this project? You can select a different one.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-500 transition hover:border-red-200 hover:text-red-600">
                                                <i class="fi fi-rr-replace text-xs leading-none"></i>
                                                Change Project
                                            </button>
                                        </form>
                                        @endif
                                    @elseif (! $internshipPaid && $studentLevel && ! ($project['level_locked'] ?? false))
                                        <a href="{{ route('student.internship.pay') }}"
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

    </div>
@endsection
