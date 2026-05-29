@extends('layouts.app')

@section('content')
<section class="relative flex min-h-[80vh] items-center justify-center overflow-hidden bg-bgWhite">
    {{-- Background decorative elements --}}
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(115deg,rgba(245,240,255,0.88),rgba(255,255,255,0.94)_44%,rgba(238,245,255,0.82))]"></div>
    <div class="pointer-events-none absolute -left-32 -top-32 h-80 w-80 rounded-full bg-brand/8 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-32 -right-32 h-80 w-80 rounded-full bg-secondary/8 blur-3xl"></div>

    {{-- Grid pattern overlay --}}
    <div class="pointer-events-none absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cpath d=&quot;M60 0H0v60&quot; fill=&quot;none&quot; stroke=&quot;%237C5CFC&quot; stroke-width=&quot;0.5&quot;/%3E%3C/svg%3E'); background-size: 60px 60px;"></div>

    <div class="relative mx-auto max-w-2xl px-6 py-20 text-center">
        {{-- Animated icon --}}
        <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-2xl border border-borderLight bg-bgWhite shadow-[0_24px_70px_rgba(22,8,64,0.10)]">
            <div class="relative">
                <svg class="h-10 w-10 text-brand" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                {{-- Pulsing dot --}}
                <span class="absolute -right-1 -top-1 flex h-3.5 w-3.5">
                    <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-brand/40"></span>
                    <span class="relative inline-flex h-3.5 w-3.5 rounded-full bg-brand"></span>
                </span>
            </div>
        </div>

        {{-- Badge --}}
        <div class="mx-auto mb-6 inline-flex items-center gap-3 rounded-full border border-borderLight bg-bgWhite/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-brand shadow-sm">
            <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
            Coming Soon
        </div>

        {{-- Title --}}
        <h1 class="text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-[3.2rem] lg:leading-[1.1]">
            @if(isset($title))
                {{ $title }}
            @else
                Something exciting is on the way.
            @endif
        </h1>

        {{-- Description --}}
        <p class="mx-auto mt-5 max-w-lg text-base leading-8 text-textSecondary sm:text-lg">
            @if(isset($description))
                {{ $description }}
            @else
                We're working hard to bring this feature to life. Stay tuned — it'll be worth the wait.
            @endif
        </p>

        {{-- Progress indicator --}}
        <div class="mx-auto mt-8 max-w-xs">
            <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-brand">In Development</span>
                <span class="text-textMuted">Launching Soon</span>
            </div>
            <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-borderLight">
                <div class="h-full w-3/5 rounded-full bg-gradient-to-r from-brand to-brandLight transition-all duration-1000" style="animation: progressPulse 2.5s ease-in-out infinite alternate;"></div>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="mt-10 flex flex-col items-center justify-center gap-3 sm:flex-row">
            <a href="/"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-7 py-3.5 text-sm font-semibold text-white shadow-[0_16px_34px_rgba(124,92,252,0.20)] transition hover:bg-brandDark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Home
            </a>
            <a href="/#heroSection"
                class="inline-flex items-center justify-center rounded-lg border border-borderLight bg-bgWhite px-7 py-3.5 text-sm font-semibold text-textPrimary transition hover:border-brand hover:text-brand">
                Explore Internships
            </a>
        </div>

        {{-- Feature hints --}}
        @if(isset($features) && count($features) > 0)
        <div class="mx-auto mt-12 grid max-w-lg gap-3 sm:grid-cols-{{ min(count($features), 3) }}">
            @foreach($features as $feature)
            <div class="rounded-lg border border-borderLight bg-bgWhite/80 p-4 text-center shadow-sm">
                <p class="text-sm font-semibold text-textPrimary">{{ $feature }}</p>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

<style>
    @keyframes progressPulse {
        0% { opacity: 0.7; width: 55%; }
        100% { opacity: 1; width: 65%; }
    }
</style>
@endsection
