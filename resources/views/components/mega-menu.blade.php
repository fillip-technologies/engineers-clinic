@php
    $programs = [
        ['label' => 'UI/UX & Product Design Professional', 'slug' => 'ui-ux-product-design-professional'],
        ['label' => 'Data Science & Analytics Expert', 'slug' => 'data-science-analytics-expert'],
        ['label' => 'B2B Digital Marketing & Automation (MBA/BBA)', 'slug' => 'b2b-digital-marketing-automation-mba-bba'],
        ['label' => 'AWS Cloud Solutions Architect', 'slug' => 'aws-cloud-solutions-architect'],
        ['label' => 'B.Tech Civil Engineering (Smart City & BIM Infrastructure)', 'slug' => 'btech-civil-engineering-smart-city-bim-infrastructure'],
        ['label' => 'B.Tech Mechanical Engineering (Digital Twin & Automation)', 'slug' => 'btech-mechanical-engineering-digital-twin-automation'],
        ['label' => 'B.Tech Electrical & Electronics (IoT & Power Grids)', 'slug' => 'btech-electrical-electronics-iot-power-grids'],
        ['label' => 'LLB & Corporate Law (Legal Tech & Tech Law)', 'slug' => 'llb-corporate-law-legal-tech-tech-law'],
        ['label' => 'Mass Communication & Journalism (Digital Media & PR Tech)', 'slug' => 'mass-communication-journalism-digital-media-pr-tech'],
    ];
@endphp

<div class="relative flex h-full items-center" x-data="{ academyOpen: false }" @mouseenter="academyOpen = true"
    @mouseleave="academyOpen = false" @keydown.escape.window="academyOpen = false">
    <button type="button" @click="academyOpen = !academyOpen"
        class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all duration-300"
        :class="academyOpen ? 'bg-bgWhite text-textPrimary shadow-sm' : 'text-textSecondary hover:bg-bgWhite hover:text-textPrimary'"
        :aria-expanded="academyOpen.toString()">
        <span>Internship</span>
        <svg class="h-4 w-4 transition duration-300" :class="academyOpen ? 'rotate-180' : ''"
            viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <path d="m5 7.5 5 5 5-5" />
        </svg>
    </button>

    <div x-cloak x-show="academyOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="absolute left-1/2 top-full z-50 mt-3 hidden w-[min(calc(100vw-2rem),56rem)] -translate-x-1/2 rounded-[1.5rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-slate-300/20 backdrop-blur-xl lg:block">
        <div class="p-5">
            <div class="mb-4 flex items-center justify-between gap-4 border-b border-slate-200/80 pb-3">
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-primary">Internship Programs</p>
                    <p class="mt-1 text-sm text-slate-500">Choose a specialization track</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    {{ count($programs) }} Tracks
                </span>
            </div>

            <div class="grid gap-2.5 sm:grid-cols-2 xl:grid-cols-3">
                @foreach ($programs as $program)
                    <a href="{{ route('course.detail', $program['slug']) }}"
                        class="group rounded-[1rem] border border-slate-200/80 bg-slate-50/70 px-4 py-3 text-sm font-medium leading-5 text-slate-700 transition hover:-translate-y-0.5 hover:border-primary/30 hover:bg-white hover:text-primary">
                        {{ $program['label'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
