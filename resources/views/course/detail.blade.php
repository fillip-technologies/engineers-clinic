@extends('layouts.app')

@section('content')

@php
$hero = $course['hero'] ?? [];
$heroTitle = $hero['title'] ?? $course['title'] ?? null;
$heroSubtitle = $hero['subtitle'] ?? $course['description'] ?? null;
$heroBadge = $hero['trusted_badge'] ?? null;
$heroFeatures = $hero['features'] ?? [];
$primaryCta = $hero['primary_cta'] ?? ['label' => 'Reserve Your Seat', 'href' => '#enroll-now'];
$selectedLevel = $hero['level'] ?? 'Beginner';
@endphp

<div x-data="{ enquiryOpen: {{ ($errors->courseEnquiry->any() || session()->has('course_enquiry_success')) ? 'true' : 'false' }} }" @keydown.escape.window="enquiryOpen = false">

    <section class="relative overflow-hidden bg-[#fcfcfd] py-20">

        <!-- SOFT BG -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,#6C63FF08,transparent_30%)]"></div>

        <div class="relative z-10 mx-auto max-w-7xl px-6">

            <div class="grid items-center gap-16 lg:grid-cols-[0.95fr_0.85fr]">

                <!-- LEFT CONTENT -->
                <div class="max-w-xl">

                    <!-- BADGE -->
                    @if(!empty($heroBadge))
                    <div
                        class="inline-flex items-center gap-2 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 shadow-sm">

                        <span class="h-2 w-2 rounded-full bg-[#5B5BF6]"></span>

                        <span
                            class="text-xs font-semibold uppercase tracking-[0.18em] text-[#5B5BF6]">

                            {{ $heroBadge }}

                        </span>

                    </div>
                    @endif

                    <!-- HEADING -->
                    @if(!empty($heroTitle))
                    <h1
                        class="mt-8 text-[3.4rem] font-bold leading-[1.02] tracking-tight text-transparent bg-clip-text bg-gradient-to-r from-brandDark via-[#7C5CFC] to-[#A78BFA]">

                        {{ $heroTitle }}

                    </h1>
                    @endif

                    <!-- SUBTEXT -->
                    @if(!empty($heroSubtitle))
                    <p
                        class="mt-6 text-[18px] leading-8 text-[#475569]">

                        {{ $heroSubtitle }}

                    </p>
                    @endif

                    @if(!empty($hero['duration']) || !empty($hero['level']))
                    <div class="mt-8 flex flex-wrap gap-3">
                        @if(!empty($hero['duration']))
                        <div class="inline-flex items-center gap-2 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-semibold text-[#111827] shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-secondary"></span>
                            <span>{{ $hero['duration'] }}</span>
                        </div>
                        @endif

                        @if(!empty($hero['level']))
                        <div class="inline-flex items-center gap-2 rounded-full border border-[#E5E7EB] bg-white px-4 py-2 text-sm font-semibold text-[#111827] shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-[#5B5BF6]"></span>
                            <span>{{ $hero['level'] }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- FEATURES -->
                    @if(!empty($heroFeatures))
                    <div class="mt-10 space-y-5">

                        @foreach($heroFeatures as $feature)
                        <div class="flex items-start gap-4">

                            <div
                                class="mt-1 flex h-6 w-6 items-center justify-center rounded-full bg-[#EEF2FF]">

                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-3.5 w-3.5 text-[#5B5BF6]"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor">

                                    <path stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M5 13l4 4L19 7" />

                                </svg>

                            </div>

                            <div>

                                <p class="text-[15px] font-semibold text-[#111827]">
                                    {{ $feature }}
                                </p>

                            </div>

                        </div>
                        @endforeach

                    </div>
                    @endif

                    @if(!empty($primaryCta['label']))
                    <div class="mt-10 flex flex-col gap-3 sm:flex-row sm:items-center">
                        <a href="{{ $primaryCta['href'] ?? '#enroll-now' }}"
                            class="inline-flex items-center justify-center rounded-2xl bg-[#5B5BF6] px-6 py-3.5 text-sm font-semibold text-white shadow-[0_18px_40px_rgba(91,91,246,0.24)] transition duration-300 hover:-translate-y-0.5 hover:bg-[#4F46E5]">
                            {{ $primaryCta['label'] }}
                        </a>

                        <button type="button"
                            @click="enquiryOpen = true"
                            class="relative inline-flex items-center justify-center overflow-hidden rounded-2xl p-[1.5px] group hover:-translate-y-0.5 transition duration-300">

                            <!-- ANIMATED BORDER -->
                            <span
                                class="absolute inset-[-1000%] animate-[spin_5s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,#5B5BF6_0%,#8B5CF6_30%,#C084FC_60%,#5B5BF6_100%)]">
                            </span>

                            <!-- INNER BUTTON -->
                            <span
                                class="relative inline-flex items-center justify-center rounded-2xl bg-white px-6 py-3.5 text-sm font-semibold text-[#111827] shadow-sm transition duration-300 group-hover:text-[#5B5BF6]">

                                Get Free Counselling

                            </span>

                        </button>
                    </div>
                    @endif

                </div>

                @include('form.enrollment-form', [
                    'selectedLevel' => $selectedLevel,
                    'courseModel' => $dbCourse ?? null,
                ])

        </div>

    </section>

    @include('form.enquiry', ['course' => $course])

</div>

<!-- DYNAMIC SECTIONS - KEPT EXACTLY THE SAME -->
@if(isset($course['program_overview']))
@include('course.program-overview.program-overview', ['course' => $course])
@endif

@if(!empty($course['curriculum']))
@include('course.curriculum.curriculum', ['course' => $course])
@endif

@if(!empty($course['why_choose']) || !empty($course['testimonials']) || !empty($course['faq']))
@include('course.conversion.sections', ['course' => $course])
@endif

@endsection
