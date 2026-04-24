@props(['course'])

@php
$overview = $course['program_overview'] ?? [];
$iconStyles = [
    ['bg' => 'bg-brandSoft', 'text' => 'text-brand'],
    ['bg' => 'bg-secondarySoft', 'text' => 'text-secondary'],
    ['bg' => 'bg-bgMain', 'text' => 'text-textPrimary'],
];
$icons = [
    '<path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M5 9h14M4 15h14" />',
    '<path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a3 3 0 016 0v6m-9 0h12a2 2 0 002-2V9a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.414-1.414A1 1 0 0013.586 5h-3.172a1 1 0 00-.707.293L8.293 6.707A1 1 0 017.586 7H6a2 2 0 00-2 2v6a2 2 0 002 2z" />',
    '<path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />',
];
@endphp

<section class="relative overflow-hidden bg-bgWhite px-6 py-20 sm:px-10 lg:px-14">
    <div
        class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(21,128,61,0.08),_transparent_24%),radial-gradient(circle_at_bottom_right,_rgba(249,115,22,0.08),_transparent_30%)]">
    </div>

    <div class="relative mx-auto max-w-6xl">
        <div class="mx-auto max-w-3xl text-center">
            <span
                class="inline-flex items-center rounded-full border border-brand/10 bg-brandSoft px-4 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-brand">
                Outcomes
            </span>
            <h2 class="mt-5 text-3xl font-semibold leading-tight text-textPrimary sm:text-4xl">
                What this program helps you build by the end
            </h2>
            <p class="mt-4 text-base leading-8 text-textSecondary">
                Outcome-focused highlights designed to make the value of the program easy to understand at a glance.
            </p>
        </div>

        <div class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach ($overview['stats'] ?? [] as $index => $stat)
            @php
            $style = $iconStyles[$index % count($iconStyles)];
            $icon = $icons[$index % count($icons)];
            @endphp
            <article
                class="group relative overflow-hidden rounded-[1.9rem] border border-borderLight bg-white p-7 shadow-[0_18px_45px_rgba(15,23,42,0.06)] transition duration-300 hover:-translate-y-1.5 hover:border-brand/20 hover:shadow-[0_26px_65px_rgba(15,23,42,0.1)]">
                <div class="absolute inset-x-7 top-0 h-px bg-gradient-to-r from-brand/0 via-brand/35 to-secondary/0"></div>
                <div class="flex items-start justify-between gap-5">
                    <div class="min-w-0">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $style['bg'] }} {{ $style['text'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                {!! $icon !!}
                            </svg>
                        </div>
                        <p class="mt-6 text-2xl font-semibold text-textPrimary">
                            {{ $stat['value'] ?? '' }}
                        </p>
                        <h3 class="mt-3 text-lg font-semibold leading-7 text-textPrimary">
                            {{ $stat['label'] ?? '' }}
                        </h3>
                    </div>
                    <span
                        class="inline-flex rounded-full border border-borderLight bg-bgMain px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.18em] text-textMuted">
                        Outcome
                    </span>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
