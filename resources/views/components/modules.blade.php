@php
$topicConfig = is_file(config_path('internship_topics.php')) ? require config_path('internship_topics.php') : [];
$internshipLevels = collect($topicConfig)
->mapWithKeys(function ($levelData, $levelName) {
$categories = collect($levelData['categories'] ?? [])
->map(function ($topics, $categoryName) {
return [
'name' => $categoryName,
'slug' => \Illuminate\Support\Str::slug($categoryName),
'topics' => collect($topics)->map(fn ($topic) => [
'title' => $topic,
'slug' => \Illuminate\Support\Str::slug($topic),
])->values()->all(),
];
})
->values()
->all();

return [$levelName . ' Level' => [
'duration' => $levelData['duration'] ?? null,
'projects' => $levelData['projects'] ?? null,
'focus' => $levelData['focus'] ?? null,
'categories' => $categories,
'topic_count' => collect($categories)->sum(fn ($category) => count($category['topics'] ?? [])),
'first_topic' => collect($categories)->pluck('topics')->flatten(1)->first(),
]];
})
->all();

$levelConfig = [
'Beginner Level' => ['number' => '01', 'label' => 'Beginner', 'tone' => 'from-[#FFFFFF] to-[#F5F3FF]', 'chip' => 'bg-[#F5F3FF] text-[#6D5DF6]', 'cta' => 'bg-[#6D5DF6] hover:bg-[#5A4AE3]'],
'Intermediate Level' => ['number' => '02', 'label' => 'Intermediate', 'tone' => 'from-[#FFFFFF] to-[#F5F3FF]', 'chip' => 'bg-[#F5F3FF] text-[#6D5DF6]', 'cta' => 'bg-[#6D5DF6] hover:bg-[#5A4AE3]'],
'Advanced Level' => ['number' => '03', 'label' => 'Advanced', 'tone' => 'from-[#FFFFFF] to-[#F5F3FF]', 'chip' => 'bg-[#F5F3FF] text-[#6D5DF6]', 'cta' => 'bg-[#6D5DF6] hover:bg-[#5A4AE3]'],
];

$projectGradients = [
['from' => '#6D5DF6', 'via' => '#A855F7', 'to' => '#22C997'],
['from' => '#2563EB', 'via' => '#6D5DF6', 'to' => '#A855F7'],
['from' => '#0EA5E9', 'via' => '#22C997', 'to' => '#6D5DF6'],
['from' => '#7C3AED', 'via' => '#EC4899', 'to' => '#F59E0B'],
['from' => '#14B8A6', 'via' => '#3B82F6', 'to' => '#8B5CF6'],
['from' => '#6366F1', 'via' => '#A855F7', 'to' => '#F97316'],
];
@endphp

<style>
    .ec-project-art {
        background:
            radial-gradient(circle at 18% 16%, rgba(255, 255, 255, 0.38), transparent 24%),
            radial-gradient(circle at 82% 18%, rgba(255, 255, 255, 0.24), transparent 26%),
            linear-gradient(135deg, var(--project-from), var(--project-via) 48%, var(--project-to));
    }

    .ec-project-art::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.13) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.13) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: linear-gradient(to bottom, black, transparent 88%);
        opacity: 0.7;
        transition: transform 500ms ease;
    }

    .ec-project-art::after {
        content: "";
        position: absolute;
        inset: auto -18% -42% 18%;
        height: 68%;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        filter: blur(34px);
        transform: rotate(-10deg);
    }

    .group:hover .ec-project-art::before {
        transform: translate3d(18px, 18px, 0);
    }
</style>

<section id="courses" class="relative isolate overflow-hidden bg-[#FAFBFF] py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute left-10 top-24 h-80 w-80 rounded-full bg-[#6D5DF6]/10 blur-3xl"></div>
        <div class="absolute bottom-20 right-10 h-80 w-80 rounded-full bg-[#A855F7]/10 blur-3xl"></div>
    </div>

    <div class="mx-auto w-full max-w-[1500px] px-4 sm:px-6 lg:px-10">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-[#ECEBFF] bg-white px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6] shadow-[0_12px_30px_rgba(109,93,246,0.08)]">
                Level-Based Project Tracks
            </span>
            <h2 class="mt-5 text-3xl font-black leading-tight tracking-tight text-[#161326] sm:text-4xl lg:text-6xl">
                Pick a project that matches your level.
            </h2>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-[#6B7280]">
                Every track is designed as a practical build: milestones, GitHub submission, review, and a verified certificate.
            </p>
        </div>

        <div class="space-y-8">
            @foreach ($internshipLevels as $levelName => $level)
                @php $cfg = $levelConfig[$levelName] ?? $levelConfig['Beginner Level']; @endphp

                <div x-data="{ activeCategory: '{{ $level['categories'][0]['slug'] ?? '' }}', expanded: false }" class="overflow-hidden rounded-[2rem] border border-[#ECEBFF] bg-white/85 p-4 shadow-[0_24px_70px_rgba(15,10,42,0.08)] backdrop-blur-2xl transition duration-300 hover:border-[#6D5DF6] hover:bg-[#FCFBFF] sm:p-6">
                    <div class="grid gap-5 rounded-[1.5rem] border border-[#ECEBFF] bg-gradient-to-br {{ $cfg['tone'] }} p-6 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <span class="inline-flex rounded-full bg-[#F5F3FF] px-3 py-1.5 text-xs font-black uppercase tracking-[0.14em] text-[#6D5DF6]">{{ $cfg['number'] }} / {{ $cfg['label'] }} Level</span>
                            <h3 class="mt-4 text-2xl font-black text-[#161326] sm:text-3xl">{{ $cfg['label'] }} project workspace</h3>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-[#6B7280]">
                                {{ $level['focus'] ?? 'Choose a domain project, complete milestone tasks, and build proof of work.' }}
                            </p>
                        </div>
                        <div class="grid grid-cols-3 gap-3 sm:min-w-[28rem]">
                            @foreach ([
                                ['value' => $level['topic_count'], 'label' => 'Topics'],
                                ['value' => $level['projects'], 'label' => 'Projects'],
                                ['value' => $level['duration'], 'label' => 'Duration'],
                            ] as $meta)
                                <div class="rounded-2xl border border-[#ECEBFF] bg-white p-4 backdrop-blur-xl">
                                    <p class="text-xl font-black text-[#161326]">{{ $meta['value'] }}</p>
                                    <p class="mt-1 text-xs font-bold text-[#8A8FA3]">{{ $meta['label'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 overflow-x-auto pb-2 [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                        @foreach ($level['categories'] as $category)
                            <button type="button"
                                data-category="{{ $category['slug'] }}"
                                @click="activeCategory = '{{ $category['slug'] }}'; expanded = false; $nextTick(() => $root.querySelector('[data-category-panel=&quot;{{ $category['slug'] }}&quot;] [data-topic-swiper]')?.swiper?.update())"
                                :class="activeCategory === '{{ $category['slug'] }}' ? 'border-[#6D5DF6] bg-[#F5F3FF] text-[#161326] shadow-[0_14px_34px_rgba(109,93,246,0.12)]' : 'border-[#ECEBFF] bg-[#FAFBFF] text-[#161326] hover:border-[#6D5DF6] hover:bg-[#FCFBFF]'"
                                class="shrink-0 rounded-full border px-5 py-3 text-sm font-black transition duration-300">
                                {{ $category['name'] }}
                            </button>
                        @endforeach
                    </div>

                    @foreach ($level['categories'] as $category)
                        <div x-show="activeCategory === '{{ $category['slug'] }}'" x-cloak data-category-panel="{{ $category['slug'] }}" class="mt-4">
                            <div class="swiper w-full overflow-hidden py-2" data-topic-swiper x-init="$nextTick(() => {
                                const initTopicSwiper = () => {
                                    if (!window.Swiper || $el.swiper) return;
                                    new Swiper($el, {
                                        slidesPerView: 1,
                                        spaceBetween: 18,
                                        observer: true,
                                        observeParents: true,
                                        navigation: { nextEl: $el.querySelector('[data-swiper-next]'), prevEl: $el.querySelector('[data-swiper-prev]') },
                                        breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 }, 1280: { slidesPerView: 4 } }
                                    });
                                };
                                if (window.Swiper) { initTopicSwiper(); return; }
                                if (!document.querySelector('link[data-topic-swiper-css]')) {
                                    const swiperCss = document.createElement('link');
                                    swiperCss.rel = 'stylesheet';
                                    swiperCss.href = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css';
                                    swiperCss.dataset.topicSwiperCss = 'true';
                                    document.head.appendChild(swiperCss);
                                }
                                window.topicSwiperReady = window.topicSwiperReady || new Promise((resolve) => {
                                    const swiperScript = document.createElement('script');
                                    swiperScript.src = 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js';
                                    swiperScript.onload = resolve;
                                    document.head.appendChild(swiperScript);
                                });
                                window.topicSwiperReady.then(initTopicSwiper);
                            })">
                                <div class="mb-5 flex justify-end gap-3">
                                    <button type="button" data-swiper-prev class="grid h-11 w-11 place-items-center rounded-full border border-[#ECEBFF] bg-white text-[#161326] shadow-[0_12px_26px_rgba(15,10,42,0.08)] transition hover:-translate-y-0.5 hover:text-[#6D5DF6]" aria-label="Previous projects">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M15 18l-6-6 6-6" /></svg>
                                    </button>
                                    <button type="button" data-swiper-next class="grid h-11 w-11 place-items-center rounded-full border border-[#ECEBFF] bg-white text-[#161326] shadow-[0_12px_26px_rgba(15,10,42,0.08)] transition hover:-translate-y-0.5 hover:text-[#6D5DF6]" aria-label="Next projects">
                                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 6l6 6-6 6" /></svg>
                                    </button>
                                </div>

                                <div class="swiper-wrapper flex">
                                    @foreach ($category['topics'] as $program)
                                        @php
                                            $gradient = $projectGradients[$loop->index % count($projectGradients)];
                                            $imagePath = $program['image'] ?? null;
                                            if (!$imagePath) {
                                                foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
                                                    $candidatePath = 'images/internships/' . $program['slug'] . '.' . $extension;
                                                    if (file_exists(public_path($candidatePath))) {
                                                        $imagePath = $candidatePath;
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="swiper-slide h-auto shrink-0 !w-full md:!w-[calc((100%-1.375rem)/2)] lg:!w-[calc((100%-2.75rem)/3)] xl:!w-[calc((100%-4.125rem)/4)]" @if ($loop->index >= 10) x-cloak x-show="expanded" x-transition @endif>
                                            <a href="{{ route('course.detail', $program['slug']) }}" class="group flex h-full min-h-[27rem] flex-col overflow-hidden rounded-[1.5rem] border border-[#ECEBFF] bg-white text-[#161326] shadow-[0_16px_40px_rgba(15,10,42,0.08)] transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_24px_60px_rgba(109,93,246,0.16)]">
                                                <span class="relative block aspect-[16/10] overflow-hidden bg-gradient-to-br from-[#ECEBFF] via-white to-[#EDE9FE]">
                                                    @if($imagePath)
                                                        <img src="{{ asset($imagePath) }}" alt="{{ $program['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                                    @else
                                                        <span class="ec-project-art absolute inset-0" style="--project-from: {{ $gradient['from'] }}; --project-via: {{ $gradient['via'] }}; --project-to: {{ $gradient['to'] }};"></span>
                                                        <span class="absolute inset-x-5 bottom-5 z-10">
                                                            <span class="block text-xs font-black uppercase tracking-[0.16em]" style="color: rgba(255, 255, 255, 0.75);">{{ $category['name'] }}</span>
                                                            <span class="mt-2 block text-2xl font-black leading-tight text-white" style="text-shadow: 0 8px 18px rgba(15, 10, 42, 0.22);">{{ $program['title'] }}</span>
                                                        </span>
                                                    @endif
                                                    <span class="absolute left-5 top-5 z-10 inline-flex rounded-full bg-white/90 px-3 py-1.5 text-xs font-black text-[#6D5DF6] shadow-[0_10px_24px_rgba(15,10,42,0.10)] backdrop-blur">{{ $cfg['label'] }}</span>
                                                </span>

                                                <span class="flex flex-1 flex-col p-5">
                                                    <span class="line-clamp-2 text-xl font-black leading-snug text-[#161326]">{{ $program['title'] }}</span>
                                                    <span class="mt-3 text-sm font-medium leading-6 text-[#6B7280]">Project track in {{ $category['name'] }}</span>

                                                    <span class="mt-5 grid gap-2 text-sm font-bold text-[#6B7280]">
                                                        <span class="inline-flex items-center gap-2"><i class="fi fi-rr-list-check text-[#6D5DF6]"></i> Milestone tasks</span>
                                                        <span class="inline-flex items-center gap-2"><i class="fi fi-rr-code-branch text-[#6D5DF6]"></i> GitHub submission</span>
                                                        <span class="inline-flex items-center gap-2"><i class="fi fi-rr-badge-check text-[#22C55E]"></i> Certificate eligible</span>
                                                    </span>

                                                    <span class="mt-auto pt-6">
                                                        <span class="inline-flex w-full items-center justify-center gap-2 rounded-2xl {{ $cfg['cta'] }} px-5 py-3 text-sm font-black text-white transition">
                                                            View Project
                                                        </span>
                                                    </span>
                                                </span>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if (count($category['topics']) > 10)
                                <button type="button" class="mx-auto mt-4 inline-flex items-center justify-center gap-2 rounded-full border border-[#D9D6FF] bg-white px-5 py-3 text-sm font-black text-[#161326] shadow-[0_12px_30px_rgba(15,10,42,0.08)] transition hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#F5F3FF] hover:text-[#161326]" @click="expanded = !expanded; $nextTick(() => $el.closest('[data-category-panel]')?.querySelector('[data-topic-swiper]')?.swiper?.update())">
                                    <span x-text="expanded ? 'Show less' : 'View more projects'"></span>
                                    <svg class="h-4 w-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 9l6 6 6-6" /></svg>
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
</section>
