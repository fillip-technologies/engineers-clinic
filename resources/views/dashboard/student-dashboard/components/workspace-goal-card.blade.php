<section class="rounded-[2rem] border border-cyan-200/80 bg-gradient-to-br from-cyan-50 via-white to-indigo-50 p-5 shadow-xl shadow-cyan-100/60">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-sm font-bold text-cyan-700">Today's Goal</p>
            <h2 class="mt-1 text-xl font-black text-slate-950">{{ $todayGoal['title'] }}</h2>
            <p class="mt-2 text-sm leading-6 text-slate-600">{{ $todayGoal['body'] }}</p>
        </div>
        <div class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-cyan-500 text-white shadow-lg shadow-cyan-300/40">
            <i class="fi fi-rr-target"></i>
        </div>
    </div>
    <div class="mt-5 flex items-center justify-between rounded-2xl bg-white/75 px-4 py-3">
        <span class="text-sm font-bold text-slate-600">Focus time</span>
        <span class="text-sm font-black text-slate-950">{{ $todayGoal['time'] }}</span>
    </div>
    <a href="#roadmap-step-2" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-indigo-700">
        Start today's goal <i class="fi fi-rr-arrow-right"></i>
    </a>
</section>
