<div x-data="{ enquiryOpen: false }" class="contents">
    <div @click="enquiryOpen = true">
        {{ $trigger }}
    </div>

    <div x-cloak x-show="enquiryOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[140] bg-slate-950/70 px-4 py-6 backdrop-blur-sm sm:px-6 lg:px-8"
        @click="enquiryOpen = false">
        <div class="flex min-h-full items-center justify-center">
            <div @click.stop
                class="relative w-full max-w-5xl overflow-hidden rounded-[2rem] bg-white shadow-2xl shadow-slate-950/30">
                <button type="button"
                    class="absolute right-4 top-4 z-10 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow-lg shadow-slate-300/50 transition hover:text-slate-900"
                    @click="enquiryOpen = false" aria-label="Close enquiry modal">
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 5l10 10" />
                        <path d="M15 5 5 15" />
                    </svg>
                </button>

                <div class="grid md:grid-cols-2">
                    <div class="relative min-h-[260px] overflow-hidden md:min-h-[620px]">
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80"
                            alt="Professional team working on laptops in a modern office"
                            class="h-full w-full object-cover" />
                        <div
                            class="absolute inset-0 bg-gradient-to-br from-bgDark/70 via-bgDarkSoft/55 to-secondary/35">
                        </div>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            <div class="max-w-sm rounded-[1.5rem] border border-white/10 bg-white/10 p-5 backdrop-blur-md">
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-primaryLight">
                                    Engineers Clinic
                                </p>
                                <h3 class="mt-3 text-2xl font-semibold text-white">
                                    Build the right next step with expert guidance
                                </h3>
                                <p class="mt-3 text-sm leading-7 text-slate-200">
                                    Share your details and our team will connect with you to understand your requirement,
                                    program interest, or partnership enquiry.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 sm:p-8 md:p-10">
                        <div class="max-w-md">
                            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-primary">Enquiry Form</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
                                Connect With Our Team
                            </h2>
                            <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
                                Tell us a little about yourself and we will reach out with the right guidance, next
                                steps, and program details.
                            </p>
                        </div>

                        <form method="POST" action="#" class="mt-8 space-y-5">
                            @csrf

                            <div>
                                <label for="full_name" class="text-sm font-medium text-slate-700">Full Name</label>
                                <input id="full_name" type="text" name="full_name" placeholder="Enter your full name"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-4 focus:ring-cyan-100" />
                            </div>

                            <div>
                                <label for="phone_number" class="text-sm font-medium text-slate-700">Phone Number</label>
                                <div class="mt-2 flex rounded-xl border border-slate-200 bg-slate-50 focus-within:border-primary focus-within:bg-white focus-within:ring-4 focus-within:ring-cyan-100">
                                    <div
                                        class="flex items-center rounded-l-xl border-r border-slate-200 px-4 text-sm font-semibold text-slate-600">
                                        +91
                                    </div>
                                    <input id="phone_number" type="tel" name="phone_number"
                                        placeholder="Enter your phone number"
                                        class="w-full rounded-r-xl bg-transparent px-4 py-3 text-sm text-slate-900 outline-none placeholder:text-slate-400" />
                                </div>
                            </div>

                            <div>
                                <label for="email_id" class="text-sm font-medium text-slate-700">Email ID</label>
                                <input id="email_id" type="email" name="email_id" placeholder="Enter your email id"
                                    class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white focus:ring-4 focus:ring-cyan-100" />
                            </div>

                            <label class="flex items-start gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                                <input type="checkbox" name="communication_consent"
                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-primary focus:ring-primary" />
                                <span>I agree to receive updates via Call, SMS, Email &amp; WhatsApp</span>
                            </label>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-primary to-secondary px-4 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:opacity-90">
                                Submit Enquiry
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
