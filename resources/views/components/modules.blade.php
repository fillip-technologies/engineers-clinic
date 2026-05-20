@php
$topicConfig = is_file(config_path('internship_topics.php')) ? require config_path('internship_topics.php') : [];
$internshipLevels = collect($topicConfig)
    ->mapWithKeys(function ($levelData, $levelName) {
        $programs = collect($levelData['categories'] ?? [])
            ->flatMap(function ($topics) {
                return collect($topics)->map(fn ($topic) => [
                    'label' => $topic,
                    'slug' => \Illuminate\Support\Str::slug($topic),
                ]);
            })
            ->values()
            ->all();

        return [$levelName . ' Level' => $programs];
    })
    ->all();

$levelConfig = [
'Beginner Level' => [
'number' => '01',
'label' => 'Beginner',
'sublabel' => 'Foundation Tracks',
'description' => 'Start your journey with structured, hands-on internships covering design, tech, and civil fundamentals.',
'exp' => '0–1 yr exp',
'card_class' => 'lc-beginner',
'badge_class' => 'badge-b',
'icon_class' => 'icon-b',
'enroll_class'=> 'enroll-b',
'stat_class' => 'stat-b',
'dot_class' => 'dot-b',
'mc_class' => 'mb',
'dot_fill' => '#9c88ed',
'icon_stroke' => '#7C5CFC',
'icon_path' => '
<path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />',
'div_class' => 'div-b',
'title_class' => 'lc-title-light',
'desc_class' => 'lc-desc-light',
],
'Intermediate Level' => [
'number' => '02',
'label' => 'Intermediate',
'sublabel' => 'Specialist Tracks',
'description' => 'Advance with cloud, ML, ethical hacking, structural design and corporate-facing project work.',
'exp' => '1–2 yr exp',
'card_class' => 'lc-intermediate',
'badge_class' => 'badge-i',
'icon_class' => 'icon-i',
'enroll_class'=> 'enroll-i',
'stat_class' => 'stat-i',
'dot_class' => 'dot-i',
'mc_class' => 'mi',
'dot_fill' => '#F5C842',
'icon_stroke' => '#8B6B00',
'icon_path' => '
<path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z" />',
'div_class' => 'div-i',
'title_class' => 'lc-title-light',
'desc_class' => 'lc-desc-light',
],
'Advanced Level' => [
'number' => '03',
'label' => 'Advanced',
'sublabel' => 'Engineering Tracks',
'description' => 'Master GenAI, blockchain, robotics, BIM infrastructure, geotechnics and digital law at an expert level.',
'exp' => '2+ yr exp',
'card_class' => 'lc-advanced',
'badge_class' => 'badge-a',
'icon_class' => 'icon-a',
'enroll_class'=> 'enroll-a',
'stat_class' => 'stat-a',
'dot_class' => 'dot-a',
'mc_class' => 'ma',
'dot_fill' => '#160840',
'icon_stroke' => '#DDD0FF',
'icon_path' => '
<path d="M12 2v20M2 12h20M4.93 4.93l14.14 14.14M19.07 4.93L4.93 19.07" />',
'div_class' => 'div-a',
'title_class' => 'lc-title-dark',
'desc_class' => 'lc-desc-dark',
],
];
@endphp

{{-- ============================================================
     INTERNSHIP TRACKS SECTION
     Uses: $internshipLevels + $levelConfig (defined above)
     Colors: existing master Tailwind config (brand, secondary, etc.)
     ============================================================ --}}

<section class="relative overflow-hidden py-16 md:py-24 lg:py-28">

    {{-- Background glow blobs --}}
    <div class="pointer-events-none absolute -left-24 -top-24 h-80 w-80 rounded-full bg-glowPurple blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-20 -right-20 h-72 w-72 rounded-full bg-glowBlue blur-3xl"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- Section Header --}}
        <div class="mb-10 text-center sm:mb-14">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-brand/20 bg-brandSoft px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-brand">
                <span class="h-1.5 w-1.5 rounded-full bg-brand opacity-70"></span>
                Internship Tracks
            </div>
            <h2 class="text-3xl font-bold leading-tight text-textPrimary sm:text-4xl md:text-5xl lg:text-6xl">
                Explore internship tracks
                <span class="bg-gradient-to-r from-brand via-brandLight to-secondary bg-clip-text text-transparent">
                    built around real skills.
                </span>
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-textSecondary opacity-70 md:text-lg">
Choose from practical learning tracks designed for different engineering domains and career paths.            </p>
        </div>

        {{-- 3-Column Grid --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

            @foreach ($internshipLevels as $levelName => $programs)
            @php $cfg = $levelConfig[$levelName]; @endphp

            <div class="flex flex-col gap-4" x-data="{ expanded: false }">

                {{-- ── Level Card ── --}}
                <div class="lc-card relative overflow-hidden  border p-6
                    @if($cfg['card_class'] === 'lc-beginner')
                        border-brand/20 bg-gradient-to-br from-white/90 to-brandSoft/40 shadow-[0_4px_24px_rgba(124,92,252,0.09)]
                    @elseif($cfg['card_class'] === 'lc-intermediate')
                        border-secondary/30 bg-gradient-to-br from-white/90 to-secondarySoft/40 shadow-[0_4px_24px_rgba(245,200,66,0.09)]
                    @else
                        border-brandLight/30 bg-gradient-to-br from-brandDark to-textSecondary shadow-[0_4px_28px_rgba(22,8,64,0.22)]
                    @endif
                ">
                    {{-- Glow blob inside card --}}
                    <div class="pointer-events-none absolute -right-8 -top-8 h-28 w-28 rounded-full
                        @if($cfg['card_class'] === 'lc-beginner') bg-brand/10
                        @elseif($cfg['card_class'] === 'lc-intermediate') bg-secondary/12
                        @else bg-brandLight/12
                        @endif
                        blur-2xl">
                    </div>

                    {{-- Badge --}}
                    <div class="relative mb-4 inline-flex items-center gap-1.5  border px-3 py-1 text-[10px] font-bold uppercase tracking-widest
                        @if($cfg['badge_class'] === 'badge-b') border-brand/25 bg-brandSoft text-brand
                        @elseif($cfg['badge_class'] === 'badge-i') border-secondary/32 bg-secondarySoft text-[#7A5C00]
                        @else border-brandLight/32 bg-brandLight/15 text-auroraLeft
                        @endif
                    ">
                        <svg width="6" height="6" viewBox="0 0 6 6" fill="{{ $cfg['dot_fill'] }}">
                            <circle cx="3" cy="3" r="3" />
                        </svg>
                        Level {{ $cfg['number'] }} · {{ $cfg['label'] }}
                    </div>

                    {{-- Icon --}}
                    <div class="relative mb-4 flex h-11 w-11 items-center justify-center rounded-[13px]
                        @if($cfg['icon_class'] === 'icon-b') bg-brandSoft
                        @elseif($cfg['icon_class'] === 'icon-i') bg-secondarySoft
                        @else bg-brandLight/15
                        @endif
                    ">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
                            stroke="{{ $cfg['icon_stroke'] }}" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            {!! $cfg['icon_path'] !!}
                        </svg>
                    </div>

                    {{-- Title --}}
                    <h3 class="relative mb-2 text-lg font-extrabold leading-snug
                        @if($cfg['title_class'] === 'lc-title-light') text-textPrimary
                        @else text-white
                        @endif
                    ">
                        {{ $cfg['sublabel'] }}
                    </h3>

                    {{-- Description --}}
                    <p class="relative mb-5 text-[12.5px] leading-relaxed
                        @if($cfg['desc_class'] === 'lc-desc-light') text-textSecondary/65
                        @else text-auroraLeft/80
                        @endif
                    ">
                        {{ $cfg['description'] }}
                    </p>

                    {{-- Divider --}}
                    <div class="mb-4 h-px w-full
                        @if($cfg['div_class'] === 'div-b') bg-brand/12
                        @elseif($cfg['div_class'] === 'div-i') bg-secondary/16
                        @else bg-brandLight/16
                        @endif
                    "></div>

                    {{-- Footer: stat + button --}}
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-1.5 text-[11px]
                            @if($cfg['stat_class'] === 'stat-b') text-textMuted
                            @elseif($cfg['stat_class'] === 'stat-i') text-[#9B8020]
                            @else text-brandLight
                            @endif
                        ">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                            {{ count($programs) }} Modules · {{ $cfg['exp'] }}
                        </div>
                        <a href="{{ route('course.detail', $programs[0]['slug']) }}"
                            class="inline-flex items-center justify-center rounded-[10px] px-4 py-2 text-[11.5px] font-bold transition-transform duration-200 hover:scale-105 active:scale-95 sm:self-auto
                            @if($cfg['enroll_class'] === 'enroll-b')
                                bg-gradient-to-r from-brand to-brandLight text-white shadow-md
                            @elseif($cfg['enroll_class'] === 'enroll-i')
                                bg-gradient-to-r from-secondary to-[#e0b41c] text-textPrimary shadow-md
                            @else
                                border border-brandLight/38 bg-white/15 text-auroraLeft
                            @endif
                        ">
                            Enroll Now
                        </a>
                    </div>
                </div>

                {{-- ── Divider Label ── --}}
                <div class="flex items-center gap-2 px-0.5 text-[10px] font-bold uppercase tracking-widest text-textMuted">
                    <span class="h-px flex-1 bg-borderLight"></span>
                    Modules
                    <span class="h-px flex-1 bg-borderLight"></span>
                </div>

                {{-- ── Module Cards ── --}}
                <div class="flex flex-col gap-1.5">
                    @foreach ($programs as $program)
                    <a href="{{ route('course.detail', $program['slug']) }}"
                        @if ($loop->index >= 10)
                            x-cloak
                            x-show="expanded"
                            x-transition
                        @endif
                        class="group flex min-h-[4.5rem] items-center gap-2.5 border bg-white/82 px-3.5 py-2.5 text-sm font-normal text-textPrimary no-underline sm:text-[16px]
                        transition-all duration-200 hover:-translate-y-px hover:translate-x-1
                        @if($cfg['mc_class'] === 'mb')
                            border-brand/13 shadow-[0_2px_8px_rgba(124,92,252,0.05)]
                            hover:border-brand/30 hover:bg-brandSoft/50 hover:shadow-[0_5px_18px_rgba(124,92,252,0.11)]
                        @elseif($cfg['mc_class'] === 'mi')
                            border-secondary/18 shadow-[0_2px_8px_rgba(245,200,66,0.05)]
                            hover:border-secondary/36 hover:bg-secondarySoft/50 hover:shadow-[0_5px_18px_rgba(245,200,66,0.10)]
                        @else
                            border-textPrimary/11 shadow-[0_2px_8px_rgba(22,8,64,0.04)]
                            hover:border-textPrimary/22 hover:bg-textPrimary/[0.03] hover:shadow-[0_5px_18px_rgba(22,8,64,0.08)]
                        @endif
                    ">
                        {{-- Dot --}}
                        <span class="h-[7px] w-[7px] flex-shrink-0 rounded-full
                            @if($cfg['dot_class'] === 'dot-b') bg-brand
                            @elseif($cfg['dot_class'] === 'dot-i') bg-secondary
                            @else bg-textPrimary
                            @endif
                        "></span>

                        {{-- Label --}}
                        <span class="flex-1 leading-snug">{{ $program['label'] }}</span>

                        {{-- Arrow --}}
                        <svg class="h-3.5 w-3.5 flex-shrink-0 opacity-0 transition-all duration-150 group-hover:translate-x-1 group-hover:opacity-100
                            @if($cfg['mc_class'] === 'mb') text-brand
                            @elseif($cfg['mc_class'] === 'mi') text-[#8B6B00]
                            @else text-textSecondary
                            @endif
                        " viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                    @endforeach
                </div>

                @if (count($programs) > 10)
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-2 rounded-[10px] border border-borderLight bg-white px-4 py-2.5 text-sm font-bold text-textPrimary shadow-sm transition hover:border-brand hover:bg-brandSoft hover:text-brand"
                        @click="expanded = !expanded"
                    >
                        <span x-text="expanded ? 'Show less' : 'View more'"></span>
                        <svg class="h-4 w-4 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
                            <path d="M6 9l6 6 6-6" />
                        </svg>
                    </button>
                @endif

            </div>
            @endforeach

        </div>

        {{-- View All CTA --}}
        <div class="mt-14 text-center">
            <a href="#"
                class="inline-flex items-center gap-2 rounded-full border border-brand/24 bg-brandSoft px-7 py-3 text-sm font-bold text-brand transition-all duration-200 hover:-translate-y-0.5 hover:bg-brand/15">
                View all tracks
                <svg class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M17 8l4 4-4 4M3 12h18" />
                </svg>
            </a>
        </div>

    </div>
</section>
