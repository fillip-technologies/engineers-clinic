@php
    $programs = [
        [
            'badge' => 'Limited Offer',
            'title' => 'UI/UX Design Fundamentals',
            'image' => '/images/master1.png',
            'outcome' => 'Design a polished app screen and submit a practical UI task.',
            'meta' => ['Guided task brief', 'Certificate Included', 'Portfolio-ready output'],
            'support' => 'Beginner friendly',
            'accent' => 'from-[#7C5CFC] to-[#B8DEFF]',
        ],
        [
            'badge' => 'Career Starter',
            'title' => 'HR Recruitment Basics & ATS Navigation',
            'image' => '/images/master2.png',
            'outcome' => 'Practice a hiring workflow with shortlisting and ATS basics.',
            'meta' => ['Project submission', 'Certificate Included', 'Job workflow practice'],
            'support' => 'ATS workflow practice',
            'accent' => 'from-[#160840] to-[#7C5CFC]',
        ],
        [
            'badge' => 'New Launch',
            'title' => 'Foundational Legal Research & Writing',
            'image' => '/images/master3.png',
            'outcome' => 'Complete a guided legal research and writing mini assignment.',
            'meta' => ['Legal writing task', 'Certificate Included', 'Research based practice'],
            'support' => 'Research based task',
            'accent' => 'from-[#3D2090] to-[#F5C842]',
        ],
    ];
@endphp

<section class="section-surface section-padding-sm relative isolate overflow-hidden">
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

                            <p class="text-body mt-3">
                                {{ $program['outcome'] }}
                            </p>

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

                            <div class="mt-5 h-1.5 overflow-hidden rounded-full bg-bgSoft">
                                <div class="h-full w-2/3 rounded-full bg-gradient-to-r {{ $program['accent'] }}"></div>
                            </div>

                            <div class="mt-5 space-y-3">
                                <div class="flex items-center gap-3">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brandSoft text-xs font-black text-brand">
                                        &check;
                                    </span>
                                    <span class="text-body">{{ $program['support'] }}</span>
                                </div>

                                @foreach ($program['meta'] as $meta)
                                    <div class="flex items-center gap-3">
                                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brandSoft text-xs font-black text-brand">
                                            &check;
                                        </span>
                                        <span class="text-body">{{ $meta }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-auto pt-7">
                                <a href="#courses" class="btn-primary w-full">Start this sprint for &#8377;9</a>
                                <a href="#courses" class="mt-3 inline-flex w-full justify-center text-sm font-bold text-brand transition hover:text-brandDark">
                                    View task details
                                </a>
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
</section>
