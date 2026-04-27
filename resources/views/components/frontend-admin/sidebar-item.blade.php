@props([
    'label',
    'icon',
    'href' => '#',
    'active' => false,
])

<a href="{{ $href }}"
    class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ $active ? 'bg-primarySoft text-secondary' : 'text-secondaryLight hover:bg-slate-100 hover:text-secondary' }}">
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $active ? 'bg-white text-primaryLight ring-1 ring-slate-200' : 'text-secondaryLight transition group-hover:text-primaryLight' }}">
        <i class="{{ $icon }} mt-1 text-base"></i>
    </span>
    <span>{{ $label }}</span>
</a>
