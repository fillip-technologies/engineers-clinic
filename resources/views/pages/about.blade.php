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
                <img src="/images/founder-portrait.png" alt="Founder of Engineers Clinic" class="aspect-[4/5] w-full object-cover object-top">
                <div>
                    <div class="p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">Founder</p>
                    <h3 class="mt-1 text-xl font-semibold text-textPrimary">Founder Name</h3>
                    <p class="mt-2 text-sm leading-6 text-textSecondary">Guiding the vision, learning model, and long-term growth of Engineers Clinic.</p>
                    </div>
                </div>
            </article>

            <article class="overflow-hidden rounded-lg border border-borderLight bg-bgWhite shadow-sm">
                <img src="/images/cofounder-portrait.png" alt="Co-Founder of Engineers Clinic" class="aspect-[4/5] w-full object-cover object-top">
                <div>
                    <div class="p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">Co-Founder</p>
                    <h3 class="mt-1 text-xl font-semibold text-textPrimary">Co-Founder Name</h3>
                    <p class="mt-2 text-sm leading-6 text-textSecondary">Shaping execution, partnerships, and the learner experience across programs.</p>
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
