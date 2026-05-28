@props([
    'attempt',
])

@php
    $statusClasses = match ($attempt['status']) {
        'Passed' => 'bg-emerald-100 text-emerald-700',
        'Failed' => 'bg-red-100 text-red-700',
        'Completed' => 'bg-blue-100 text-blue-700',
        'Upcoming' => 'bg-amber-100 text-amber-700',
        default => 'bg-slate-100 text-slate-700',
    };
@endphp

<article class="flex flex-col gap-4 rounded-[1.5rem] bg-white px-5 py-5 transition hover:bg-slate-50/70 sm:flex-row sm:items-center sm:justify-between">
    <div class="min-w-0 flex items-start gap-4">
        <span class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700">
            <i class="fi fi-rr-document text-lg"></i>
        </span>

        <div class="min-w-0">
            <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:gap-4">
                <h3 class="text-lg font-semibold text-slate-900">{{ $attempt['title'] }}</h3>
                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                    {{ $attempt['status'] }}
                </span>
            </div>

            <p class="mt-2 text-sm font-medium text-slate-600">{{ $attempt['course'] }}</p>

            <div class="mt-3 flex flex-wrap gap-4 text-sm text-slate-500">
                <span>{{ $attempt['attempt'] }}</span>
                <span>Score: {{ $attempt['score'] }}</span>
                <span>{{ $attempt['updated_at'] }}</span>
            </div>
        </div>
    </div>

    <a href="{{ $attempt['href'] ?? route('dashboard.enrolled-courses') }}"
        class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryLight">
        {{ $attempt['action'] }}
    </a>
</article>
