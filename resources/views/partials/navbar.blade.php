<header
    class="sticky top-0 z-50 border-b border-borderLight/70 bg-bgWhite/82 backdrop-blur-xl"
    x-data="{
        mobileOpen: false,
        enterpriseOpen: false,
        moreOpen: false,
        mobileInternshipOpen: false,
        mobileEnterpriseOpen: false,
        mobileMoreOpen: false
    }"
>
    <div class="container-main flex items-center justify-between py-4">
        <a href="/" class="flex items-center gap-3" aria-label="Engineers Clinic home">
            <img src="/images/Engineers-clinic-logo-black.png" alt="Engineers Clinic" class="h-12 w-auto" />
        </a>

        <nav class="nav-shell hidden items-center gap-1 p-2 lg:flex">
            <a href="/" class="nav-link {{ request()->is('/') ? 'nav-link-active' : '' }}">Home</a>

            <x-mega-menu />

            <a href="{{ route('college.tieup') }}" class="nav-link {{ request()->routeIs('college.tieup') ? 'nav-link-active' : '' }}">
                College Tie-ups
            </a>

            <div class="relative" @mouseenter="enterpriseOpen = true" @mouseleave="enterpriseOpen = false">
                <button type="button" class="nav-link">
                    For Enterprises & Employers
                    <svg class="h-4 w-4 transition duration-200" :class="enterpriseOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="enterpriseOpen" x-transition x-cloak class="dropdown-panel absolute left-0 top-full mt-3 w-[340px] p-3">
                    <a href="{{ route('company.branding') }}" class="group flex items-start gap-4 rounded-control p-4 transition hover:bg-bgSoft">
                        <span class="grid h-12 w-12 place-items-center rounded-control bg-brand text-white">
                            <i class="fi fi-rr-apps text-lg"></i>
                        </span>
                        <span>
                            <span class="block text-sm font-bold text-textPrimary">Company For Branding</span>
                            <span class="mt-1 block text-caption">Promote your brand among students and colleges through campaigns.</span>
                        </span>
                    </a>

                    <a href="#" class="group mt-2 flex items-start gap-4 rounded-control p-4 transition hover:bg-bgSoft">
                        <span class="grid h-12 w-12 place-items-center rounded-control bg-brandSoft text-brand">
                            <i class="fi fi-rr-megaphone text-lg"></i>
                        </span>
                        <span>
                            <span class="block text-sm font-bold text-textPrimary">For Selling Services & Products</span>
                            <span class="mt-1 block text-caption">Reach verified students, colleges, and young professionals.</span>
                        </span>
                    </a>
                </div>
            </div>

            <div class="relative" @mouseenter="moreOpen = true" @mouseleave="moreOpen = false">
                <button type="button" class="nav-link">
                    More
                    <svg class="h-4 w-4 transition duration-200" :class="moreOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>

                <div x-show="moreOpen" x-transition x-cloak class="dropdown-panel absolute right-0 top-full mt-3 w-[260px] p-3">
                    <a href="#" class="block rounded-control px-4 py-3 text-sm font-semibold text-textSecondary transition hover:bg-bgSoft hover:text-brand">AI Tools</a>
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-referral-popup'))" class="mt-1 block rounded-control px-4 py-3 text-sm font-semibold text-textSecondary transition hover:bg-bgSoft hover:text-brand">Refer & Earn</a>
                    <a href="#" class="mt-1 block rounded-control px-4 py-3 text-sm font-semibold text-textSecondary transition hover:bg-bgSoft hover:text-brand">Blog</a>
                    <a href="{{ route('about') }}" class="mt-1 block rounded-control px-4 py-3 text-sm font-semibold text-textSecondary transition hover:bg-bgSoft hover:text-brand">About Us</a>
                </div>
            </div>
        </nav>

        <div class="flex items-center gap-3">
            <a href="#"
                @click.prevent="window.dispatchEvent(new CustomEvent('open-referral-popup'))"
                class="group relative hidden items-center gap-2 overflow-hidden rounded-full border border-secondary/50 bg-secondarySoft px-4 py-2.5 text-sm font-black text-textPrimary shadow-[0_10px_28px_rgba(245,200,66,0.22)] transition hover:-translate-y-0.5 hover:border-secondary hover:bg-secondary lg:inline-flex">
                <span class="absolute inset-0 animate-ping rounded-full bg-secondary/25 opacity-40"></span>
                <span class="relative grid h-5 w-5 place-items-center rounded-full bg-white text-brand shadow-sm">
                    <i class="fi fi-rr-gift text-xs"></i>
                </span>
                <span class="relative">Refer & Earn</span>
            </a>

            <a href="{{ url('/login') }}" class="btn-primary hidden lg:inline-flex">Login</a>

            <button
                type="button"
                class="grid h-11 w-11 place-items-center rounded-control border border-borderLight bg-bgWhite text-textPrimary shadow-sm lg:hidden"
                @click="mobileOpen = !mobileOpen"
                aria-label="Toggle menu"
            >
                <svg x-show="!mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="mobileOpen" x-transition x-cloak class="border-t border-borderLight bg-bgWhite lg:hidden">
        <nav class="container-main space-y-2 py-5">
            <a href="/" class="block rounded-control bg-brand px-5 py-4 text-sm font-bold text-white">Home</a>
            <a href="#"
                @click.prevent="mobileOpen = false; window.dispatchEvent(new CustomEvent('open-referral-popup'))"
                class="relative flex items-center justify-center gap-2 overflow-hidden rounded-control border border-secondary/50 bg-secondarySoft px-5 py-4 text-sm font-black text-textPrimary shadow-sm">
                <span class="absolute inset-0 animate-pulse bg-secondary/20"></span>
                <i class="fi fi-rr-gift relative text-brand"></i>
                <span class="relative">Refer & Earn</span>
            </a>

            <div class="rounded-control border border-borderLight bg-bgWhite">
                <button type="button" class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-bold text-textPrimary" @click="mobileInternshipOpen = !mobileInternshipOpen">
                    <span>Internships</span>
                    <svg class="h-4 w-4 transition" :class="mobileInternshipOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="mobileInternshipOpen" x-transition class="border-t border-borderLight p-4">
                    <x-mega-menu mobile />
                </div>
            </div>

            <a href="{{ route('college.tieup') }}" class="block rounded-control px-5 py-4 text-sm font-bold text-textSecondary transition hover:bg-bgSoft hover:text-brand">College Tie-ups</a>

            <div class="rounded-control border border-borderLight bg-bgWhite">
                <button type="button" class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-bold text-textPrimary" @click="mobileEnterpriseOpen = !mobileEnterpriseOpen">
                    <span>For Enterprises & Employers</span>
                    <svg class="h-4 w-4 transition" :class="mobileEnterpriseOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="mobileEnterpriseOpen" x-transition class="space-y-2 border-t border-borderLight p-4">
                    <a href="{{ route('company.branding') }}" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">Company For Branding</a>
                    <a href="#" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">For Selling Services & Products</a>
                </div>
            </div>

            <div class="rounded-control border border-borderLight bg-bgWhite">
                <button type="button" class="flex w-full items-center justify-between px-5 py-4 text-left text-sm font-bold text-textPrimary" @click="mobileMoreOpen = !mobileMoreOpen">
                    <span>More</span>
                    <svg class="h-4 w-4 transition" :class="mobileMoreOpen ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.51a.75.75 0 01-1.08 0l-4.25-4.51a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="mobileMoreOpen" x-transition class="space-y-2 border-t border-borderLight p-4">
                    <a href="#" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">AI Tools</a>
                    <a href="#" @click.prevent="mobileOpen = false; window.dispatchEvent(new CustomEvent('open-referral-popup'))" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">Refer & Earn</a>
                    <a href="#" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">Blog</a>
                    <a href="{{ route('about') }}" class="block rounded-control bg-bgSoft px-4 py-3 text-sm font-semibold text-textPrimary">About Us</a>
                </div>
            </div>
        </nav>
    </div>
</header>
