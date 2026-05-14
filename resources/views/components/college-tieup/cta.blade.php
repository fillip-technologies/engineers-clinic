<section id="partnership-enquiry" class="bg-bgWhite py-16 sm:py-20">
    <div class="mx-auto max-w-7xl px-6">
        <div class="overflow-hidden rounded-xl border border-borderLight bg-brandDark">
            <div class="grid gap-8 p-8 sm:p-10 lg:grid-cols-[1fr_auto] lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-secondary">Partnership enquiry</p>
                    <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                        Start a structured college tie-up discussion.
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/75">
                        Share your institution goals with our partnership team. We will help map tracks, student volume, reporting needs, and the right operating model.
                    </p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                    <button type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-partnership-discussion'))"
                        class="inline-flex items-center justify-center rounded-lg bg-secondary px-6 py-3 text-sm font-semibold text-brandDark transition hover:bg-white">
                        Request discussion
                    </button>
                    <a href="{{ route('signup', ['role' => 'college']) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Register college account
                    </a>
                    <a href="tel:+917545999990"
                        class="inline-flex items-center justify-center rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Call +91-75459-99990
                    </a>
                    <a href="tel:+917979030298"
                        class="inline-flex items-center justify-center rounded-lg border border-white/20 px-6 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                        Call +91-79790-30298
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
