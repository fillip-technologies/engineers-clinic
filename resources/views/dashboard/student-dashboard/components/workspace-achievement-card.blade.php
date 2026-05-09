<a href="{{ $achievement['href'] }}" class="flex items-center gap-3 rounded-2xl bg-slate-50 p-3 transition hover:bg-indigo-50 hover:shadow-sm">
    <div class="grid h-11 w-11 place-items-center rounded-2xl {{ $achievement['class'] }}">
        <i class="{{ $achievement['icon'] }}"></i>
    </div>
    <div>
        <p class="text-sm font-black text-slate-950">{{ $achievement['title'] }}</p>
        <p class="text-xs font-semibold text-slate-500">{{ $achievement['meta'] }}</p>
    </div>
</a>
