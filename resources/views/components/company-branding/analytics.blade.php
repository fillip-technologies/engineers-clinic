<section class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto grid max-w-7xl gap-10 px-6 lg:grid-cols-[0.74fr_1fr] lg:items-center">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">Reporting layer</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Performance visibility for marketing, talent, and leadership teams.
            </h2>
            <p class="mt-5 text-base leading-8 text-textSecondary">
                Every activation can be reviewed through a practical reporting layer covering audience reach, registrations, engagement quality, channel performance, and recommended follow-ups.
            </p>
            <div class="mt-7 grid gap-3 sm:grid-cols-2">
                @foreach(['Audience reach', 'Engagement quality', 'Channel lift', 'Qualified actions'] as $metric)
                    <div class="rounded-lg border border-borderLight bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">{{ $metric }}</div>
                @endforeach
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-borderLight bg-bgWhite shadow-[0_24px_70px_rgba(22,8,64,0.10)]">
            <div class="flex items-center justify-between border-b border-borderLight px-5 py-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Campaign report</p>
                    <p class="mt-1 font-semibold text-textPrimary">Employer branding webinar series</p>
                </div>
                <span class="rounded-full bg-bgSoft px-3 py-1 text-xs font-semibold text-brand">Export ready</span>
            </div>

            <div class="bg-bgSoft p-5">
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Registrations</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">2,430</p>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Engagement</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">61%</p>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-4 shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-textMuted">Qualified actions</p>
                        <p class="mt-2 text-2xl font-semibold text-textPrimary">380</p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg border border-borderLight bg-bgWhite p-5 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <p class="font-semibold text-textPrimary">Channel performance</p>
                        <span class="rounded-full bg-bgSoft px-3 py-1 text-xs font-semibold text-brand">Attribution view</span>
                    </div>
                    <div class="mt-5 space-y-4">
                        @foreach([['LMS placements', 76], ['Webinar reminders', 69], ['College outreach', 58], ['Community media', 44]] as $row)
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

                <div class="mt-5 grid gap-4 sm:grid-cols-[1.1fr_0.9fr]">
                    <div class="rounded-lg border border-borderLight bg-bgWhite p-5 shadow-sm">
                        <p class="text-sm font-semibold text-textPrimary">Audience quality signals</p>
                        <div class="mt-4 flex items-end gap-2">
                            @foreach([42, 68, 54, 78, 64, 86, 72] as $height)
                                <span class="flex-1 rounded-t-md bg-brand/80" style="height: {{ $height }}px"></span>
                            @endforeach
                        </div>
                    </div>
                    <div class="rounded-lg border border-borderLight bg-brandDark p-5 text-white shadow-sm">
                        <p class="text-xs font-semibold uppercase tracking-[0.14em] text-white/60">Recommendation</p>
                        <p class="mt-3 text-sm leading-7 text-white/80">Retarget webinar registrants with role-specific content and a follow-up hiring campaign.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
