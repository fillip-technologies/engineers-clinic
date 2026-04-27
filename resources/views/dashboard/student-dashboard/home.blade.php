@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-6xl">
        @include('dashboard.partials.student-overview')

        <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="flex flex-col gap-3 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Quick Access</p>
                    <h2 class="mt-3 text-2xl font-semibold text-slate-900 sm:text-3xl">Jump back into your learning flow</h2>
                </div>
                <p class="text-sm leading-6 text-slate-500">Keep the next step close, whether you are resuming a course or opening quizzes.</p>
            </div>

            <div class="mt-6 divide-y divide-slate-200">
                <a href="{{ route('dashboard.enrolled-courses') }}"
                    class="flex flex-col gap-4 py-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
                            <i class="fi fi-rr-play-alt text-lg"></i>
                        </span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Continue Learning</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">Resume your active internship courses and stay on track with current modules.</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-primaryLight">
                        <span>Open Courses</span>
                        <i class="fi fi-rr-arrow-small-right text-base"></i>
                    </span>
                </a>

                <a href="{{ route('dashboard.quiz-attempts') }}"
                    class="flex flex-col gap-4 py-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-start gap-4">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-700">
                            <i class="fi fi-rr-document text-lg"></i>
                        </span>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">My Quiz Attempts</h3>
                            <p class="mt-1 text-sm leading-6 text-slate-500">View and attempt your quizzes</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-primaryLight">
                        <span>Go to Quizzes</span>
                        <i class="fi fi-rr-arrow-small-right text-base"></i>
                    </span>
                </a>
            </div>
        </section>
    </div>
@endsection
