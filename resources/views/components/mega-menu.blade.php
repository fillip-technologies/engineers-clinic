@php
$internshipLevels = [
'Beginner Level' => [
['label' => 'Web Ecosystems & Frontend Architecture', 'slug' => 'web-ecosystems-frontend'],
['label' => 'Core Python & Computational Logic', 'slug' => 'core-python-computational-logic'],
['label' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
['label' => 'Data Analytics', 'slug' => 'data-analytics'],
['label' => 'AutoCAD Drafting', 'slug' => 'autocad-drafting'],
['label' => 'Manufacturing Basics', 'slug' => 'manufacturing-basics'],
['label' => 'Civil Drafting', 'slug' => 'civil-drafting'],
['label' => 'Site Surveying', 'slug' => 'site-surveying'],
['label' => 'Legal Research', 'slug' => 'legal-research'],
['label' => 'Digital Journalism', 'slug' => 'digital-journalism'],
],
'Intermediate Level' => [
['label' => 'Cloud & Backend Systems', 'slug' => 'cloud-backend-systems'],
['label' => 'Machine Learning', 'slug' => 'machine-learning'],
['label' => 'Ethical Hacking', 'slug' => 'ethical-hacking'],
['label' => 'Mobile Development', 'slug' => 'mobile-development'],
['label' => 'CAD/CAM', 'slug' => 'cad-cam'],
['label' => 'HVAC Design', 'slug' => 'hvac-design'],
['label' => 'Structural Design', 'slug' => 'structural-design'],
['label' => 'Project Estimation', 'slug' => 'project-estimation'],
['label' => 'Corporate Law', 'slug' => 'corporate-law'],
['label' => 'PR Strategy', 'slug' => 'pr-strategy'],
],
'Advanced Level' => [
['label' => 'Generative AI', 'slug' => 'generative-ai'],
['label' => 'Cloud Architecture', 'slug' => 'cloud-architecture'],
['label' => 'Blockchain Systems', 'slug' => 'blockchain-systems'],
['label' => 'Big Data Systems', 'slug' => 'big-data-systems'],
['label' => 'CFD & FEA', 'slug' => 'cfd-fea'],
['label' => 'Robotics Automation', 'slug' => 'robotics-automation'],
['label' => 'BIM Infrastructure', 'slug' => 'bim-infrastructure'],
['label' => 'Geotechnical Engineering', 'slug' => 'geotechnical-engineering'],
['label' => 'Digital Law', 'slug' => 'digital-law'],
['label' => 'Corporate Communication', 'slug' => 'corporate-communication'],
],
];
@endphp

<div class="relative flex h-full items-center" x-data="{ academyOpen: false, activeLevel: 'beginner' }" @mouseenter="academyOpen = true"
    @mouseleave="academyOpen = false" @keydown.escape.window="academyOpen = false">
    <button type="button" @click="academyOpen = !academyOpen"
        class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium transition-all duration-300"
        :class="academyOpen ? 'bg-bgWhite text-textPrimary shadow-sm' : 'text-textSecondary hover:bg-bgWhite hover:text-textPrimary'"
        :aria-expanded="academyOpen.toString()">
        <span>Internships</span>
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
        class="absolute left-1/2 top-full z-50 mt-3 block w-[min(calc(100vw-2rem),72rem)] -translate-x-1/2 rounded-[1.5rem] border border-slate-200/80 bg-white/95 shadow-xl shadow-slate-300/20 backdrop-blur-xl">
        <div class="p-5">
            <div class="mb-4 flex items-center justify-between gap-4 border-b border-slate-200/80 pb-3">
                <!-- <div>
                    <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-primary">Internships</p>
                    <p class="mt-1 text-sm text-slate-500">Choose a level and open any internship through the shared course system</p>
                </div> -->
                <!-- <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                    {{ collect($internshipLevels)->flatten(1)->count() }} Tracks
                </span> -->
            </div>

            <div class="grid gap-5 lg:grid-cols-[14rem_1fr]">
                <div class="flex gap-2 overflow-x-auto lg:flex-col lg:overflow-visible">
                    @foreach ($internshipLevels as $level => $programs)
                    @php $levelKey = strtolower(str_replace(' Level', '', $level)); @endphp
                    <button type="button" @click="activeLevel = @js($levelKey)"
                        class="shrink-0 rounded-[1.25rem] border border-slate-200/80 bg-slate-50/60 p-4 text-left transition hover:-translate-y-0.5 hover:border-primary/30"
                        :class="activeLevel === @js($levelKey) ? 'border-primary/30 bg-white text-primary shadow-sm' : 'text-slate-700'">
                        <div class="border-b border-slate-200/80 pb-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500"
                                :class="activeLevel === @js($levelKey) ? 'text-primary' : 'text-slate-500'">
                                {{ str_replace(' Level', '', $level) }}
                            </p>
                        </div>
                    </button>
                    @endforeach
                </div>

                <div class="min-w-0">
                    @foreach ($internshipLevels as $level => $programs)
                    @php $levelKey = strtolower(str_replace(' Level', '', $level)); @endphp
                    <div x-show="activeLevel === @js($levelKey)"
                        class="rounded-[1.25rem] border border-slate-200/80 bg-slate-50/60 p-4">
                        <div class="border-b border-slate-200/80 pb-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">{{ $level }}</p>
                        </div>

                        <div class="mt-4 grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
                            @foreach ($programs as $program)
                            <a href="{{ route('course.detail', $program['slug']) }}"
                                class="group block rounded-[1rem] border border-slate-200/80 bg-white px-4 py-3 text-sm font-medium leading-5 text-slate-700 transition hover:-translate-y-0.5 hover:border-primary/30 hover:bg-white hover:text-primary">
                                {{ $program['label'] }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
