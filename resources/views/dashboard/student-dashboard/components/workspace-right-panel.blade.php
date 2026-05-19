<aside class="space-y-4 xl:sticky xl:top-24 xl:self-start">
    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
        <p class="text-sm font-semibold text-blue-600">Your progress</p>
        <h2 class="mt-2 text-xl font-bold text-slate-950"><span x-text="progress">{{ $workspace['progress'] }}</span>% complete</h2>
        <div class="mt-4 h-2 rounded-full bg-slate-100">
            <div class="h-2 rounded-full bg-blue-500 transition-all duration-500" :style="`width: ${progress}%`" style="width: {{ $workspace['progress'] }}%"></div>
        </div>
        <p class="mt-4 text-sm leading-6 text-slate-600">{{ $todayGoal['body'] }}</p>
        <a href="#step-{{ $workspace['current_step_slug'] ?? ($steps[0]['slug'] ?? 'steps') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
            {{ $workspace['current_step_label'] ?? 'Continue' }}
        </a>
    </section>

    <section id="mentor-tip" class="rounded-3xl border border-amber-100 bg-amber-50 p-5">
        <p class="text-sm font-semibold text-amber-700">Mentor tip</p>
        <p class="mt-2 text-sm leading-6 text-slate-700">{{ $mentorTip['body'] }}</p>
    </section>
</aside>
