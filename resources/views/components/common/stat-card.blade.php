@props([
    'label',
    'value',
    'accent' => 'primary',
])

@php
    $accentClasses = [
        'primary' => 'from-primarySoft to-white text-primaryLight ring-1 ring-slate-200',
        'secondary' => 'from-secondarySoft to-white text-secondaryLight ring-1 ring-slate-200',
        'glass' => 'from-slate-100 to-white text-textPrimary ring-1 ring-slate-200',
    ];
@endphp

<article class="rounded-[1.5rem] border border-glassBorder bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-textMuted">{{ $label }}</p>
            <p class="mt-3 text-4xl font-semibold text-textPrimary">{{ $value }}</p>
        </div>

        <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $accentClasses[$accent] ?? $accentClasses['primary'] }}">
            <span class="h-3 w-3 rounded-full bg-current"></span>
        </span>
    </div>
    <p class="mt-4 text-sm leading-7 text-textSecondary">Track your progress and keep your learning flow visible at a glance.</p>
</article>
