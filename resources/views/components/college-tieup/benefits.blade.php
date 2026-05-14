@php
    $benefits = [
        ['title' => 'Structured internship delivery', 'body' => 'Run cohort-based practical learning with fixed milestones, domain tracks, and completion visibility for each department.'],
        ['title' => 'Lower coordination load', 'body' => 'Our team supports onboarding, student communication, mentor mapping, and routine academic updates.'],
        ['title' => 'Evidence for reviews', 'body' => 'Colleges receive progress signals, project outputs, attendance views, and completion-ready reports.'],
        ['title' => 'Placement cell alignment', 'body' => 'Learning paths are mapped to portfolios, interview discussion points, and applied project confidence.'],
    ];
@endphp

<section id="college-benefits" class="bg-bgSoft py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-10 lg:grid-cols-[0.74fr_1fr] lg:items-start">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Why partner with us</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Built for college operations, not just student sign-ups.
                </h2>
                <p class="mt-5 text-base leading-8 text-textSecondary">
                    The partnership model focuses on governance, measurable progress, and repeatable delivery so administrators can scale practical learning without adding operational noise.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($benefits as $benefit)
                    <article class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm">
                        <div class="mb-5 h-1 w-10 rounded-full bg-gradient-to-r from-brand to-secondary"></div>
                        <h3 class="text-lg font-semibold text-textPrimary">{{ $benefit['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $benefit['body'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
