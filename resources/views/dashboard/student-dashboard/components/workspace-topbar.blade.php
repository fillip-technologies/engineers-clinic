<header class="sticky top-0 z-40 border-b border-slate-200 bg-white/90 px-4 py-3 backdrop-blur sm:px-6 lg:px-10">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4">
        <div class="flex min-w-0 items-center gap-3">
            <button type="button" @click="mobileSidebarOpen = true" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50 lg:hidden" aria-label="Open workspace navigation">
                <i class="fi fi-rr-menu-burger"></i>
            </button>
            <a href="{{ route('dashboard.enrolled-courses') }}" class="grid h-10 w-10 shrink-0 place-items-center rounded-xl border border-slate-200 bg-white text-slate-700 transition hover:bg-slate-50">
                <i class="fi fi-rr-arrow-left"></i>
            </a>
            <div class="min-w-0">
                <p class="truncate text-sm font-bold text-slate-950">{{ $workspace['title'] }}</p>
                <p class="truncate text-xs font-medium text-slate-500">Guided project lesson</p>
            </div>
        </div>

        <a href="#steps" class="hidden rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700 sm:inline-flex">
            Continue
        </a>
    </div>
</header>
