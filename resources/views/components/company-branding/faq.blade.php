@php
    $faqs = [
        ['q' => 'What kinds of companies can run campaigns?', 'a' => 'Technology companies, startups, HR teams, certification providers, SaaS brands, training companies, and employer branding teams can run relevant campaigns.'],
        ['q' => 'Can campaigns be targeted by student domain?', 'a' => 'Yes. Campaigns can be mapped to engineering, data, design, business, law, communication, or other relevant track audiences.'],
        ['q' => 'Do you support webinars and workshops?', 'a' => 'Yes. We can support registration flows, audience outreach, session positioning, and post-event reporting.'],
        ['q' => 'Will companies get campaign reports?', 'a' => 'Yes. Reporting can include reach, engagement, registrations, channel performance, qualified interest, and follow-up recommendations.'],
    ];
@endphp

<section class="bg-bgSoft py-14 sm:py-16">
    <div class="mx-auto grid max-w-7xl gap-8 px-6 lg:grid-cols-[0.72fr_1fr]">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Buyer FAQ</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Common questions from campaign and brand teams.
            </h2>
            <p class="mt-5 text-base leading-8 text-textSecondary">
                Designed for teams evaluating audience fit, campaign scope, activation support, and reporting quality.
            </p>
        </div>

        <div class="space-y-3">
            @foreach($faqs as $faq)
                <details class="group rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm transition hover:border-brand/30 hover:shadow-[0_14px_34px_rgba(22,8,64,0.06)]">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-5 font-semibold text-textPrimary">
                        {{ $faq['q'] }}
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-md bg-bgSoft text-brand transition group-open:rotate-45 group-open:bg-brand group-open:text-white">+</span>
                    </summary>
                    <p class="mt-4 text-sm leading-7 text-textSecondary">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
