<article class="rounded-3xl border border-white/80 bg-white/85 p-4 shadow-lg shadow-slate-200/60 backdrop-blur-xl transition hover:-translate-y-1 hover:shadow-2xl hover:shadow-cyan-100">
    <div class="flex items-start justify-between gap-3">
        <div class="grid h-11 w-11 place-items-center rounded-2xl {{ $task['icon_class'] }}">
            <i class="{{ $task['icon'] }}"></i>
        </div>
        <span class="rounded-full px-2.5 py-1 text-xs font-bold {{ $task['status_class'] }}">{{ $task['status'] }}</span>
    </div>
    <h3 class="mt-4 text-base font-black text-slate-950">{{ $task['title'] }}</h3>
    <p class="mt-2 text-sm leading-6 text-slate-500">{{ $task['description'] }}</p>
    <a href="{{ $task['href'] }}" class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-indigo-700">
        {{ $task['action'] }} <i class="fi fi-rr-arrow-right"></i>
    </a>
</article>
