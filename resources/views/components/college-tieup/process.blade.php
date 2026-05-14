@php
    $steps = [
        ['label' => '01', 'title' => 'Institution discovery', 'body' => 'We understand departments, student volume, academic calendar, placement priorities, and reporting expectations.'],
        ['label' => '02', 'title' => 'Program mapping', 'body' => 'Tracks are aligned to branches, semesters, learning level, and the college coordination model.'],
        ['label' => '03', 'title' => 'Cohort onboarding', 'body' => 'Students are enrolled, oriented, assigned learning paths, and introduced to project milestones.'],
        ['label' => '04', 'title' => 'Delivery and reporting', 'body' => 'Mentor-led learning, attendance tracking, progress reviews, and outcome reports continue through completion.'],
    ];
@endphp

<section id="how-it-works" class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">How it works</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                A clear partnership flow from discussion to measurable outcomes.
            </h2>
        </div>

        <div class="mt-12 grid gap-5 lg:grid-cols-4">
            @foreach($steps as $step)
                <article class="relative rounded-lg border border-borderLight bg-bgWhite p-6">
                    <div class="flex items-center gap-4">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-bgMain text-sm font-semibold text-brand">
                            {{ $step['label'] }}
                        </span>
                        <div class="hidden h-px flex-1 bg-borderLight lg:block"></div>
                    </div>
                    <h3 class="mt-7 text-lg font-semibold text-textPrimary">{{ $step['title'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $step['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
