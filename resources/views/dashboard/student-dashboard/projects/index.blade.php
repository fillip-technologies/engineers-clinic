@extends('layouts.frontend-admin')

@section('content')
    @php
        $levels = $levels ?? ['Beginner', 'Intermediate', 'Advanced'];
        $projectsByLevel = $projectsByLevel ?? [];

        $levelMeta = [
            'Beginner' => [
                'icon'    => 'fi fi-rr-seedling',
                'color'   => 'emerald',
                'badge'   => 'bg-emerald-50 text-emerald-700 ring-emerald-200',
                'border'  => 'border-emerald-200',
                'btn'     => 'bg-emerald-600 hover:bg-emerald-700 text-white',
                'desc'    => 'Start here if you are new to building projects. These are designed to be completed in about a month.',
            ],
            'Intermediate' => [
                'icon'    => 'fi fi-rr-chart-line-up',
                'color'   => 'blue',
                'badge'   => 'bg-blue-50 text-blue-700 ring-blue-200',
                'border'  => 'border-blue-200',
                'btn'     => 'bg-blue-600 hover:bg-blue-700 text-white',
                'desc'    => 'For students comfortable with the basics who are ready for full-stack and multi-feature builds.',
            ],
            'Advanced' => [
                'icon'    => 'fi fi-rr-rocket',
                'color'   => 'violet',
                'badge'   => 'bg-violet-50 text-violet-700 ring-violet-200',
                'border'  => 'border-violet-200',
                'btn'     => 'bg-violet-600 hover:bg-violet-700 text-white',
                'desc'    => 'Production-grade projects covering real-time systems, AI integration, and cloud deployment.',
            ],
        ];

        $totalEnrolled = collect($projectsByLevel)->flatten(1)->where('is_enrolled', true)->count();
    @endphp

    <div class="mx-auto max-w-6xl" x-data="{ activeLevel: '{{ $levels[0] ?? 'Beginner' }}' }">

        {{-- Page header --}}
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">Browse Projects</h1>
                    <p class="mt-3 text-base leading-8 text-slate-600">
                        Choose a project that matches your current skill level. Each project comes with a guided workspace and step-by-step checkpoints.
                    </p>
                </div>

                @if ($totalEnrolled > 0)
                    <a href="{{ route('dashboard.enrolled-courses') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-700">
                        <i class="fi fi-rr-book-alt text-sm leading-none"></i>
                        My Projects ({{ $totalEnrolled }})
                    </a>
                @endif
            </div>
        </section>

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
                @php $meta = $levelMeta[$level] ?? $levelMeta['Beginner']; @endphp
                <button
                    @click="activeLevel = '{{ $level }}'"
                    :class="activeLevel === '{{ $level }}'
                        ? 'bg-slate-900 text-white shadow-sm'
                        : 'bg-white text-slate-600 border border-slate-200 hover:border-slate-400 hover:text-slate-900'"
                    class="inline-flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold transition">
                    <i class="{{ $meta['icon'] }} text-sm leading-none"></i>
                    {{ $level }}
                    @php $count = count($projectsByLevel[$level] ?? []); @endphp
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

                {{-- Level description banner --}}
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
                        <p class="mt-1 text-sm text-slate-500">Projects for this level will appear here once they are added.</p>
                    </div>
                @else
                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
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

                                {{-- Title & description --}}
                                <h3 class="mt-4 text-base font-semibold leading-snug text-slate-900">{{ $project['title'] }}</h3>
                                <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">{{ Str::limit($project['description'], 110) }}</p>

                                {{-- Progress bar (if enrolled) --}}
                                @if ($project['is_enrolled'])
                                    <div class="mt-4">
                                        <div class="flex justify-between text-xs">
                                            <span class="font-medium text-slate-600">Progress</span>
                                            <span class="font-semibold text-slate-900">{{ $project['progress'] }}%</span>
                                        </div>
                                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-{{ $meta['color'] }}-500 transition-all"
                                                style="width: {{ $project['progress'] }}%"></div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Action button --}}
                                <div class="mt-5">
                                    @if ($project['is_enrolled'])
                                        <a href="{{ $project['workspace_url'] }}"
                                            class="flex w-full items-center justify-center gap-2 rounded-xl {{ $meta['btn'] }} px-4 py-2.5 text-sm font-semibold shadow-sm transition">
                                            <i class="fi fi-rr-play text-sm leading-none"></i>
                                            Continue Project
                                        </a>
                                    @else
                                        <form method="POST" action="{{ $project['select_url'] }}">
                                            @csrf
                                            <button type="submit"
                                                class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-900 hover:bg-slate-900 hover:text-white">
                                                <i class="fi fi-rr-plus text-sm leading-none"></i>
                                                Select Project
                                            </button>
                                        </form>
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
