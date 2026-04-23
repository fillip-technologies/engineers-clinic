@extends('layouts.app')

@section('content')
<section
    class="relative overflow-hidden bg-gradient-to-br from-bgDark via-bgDarkSoft to-bgIndigo px-6 py-16 sm:px-10 lg:px-14 lg:py-20">
    <div
        class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.18),_transparent_32%),radial-gradient(circle_at_bottom_right,_rgba(99,102,241,0.22),_transparent_36%)]">
    </div>
    <div class="absolute left-0 top-16 h-72 w-72 rounded-full bg-primarySoft blur-3xl"></div>
    <div class="absolute right-0 top-1/3 h-80 w-80 rounded-full bg-secondarySoft blur-3xl"></div>

    <div class="relative mx-auto max-w-7xl">
        <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:items-stretch">
            <div class="flex h-full flex-col">
                <div class="max-w-2xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.32em] text-primaryLight">Course Detail</p>
                    <h1 class="mt-5 text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl lg:text-6xl">
                        {{ $course['title'] }}
                    </h1>
                    <p class="mt-6 max-w-xl text-base leading-8 text-textSecondary sm:text-lg">
                        {{ $course['description'] }}
                    </p>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-[1.5rem] border border-glassBorder bg-glass p-5 backdrop-blur">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Duration</p>
                        <p class="mt-3 text-2xl font-semibold text-textPrimary">{{ $course['duration'] }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-glassBorder bg-glass p-5 backdrop-blur">
                        <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Level</p>
                        <p class="mt-3 text-2xl font-semibold text-textPrimary">{{ $course['level'] }}</p>
                    </div>
                </div>

                <div class="mt-8 grid gap-4">
                    <div class="rounded-[1.5rem] border border-glassBorder bg-glass p-5 backdrop-blur">
                        <p class="text-base font-semibold text-textPrimary">Real-world project exposure</p>
                        <p class="mt-2 text-sm leading-7 text-textMuted">
                            Build practical confidence through guided assignments and implementation-focused learning.
                        </p>
                    </div>
                    <div class="rounded-[1.5rem] border border-glassBorder bg-glass p-5 backdrop-blur">
                        <p class="text-base font-semibold text-textPrimary">Structured skill progression</p>
                        <p class="mt-2 text-sm leading-7 text-textMuted">
                            Move from core fundamentals to applied workflows with a roadmap built for steady growth.
                        </p>
                    </div>

                </div>
            </div>

            <div class="h-full rounded-[2rem] border border-glassBorder bg-glass p-4 shadow-2xl backdrop-blur">
                <div class="flex h-full flex-col rounded-[1.75rem] bg-slate-900/95 p-6 ring-1 ring-inset ring-glassBorder sm:p-8">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                        <span class="h-3 w-3 rounded-full bg-amber-300"></span>
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>

                        <span class="ml-2 text-xs font-semibold text-amber-300">
                            Enrollment Open for 2026
                        </span>
                    </div>

                    <div class="mt-8 flex-1">
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-primaryLight">
                            Enroll Now
                        </p>
                        <p class="mt-3 text-sm leading-7 text-slate-300">
                            Fill in your details and our team will connect with you to guide you through the
                            enrollment process for {{ $course['title'] }}.
                        </p>

                        <form method="POST" action="#" class="mt-6 space-y-4 lg:max-h-[33rem] lg:overflow-y-auto lg:pr-2">
                            @csrf

                            <input type="hidden" name="course_slug" value="{{ $course['slug'] }}">
                            <input type="hidden" name="course_title" value="{{ $course['title'] }}">

                            <div>
                                <label for="full_name" class="text-sm font-medium text-slate-200">Full Name</label>
                                <input id="full_name" type="text" name="full_name"
                                    placeholder="Enter your full name"
                                    class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white/10 focus:ring-4 focus:ring-cyan-500/10" />
                            </div>

                            <div>
                                <label for="phone_number" class="text-sm font-medium text-slate-200">Phone Number</label>
                                <input id="phone_number" type="tel" name="phone_number"
                                    placeholder="Enter your phone number"
                                    class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white/10 focus:ring-4 focus:ring-cyan-500/10" />
                            </div>

                            <div>
                                <label for="email" class="text-sm font-medium text-slate-200">Email Address</label>
                                <input id="email" type="email" name="email"
                                    placeholder="Enter your email address"
                                    class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition placeholder:text-slate-400 focus:border-primary focus:bg-white/10 focus:ring-4 focus:ring-cyan-500/10" />
                            </div>

                            <div>
                                <label for="experience_level" class="text-sm font-medium text-slate-200">Current Level</label>
                                <select id="experience_level" name="experience_level"
                                    class="mt-2 w-full rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-white outline-none transition focus:border-primary focus:bg-slate-800 focus:ring-4 focus:ring-cyan-500/10">
                                    <option value="" class="text-slate-900">Select your current level</option>
                                    <option value="student" class="text-slate-900">Student</option>
                                    <option value="beginner" class="text-slate-900">Beginner</option>
                                    <option value="working_professional" class="text-slate-900">Working Professional</option>
                                </select>
                            </div>

                            <button type="submit"
                                class="inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-primary to-secondary px-5 py-3 text-sm font-semibold text-slate-950 shadow-lg shadow-cyan-500/20 transition hover:opacity-90">
                                Submit Enrollment Request
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@if(isset($course['program_overview']))
@include('course.program-overview.program-overview', [
'data' => $course['program_overview'],
'course' => $course
])
@endif


@endsection