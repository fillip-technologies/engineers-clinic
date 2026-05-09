<header class="sticky top-0 z-50 border-b border-white/10 bg-white/80 backdrop-blur-xl"
    x-data="{
        mobileOpen: false,
        enterpriseOpen: false,
        moreOpen: false,
        mobileInternshipOpen: false,
        mobileInternshipLevel: 'beginner',
        mobileEnterpriseOpen: false,
        mobileMoreOpen: false
    }">

    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

        <!-- Logo -->
        <a href="/" class="flex items-center gap-3">
            <img src="/images/Engineers-clinic-logo-black.png" class="h-12 w-auto" />
        </a>

        <!-- Desktop Menu -->
        <nav class="hidden items-center gap-1 rounded-full border border-black/5 bg-[#f7f7f7] p-2 shadow-sm lg:flex">

            <!-- Home -->
            <a href="/"
                class="rounded-full bg-black px-5 py-2.5 text-sm font-medium text-white transition hover:bg-neutral-800">
                Home
            </a>

            <!-- Internship Mega Menu -->
            <x-mega-menu />

            <!-- College Tie-up -->
            <a href="{{ route('college.tieup') }}"
                class="rounded-full px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-white hover:shadow-sm">
                College Tie-ups
            </a>

            <!-- Enterprise Dropdown -->
            <div class="relative" @mouseenter="enterpriseOpen = true" @mouseleave="enterpriseOpen = false">

                <button type="button"
                    class="flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-white hover:shadow-sm">

                    For Enterprises & Employers

                    <svg class="h-4 w-4 transition duration-200"
                        :class="enterpriseOpen ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="enterpriseOpen"
                    x-transition
                    x-cloak
                    class="absolute left-0 top-full mt-3 w-[340px] overflow-hidden rounded-3xl border border-black/5 bg-white p-3 shadow-2xl">

                    <a href="{{ route('company.branding') }}"
                        class="group flex items-start gap-4 rounded-2xl p-4 transition hover:bg-[#f7f7f7]">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-black text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-black">
                                Company For Branding
                            </h4>

                            <p class="mt-1 text-xs leading-relaxed text-neutral-500">
                                Promote your brand among students & colleges through campaigns.
                            </p>
                        </div>
                    </a>

                    <a href="#"
                        class="group mt-2 flex items-start gap-4 rounded-2xl p-4 transition hover:bg-[#f7f7f7]">

                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-2xl bg-neutral-100 text-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="1.7"
                                    d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3-1.343 3-3-1.343-3-3-3zm0-5v2m0 14v2m9-9h-2M5 12H3" />
                            </svg>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-black">
                                For Selling Services & Products
                            </h4>

                            <p class="mt-1 text-xs leading-relaxed text-neutral-500">
                                Reach verified students, colleges & young professionals.
                            </p>
                        </div>
                    </a>

                </div>
            </div>

            <!-- More Dropdown -->
            <div class="relative" @mouseenter="moreOpen = true" @mouseleave="moreOpen = false">

                <button type="button"
                    class="flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-medium text-neutral-700 transition hover:bg-white hover:shadow-sm">

                    More

                    <svg class="h-4 w-4 transition duration-200"
                        :class="moreOpen ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="moreOpen"
                    x-transition
                    x-cloak
                    class="absolute right-0 top-full mt-3 w-[260px] overflow-hidden rounded-3xl border border-black/5 bg-white p-3 shadow-2xl">

                    <a href="#"
                        class="flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#f7f7f7]">
                        AI Tools
                    </a>

                    <a href="#"
                        class="mt-1 flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#f7f7f7]">
                        Refer & Earn
                    </a>

                    <a href="#"
                        class="mt-1 flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#f7f7f7]">
                        Blog
                    </a>

                    <a href="#"
                        class="mt-1 flex items-center rounded-2xl px-4 py-3 text-sm font-medium text-neutral-700 transition hover:bg-[#f7f7f7]">
                        About Us
                    </a>

                </div>
            </div>

        </nav>

        <!-- Right Side -->
        <div class="flex items-center gap-3">

            <a href="{{ url('/login') }}"
                class="hidden rounded-full bg-black px-6 py-3 text-sm font-semibold text-white transition hover:bg-neutral-800 lg:inline-flex">
                Login
            </a>

            <!-- Mobile Toggle -->
            <button type="button"
                class="flex h-11 w-11 items-center justify-center rounded-2xl border border-black/10 bg-white text-black lg:hidden"
                @click="mobileOpen = !mobileOpen">

                <svg x-show="!mobileOpen" x-cloak class="h-6 w-6" fill="none"
                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>

                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none"
                    stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen"
        x-transition
        x-cloak
        class="border-t border-black/5 bg-white lg:hidden">

        <nav class="space-y-2 px-6 py-6">

            <a href="/"
                class="block rounded-2xl bg-black px-5 py-4 text-sm font-medium text-white">
                Home
            </a>

            <!-- Internship -->
            <div class="rounded-2xl border border-black/10">

                <button type="button"
                    class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-medium text-black"
                    @click="mobileInternshipOpen = !mobileInternshipOpen">

                    <span>Internships</span>

                    <svg class="h-4 w-4 transition"
                        :class="mobileInternshipOpen ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="mobileInternshipOpen"
                    x-transition
                    class="border-t border-black/5 p-4">

                    <x-mega-menu mobile />

                </div>
            </div>

            <a href="{{ route('college.tieup') }}"
                class="block rounded-2xl px-5 py-4 text-sm font-medium text-neutral-700 transition hover:bg-[#f7f7f7]">
                College Tie-ups
            </a>

            <!-- Enterprise -->
            <div class="rounded-2xl border border-black/10">

                <button type="button"
                    class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-medium text-black"
                    @click="mobileEnterpriseOpen = !mobileEnterpriseOpen">

                    <span>For Enterprises & Employers</span>

                    <svg class="h-4 w-4 transition"
                        :class="mobileEnterpriseOpen ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="mobileEnterpriseOpen"
                    x-transition
                    class="space-y-2 border-t border-black/5 p-4">

                    <a href="{{ route('company.branding') }}"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        Company For Branding
                    </a>

                    <a href="#"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        For Selling Services & Products
                    </a>

                </div>
            </div>

            <!-- More -->
            <div class="rounded-2xl border border-black/10">

                <button type="button"
                    class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-medium text-black"
                    @click="mobileMoreOpen = !mobileMoreOpen">

                    <span>More</span>

                    <svg class="h-4 w-4 transition"
                        :class="mobileMoreOpen ? 'rotate-180' : ''"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="mobileMoreOpen"
                    x-transition
                    class="space-y-2 border-t border-black/5 p-4">

                    <a href="#"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        AI Tools
                    </a>

                    <a href="#"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        Refer & Earn
                    </a>

                    <a href="#"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        Blog
                    </a>

                    <a href="#"
                        class="block rounded-xl bg-[#f7f7f7] px-4 py-3 text-sm font-medium text-black">
                        About Us
                    </a>

                </div>
            </div>

            <!-- Login -->
            <a href="{{ url('/login') }}"
                class="mt-4 flex items-center justify-center rounded-2xl bg-black px-5 py-4 text-sm font-semibold text-white">
                Login
            </a>

        </nav>
    </div>

</header>
