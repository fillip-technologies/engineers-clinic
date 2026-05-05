@props([
    'label',
    'icon',
    'href' => '#',
    'method' => 'GET',
    'active' => false,
])

@php
    $classes = 'group flex w-full items-center gap-3 rounded-xl px-3 py-3 text-left text-sm font-semibold transition ' .
        ($active
            ? 'bg-primary text-white shadow-sm'
            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950');

    $iconClasses = 'inline-flex h-10 w-10 items-center justify-center rounded-xl ' .
        ($active
            ? 'bg-white/15 text-white'
            : 'text-slate-500 transition group-hover:bg-white group-hover:text-primary');
@endphp

@if (strtoupper($method) === 'POST')
    <form method="POST" action="{{ $href }}">
        @csrf
        <button type="submit" class="{{ $classes }}">
            <span class="{{ $iconClasses }}">
                <i class="{{ $icon }} mt-1 text-base"></i>
            </span>
            <span>{{ $label }}</span>
        </button>
    </form>
@else
    <a href="{{ $href }}" class="{{ $classes }}">
        <span class="{{ $iconClasses }}">
            <i class="{{ $icon }} mt-1 text-base"></i>
        </span>
        <span>{{ $label }}</span>
    </a>
@endif
