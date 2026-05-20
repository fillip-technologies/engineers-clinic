@props([
    'mobile' => false,
])

@php
    $topicConfig = is_file(config_path('internship_topics.php')) ? require config_path('internship_topics.php') : [];

    $menuLevels = collect($topicConfig)
        ->mapWithKeys(function ($levelData, $levelName) {
            $categories = collect($levelData['categories'] ?? [])
                ->map(function ($topics, $category) {
                    return [
                        'label' => $category,
                        'topics' => collect($topics)->map(fn ($topic) => [
                            'label' => $topic,
                            'slug' => \Illuminate\Support\Str::slug($topic),
                        ])->values()->all(),
                    ];
                })
                ->values()
                ->all();

            return [$levelName => [
                'duration' => $levelData['duration'] ?? '',
                'projects' => $levelData['projects'] ?? '',
                'focus' => $levelData['focus'] ?? '',
                'categories' => $categories,
            ]];
        })
        ->all();

    $levels = array_keys($menuLevels);
    $defaultLevel = $levels[0] ?? 'Beginner';
    $trendingGoals = [
        ['label' => 'Core Python & Computational Logic', 'href' => route('course.detail', \Illuminate\Support\Str::slug('Core Python & Computational Logic'))],
        ['label' => 'Data Foundations & Visual Analytics', 'href' => route('course.detail', \Illuminate\Support\Str::slug('Data Foundations & Visual Analytics (Excel/SQL)'))],
        ['label' => 'Full Stack Web Development', 'href' => route('course.detail', \Illuminate\Support\Str::slug('Full Stack Web Development (React/Node)'))],
        ['label' => 'Applied Machine Learning', 'href' => route('course.detail', \Illuminate\Support\Str::slug('Applied Machine Learning & Data Modeling'))],
    ];
@endphp

@if ($mobile)
<div x-data="{ level: '{{ $defaultLevel }}', levels: {{ json_encode($menuLevels) }} }">
    <div class="mb-4 rounded-card border border-borderLight bg-white p-4">
        <p class="flex items-center gap-2 text-sm font-bold text-textPrimary">
            Trending goals
            <i class="fi fi-rr-target text-xs text-brand"></i>
        </p>
        <div class="mt-3 flex flex-wrap gap-2">
            @foreach ($trendingGoals as $goal)
                <a href="{{ $goal['href'] }}"
                    class="inline-flex min-h-9 items-center rounded-control border border-borderLight bg-bgWhite px-3 py-1.5 text-xs font-semibold text-textPrimary transition hover:border-brand hover:bg-brandSoft hover:text-brand">
                    {{ $goal['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-3 gap-2">
        <template x-for="lvl in Object.keys(levels)" :key="lvl">
            <button type="button"
                @click="level = lvl"
                class="rounded-control px-3 py-2 text-xs font-semibold transition"
                :class="level === lvl ? 'bg-brand text-white' : 'bg-bgSoft text-textSecondary hover:bg-brandSoft hover:text-brand'"
                x-text="lvl">
            </button>
        </template>
    </div>

    <div class="mt-4 rounded-card border border-borderLight bg-bgSoft p-4">
        <p class="text-sm font-bold text-textPrimary" x-text="level + ' Tier'"></p>
        <p class="text-caption mt-1" x-text="levels[level].duration + ' | ' + levels[level].projects"></p>
    </div>

    <div class="mt-4 max-h-[28rem] space-y-4 overflow-y-auto pr-1">
        <template x-for="category in levels[level].categories" :key="category.label">
            <div class="rounded-card border border-borderLight bg-white p-4">
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-brand" x-text="category.label"></p>
                <div class="mt-3 space-y-2">
                    <template x-for="program in category.topics" :key="program.slug">
                        <a :href="'/course/' + program.slug"
                            class="flex items-start gap-2 rounded-control px-3 py-2 text-sm font-semibold text-textSecondary transition hover:bg-brandSoft hover:text-brand">
                            <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                            <span x-text="program.label"></span>
                        </a>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
@else
<div
    class="relative"
    x-data="{ open: false, level: '{{ $defaultLevel }}', levels: {{ json_encode($menuLevels) }} }"
    @mouseenter="open = true"
    @mouseleave="open = false"
>
    <button class="nav-link" :class="open ? 'nav-link-active' : ''">
        Internships
        <svg class="ml-1 inline h-3 w-3 transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-[0.98]"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-[0.98]"
        class="dropdown-panel fixed left-1/2 top-24 z-50 w-[min(1120px,calc(100vw-2rem))] -translate-x-1/2 overflow-hidden p-0"
    >
        <div class="grid max-h-[calc(100vh-7rem)] grid-cols-[250px_1fr] overflow-hidden">
            <aside class="border-r border-borderLight bg-gradient-to-b from-bgSoft to-white p-5">
                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-brand">Choose your level</p>

                <div class="mt-4 space-y-2">
                    <template x-for="lvl in Object.keys(levels)" :key="lvl">
                        <button
                            type="button"
                            @click="level = lvl"
                            class="group w-full rounded-card border p-4 text-left transition"
                            :class="level === lvl ? 'border-brand bg-white shadow-card' : 'border-transparent hover:border-borderLight hover:bg-white/80'"
                        >
                            <span class="flex items-center justify-between gap-3">
                                <span class="text-sm font-black text-textPrimary" x-text="lvl + ' Tier'"></span>
                                <span class="grid h-7 w-7 place-items-center rounded-full transition"
                                    :class="level === lvl ? 'bg-brand text-white' : 'bg-bgSoft text-brand group-hover:bg-brandSoft'">
                                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6">
                                        <path d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            </span>
                            <span class="mt-2 block text-xs font-semibold text-textMuted" x-text="levels[lvl].duration"></span>
                        </button>
                    </template>
                </div>

                <div class="mt-5 rounded-card bg-brandDark p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.14em] text-brandLight" x-text="level + ' path'"></p>
                    <p class="mt-2 text-sm font-bold leading-5 text-white" x-text="levels[level].projects"></p>
                    <p class="mt-3 text-xs leading-5 text-white/70" x-text="levels[level].focus"></p>
                </div>
            </aside>

            <div class="bg-white p-5">
                <div class="border-b border-borderLight pb-4">
                    <div class="mb-4">
                        <p class="flex items-center gap-2 text-sm font-black text-textPrimary">
                            Trending goals
                            <i class="fi fi-rr-target text-xs text-brand"></i>
                        </p>
                        <div class="mt-3 flex flex-wrap gap-2.5">
                            @foreach ($trendingGoals as $goal)
                                <a href="{{ $goal['href'] }}"
                                    class="inline-flex min-h-10 items-center rounded-control border border-borderLight bg-bgWhite px-3.5 py-2 text-xs font-bold text-textPrimary shadow-sm transition hover:border-brand hover:bg-brandSoft hover:text-brand">
                                    {{ $goal['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-6">
                        <div>
                            <p class="text-sm font-black text-textPrimary" x-text="level + ' Internship Topics'"></p>
                            <p class="text-caption mt-1">Pick a focused track and open the full course page.</p>
                        </div>

                        <a href="#courses" class="rounded-full bg-secondarySoft px-4 py-2 text-xs font-black text-textPrimary transition hover:bg-secondary">
                            View modules
                        </a>
                    </div>
                </div>

                <div class="mt-4 grid max-h-[calc(100vh-19rem)] grid-cols-1 gap-3 overflow-y-auto pr-1 xl:grid-cols-2">
                    <template x-for="category in levels[level].categories" :key="category.label">
                        <section class="rounded-card border border-borderLight bg-bgSoft/45 p-4 transition hover:border-brand/30 hover:bg-white hover:shadow-card">
                            <div class="flex items-start gap-3">
                                <span class="grid h-9 w-9 shrink-0 place-items-center rounded-control bg-white text-brand shadow-sm">
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                                        <path d="M4 19.5V5a2 2 0 012-2h12a2 2 0 012 2v14.5" />
                                        <path d="M4 19.5A2.5 2.5 0 016.5 17H20" />
                                        <path d="M8 7h8M8 11h6" />
                                    </svg>
                                </span>

                                <div>
                                    <p class="text-[11px] font-black uppercase leading-4 tracking-[0.12em] text-brand" x-text="category.label"></p>
                                    <p class="text-caption mt-1" x-text="category.topics.length + ' focused tracks'"></p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-1.5">
                                <template x-for="program in category.topics" :key="program.slug">
                                    <a :href="'/course/' + program.slug"
                                        class="group/link flex items-start gap-2 rounded-control px-2.5 py-2 text-[13px] font-bold leading-snug text-textSecondary transition hover:bg-brandSoft hover:text-brand">
                                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand/55 transition group-hover/link:bg-brand"></span>
                                        <span class="flex-1" x-text="program.label"></span>
                                    </a>
                                </template>
                            </div>
                        </section>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
