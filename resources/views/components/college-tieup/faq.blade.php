@php
    $faqs = [
        ['q' => 'Can programs be mapped department-wise?', 'a' => 'Yes. Tracks can be aligned by branch, semester, student level, and placement priorities.'],
        ['q' => 'Do colleges receive student progress reports?', 'a' => 'Yes. The partnership includes progress visibility, attendance signals, completion updates, and structured reporting support.'],
        ['q' => 'Is an MoU possible for institutional partnerships?', 'a' => 'Yes. Our team can discuss MoU-ready engagement models based on the college requirement and program scope.'],
        ['q' => 'Can placement cells use this for readiness programs?', 'a' => 'Yes. The model is designed to support project portfolios, skill visibility, and placement-readiness conversations.'],
    ];
@endphp

<section class="bg-bgSoft py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[0.72fr_1fr]">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">FAQ</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Clear answers for college decision-makers.
            </h2>
        </div>

        <div class="space-y-3">
            @foreach($faqs as $faq)
                <details class="group rounded-lg border border-borderLight bg-bgWhite p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-5 font-semibold text-textPrimary">
                        {{ $faq['q'] }}
                        <span class="text-brand transition group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-4 text-sm leading-7 text-textSecondary">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
