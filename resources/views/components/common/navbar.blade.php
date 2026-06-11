@props([
    'userName' => 'Aman',
])

@php
    $user = Auth::user();
    $displayName = $user?->name ?? $userName;
    $roleName = $user?->role?->name;
    $accountUrl = match ($roleName) {
        'college' => route('college.settings'),
        'admin' => route('admin.dashboard'),
        default => route('dashboard.student.profile'),
    };
    $coursesUrl = match ($roleName) {
        'college' => route('college.courses'),
        default => route('dashboard.enrolled-courses'),
    };
    $settingsUrl = match ($roleName) {
        'college' => route('college.settings'),
        'admin' => route('admin.dashboard'),
        default => route('dashboard.student.profile'),
    };
    $coursesLabel = $roleName === 'college' ? 'Courses' : 'My Courses';
    $initials = collect(explode(' ', trim($displayName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->implode('') ?: 'G';
@endphp

<header class="px-6 pt-6 sm:px-8 lg:px-10 lg:pt-8">
    <div class="flex items-center justify-between border-b border-glassBorder pb-5">
        <div class="hidden w-full max-w-xs items-center gap-3 rounded-lg border border-slate-200 bg-white px-4 py-2.5 shadow-sm sm:flex">
            <i class="fi fi-rr-search text-base text-textMuted"></i>
            <span class="text-sm text-textMuted">Search...</span>
        </div>

        <div class="ml-auto flex items-center gap-3">
            @auth
                <button type="button"
                    class="relative inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 shadow-sm transition hover:bg-slate-50"
                    aria-label="Notifications">
                    <i class="fi fi-rr-bell text-base"></i>
                    <span class="absolute right-2 top-2 h-2 w-2 rounded-full bg-primary ring-2 ring-white"></span>
                </button>

                <div class="relative" x-data="{ open: false }" @keydown.escape.window="open = false">
                    <button type="button" @click="open = ! open"
                        class="flex items-center gap-3 rounded-lg border border-slate-200 bg-white py-1.5 pl-2 pr-3 shadow-sm transition hover:bg-slate-50"
                        :aria-expanded="open.toString()" aria-haspopup="menu">
                        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary text-sm font-semibold text-white">
                            {{ $initials }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-xs text-slate-500">Hello,</span>
                            <span class="block max-w-32 truncate text-sm font-semibold text-slate-900">{{ $displayName }}</span>
                        </span>
                        <i class="fi fi-rr-angle-small-down text-base text-slate-400"></i>
                    </button>

                    <div x-cloak x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-100"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute right-0 z-30 mt-2 w-56 overflow-hidden rounded-xl border border-slate-200 bg-white py-2 shadow-lg"
                        role="menu">
                        <a href="{{ $accountUrl }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            role="menuitem">
                            <i class="fi fi-rr-user text-slate-400"></i>
                            <span>Account</span>
                        </a>
                        <a href="{{ $coursesUrl }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            role="menuitem">
                            <i class="fi fi-rr-book-alt text-slate-400"></i>
                            <span>{{ $coursesLabel }}</span>
                        </a>
                        <a href="{{ $settingsUrl }}"
                            class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50"
                            role="menuitem">
                            <i class="fi fi-rr-settings text-slate-400"></i>
                            <span>Settings</span>
                        </a>
                        <div class="my-2 border-t border-slate-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                                role="menuitem">
                                <i class="fi fi-rr-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Login
                </a>
                <a href="{{ route('signup', ['role' => 'student']) }}"
                    class="inline-flex items-center justify-center rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brandDark">
                    Signup
                </a>
            @endauth
        </div>
    </div>
</header>
