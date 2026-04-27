@extends('layouts.frontend-admin')

@section('content')
    <div class="mx-auto max-w-6xl">
        <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-primaryLight">Student Dashboard</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">My Quiz Attempts</h1>
                    <p class="mt-3 text-base leading-8 text-slate-600">
                        Review upcoming quizzes, revisit completed attempts, and stay ready for your next checkpoint.
                    </p>
                </div>

                <a href="{{ route('dashboard') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-primary transition hover:text-primaryLight">
                    <i class="fi fi-rr-arrow-left text-sm"></i>
                    <span>Back to Dashboard</span>
                </a>
            </div>
        </section>

        <section class="mt-8 rounded-[1.75rem] border border-slate-200/70 bg-white p-3 shadow-sm sm:p-4">
            <div class="flex flex-col gap-3 border-b border-slate-200 px-3 pb-4 pt-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500">Quiz Activity</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-900">Track every quiz from one place</h2>
                </div>
                <p class="text-sm leading-6 text-slate-500">Dummy interface for student quiz history and next attempts.</p>
            </div>

            <div class="mt-4 space-y-3">
                @foreach ($quizAttempts as $attempt)
                    @include('dashboard.student-dashboard.components.quiz-attempt-item', ['attempt' => $attempt])
                @endforeach
            </div>
        </section>
    </div>
@endsection
