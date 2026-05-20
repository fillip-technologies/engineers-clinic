@php
    $programs = [
        [
            'badge' => 'Limited Offer',
            'title' => 'UI/UX Design Fundamentals',
            'image' => '/images/master1.png',
            'outcome' => '',
            'meta' => [],
            'support' => '',
        ],
        [
            'badge' => 'Career Starter',
            'title' => 'HR Recruitment Basics & ATS Navigation',
            'image' => '/images/master2.png',
            'outcome' => '',
            'meta' => [],
            'support' => '',
        ],
        [
            'badge' => 'New Launch',
            'title' => 'Foundational Legal Research & Writing',
            'image' => '/images/master3.png',
            'outcome' => '',
            'meta' => [],
            'support' => '',
        ],
    ];
@endphp

<section
    x-data="{ enrollmentOpen: false }"
    x-init="$watch('enrollmentOpen', value => {
        document.documentElement.classList.toggle('overflow-hidden', value);
        document.body.classList.toggle('overflow-hidden', value);
    })"
    @keydown.escape.window="enrollmentOpen = false"
    class="section-surface section-padding-sm relative isolate overflow-hidden"
>
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="absolute left-0 top-10 h-64 w-64 rounded-full bg-glowPurple blur-3xl"></div>
        <div class="absolute bottom-10 right-0 h-72 w-72 rounded-full bg-glowBlue blur-3xl"></div>
    </div>

    <div class="container-main">
        <div class="max-w-4xl">
            <span class="badge-pill">
                <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                MasterInternship Launch Offer
            </span>

            <h2 class="text-section mt-5 max-w-3xl">
                Start with one real task, earn one certificate, pay only
                <span class="gradient-text">&#8377;9</span>
            </h2>

            <p class="text-body-lg mt-5 max-w-2xl">
                A low-risk internship sprint for students who want proof of work fast. Pick a track, complete the guided task, and unlock your certificate.
            </p>
        </div>

        <div class="mt-10">
            <div class="grid gap-6 lg:grid-cols-3">
                @foreach ($programs as $program)
                    <article class="group flex min-h-full flex-col overflow-hidden rounded-card border border-borderLight bg-white shadow-card transition duration-300 hover:-translate-y-1 hover:shadow-glass">
                        <div class="relative p-3">
                            <div class="absolute left-6 top-6 z-10 rounded-full bg-white/90 px-3 py-2 text-xs font-bold text-brand shadow-card backdrop-blur">
                                {{ $program['badge'] }}
                            </div>

                            <div class="absolute bottom-6 right-6 z-10 rounded-full bg-brandDark px-3 py-2 text-xs font-bold text-white shadow-card">
                                Offer Live
                            </div>

                            <div class="relative h-56 overflow-hidden rounded-[20px] bg-bgSoft">
                                <img
                                    src="{{ $program['image'] }}"
                                    alt="{{ $program['title'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                                <div class="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-[#160840]/60 to-transparent"></div>
                            </div>
                        </div>

                        <div class="flex flex-1 flex-col px-6 pb-6 pt-3">
                            <h3 class="text-card-title">
                                {{ $program['title'] }}
                            </h3>

                            @if (!empty($program['outcome']))
                                <p class="text-body mt-3">
                                    {{ $program['outcome'] }}
                                </p>
                            @endif

                            <div class="mt-5 rounded-[20px] border border-borderLight bg-gradient-to-br from-white to-bgSoft p-4">
                                <div class="flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-caption font-bold uppercase tracking-[0.12em] text-brand">Launch price</p>
                                        <div class="mt-1 flex items-end gap-2">
                                            <span class="text-4xl font-black leading-none text-textPrimary">&#8377;9</span>
                                            <span class="pb-1 text-sm font-bold text-textMuted line-through">&#8377;499</span>
                                        </div>
                                    </div>

                                    <span class="rounded-full bg-secondary px-3 py-2 text-xs font-black text-textPrimary">
                                        Save 98%
                                    </span>
                                </div>
                                <p class="text-caption mt-3">Includes task brief, submission flow, and certificate eligibility.</p>
                            </div>

                            @if (!empty($program['support']) || !empty($program['meta']))
                                <div class="mt-5 space-y-3">
                                    @if (!empty($program['support']))
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brandSoft text-xs font-black text-brand">
                                                &check;
                                            </span>
                                            <span class="text-body">{{ $program['support'] }}</span>
                                        </div>
                                    @endif

                                    @foreach ($program['meta'] as $meta)
                                        <div class="flex items-center gap-3">
                                            <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brandSoft text-xs font-black text-brand">
                                                &check;
                                            </span>
                                            <span class="text-body">{{ $meta }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="mt-auto pt-7">
                                <button type="button" class="btn-primary w-full" @click="enrollmentOpen = true">
                                    Enroll Now
                                </button>
                                {{-- <a href="#courses" class="mt-3 inline-flex w-full justify-center text-sm font-bold text-brand transition hover:text-brandDark">
                                    View task details
                                </a> --}}
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- <div class="mt-8 flex flex-col items-start justify-between gap-4 rounded-card border border-brand/20 bg-brandDark p-5 shadow-glass sm:flex-row sm:items-center">
                <div>
                    <p class="text-sm font-bold text-white">Still deciding? Start with the lowest-risk track.</p>
                    <p class="text-caption mt-1 text-white/70">For &#8377;9, users can try the experience, complete one real task, and see the value immediately.</p>
                </div>

                <a href="#courses" class="btn-primary w-full sm:w-auto">
                    Get my &#8377;9 certificate sprint
                </a>
            </div> -->
        </div>
    </div>

    <div
        x-cloak
        x-show="enrollmentOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[140] overflow-y-auto bg-slate-950/70 px-4 py-6 backdrop-blur-sm sm:px-6 lg:px-8"
        role="dialog"
        aria-modal="true"
        @click="enrollmentOpen = false"
    >
        <div class="flex min-h-full items-center justify-center">
            <div
                x-show="enrollmentOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-4 scale-[0.98] opacity-0"
                x-transition:enter-end="translate-y-0 scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 scale-100 opacity-100"
                x-transition:leave-end="translate-y-4 scale-[0.98] opacity-0"
                class="relative w-full max-w-3xl"
                @click.stop
            >
                <button
                    type="button"
                    class="absolute right-4 top-4 z-20 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/90 text-slate-500 shadow-lg shadow-slate-300/50 transition hover:text-slate-900"
                    @click="enrollmentOpen = false"
                    aria-label="Close enrollment form"
                >
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 5l10 10" />
                        <path d="M15 5 5 15" />
                    </svg>
                </button>

                @include('form.enrollment-form', ['selectedLevel' => 'Beginner'])
            </div>
        </div>
    </div>
</section>
