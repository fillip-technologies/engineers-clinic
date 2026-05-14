@php
    $steps = [
        ['label' => '01', 'icon' => 'fi fi-rr-target', 'title' => 'Campaign brief', 'body' => 'Define the business goal: hiring demand, brand recall, product adoption, event registrations, or certification uptake.'],
        ['label' => '02', 'icon' => 'fi fi-rr-users-alt', 'title' => 'Audience build', 'body' => 'Map segments by domain, learning track, seniority, college context, and expected action.'],
        ['label' => '03', 'icon' => 'fi fi-rr-megaphone', 'title' => 'Distribution launch', 'body' => 'Activate selected placements with messaging, registration flows, reminder systems, and campaign coordination.'],
        ['label' => '04', 'icon' => 'fi fi-rr-chart-histogram', 'title' => 'Performance readout', 'body' => 'Review reach, engagement, conversion quality, channel lift, and next-step recommendations.'],
    ];
@endphp

<section class="bg-bgSoft py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-8 lg:grid-cols-[0.8fr_1fr] lg:items-end">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Operating workflow</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    A campaign system from brief to performance review.
                </h2>
            </div>
            <div class="rounded-xl border border-borderLight bg-bgWhite p-5 shadow-sm">
                <div class="flex flex-wrap gap-2 text-xs font-semibold text-textSecondary">
                    @foreach(['Brief', 'Segment', 'Launch', 'Track', 'Report'] as $stage)
                        <span class="rounded-full border border-borderLight bg-bgSoft px-3 py-1.5">{{ $stage }}</span>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="relative mt-12 grid gap-5 lg:grid-cols-4">
            <div class="absolute left-0 right-0 top-[2.65rem] hidden h-px bg-gradient-to-r from-transparent via-brand/25 to-transparent lg:block"></div>
            @foreach($steps as $step)
                <article class="group relative rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/35 hover:shadow-[0_18px_44px_rgba(22,8,64,0.10)]">
                    <div class="flex items-center justify-between gap-4">
                        <span class="flex h-12 w-12 items-center justify-center rounded-lg border border-borderLight bg-bgWhite text-lg text-brand shadow-sm transition duration-300 group-hover:border-brand/30 group-hover:bg-brandSoft">
                            <i class="{{ $step['icon'] }}"></i>
                        </span>
                        <span class="rounded-full border border-borderLight bg-bgMain px-3 py-1 text-xs font-semibold text-brand">{{ $step['label'] }}</span>
                    </div>
                    <h3 class="mt-6 text-lg font-semibold text-textPrimary">{{ $step['title'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $step['body'] }}</p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">
                        <span class="h-px flex-1 bg-borderLight"></span>
                        <span>Ops step</span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
