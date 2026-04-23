<header class="w-full bg-bgMain" x-data="{ mobileOpen: false, internshipOpen: false, mobileInternshipOpen: false }">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <div class="text-xl font-semibold text-textPrimary">
            Engineers <span class="text-textPrimary">Clinic</span>
        </div>

        <nav class="hidden items-center gap-2 px-3 py-2 md:flex">
            <a href="#"
                class="rounded-full bg-bgWhite px-4 py-2 text-sm font-medium text-textPrimary shadow-sm">
                Home
            </a>

            <div class="relative" @mouseenter="internshipOpen = true" @mouseleave="internshipOpen = false">
                <button type="button"
                    class="flex items-center gap-2 rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite"
                    @click="internshipOpen = !internshipOpen">
                    <span>Internship</span>
                    <svg class="h-4 w-4 transition" :class="internshipOpen ? 'rotate-180' : ''" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="internshipOpen" x-cloak x-transition
                    class="absolute left-0 top-full z-50 mt-3 w-[26rem] rounded-3xl border border-borderLight bg-white p-5 shadow-2xl shadow-glowGreen/10">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">AI Remote Internships</p>
                            <p class="mt-1 text-xs text-textMuted">Our Programs</p>
                            <div class="mt-3 space-y-2">
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Computer Science & IT</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Core Engineering (Mechanical, Civil)</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Business & Management</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Law & Humanities</a>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary">Corporate Internships</p>
                            <p class="mt-1 text-xs text-textMuted">Job Board</p>
                            <div class="mt-3 space-y-2">
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Browse by Branch</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Browse by Location / Remote</a>
                                <a href="#" class="block rounded-2xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Pre-Placement Offers (PPO)</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#"
                class="rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                For Enterprise & Employers
            </a>

            <a href="#"
                class="rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                AI Tools
            </a>

            <a href="#"
                class="relative rounded-full px-4 py-2 text-sm font-medium text-textSecondary transition hover:bg-bgWhite">
                College Tie-up
                <span class="absolute -right-2 -top-2 rounded-full bg-textPrimary px-2 py-[2px] text-[10px] text-white">
                    NEW
                </span>
            </a>
        </nav>

        <div class="flex items-center gap-3">
            <a href="{{ url('/login') }}"
                class="hidden rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-brandDark sm:inline-block">
                Login
            </a>

            <button type="button"
                class="inline-flex items-center justify-center rounded-lg border border-borderLight bg-white p-2 text-textPrimary transition hover:bg-bgSoft md:hidden"
                @click="mobileOpen = !mobileOpen" :aria-expanded="mobileOpen.toString()" aria-label="Toggle menu">
                <svg x-show="!mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-cloak x-transition class="border-t border-borderLight bg-white md:hidden">
        <nav class="mx-auto max-w-7xl space-y-2 px-6 py-5">
            <a href="#"
                class="block rounded-2xl bg-bgSoft px-4 py-3 text-sm font-medium text-textPrimary">
                Home
            </a>

            <div class="rounded-2xl border border-borderLight">
                <button type="button"
                    class="flex w-full items-center justify-between px-4 py-3 text-left text-sm font-medium text-textPrimary"
                    @click="mobileInternshipOpen = !mobileInternshipOpen">
                    <span>Internship</span>
                    <svg class="h-4 w-4 transition" :class="mobileInternshipOpen ? 'rotate-180' : ''" viewBox="0 0 20 20"
                        fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="mobileInternshipOpen" x-cloak x-transition class="border-t border-borderLight px-4 py-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">AI Remote Internships</p>
                        <p class="mt-1 text-xs text-textMuted">Our Programs</p>
                        <div class="mt-3 space-y-1">
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Computer Science & IT</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Core Engineering (Mechanical, Civil)</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Business & Management</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Law & Humanities</a>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-secondary">Corporate Internships</p>
                        <p class="mt-1 text-xs text-textMuted">Job Board</p>
                        <div class="mt-3 space-y-1">
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Browse by Branch</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Browse by Location / Remote</a>
                            <a href="#" class="block rounded-xl px-3 py-2 text-sm text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">Pre-Placement Offers (PPO)</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                For Enterprise & Employers
            </a>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                AI Tools
            </a>

            <a href="#"
                class="block rounded-2xl px-4 py-3 text-sm font-medium text-textSecondary transition hover:bg-bgSoft hover:text-textPrimary">
                College Tie-up
            </a>

            <a href="{{ url('/login') }}"
                class="mt-2 inline-flex w-full items-center justify-center rounded-lg bg-brand px-5 py-3 text-sm font-semibold text-white shadow-md transition hover:bg-brandDark">
                Login
            </a>
        </nav>
    </div>

</header>
