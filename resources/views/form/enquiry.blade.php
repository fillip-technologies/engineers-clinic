@props([
    'course' => [],
])

<!-- Modal Popup (preserved with cleaner styling) -->
<div x-cloak
    x-show="enquiryOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/50 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true">

    <div class="absolute inset-0" @click="enquiryOpen = false"></div>

    <div x-show="enquiryOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-y-4 scale-[0.98] opacity-0"
        x-transition:enter-end="translate-y-0 scale-100 opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="translate-y-0 scale-100 opacity-100"
        x-transition:leave-end="translate-y-4 scale-[0.98] opacity-0"
        class="relative w-full max-w-4xl overflow-hidden rounded-2xl bg-white shadow-xl lg:grid lg:grid-cols-2">

        <button type="button"
            @click="enquiryOpen = false"
            class="absolute right-4 top-4 z-10 flex h-8 w-8 items-center justify-center rounded-full text-slate-400 transition hover:bg-slate-100 hover:text-slate-600"
            aria-label="Close enquiry form">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
            </svg>
        </button>

        <!-- Modal Left Image Section -->
        <div class="relative hidden min-h-[500px] bg-slate-100 lg:block">
            <img src="/images/college-tie-up-illustration.png"
                alt="Engineers Clinic counselling team"
                class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
            <div class="absolute bottom-6 left-6 right-6 rounded-xl border border-white/20 bg-white/90 p-4 backdrop-blur-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-600">
                    {{ $course['level'] ?? 'Internship' }} Guidance
                </p>
                <p class="mt-1.5 text-sm font-medium text-slate-900">
                    Get course guidance for {{ $course['title'] ?? 'your selected internship track' }}
                </p>
            </div>
        </div>

        <!-- Modal Right Form -->
        <div class="p-6 sm:p-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Connect With Our Team</h2>
            <p class="mt-2 text-sm text-slate-500">Fill your details and our team will contact you shortly.</p>

            <form method="POST" action="#" class="mt-6 space-y-5">
                @csrf
                <input type="hidden" name="course_slug" value="{{ $course['slug'] ?? '' }}">
                <input type="hidden" name="course_title" value="{{ $course['title'] ?? '' }}">

                <div>
                    <label class="block text-sm font-medium text-slate-700">Full Name *</label>
                    <input type="text"
                        name="name"
                        placeholder="Enter your full name"
                        class="mt-1.5 block w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Phone Number *</label>
                    <div class="mt-1.5 flex">
                        <span class="inline-flex items-center rounded-l-lg border border-r-0 border-slate-200 bg-slate-50 px-3 text-sm text-slate-600">+91</span>
                        <input type="tel"
                            name="phone"
                            placeholder="98765 43210"
                            class="block w-full rounded-r-lg border border-slate-200 px-4 py-2.5 text-sm text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Email ID *</label>
                    <input type="email"
                        name="email"
                        placeholder="you@example.com"
                        class="mt-1.5 block w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm text-slate-900 transition focus:border-slate-400 focus:outline-none focus:ring-1 focus:ring-slate-400">
                </div>

                <label class="flex items-start gap-3 text-sm text-slate-500">
                    <input type="checkbox"
                        name="consent"
                        class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                    <span>I agree to receive updates via Call, SMS, Email & WhatsApp from Engineers Clinic.</span>
                </label>

                <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-800">
                    Submit Enquiry
                </button>
            </form>
        </div>
    </div>
</div>
