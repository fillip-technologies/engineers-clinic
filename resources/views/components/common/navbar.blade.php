@props([
    'userName' => 'Aman',
])

<header class="px-6 pt-6 sm:px-8 lg:px-10 lg:pt-8">
    <div class="flex items-center justify-between border-b border-glassBorder pb-5">
        <div class="flex w-full max-w-xs items-center gap-3 rounded-full bg-slate-100 px-4 py-3">
            <i class="fi fi-rr-search text-base text-textMuted"></i>
            <span class="text-sm text-textMuted">Search...</span>
        </div>

        <div class="flex items-center gap-5">
            <a href="{{ url('/login') }}"
                class="inline-flex items-center justify-center rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-brandDark">
                Login
            </a>
            <i class="fi fi-rr-bell text-lg text-primary"></i>
            <div class="flex items-center gap-3">
                <i class="fi fi-rr-user text-lg text-primary"></i>
                <p class="text-lg font-semibold text-textPrimary">Hello, {{ $userName }}</p>
            </div>
        </div>
    </div>
</header>
