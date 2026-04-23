@php
    $menuGroups = [
        [
            'title' => 'AI Remote Internships (Our Programs)',
            'items' => [
                ['label' => 'Computer Science & IT', 'href' => '#'],
                ['label' => 'Core Engineering (Mechanical, Civil)', 'href' => '#'],
                ['label' => 'Business & Management', 'href' => '#'],
                ['label' => 'Law & Humanities', 'href' => '#'],
            ],
        ],
        [
            'title' => 'Corporate Internships (Job Board)',
            'items' => [
                ['label' => 'Browse by Branch', 'href' => '#'],
                ['label' => 'Browse by Location / Remote', 'href' => '#'],
                ['label' => 'Pre-Placement Offers (PPO)', 'href' => '#'],
            ],
        ],
    ];
@endphp

<div class="flex h-full items-center" x-data="{ academyOpen: false }" @mouseenter="academyOpen = true"
    @mouseleave="academyOpen = false" @keydown.escape.window="academyOpen = false">
    <button type="button" @click="academyOpen = !academyOpen"
        class="flex items-center gap-2 font-medium transition-all duration-300"
        :class="academyOpen ? 'text-primary' : 'text-gray-900 hover:text-primary'" :aria-expanded="academyOpen.toString()">
        <span>Internship</span>
        <svg class="h-4 w-4 transition duration-300" :class="academyOpen ? 'rotate-180 text-primary' : ''"
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
        class="absolute left-1/2 top-full z-50 mt-4 hidden w-[min(calc(100vw-2rem),72rem)] -translate-x-1/2 rounded-[2rem] border border-slate-200 bg-white/95 shadow-2xl shadow-slate-300/30 backdrop-blur-xl lg:block">
        <div class="grid gap-8 p-8 xl:grid-cols-[minmax(0,1fr)_320px]">
            <div class="grid gap-6 sm:grid-cols-2">
                @foreach ($menuGroups as $group)
                    <div class="min-w-0 rounded-[1.5rem] border border-slate-200 bg-slate-50/80 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primary">
                            {{ $group['title'] }}
                        </p>

                        <div class="mt-4 space-y-3">
                            @foreach ($group['items'] as $item)
                                <a href="{{ $item['href'] }}"
                                    class="block text-sm font-medium leading-6 text-slate-600 transition hover:text-primary">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <aside class="w-full rounded-[1.75rem] bg-slate-900 p-6 shadow-xl shadow-slate-300/40">
                <span
                    class="inline-flex rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-primaryLight">
                    Internship Highlight
                </span>

                <h3 class="mt-5 text-2xl font-semibold text-white">Internship Tracks</h3>

                <p class="mt-3 text-sm leading-7 text-slate-300">
                    Explore remote internship programs and corporate opportunities across multiple domains.
                </p>

                <div class="mt-8 rounded-2xl bg-white/5 px-4 py-4 ring-1 ring-white/10">
                    <p class="text-sm font-medium text-slate-200">Built for students and career starters</p>
                </div>

                <a href="#"
                    class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:bg-slate-100">
                    Explore Internships
                </a>
            </aside>
        </div>
    </div>
</div>
