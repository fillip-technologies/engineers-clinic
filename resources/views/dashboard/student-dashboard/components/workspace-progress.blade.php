<section id="progress" class="rounded-[2rem] border border-white/80 bg-white/80 p-5 shadow-xl shadow-slate-200/70 backdrop-blur-xl sm:p-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-bold text-indigo-600">Project Progress</p>
            <h2 class="mt-1 text-2xl font-black text-slate-950">{{ $workspace['progress'] }}% completed</h2>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach ($progressPills as $pill)
                <span class="rounded-full px-3 py-1.5 text-xs font-bold {{ $pill['class'] }}">{{ $pill['label'] }}</span>
            @endforeach
        </div>
    </div>

    <div class="mt-6">
        <div class="h-4 overflow-hidden rounded-full bg-slate-100 ring-1 ring-slate-200">
            <div class="h-full rounded-full bg-gradient-to-r from-cyan-500 via-indigo-500 to-fuchsia-500 shadow-lg shadow-indigo-500/25 transition-all duration-700" style="width: {{ $workspace['progress'] }}%"></div>
        </div>
        <div class="mt-4 grid gap-3 sm:grid-cols-4">
            @foreach ($progressStats as $stat)
                @include('dashboard.student-dashboard.components.workspace-stats-card', ['stat' => $stat])
            @endforeach
        </div>
    </div>
</section>
