<article
    id="step-{{ $step['slug'] }}"
    x-data="{ open: {{ $step['active'] ? 'true' : 'false' }}, copied: false, showHint: false, showTip: false }"
    class="rounded-3xl border bg-white shadow-sm transition"
    :class="stepState({{ $step['number'] }}) === 'locked' ? 'border-slate-200 opacity-75' : 'border-slate-200 hover:border-blue-200'">
    <button type="button" @click="if (isUnlocked({{ $step['number'] }})) open = !open" class="flex w-full items-start justify-between gap-4 p-5 text-left sm:p-6">
        <div class="flex gap-4">
            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-2xl"
                :class="stepState({{ $step['number'] }}) === 'completed' ? 'bg-emerald-50 text-emerald-700' : (stepState({{ $step['number'] }}) === 'active' ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-400')">
                <i x-show="stepState({{ $step['number'] }}) === 'completed'" class="fi fi-rr-check"></i>
                <i x-show="stepState({{ $step['number'] }}) === 'locked'" class="fi fi-rr-lock"></i>
                <span x-show="stepState({{ $step['number'] }}) === 'active'">{{ $step['number'] }}</span>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Step {{ str_pad($step['number'], 2, '0', STR_PAD_LEFT) }}</p>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold"
                        :class="stepState({{ $step['number'] }}) === 'completed' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : (stepState({{ $step['number'] }}) === 'active' ? 'bg-blue-50 text-blue-700 ring-1 ring-blue-200' : 'bg-slate-100 text-slate-500 ring-1 ring-slate-200')"
                        x-text="stepState({{ $step['number'] }}) === 'completed' ? 'Completed' : (stepState({{ $step['number'] }}) === 'active' ? 'In Progress' : 'Locked')">{{ $step['status'] }}</span>
                </div>
                <h3 class="mt-2 text-xl font-bold text-slate-950">{{ $step['title'] }}</h3>
                <p class="mt-2 max-w-2xl text-base leading-7 text-slate-600">{{ $step['description'] }}</p>
            </div>
        </div>
        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-slate-100 text-slate-500 transition">
            <i class="fi fi-rr-angle-small-down transition" :class="open ? 'rotate-180' : ''"></i>
        </span>
    </button>

    <div x-show="open" x-collapse class="border-t border-slate-100 px-5 pb-5 pt-5 sm:px-6 sm:pb-6">
        <div class="space-y-6">
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <h4 class="text-sm font-semibold text-slate-950">What you will build</h4>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step['build'] }}</p>
                </div>
                <div class="rounded-2xl bg-blue-50 p-4">
                    <h4 class="text-sm font-semibold text-blue-900">Why this matters</h4>
                    <p class="mt-2 text-sm leading-6 text-slate-700">{{ $step['why'] }}</p>
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-slate-950">Simple explanation</h4>
                <p class="mt-2 text-base leading-7 text-slate-600">{{ $step['lesson'] }}</p>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-800 bg-[#0f172a]">
                <div class="flex items-center justify-between border-b border-white/10 bg-[#111827] px-4 py-3">
                    <div class="flex min-w-0 items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-red-400"></span>
                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                        <span class="ml-2 truncate text-xs font-semibold text-slate-300">{{ $step['file'] }}</span>
                    </div>
                    <button type="button" @click="copyCode(@js($step['code'])); copied = true; setTimeout(() => copied = false, 1500)" class="rounded-lg bg-white/10 px-3 py-1.5 text-xs font-semibold text-slate-200 transition hover:bg-white/15">
                        <span x-show="!copied">Copy</span>
                        <span x-show="copied">Copied</span>
                    </button>
                </div>
                <pre class="overflow-x-auto p-4 text-sm leading-7 text-slate-100"><code>{{ $step['code'] }}</code></pre>
            </div>

            <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_16rem]">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <h4 class="text-sm font-semibold text-slate-950">Expected output</h4>
                    <p class="mt-2 text-sm leading-6 text-slate-600">{{ $step['expected_output'] }}</p>
                    <ul class="mt-3 space-y-2 text-sm text-slate-600">
                        @foreach ($step['preview_points'] as $point)
                            <li class="flex gap-2"><span class="text-blue-500">-</span><span>{{ $point }}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-3">
                        <div class="h-2 w-16 rounded-full bg-slate-300"></div>
                        <div class="mt-4 h-3 w-28 rounded-full bg-blue-200"></div>
                        <div class="mt-3 space-y-2">
                            <div class="h-8 rounded-lg bg-white ring-1 ring-slate-200"></div>
                            <div class="h-8 rounded-lg bg-white ring-1 ring-slate-200"></div>
                            <div class="h-8 rounded-lg bg-blue-500"></div>
                        </div>
                    </div>
                    <p class="mt-3 text-xs font-semibold text-slate-600">{{ $step['preview_title'] }}</p>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                <h4 class="text-sm font-semibold text-slate-950">Mini task</h4>
                <p class="mt-2 text-base leading-7 text-slate-600">{{ $step['task'] }}</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl bg-rose-50 p-4">
                    <h4 class="text-sm font-semibold text-rose-900">Common mistakes</h4>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                        @foreach ($step['mistakes'] as $mistake)
                            <li class="flex gap-2"><span class="text-rose-500">-</span><span>{{ $mistake }}</span></li>
                        @endforeach
                    </ul>
                </div>
                <div class="rounded-2xl bg-emerald-50 p-4">
                    <h4 class="text-sm font-semibold text-emerald-900">Tips</h4>
                    <ul class="mt-3 space-y-2 text-sm leading-6 text-slate-700">
                        @foreach ($step['tips'] as $tip)
                            <li class="flex gap-2"><span class="text-emerald-600">-</span><span>{{ $tip }}</span></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <button type="button" @click="markStepComplete({{ $step['number'] }})"
                    class="inline-flex items-center justify-center rounded-2xl px-5 py-3 text-sm font-semibold text-white transition"
                    :class="isComplete({{ $step['number'] }}) ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-blue-600 hover:bg-blue-700'">
                    <span x-text="isComplete({{ $step['number'] }}) ? 'Step completed' : 'Mark complete'">Mark complete</span>
                </button>
                <button type="button" @click="showHint = !showHint" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    View hint
                </button>
                <button type="button" @click="showTip = !showTip" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Mentor tip
                </button>
                <a href="#resources" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Need help?
                </a>
            </div>

            <div x-show="showHint" x-collapse class="rounded-2xl border border-blue-100 bg-blue-50 p-4 text-sm leading-6 text-slate-700">
                {{ $step['hint'] }}
            </div>
            <div x-show="showTip" x-collapse class="rounded-2xl border border-amber-100 bg-amber-50 p-4 text-sm leading-6 text-slate-700">
                {{ $step['mentor_tip'] }}
            </div>
        </div>
    </div>
</article>
