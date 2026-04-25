@props(['courses' => []])

@php
    $trackStyles = [
        'ui-ux-product-design-professional' => [
            'tag' => 'Design Track',
            'icon_bg' => 'bg-brandSoft',
            'icon_color' => 'text-brand',
            'tag_bg' => 'bg-brandSoft',
            'tag_color' => 'text-brand',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M12 20h9" /><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" /></svg>',
        ],
        'data-science-analytics-expert' => [
            'tag' => 'Analytics Track',
            'icon_bg' => 'bg-secondarySoft',
            'icon_color' => 'text-secondary',
            'tag_bg' => 'bg-secondarySoft',
            'tag_color' => 'text-secondary',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M3 3v18h18" /><path d="M18 17V9" /><path d="M13 17V5" /><path d="M8 17v-3" /></svg>',
        ],
        'b2b-digital-marketing-automation-mba-bba' => [
            'tag' => 'Business Track',
            'icon_bg' => 'bg-brandSoft',
            'icon_color' => 'text-brand',
            'tag_bg' => 'bg-brandSoft',
            'tag_color' => 'text-brand',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M3 17l6-6 4 4 8-8" /></svg>',
        ],
        'aws-cloud-solutions-architect' => [
            'tag' => 'Cloud Track',
            'icon_bg' => 'bg-secondarySoft',
            'icon_color' => 'text-secondary',
            'tag_bg' => 'bg-secondarySoft',
            'tag_color' => 'text-secondary',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M3 15a4 4 0 0 1 4-4h1a4 4 0 1 1 7.9 1H17a3 3 0 1 1 0 6H7a4 4 0 0 1-4-3z" /></svg>',
        ],
        'btech-civil-engineering-smart-city-bim-infrastructure' => [
            'tag' => 'Civil Track',
            'icon_bg' => 'bg-brandSoft',
            'icon_color' => 'text-brand',
            'tag_bg' => 'bg-brandSoft',
            'tag_color' => 'text-brand',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M4 19h16" /><path d="M5 19V9l7-5 7 5v10" /><path d="M9 19v-4h6v4" /></svg>',
        ],
        'btech-mechanical-engineering-digital-twin-automation' => [
            'tag' => 'Core Track',
            'icon_bg' => 'bg-brandSoft',
            'icon_color' => 'text-brand',
            'tag_bg' => 'bg-brandSoft',
            'tag_color' => 'text-brand',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M12 6v6l4 2" /><path d="M5 12a7 7 0 1 0 14 0 7 7 0 1 0-14 0Z" /></svg>',
        ],
        'btech-electrical-electronics-iot-power-grids' => [
            'tag' => 'IoT Track',
            'icon_bg' => 'bg-secondarySoft',
            'icon_color' => 'text-secondary',
            'tag_bg' => 'bg-secondarySoft',
            'tag_color' => 'text-secondary',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z" /></svg>',
        ],
        'llb-corporate-law-legal-tech-tech-law' => [
            'tag' => 'Legal Track',
            'icon_bg' => 'bg-secondarySoft',
            'icon_color' => 'text-secondary',
            'tag_bg' => 'bg-secondarySoft',
            'tag_color' => 'text-secondary',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M12 3v18" /><path d="M5 7h14" /><path d="M7 7c0 3-2 5-4 6 2 1 4 3 4 6" /><path d="M17 7c0 3 2 5 4 6-2 1-4 3-4 6" /></svg>',
        ],
        'mass-communication-journalism-digital-media-pr-tech' => [
            'tag' => 'Media Track',
            'icon_bg' => 'bg-brandSoft',
            'icon_color' => 'text-brand',
            'tag_bg' => 'bg-brandSoft',
            'tag_color' => 'text-brand',
            'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M4 6h16v12H4z" /><path d="M8 10h8" /><path d="M8 14h5" /></svg>',
        ],
    ];

    $defaultStyle = [
        'tag' => 'Internship Track',
        'icon_bg' => 'bg-brandSoft',
        'icon_color' => 'text-brand',
        'tag_bg' => 'bg-brandSoft',
        'tag_color' => 'text-brand',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" stroke="currentColor"><path d="M12 6v6l4 2" /></svg>',
    ];

    $tracks = collect($courses)->map(function ($course) use ($trackStyles, $defaultStyle) {
        $style = $trackStyles[$course['slug']] ?? $defaultStyle;

        return array_merge($style, [
            'title' => $course['title'],
            'description' => $course['description'],
            'link' => route('course.detail', $course['slug']),
        ]);
    });
@endphp

<section class="bg-gradient-to-b from-bgWhite to-bgGray/30 py-24">

    <div class="mx-auto max-w-7xl px-6">
        <div class="relative mb-20 text-center">
            <div class="absolute left-1/2 top-0 h-1 w-24 -translate-x-1/2 rounded-full bg-gradient-to-r from-brand/20 via-brand to-brand/20"></div>
            <h2 class="mt-6 text-4xl font-bold text-textPrimary md:text-6xl">
                Explore Our
                <span class="bg-gradient-to-r from-brand via-brandDark to-secondary bg-clip-text text-transparent">
                    Internship Tracks
                </span>
            </h2>
            <p class="mx-auto mt-6 max-w-2xl text-lg text-textSecondary">
                Choose from industry-focused internship tracks designed to build real-world skills.
            </p>
        </div>

        <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($tracks as $track)
                <div
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-md transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                    <div
                        class="pointer-events-none absolute inset-0 rounded-2xl bg-gradient-to-r from-brand/0 via-brand/0 to-brand/0 transition duration-500 group-hover:from-brand/5 group-hover:via-brand/10 group-hover:to-brand/5">
                    </div>

                    <div class="relative z-10 flex h-full flex-col p-7">
                        <div
                            class="relative mb-6 flex h-14 w-14 items-center justify-center rounded-xl {{ $track['icon_bg'] }} {{ $track['icon_color'] }} transition-transform duration-300 group-hover:scale-110">
                            {!! $track['icon'] !!}
                        </div>

                        <h3
                            class="mb-3 text-xl font-bold text-textPrimary transition-colors duration-300 group-hover:text-brand">
                            {{ $track['title'] }}
                        </h3>

                        <p class="mb-5 flex-grow leading-relaxed text-textSecondary">
                            {{ $track['description'] }}
                        </p>

                        <div class="mb-6">
                            <span
                                class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-medium {{ $track['tag_bg'] }} {{ $track['tag_color'] }}">
                                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-60"></span>
                                {{ $track['tag'] }}
                            </span>
                        </div>

                        <div class="my-2 h-px w-full bg-gradient-to-r from-transparent via-borderLight to-transparent"></div>

                        <div class="mt-4 flex items-center justify-between gap-4">
                            <a href="{{ $track['link'] }}"
                                class="group/link inline-flex items-center gap-1.5 text-sm font-medium text-textSecondary transition-all duration-300 hover:text-brand">
                                View Details
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 transition-transform duration-300 group-hover/link:translate-x-1"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>

                            <a href="{{ $track['link'] }}"
                                class="group/btn relative overflow-hidden rounded-xl bg-gradient-to-r from-brand to-brandDark px-5 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-300 hover:scale-105 hover:from-brandDark hover:to-brand hover:shadow-lg active:scale-95">
                                <span class="relative z-10">Enroll Now</span>
                                <span
                                    class="absolute inset-0 bg-gradient-to-r from-brandDark to-brand opacity-0 transition-opacity duration-300 group-hover/btn:opacity-100"></span>
                            </a>
                        </div>
                    </div>

                    <div
                        class="pointer-events-none absolute right-0 top-0 h-16 w-16 opacity-0 transition-opacity duration-500 group-hover:opacity-100">
                        <div
                            class="absolute right-0 top-0 h-0 w-0 border-r-[64px] border-t-[64px] border-r-transparent border-t-brand/5">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-16 text-center">
            <a href="#"
                class="group inline-flex items-center gap-2 px-6 py-3 font-medium text-brand transition-colors duration-300 hover:text-brandDark">
                <span>View all tracks</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-4 w-4 transition-transform duration-300 group-hover:translate-x-1" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>
    </div>

</section>
