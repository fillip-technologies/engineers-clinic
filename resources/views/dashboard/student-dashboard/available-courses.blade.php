@extends('layouts.frontend-admin')

@section('content')
<div class="mx-auto max-w-6xl">
    <div class="mb-6">
        <p class="text-sm font-semibold text-primary">Student Dashboard</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-950">Enroll in a Course</h1>
        <p class="mt-1 text-sm text-slate-500">Browse and pay for courses directly below.</p>
    </div>

    @if(empty($courses))
        <div class="rounded-xl border border-dashed border-slate-300 py-16 text-center">
            <i class="fi fi-rr-book-alt text-4xl text-slate-300"></i>
            <p class="mt-4 text-sm font-medium text-slate-500">No additional courses available right now.</p>
        </div>
    @else
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($courses as $course)
            <div class="flex flex-col rounded-xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <p class="text-xs font-medium uppercase tracking-wide text-slate-400">{{ $course['category'] ?? 'Course' }}</p>
                <h3 class="mt-2 font-semibold text-slate-950">{{ $course['title'] }}</h3>
                <p class="mt-1 flex-1 text-sm text-slate-500 line-clamp-3">{{ $course['description'] }}</p>

                <dl class="mt-4 grid grid-cols-2 gap-2">
                    <div class="rounded-lg bg-slate-50 px-3 py-2">
                        <dt class="text-xs text-slate-400">Level</dt>
                        <dd class="mt-0.5 text-xs font-semibold text-slate-800">{{ $course['level'] ?? 'All levels' }}</dd>
                    </div>
                    <div class="rounded-lg bg-primary/5 px-3 py-2">
                        <dt class="text-xs text-primary">Price</dt>
                        <dd class="mt-0.5 text-sm font-bold text-slate-950">{{ $course['fee'] }}</dd>
                    </div>
                </dl>

                <a href="{{ $course['checkout_url'] }}"
                    class="mt-5 flex items-center justify-center gap-2 rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                    <i class="fi fi-rr-{{ $course['is_free'] ? 'check' : 'credit-card' }} text-sm leading-none"></i>
                    {{ $course['is_free'] ? 'Enroll Free' : 'Enroll & Pay' }}
                </a>
            </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
