@php
    $cases = [
        ['title' => 'SaaS product awareness campaign', 'metric' => '3.8k', 'label' => 'student interactions', 'body' => 'A software tools brand introduced its platform through LMS placements, webinar registration, and project-context messaging.', 'tag' => 'Product marketing'],
        ['title' => 'Graduate hiring visibility drive', 'metric' => '720', 'label' => 'qualified interests', 'body' => 'A hiring team ran employer brand content and role-awareness communication across engineering communities.', 'tag' => 'Talent acquisition'],
        ['title' => 'Certification launch with colleges', 'metric' => '28', 'label' => 'distribution touchpoints', 'body' => 'A certification provider built awareness through college-aligned sessions and targeted student communications.', 'tag' => 'Growth campaign'],
    ];
@endphp

<section class="bg-bgSoft py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-8 lg:grid-cols-[0.72fr_1fr] lg:items-end">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Campaign use cases</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Formats that translate reach into measurable business signals.
                </h2>
            </div>
            <p class="text-base leading-8 text-textSecondary">
                Each format combines audience context, distribution channels, conversion moments, and reporting, so teams can understand what moved and why.
            </p>
        </div>

        <div class="mt-10 grid gap-5 lg:grid-cols-3">
            @foreach($cases as $case)
                <article class="group rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-[0_18px_44px_rgba(22,8,64,0.10)]">
                    <div class="flex items-start justify-between gap-4">
                        <p class="text-sm font-semibold text-brand">{{ $case['title'] }}</p>
                        <span class="rounded-full border border-borderLight bg-bgSoft px-3 py-1 text-xs font-semibold text-textSecondary">{{ $case['tag'] }}</span>
                    </div>
                    <div class="mt-6 rounded-lg border border-borderLight bg-bgSoft p-4 transition duration-300 group-hover:bg-bgWhite">
                        <p class="text-3xl font-semibold text-textPrimary">{{ $case['metric'] }}</p>
                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">{{ $case['label'] }}</p>
                    </div>
                    <p class="mt-5 text-sm leading-7 text-textSecondary">{{ $case['body'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
