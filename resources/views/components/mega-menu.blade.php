@props([
    'mobile' => false,
])

@php
$internshipLevels = [
'Beginner' => [
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
'Intermediate' => [
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
'Advanced' => [
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

@if ($mobile)
<div x-data="{ level: 'Beginner' }">
    <div class="grid grid-cols-3 gap-2">
        <template x-for="lvl in ['Beginner', 'Intermediate', 'Advanced']">
            <button type="button"
                @click="level = lvl"
                class="rounded-xl px-3 py-2 text-xs font-semibold transition"
                :class="level === lvl ? 'bg-black text-white' : 'bg-[#f7f7f7] text-neutral-700 hover:bg-neutral-100'"
                x-text="lvl">
            </button>
        </template>
    </div>

    <div class="mt-4 max-h-80 space-y-2 overflow-y-auto">
        <template x-for="program in {{ json_encode($internshipLevels) }}[level]">
            <a :href="'/course/' + program.slug"
                class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-neutral-700 transition hover:bg-neutral-100 hover:text-black"
                x-text="program.label"></a>
        </template>
    </div>
</div>
@else
<div class="relative" x-data="{ open: false, level: 'Beginner' }" @mouseenter="open = true" @mouseleave="open = false">
    <button class="px-4 py-2 text-sm transition hover:text-gray-700" :class="open ? 'text-gray-900' : 'text-gray-500'">
        Internships
        <svg class="ml-1 inline h-3 w-3 transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-cloak x-show="open" x-transition class="absolute left-0 top-full mt-2 w-80 rounded-lg border bg-white shadow-lg">
        <div class="p-2">
            <!-- Levels -->
            <div class="flex gap-1 border-b pb-2">
                <template x-for="lvl in ['Beginner', 'Intermediate', 'Advanced']">
                    <button @click="level = lvl" class="flex-1 rounded px-3 py-1.5 text-sm capitalize transition"
                        :class="level === lvl ? 'bg-gray-900 text-white' : 'hover:bg-gray-100'"
                        x-text="lvl.toLowerCase()">
                    </button>
                </template>
            </div>

            <!-- Programs -->
            <div class="mt-2 max-h-96 overflow-y-auto">
                <template x-for="program in {{ json_encode($internshipLevels) }}[level]">
                    <a :href="'/course/' + program.slug" class="block rounded px-3 py-2 text-sm text-gray-700 transition hover:bg-gray-100 hover:text-gray-900" x-text="program.label"></a>
                </template>
            </div>
        </div>
    </div>
</div>
@endif
