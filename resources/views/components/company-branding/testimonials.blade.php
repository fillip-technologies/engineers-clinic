@php
    $testimonials = [
        ['quote' => 'The campaign felt far more structured than a typical student promotion. We had clear audience mapping, webinar participation data, and next-step recommendations.', 'name' => 'Employer Branding Lead', 'meta' => 'Technology Services Company'],
        ['quote' => 'Engineers Clinic helped us reach students in the right learning context instead of running a broad, noisy campaign.', 'name' => 'Product Marketing Manager', 'meta' => 'Developer Tools Brand'],
    ];
@endphp

<section class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-8 lg:grid-cols-[0.72fr_1fr] lg:items-end">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Partner signals</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Built for teams that need reach with operational clarity.
                </h2>
            </div>
            <div class="rounded-xl border border-borderLight bg-bgSoft p-5">
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach([['Signal', 'Audience fit'], ['Motion', 'Campaign-led'], ['Output', 'Actionable report']] as $item)
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">{{ $item[0] }}</p>
                            <p class="mt-1 text-sm font-semibold text-textPrimary">{{ $item[1] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-10 grid gap-5 lg:grid-cols-2">
            @foreach($testimonials as $testimonial)
                <figure class="relative rounded-lg border border-borderLight bg-bgWhite p-7 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-[0_18px_44px_rgba(22,8,64,0.08)]">
                    <div class="absolute right-7 top-6 text-5xl font-semibold leading-none text-brand/10">&ldquo;</div>
                    <blockquote class="relative text-base leading-8 text-textSecondary">"{{ $testimonial['quote'] }}"</blockquote>
                    <figcaption class="mt-6 border-t border-borderLight pt-5">
                        <p class="font-semibold text-textPrimary">{{ $testimonial['name'] }}</p>
                        <p class="mt-1 text-sm text-textMuted">{{ $testimonial['meta'] }}</p>
                    </figcaption>
                </figure>
            @endforeach
        </div>
    </div>
</section>
