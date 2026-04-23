@props([
    'title',
    'duration',
    'progress',
    'lessonCount',
])

<article class="rounded-[1.5rem] border border-glassBorder bg-white p-5 shadow-sm transition hover:shadow-md">
    <div class="flex items-start justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-primaryLight">In Progress</p>
            <h3 class="mt-3 text-xl font-semibold text-textPrimary">{{ $title }}</h3>
        </div>

        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-textSecondary">
            {{ $duration }}
        </span>
    </div>

    <div class="mt-6 rounded-[1.25rem] bg-slate-50 p-4 ring-1 ring-inset ring-glassBorder">
        <div class="flex items-center justify-between text-sm">
            <span class="text-textMuted">Progress</span>
            <span class="font-semibold text-textPrimary">{{ $progress }}%</span>
        </div>
        <div class="mt-3 h-2 rounded-full bg-slate-200">
            <div class="h-2 rounded-full bg-gradient-to-r from-primary to-primaryLight" style="width: {{ $progress }}%"></div>
        </div>
        <p class="mt-4 text-sm text-textSecondary">{{ $lessonCount }} lessons available in this learning track.</p>
    </div>

    <div class="mt-5 flex items-center justify-between">
        <p class="text-sm text-textMuted">Stay consistent to unlock your next milestone.</p>
        <a href="#" class="text-sm font-semibold text-primaryLight transition hover:text-primary">Continue</a>
    </div>
</article>
