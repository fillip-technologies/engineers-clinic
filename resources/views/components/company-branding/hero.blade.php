<section class="relative overflow-hidden bg-bgWhite">
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(120deg,rgba(245,240,255,0.72),rgba(255,255,255,0.98)_42%,rgba(238,245,255,0.86))]"></div>
    <div class="pointer-events-none absolute -right-24 top-16 h-72 w-72 rounded-full bg-brandSoft blur-3xl"></div>

    <div class="relative mx-auto grid max-w-7xl gap-12 px-6 py-16 sm:py-20 lg:grid-cols-[0.92fr_1fr] lg:items-center lg:py-24">
        <div>
            <div class="inline-flex items-center gap-3 rounded-full border border-borderLight bg-bgWhite/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-brand shadow-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                Audience Reach Infrastructure
            </div>

            <h1 class="mt-7 max-w-4xl text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-[4.25rem] lg:leading-[1.03]">
                Scale student reach through a measurable campaign network.
            </h1>

            <p class="mt-6 max-w-2xl text-base leading-8 text-textSecondary sm:text-lg">
                Engineers Clinic gives employer brands, SaaS teams, hiring teams, and certification providers a structured distribution layer for awareness, registrations, engagement, and qualified student interest.
            </p>

            <div class="mt-9 flex flex-col gap-3 sm:flex-row">
                <a href="#branding-enquiry"
                    class="inline-flex min-h-12 items-center justify-center rounded-lg bg-brand px-7 py-3.5 text-sm font-semibold text-white shadow-[0_18px_42px_rgba(124,92,252,0.24)] transition duration-300 hover:-translate-y-0.5 hover:bg-brandDark">
                    Launch Campaign
                </a>
                <a href="#branding-channels"
                    class="inline-flex min-h-12 items-center justify-center rounded-lg border border-borderLight bg-bgWhite/90 px-7 py-3.5 text-sm font-semibold text-textPrimary shadow-sm transition duration-300 hover:-translate-y-0.5 hover:border-brand hover:text-brand">
                    Explore Audience Reach
                </a>
            </div>

            <div class="mt-8 grid gap-3 border-t border-borderLight pt-6 text-sm text-textSecondary sm:grid-cols-3">
                <div class="flex items-start gap-3">
                    <span class="mt-2 h-1.5 w-1.5 rounded-full bg-brand"></span>
                    <span>Audience segmentation</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-2 h-1.5 w-1.5 rounded-full bg-brand"></span>
                    <span>Multi-channel activation</span>
                </div>
                <div class="flex items-start gap-3">
                    <span class="mt-2 h-1.5 w-1.5 rounded-full bg-brand"></span>
                    <span>Performance reporting</span>
                </div>
            </div>
        </div>

        <div class="relative">
            <div class="absolute -left-5 top-10 hidden rounded-lg border border-borderLight bg-bgWhite px-4 py-3 shadow-[0_18px_44px_rgba(22,8,64,0.10)] lg:block">
                <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Live audience</p>
                <p class="mt-1 text-xl font-semibold text-textPrimary">48,200</p>
            </div>

            <div class="overflow-hidden rounded-xl border border-borderLight bg-bgWhite shadow-[0_28px_80px_rgba(22,8,64,0.12)]">
            <div class="flex items-center justify-between border-b border-borderLight px-5 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Campaign console</p>
                    <p class="mt-1 text-sm font-semibold text-textPrimary">Employer brand launch dashboard</p>
                </div>
                <span class="rounded-full border border-brand/20 bg-brandSoft px-3 py-1 text-xs font-semibold text-brand">Live plan</span>
            </div>

            <div class="bg-[linear-gradient(135deg,#F5F0FF,#ffffff_48%,#EEF5FF)] p-5">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Projected reach</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">48k</p>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Intent signals</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">1,860</p>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Segments</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">34</p>
                    </div>
                </div>

                <div class="mt-4 rounded-lg border border-borderLight bg-bgWhite p-5">
                    <div class="flex items-center justify-between">
                        <p class="font-semibold text-textPrimary">Campaign funnel</p>
                        <span class="rounded-full bg-bgSoft px-3 py-1 text-xs font-semibold text-brand">Weekly lift</span>
                    </div>
                    <div class="mt-5 space-y-4">
                        @foreach([['Awareness delivery', 88], ['Registration conversion', 64], ['Qualified interest', 42]] as $row)
                            <div>
                                <div class="mb-2 flex justify-between text-xs font-medium text-textSecondary">
                                    <span>{{ $row[0] }}</span>
                                    <span>{{ $row[1] }}%</span>
                                </div>
                                <div class="h-2 rounded-full bg-bgSoft">
                                    <div class="h-2 rounded-full bg-brand" style="width: {{ $row[1] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 grid gap-4 lg:grid-cols-[0.9fr_1.1fr]">
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Audience mix</p>
                        <div class="mt-4 space-y-3 text-xs text-textSecondary">
                            @foreach([['Engineering', 46], ['Management', 24], ['Design', 18], ['Early career', 12]] as $segment)
                                <div class="flex items-center gap-3">
                                    <span class="w-20">{{ $segment[0] }}</span>
                                    <span class="h-2 flex-1 rounded-full bg-bgSoft">
                                        <span class="block h-2 rounded-full bg-brand" style="width: {{ $segment[1] }}%"></span>
                                    </span>
                                    <span class="w-8 text-right font-semibold text-textPrimary">{{ $segment[1] }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <div class="flex items-center justify-between gap-4">
                            <p class="text-sm font-semibold text-textPrimary">Distribution queue</p>
                            <span class="text-xs font-semibold text-brand">4 channels</span>
                        </div>
                        <div class="mt-4 grid gap-2 text-xs font-medium text-textSecondary sm:grid-cols-2">
                            @foreach(['LMS placement', 'Webinar funnel', 'Community posts', 'College outreach'] as $channel)
                                <span class="rounded-md border border-borderLight bg-bgSoft px-3 py-2">{{ $channel }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</section>
