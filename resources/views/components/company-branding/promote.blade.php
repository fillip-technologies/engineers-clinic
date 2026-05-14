@php
    $items = [
        ['icon' => 'fi fi-rr-briefcase', 'title' => 'Hiring demand generation', 'body' => 'Build role awareness, internship pipelines, graduate hiring funnels, and company culture recall.'],
        ['icon' => 'fi fi-rr-apps', 'title' => 'Product adoption campaigns', 'body' => 'Introduce SaaS tools, developer platforms, and student-facing products inside relevant learning contexts.'],
        ['icon' => 'fi fi-rr-diploma', 'title' => 'Certification launches', 'body' => 'Promote certification offers, scholarships, skill challenges, and cohort enrollment journeys.'],
        ['icon' => 'fi fi-rr-trophy', 'title' => 'Events and challenges', 'body' => 'Run hackathons, webinars, workshops, and registration-led activations with follow-up reporting.'],
        ['icon' => 'fi fi-rr-users-alt', 'title' => 'Employer brand visibility', 'body' => 'Position your company as a serious career destination for technical, business, and creative talent.'],
        ['icon' => 'fi fi-rr-megaphone', 'title' => 'Always-on awareness', 'body' => 'Create repeated visibility across student cohorts, community updates, and institutional touchpoints.'],
    ];
@endphp

<section class="relative overflow-hidden bg-bgSoft py-16 sm:py-20">
    <div class="pointer-events-none absolute left-0 top-12 h-56 w-56 rounded-full bg-brandSoft blur-3xl"></div>
    <div class="mx-auto max-w-7xl px-6">
        <div class="relative grid gap-8 lg:grid-cols-[0.72fr_1fr] lg:items-end">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Campaign inventory</p>
                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Built for brand, talent, and product teams with clear activation goals.
                </h2>
            </div>
            <div class="rounded-xl border border-borderLight bg-bgWhite p-5 shadow-sm">
                <div class="grid gap-3 text-sm sm:grid-cols-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Planning unit</p>
                        <p class="mt-1 font-semibold text-textPrimary">Campaign brief</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Output</p>
                        <p class="mt-1 font-semibold text-textPrimary">Reach + intent</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Review</p>
                        <p class="mt-1 font-semibold text-textPrimary">Performance report</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($items as $item)
                <article class="group rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand/35 hover:shadow-[0_20px_48px_rgba(22,8,64,0.10)]">
                    <div class="flex items-start justify-between gap-5">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg border border-borderLight bg-bgMain text-brand transition duration-300 group-hover:border-brand/30 group-hover:bg-brandSoft">
                            <i class="{{ $item['icon'] }}"></i>
                        </span>
                        <span class="h-2 w-2 rounded-full bg-secondary"></span>
                    </div>
                    <h3 class="mt-6 text-lg font-semibold text-textPrimary">{{ $item['title'] }}</h3>
                    <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $item['body'] }}</p>
                    <div class="mt-5 h-px bg-gradient-to-r from-brand/30 via-borderLight to-transparent"></div>
                </article>
            @endforeach
        </div>
    </div>
</section>
