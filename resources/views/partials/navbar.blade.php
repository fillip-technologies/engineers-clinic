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

<header class="w-full bg-bgMain" x-data="{ mobileOpen: false, mobileInternshipOpen: false }">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <div class="text-xl font-semibold text-textPrimary">
            <!-- Engineers <span class="text-textPrimary">Clinic</span> -->
            <img src="/images/Engineers-clinics.png" class="h-14" />
        </div>

        <nav class="hidden items-center gap-2 px-3 py-2 md:flex">
            <a href="#"
                class="rounded-full bg-bgWhite px-4 py-2 text-sm font-medium text-textPrimary shadow-sm">
                Home
            </a>

            <x-mega-menu />

            <a href="#"
                class="rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                For Enterprise & Employers
            </a>

            <a href="#"
                class="rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                AI Tools
            </a>

            <a href="#"
                class="relative rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                College Tie-up
                <span class="absolute -right-2 -top-2 rounded-full bg-textPrimary px-2 py-[2px] text-[10px] text-white">
                    NEW
                </span>
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ url('/login') }}"
                class="hidden rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-brandDark sm:inline-block">
                Login
            </a>

            <button type="button"
                class="inline-flex items-center justify-center rounded-lg border border-borderLight bg-white p-2 text-textPrimary transition hover:bg-bgSoft md:hidden"
                @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen.toString()" aria-label="Toggle menu">
                <svg x-show="!mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-borderLight bg-white md:hidden">
        <nav class="mx-auto max-w-7xl space-y-2 px-6 py-5">
            <a href="#"
                class="block rounded-2xl bg-bgSoft px-4 py-3 text-sm font-medium text-textPrimary">
                Home
            </a>

            <div class="rounded-2xl border border-borderLight">
                <button type="button"
                    class="flex w-full items-center justify-between px-4 py-3 text-left text-sm font-medium text-textPrimary"
                    @click="mobileInternshipOpen = !mobileInternshipOpen">
                    <span>Internships</span>
                    <svg class="h-4 w-4 transition" :class="mobileInternshipOpen ? 'rotate-180' : ''" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="mobileInternshipOpen" x-cloak x-transition class="border-t border-borderLight px-4 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">Internships</p>
                        <p class="mt-1 text-xs text-textMuted">Grouped by learning level</p>
                        <div class="mt-3 space-y-4">
                            @foreach ($internshipLevels as $level => $programs)
                            <div>
                                <p class="px-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-textMuted">{{ $level }}</p>
                                <div class="mt-2 space-y-1">
                                    @foreach ($programs as $program)
                                    <a href="{{ route('course.detail', $program['slug']) }}"
                                        class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">{{ $program['label'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                For Enterprise & Employers
            </a>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                AI Tools
            </a>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                College Tie-up
            </a>

            <a href="{{ url('/login') }}"
                class="mt-2 inline-flex w-full items-center justify-center rounded-lg bg-brand px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-brandDark">
                Login
            </a>
        </nav>
    </div>

</header>