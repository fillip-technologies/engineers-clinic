<section id="overview" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
    <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-blue-600">{{ $workspace['track'] }}</p>
            <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $workspace['headline'] }}</h1>
            <p class="mt-4 text-base leading-7 text-slate-600">{{ $workspace['summary'] }}</p>

            <div class="mt-5 rounded-2xl border border-blue-100 bg-blue-50 px-4 py-3">
                <p class="text-sm font-semibold text-blue-700">Continue from your last checkpoint</p>
                <p class="mt-1 text-sm text-slate-600">{{ $workspace['next_milestone'] }}</p>
            </div>

            <div class="mt-6 max-w-xl">
                <div class="flex items-center justify-between text-sm">
                    <span class="font-medium text-slate-600">Project progress</span>
                    <span class="font-semibold text-slate-900" x-text="`${progress}%`">{{ $workspace['progress'] }}%</span>
                </div>
                <div class="mt-2 h-2 rounded-full bg-slate-100">
                    <div class="h-2 rounded-full bg-blue-500 transition-all duration-500" :style="`width: ${progress}%`" style="width: {{ $workspace['progress'] }}%"></div>
                </div>
                <p x-cloak x-show="progressMessage" x-transition class="mt-3 text-sm font-semibold text-emerald-700" x-text="progressMessage"></p>
            </div>
        </div>

        <a href="#steps" class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 sm:w-auto">
            Continue learning
        </a>
    </div>
</section>
