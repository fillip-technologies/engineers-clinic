@props([
'data' => [],
'step' => null,
'title' => null,
'description' => null,
'duration' => null,
'modules' => [],
'tasks' => [],
])

@php
$step = $step ?? ($data['step'] ?? null);
$title = $title ?? ($data['title'] ?? null);
$description = $description ?? ($data['description'] ?? null);
$duration = $duration ?? ($data['duration'] ?? null);
$modules = $modules ?: ($data['modules'] ?? []);
$tasks = $tasks ?: ($data['tasks'] ?? []);
@endphp

<article
    {{ $attributes->class([
        'group relative rounded-[2rem] border border-primary/30 bg-white p-7 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl'
    ]) }}>

    <!-- STEP + DURATION -->
    <div class="flex items-center justify-between">

        <!-- STEP NUMBER -->
        <div class="flex items-center gap-3">
            <span class="text-primary text-xl font-bold">
                {{ $step }}
            </span>

            <h3 class="text-lg font-semibold text-slate-900">
                {{ $title }}
            </h3>
        </div>

        @if($duration)
        <span class="rounded-full border border-primary/20 px-3 py-1 text-xs font-medium text-primary">
            {{ $duration }}
        </span>
        @endif
    </div>

    <!-- DESCRIPTION -->
    @if($description)
    <p class="mt-4 text-sm leading-7 text-slate-600">
        {{ $description }}
    </p>
    @endif

    <!-- MODULES -->
    @if(!empty($modules))
    <div class="mt-5">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary/80">
            Modules
        </p>

        <div class="mt-3 flex flex-wrap gap-2">
            @foreach($modules as $module)
            <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs text-slate-600">
                {{ $module }}
            </span>
            @endforeach
        </div>
    </div>
    @endif

    <!-- TASKS -->
    @if(!empty($tasks))
    <div class="mt-5 border-t border-slate-100 pt-4">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary/80">
            Tasks
        </p>

        <ul class="mt-3 space-y-2">
            @foreach($tasks as $task)
            <li class="flex items-start gap-2 text-sm text-slate-600">
                <span class="mt-2 h-1.5 w-1.5 rounded-full bg-primary"></span>
                <span>{{ $task }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- HOVER GLOW -->
    <div class="pointer-events-none absolute inset-0 rounded-[2rem] opacity-0 transition group-hover:opacity-100"
        style="box-shadow: 0 0 40px rgba(0,0,0,0.05);">
    </div>

</article>