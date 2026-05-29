@extends('layouts.app')

@section('content')
<section class="relative overflow-hidden bg-bgWhite">
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-borderLight to-transparent"></div>
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(115deg,rgba(245,240,255,0.88),rgba(255,255,255,0.94)_44%,rgba(238,245,255,0.82))]"></div>

    <div class="relative mx-auto grid max-w-7xl gap-10 px-6 py-14 sm:py-16 lg:grid-cols-[1fr_0.86fr] lg:items-center lg:py-20">
        <div>
            <div class="inline-flex items-center gap-3 rounded-full border border-borderLight bg-bgWhite/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-brand shadow-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                {{ $about['eyebrow'] }}
            </div>

            <h1 class="mt-6 max-w-3xl text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-[3.8rem] lg:leading-[1.04]">
                Building practical careers for modern learners.
            </h1>

            <p class="mt-5 max-w-2xl text-base leading-8 text-textSecondary sm:text-lg">
                Engineers Clinic helps students move from theory to real work through focused internships, guided projects, and career-ready skill tracks.
            </p>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                <a href="/#heroSection"
                    class="inline-flex items-center justify-center rounded-lg bg-brand px-6 py-3 text-sm font-semibold text-white shadow-[0_16px_34px_rgba(124,92,252,0.20)] transition hover:bg-brandDark">
                    Explore internships
                </a>
                <a href="{{ route('college.tieup') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-borderLight bg-bgWhite px-6 py-3 text-sm font-semibold text-textPrimary transition hover:border-brand hover:text-brand">
                    Partner with us
                </a>
            </div>
        </div>

        <div class="relative">
            <div class="overflow-hidden rounded-xl border border-borderLight bg-bgWhite shadow-[0_24px_70px_rgba(22,8,64,0.10)]">
                <img src="/images/college-image.png" alt="Students learning with Engineers Clinic" class="aspect-[5/4] w-full object-cover">
                <div class="border-t border-borderLight bg-bgWhite p-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-textMuted">Our focus</p>
                    <p class="mt-2 text-lg font-semibold text-textPrimary">Skills that turn into real output</p>
                </div>
            </div>

            <div class="mt-4 grid gap-4 sm:grid-cols-3">
                @foreach ($about['stats'] as $stat)
                <div class="rounded-lg border border-borderLight bg-bgWhite p-4">
                    <p class="text-2xl font-semibold text-textPrimary">{{ $stat['value'] }}</p>
                    <p class="mt-1 text-sm text-textSecondary">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="bg-bgSoft py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="grid gap-5 md:grid-cols-3">
            @foreach ($about['pillars'] as $pillar)
            <article class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm">
                <div class="mb-5 h-1.5 w-14 rounded-full bg-gradient-to-r from-brand to-secondary"></div>
                <h3 class="text-xl font-semibold text-textPrimary">{{ $pillar['title'] }}</h3>
                <p class="mt-3 leading-7 text-textSecondary">{{ $pillar['description'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-bgWhite py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-8 max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">Leadership</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                The people behind Engineers Clinic.
            </h2>
        </div>

        <div class="grid gap-5 md:grid-cols-2">
            <article class="overflow-hidden rounded-lg border border-borderLight bg-bgWhite shadow-sm">
                <img src="/images/founder-portrait.webp" alt="Founder of Engineers Clinic" class="aspect-[4/5] w-full object-cover object-top">
                <div>
                    <div class="p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">Founder</p>
                        <h3 class="mt-1 text-xl font-semibold text-textPrimary">Shreekant Pratap Singh</h3>
                        <p class="mt-2 text-sm leading-6 text-textSecondary">Guiding the vision, learning model, and long-term growth of Engineers Clinic.</p>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-lg border border-borderLight bg-bgWhite shadow-sm">
                <img src="/images/cofounder-portrait.jpeg" alt="Co-Founder of Engineers Clinic" class="aspect-[4/5] w-full object-cover object-top">
                <div>
                    <div class="p-5">
                        <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">Co-Founder</p>
                        <h3 class="mt-1 text-xl font-semibold text-textPrimary">Vikash Kumar</h3>
                        <p class="mt-2 text-sm leading-6 text-textSecondary">Shaping execution, partnerships, and the learner experience across programs.</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>

{{-- ───────── Our Story ───────── --}}
<section class="relative bg-bgWhite py-14 sm:py-16">
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(160deg,rgba(245,240,255,0.60),rgba(255,255,255,0.92)_50%,rgba(238,245,255,0.55))]"></div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="mb-8 max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">Our Story</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                From a simple idea to a growing movement.
            </h2>
        </div>

        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-lg border border-borderLight bg-bgWhite p-8 shadow-sm">
                <p class="text-base leading-8 text-textSecondary">
                    Engineers Clinic started with a frustration every engineering student knows — the gap between what textbooks teach and what employers demand. Founded in 2023, we set out to build a learning platform that doesn't just teach, but trains. Our programs are modelled on real workflows, giving learners a rehearsal ground before they step into their first role.
                </p>
                <p class="mt-5 text-base leading-8 text-textSecondary">
                    What began as a small internship initiative has grown into a structured ecosystem of skill tracks, college partnerships, and industry-shaped programs. Today, Engineers Clinic bridges academia and industry for students across the country — one project at a time.
                </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div class="rounded-lg border border-borderLight bg-gradient-to-br from-brand/5 to-transparent p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand/10">
                        <svg class="h-5 w-5 text-brand" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-textPrimary">Our Mission</h3>
                    <p class="mt-3 text-sm leading-7 text-textSecondary">
                        To make career-ready learning accessible to every engineering student — regardless of college tier, location, or background — through structured internships and project-led programs.
                    </p>
                </div>

                <div class="rounded-lg border border-borderLight bg-gradient-to-br from-secondary/8 to-transparent p-6 shadow-sm">
                    <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-secondary/15">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-textPrimary">Our Vision</h3>
                    <p class="mt-3 text-sm leading-7 text-textSecondary">
                        To become India's most trusted bridge between academic learning and professional readiness — building a generation of engineers who are skilled, confident, and employable from day one.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ───────── What We Offer ───────── --}}
<section class="bg-bgSoft py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-8 max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">What We Offer</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Everything a learner needs to go from classroom to career.
            </h2>
            <p class="mt-4 text-base leading-8 text-textSecondary">
                We've designed each offering around a single goal — turning potential into performance through real execution.
            </p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $offerings = [
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.193 23.193 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0H8m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"/></svg>',
                        'title' => 'Industry Internships',
                        'desc' => 'Structured internship programs across Web Development, Data Science, AI/ML, Cybersecurity, and more — designed to simulate real workplace demands.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                        'title' => 'Live Project Training',
                        'desc' => 'Every track features guided, hands-on projects that mirror real deliverables — not toy exercises. Build portfolios that actually demonstrate skill.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
                        'title' => 'Skill-Based Courses',
                        'desc' => 'Curated learning tracks in Full-Stack, DevOps, Cloud Computing, Data Analytics, Mobile Development, and emerging tech domains.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                        'title' => 'College Tie-Ups',
                        'desc' => 'We partner with universities and colleges to bring industry-aligned training directly to campus — boosting placement outcomes and student readiness.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>',
                        'title' => 'Certifications',
                        'desc' => 'Earn verifiable completion certificates upon finishing each track — recognized by our college and industry partners to strengthen your résumé.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                        'title' => 'Mentorship & Guidance',
                        'desc' => 'Every learner gets access to experienced mentors who provide code reviews, career advice, and real-time feedback throughout their learning journey.',
                    ],
                ];
            @endphp

            @foreach ($offerings as $offering)
            <article class="group rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm transition hover:border-brand/30 hover:shadow-md">
                <div class="mb-5 inline-flex h-11 w-11 items-center justify-center rounded-lg bg-brand/10 text-brand transition group-hover:bg-brand group-hover:text-white">
                    {!! $offering['icon'] !!}
                </div>
                <h3 class="text-xl font-semibold text-textPrimary">{{ $offering['title'] }}</h3>
                <p class="mt-3 leading-7 text-textSecondary">{{ $offering['desc'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ───────── How We Work — Process ───────── --}}
<section class="bg-bgWhite py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-10 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">How We Work</p>
            <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                A clear path from enrollment to employment.
            </h2>
            <p class="mx-auto mt-4 max-w-xl text-base leading-8 text-textSecondary">
                Our process is intentionally simple — so learners spend time building, not wondering what to do next.
            </p>
        </div>

        @php
            $steps = [
                [
                    'step' => '01',
                    'title' => 'Explore & Enroll',
                    'desc' => 'Browse career-focused tracks, pick a domain that aligns with your goals, and enroll in minutes. We guide you if you\'re unsure.',
                    'gradient' => 'from-brand/15 to-brand/5',
                ],
                [
                    'step' => '02',
                    'title' => 'Learn by Doing',
                    'desc' => 'Dive into structured modules with real-world projects. Each task is designed to build a specific, demonstrable skill.',
                    'gradient' => 'from-blue-500/12 to-blue-400/5',
                ],
                [
                    'step' => '03',
                    'title' => 'Get Mentored',
                    'desc' => 'Receive code reviews, feedback sessions, and career guidance from mentors who understand industry expectations.',
                    'gradient' => 'from-emerald-500/12 to-emerald-400/5',
                ],
                [
                    'step' => '04',
                    'title' => 'Earn & Advance',
                    'desc' => 'Complete your track, earn a verifiable certificate, build a portfolio of real work, and step into your career with confidence.',
                    'gradient' => 'from-secondary/18 to-secondary/6',
                ],
            ];
        @endphp

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($steps as $index => $step)
            <div class="relative rounded-lg border border-borderLight bg-gradient-to-b {{ $step['gradient'] }} p-6 shadow-sm">
                {{-- Connector line (visible on lg between cards) --}}
                @if ($index < 3)
                <div class="pointer-events-none absolute right-0 top-1/2 hidden h-px w-5 -translate-y-1/2 translate-x-full bg-borderLight lg:block"></div>
                @endif

                <span class="text-3xl font-extrabold text-brand/25">{{ $step['step'] }}</span>
                <h3 class="mt-3 text-lg font-semibold text-textPrimary">{{ $step['title'] }}</h3>
                <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ───────── Our Values ───────── --}}
<section class="bg-bgSoft py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-8 max-w-2xl">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">Our Values</p>
            <h2 class="mt-4 text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                The principles that shape every program we build.
            </h2>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $values = [
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                        'title' => 'Execution Over Theory',
                        'desc' => 'We believe that building something is worth more than reading about it. Every module ends with output.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                        'title' => 'Accessibility First',
                        'desc' => 'Tier-1 or tier-3, metro or rural — every student deserves access to career-grade training without barriers.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>',
                        'title' => 'Transparency & Trust',
                        'desc' => 'Clear pricing, honest outcomes, real student testimonials. We never over-promise — we let results speak.',
                    ],
                    [
                        'icon' => '<svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                        'title' => 'Learner-Centric Design',
                        'desc' => 'From dashboard UX to curriculum pacing, every detail is designed with the student\'s experience at the center.',
                    ],
                ];
            @endphp

            @foreach ($values as $value)
            <article class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm">
                <div class="mb-4 inline-flex h-10 w-10 items-center justify-center rounded-lg bg-brand/10 text-brand">
                    {!! $value['icon'] !!}
                </div>
                <h3 class="text-lg font-semibold text-textPrimary">{{ $value['title'] }}</h3>
                <p class="mt-3 text-sm leading-7 text-textSecondary">{{ $value['desc'] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<!-- {{-- ───────── Testimonials ───────── --}}
<section class="bg-bgWhite py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="mb-10 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">What Learners Say</p>
            <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                Real stories from students who trained with us.
            </h2>
        </div>

        <div class="grid gap-5 md:grid-cols-3">
            @php
                $testimonials = [
                    [
                        'quote' => 'Engineers Clinic gave me the confidence to build real projects during my internship. Within two months of completing the Full-Stack track, I landed a developer role at a product startup.',
                        'name' => 'Aarav Mishra',
                        'role' => 'Full-Stack Web Development Intern',
                        'initials' => 'AM',
                    ],
                    [
                        'quote' => 'The structured learning path made all the difference. Unlike other platforms where I felt lost, here every module had clear outcomes. My mentor\'s feedback on live projects was invaluable.',
                        'name' => 'Priya Sharma',
                        'role' => 'Data Science Intern',
                        'initials' => 'PS',
                    ],
                    [
                        'quote' => 'Our college partnered with Engineers Clinic for placement training. The improvement in student project quality and interview readiness has been remarkable — a truly industry-aligned approach.',
                        'name' => 'Prof. Rakesh Gupta',
                        'role' => 'Training & Placement Officer',
                        'initials' => 'RG',
                    ],
                ];
            @endphp

            @foreach ($testimonials as $testimonial)
            <article class="flex flex-col rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm">
                <svg class="mb-4 h-8 w-8 text-brand/25" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151C7.563 6.068 6 8.789 6 11h4v10H0z"/></svg>
                <p class="flex-1 text-sm leading-7 text-textSecondary">{{ $testimonial['quote'] }}</p>
                <div class="mt-6 flex items-center gap-3 border-t border-borderLight pt-5">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-brand/10 text-sm font-bold text-brand">{{ $testimonial['initials'] }}</span>
                    <div>
                        <p class="text-sm font-semibold text-textPrimary">{{ $testimonial['name'] }}</p>
                        <p class="text-xs text-textMuted">{{ $testimonial['role'] }}</p>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section> -->

{{-- ───────── Numbers That Matter ───────── --}}
<section class="relative overflow-hidden bg-surfaceDark py-14 sm:py-16">
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,rgba(124,92,252,0.18),transparent_60%)]"></div>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,rgba(245,200,66,0.10),transparent_60%)]"></div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="mb-10 text-center">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brandLight">Impact & Reach</p>
            <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                Numbers that reflect our commitment.
            </h2>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @php
                $impactStats = [
                    ['value' => '1,200+', 'label' => 'Students Trained', 'desc' => 'Across multiple engineering disciplines and career tracks'],
                    ['value' => '50+', 'label' => 'College Partners', 'desc' => 'Universities and institutions trust our training model'],
                    ['value' => '95%', 'label' => 'Completion Rate', 'desc' => 'Of enrolled students complete their chosen program track'],
                    ['value' => '10+', 'label' => 'Skill Domains', 'desc' => 'From Web Dev to AI/ML, Cybersecurity, Cloud, and beyond'],
                ];
            @endphp

            @foreach ($impactStats as $stat)
            <div class="rounded-lg border border-white/10 bg-white/5 p-6 backdrop-blur-sm">
                <p class="text-3xl font-bold text-white">{{ $stat['value'] }}</p>
                <p class="mt-2 text-sm font-semibold text-brandLight">{{ $stat['label'] }}</p>
                <p class="mt-2 text-xs leading-5 text-white/55">{{ $stat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ───────── CTA Banner ───────── --}}
<section class="bg-bgWhite py-14 sm:py-16">
    <div class="mx-auto max-w-7xl px-6">
        <div class="relative overflow-hidden rounded-xl border border-borderLight bg-gradient-to-r from-brand/8 via-bgWhite to-secondary/8 px-8 py-12 text-center shadow-sm sm:px-14 sm:py-16">
            <div class="pointer-events-none absolute -left-12 -top-12 h-40 w-40 rounded-full bg-brand/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-12 -right-12 h-40 w-40 rounded-full bg-secondary/10 blur-3xl"></div>

            <div class="relative">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand">Ready to start?</p>
                <h2 class="mx-auto mt-4 max-w-2xl text-3xl font-semibold tracking-tight text-textPrimary sm:text-4xl">
                    Your career-ready journey begins here.
                </h2>
                <p class="mx-auto mt-4 max-w-xl text-base leading-8 text-textSecondary">
                    Whether you're a student exploring internships or a college looking to partner — Engineers Clinic has a path for you.
                </p>

                <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                    <a href="/#heroSection"
                        class="inline-flex items-center justify-center rounded-lg bg-brand px-7 py-3.5 text-sm font-semibold text-white shadow-[0_16px_34px_rgba(124,92,252,0.20)] transition hover:bg-brandDark">
                        Browse Internships
                    </a>
                    <a href="{{ route('college.tieup') }}"
                        class="inline-flex items-center justify-center rounded-lg border border-borderLight bg-bgWhite px-7 py-3.5 text-sm font-semibold text-textPrimary transition hover:border-brand hover:text-brand">
                        College Partnership
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>  
@endsection