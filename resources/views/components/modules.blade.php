@php
$tracks = [
[
'title' => 'UI/UX & Product Design',
'description' => 'Learn design systems, UX flows, and real product prototyping.',
'tag' => 'Design Track',
'link' => '#',
'icon_bg' => 'bg-brandSoft',
'icon_color' => 'text-brand',
'tag_bg' => 'bg-brandSoft',
'tag_color' => 'text-brand',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M12 20h9" />
    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z" />
</svg>'
],

[
'title' => 'Data Science & AI',
'description' => 'Work on ML models, data pipelines, and real datasets.',
'tag' => 'Tech Track',
'link' => '#',
'icon_bg' => 'bg-secondarySoft',
'icon_color' => 'text-secondary',
'tag_bg' => 'bg-secondarySoft',
'tag_color' => 'text-secondary',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M3 3v18h18" />
    <path d="M18 17V9" />
    <path d="M13 17V5" />
    <path d="M8 17v-3" />
</svg>'
],

[
'title' => 'Digital Marketing & Automation',
'description' => 'Build funnels, automation workflows, and campaign tracking.',
'tag' => 'Business Track',
'link' => '#',
'icon_bg' => 'bg-brandSoft',
'icon_color' => 'text-brand',
'tag_bg' => 'bg-brandSoft',
'tag_color' => 'text-brand',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M3 17l6-6 4 4 8-8" />
</svg>'
],

[
'title' => 'AWS Cloud Architect',
'description' => 'Learn cloud infrastructure, VPC setup, and scalable deployments.',
'tag' => 'Cloud Track',
'link' => '#',
'icon_bg' => 'bg-secondarySoft',
'icon_color' => 'text-secondary',
'tag_bg' => 'bg-secondarySoft',
'tag_color' => 'text-secondary',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M3 15a4 4 0 0 1 4-4h1a4 4 0 1 1 7.9 1H17a3 3 0 1 1 0 6H7a4 4 0 0 1-4-3z" />
</svg>'
],

[
'title' => 'Mechanical & Automation',
'description' => 'Work with digital twins, CNC logic, and automation systems.',
'tag' => 'Core Track',
'link' => '#',
'icon_bg' => 'bg-brandSoft',
'icon_color' => 'text-brand',
'tag_bg' => 'bg-brandSoft',
'tag_color' => 'text-brand',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M12 6v6l4 2" />
</svg>'
],

[
'title' => 'Electrical & IoT Systems',
'description' => 'Build IoT systems, smart grids, and real-time applications.',
'tag' => 'IoT Track',
'link' => '#',
'icon_bg' => 'bg-secondarySoft',
'icon_color' => 'text-secondary',
'tag_bg' => 'bg-secondarySoft',
'tag_color' => 'text-secondary',
'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor">
    <path d="M13 2L3 14h7l-1 8 10-12h-7l1-8z" />
</svg>'
],
];
@endphp

<section class="bg-gradient-to-b from-bgWhite to-bgGray/30 py-24">

    <div class="max-w-7xl mx-auto px-6">

        <!-- HEADING with decorative elements -->
        <div class="text-center mb-20 relative">
            <div class="absolute left-1/2 -translate-x-1/2 top-0 w-24 h-1 bg-gradient-to-r from-brand/20 via-brand to-brand/20 rounded-full"></div>
            <h2 class="text-4xl md:text-6xl font-bold text-textPrimary mt-6">
                Explore Our
                <span class="bg-gradient-to-r from-brand via-brandDark to-secondary bg-clip-text text-transparent">
                    Internship Tracks
                </span>
            </h2>
            <p class="mt-6 text-textSecondary max-w-2xl mx-auto text-lg">
                Choose from industry-focused internship tracks designed to build real-world skills.
            </p>
        </div>

        <!-- CARDS - Redesigned with modern layout -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">

            @foreach($tracks as $index => $track)
            <div class="group relative bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 flex flex-col">

                <!-- Gradient border accent on hover -->
                <div class="absolute inset-0 bg-gradient-to-r from-brand/0 via-brand/0 to-brand/0 rounded-2xl transition duration-500 group-hover:from-brand/5 group-hover:via-brand/10 group-hover:to-brand/5 pointer-events-none"></div>

                <!-- Card content -->
                <div class="p-7 flex flex-col h-full relative z-10">

                    <!-- Icon with animated background -->
                    <div class="relative mb-6 w-14 h-14 flex items-center justify-center rounded-xl {{ $track['icon_bg'] }} {{ $track['icon_color'] }} group-hover:scale-110 transition-transform duration-300">
                        {!! $track['icon'] !!}
                    </div>

                    <!-- Title -->
                    <h3 class="text-xl font-bold text-textPrimary mb-3 group-hover:text-brand transition-colors duration-300">
                        {{ $track['title'] }}
                    </h3>

                    <!-- Description -->
                    <p class="text-textSecondary leading-relaxed mb-5 flex-grow">
                        {{ $track['description'] }}
                    </p>

                    <!-- Tag with new styling -->
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-full {{ $track['tag_bg'] }} {{ $track['tag_color'] }}">
                            <span class="w-1.5 h-1.5 rounded-full currentColor bg-current opacity-60"></span>
                            {{ $track['tag'] }}
                        </span>
                    </div>

                    <!-- Divider -->
                    <div class="w-full h-px bg-gradient-to-r from-transparent via-borderLight to-transparent my-2"></div>

                    <!-- Bottom actions -->
                    <div class="mt-4 flex items-center justify-between gap-4">
                        <a href="{{ $track['link'] }}"
                            class="group/link inline-flex items-center gap-1.5 text-sm font-medium text-textSecondary hover:text-brand transition-all duration-300">
                            View Details
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300 group-hover/link:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <button
                            class="relative px-5 py-2.5 text-sm font-semibold text-white rounded-xl bg-gradient-to-r from-brand to-brandDark hover:from-brandDark hover:to-brand transition-all duration-300 shadow-md hover:shadow-lg hover:scale-105 active:scale-95 overflow-hidden group/btn">
                            <span class="relative z-10">Enroll Now</span>
                            <span class="absolute inset-0 bg-gradient-to-r from-brandDark to-brand opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></span>
                        </button>
                    </div>

                </div>

                <!-- Decorative corner element -->
                <div class="absolute top-0 right-0 w-16 h-16 opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none">
                    <div class="absolute top-0 right-0 w-0 h-0 border-t-[64px] border-r-[64px] border-t-brand/5 border-r-transparent"></div>
                </div>

            </div>
            @endforeach

        </div>

        <!-- Bottom CTA -->
        <div class="text-center mt-16">
            <a href="#" class="inline-flex items-center gap-2 px-6 py-3 text-brand hover:text-brandDark font-medium transition-colors duration-300 group">
                <span>View all tracks</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

    </div>

</section>