@php
    $isMobile = $isMobile ?? false;
@endphp

<aside class="{{ $isMobile ? 'h-full w-full' : 'sticky top-0 hidden h-screen w-64 shrink-0 lg:block' }} overflow-y-auto border-r border-slate-200 bg-white px-5 py-6">
    <div class="flex items-center gap-3">
        <div class="grid h-10 w-10 place-items-center rounded-2xl bg-blue-600 text-sm font-bold text-white">EC</div>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-bold text-slate-950">Project Lesson</p>
            <p class="text-xs font-medium text-slate-500">Learn step by step</p>
        </div>
        @if ($isMobile)
            <button type="button" @click="mobileSidebarOpen = false" class="grid h-10 w-10 place-items-center rounded-2xl bg-slate-100 text-slate-600 transition hover:bg-slate-200" aria-label="Close workspace navigation">
                <i class="fi fi-rr-cross-small"></i>
            </button>
        @endif
    </div>

    <nav class="mt-8 space-y-1">
        @foreach ($sidebarItems as $item)
            <a href="#{{ $item['target'] }}"
                @click="currentSection = '{{ $item['target'] }}'; mobileSidebarOpen = false"
                class="group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold transition"
                :class="currentSection === '{{ $item['target'] }}'
                    ? 'bg-blue-50 text-blue-700'
                    : (stepState({{ $item['number'] }}) === 'locked' ? 'text-slate-400' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950')">
                <span class="grid h-8 w-8 place-items-center rounded-lg"
                    :class="currentSection === '{{ $item['target'] }}'
                        ? 'bg-blue-100 text-blue-700'
                        : (stepState({{ $item['number'] }}) === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500 group-hover:bg-white')">
                    <i x-show="stepState({{ $item['number'] }}) === 'completed'" class="fi fi-rr-check"></i>
                    <i x-show="stepState({{ $item['number'] }}) === 'locked'" class="fi fi-rr-lock"></i>
                    <span x-show="stepState({{ $item['number'] }}) === 'active'">{{ $item['number'] }}</span>
                </span>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Today</p>
        <h3 class="mt-2 text-base font-bold leading-6 text-slate-950">{{ $workspace['next_milestone'] }}</h3>
        <div class="mt-4 h-2 rounded-full bg-white">
            <div class="h-2 rounded-full bg-blue-500 transition-all duration-500" :style="`width: ${progress}%`" style="width: {{ $workspace['progress'] }}%"></div>
        </div>
        <p class="mt-3 text-xs font-medium text-slate-500"><span x-text="progress">{{ $workspace['progress'] }}</span>% complete</p>
    </div>
</aside>
