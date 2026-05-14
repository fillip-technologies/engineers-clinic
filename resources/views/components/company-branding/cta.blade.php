<section id="branding-enquiry" class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="relative overflow-hidden rounded-xl border border-borderLight bg-brandDark shadow-[0_24px_70px_rgba(22,8,64,0.18)]">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_18%_18%,rgba(255,255,255,0.16),transparent_30%),linear-gradient(120deg,rgba(37,99,235,0.28),transparent_48%)]"></div>
            <div class="relative grid gap-8 p-8 sm:p-12 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-secondary">Campaign onboarding</p>
                    <h2 class="mt-4 max-w-3xl text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                        Launch a measurable audience reach campaign with Engineers Clinic.
                    </h2>
                    <p class="mt-4 max-w-2xl text-base leading-8 text-white/75">
                        Share your campaign objective. We will help define audience fit, channel mix, activation flow, reporting structure, and next best campaign motion.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-2 text-xs font-semibold text-white/75">
                        @foreach(['Media kit', 'Audience plan', 'Campaign calendar', 'Reporting scope'] as $item)
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1.5">{{ $item }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <a href="mailto:info@engineersclinic.com?subject=Company%20Branding%20Campaign%20Enquiry"
                        class="inline-flex min-h-12 items-center justify-center rounded-lg bg-secondary px-7 py-3.5 text-sm font-semibold text-brandDark shadow-[0_16px_34px_rgba(245,200,66,0.18)] transition hover:-translate-y-0.5 hover:bg-white">
                        Schedule Branding Call
                    </a>
                    <a href="tel:+917545999990"
                        class="inline-flex min-h-12 items-center justify-center rounded-lg border border-white/20 px-7 py-3.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/10">
                        Request Media Kit
                    </a>
                    <a href="tel:+917979030298"
                        class="inline-flex min-h-12 items-center justify-center rounded-lg border border-white/20 px-7 py-3.5 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/10">
                        Call +91-79790-30298
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
