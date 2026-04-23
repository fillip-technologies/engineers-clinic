@php
    $items = [
        ['label' => 'Dashboard', 'icon' => 'fi fi-rr-apps', 'href' => '#', 'route' => 'college.dashboard'],
        ['label' => 'Students', 'icon' => 'fi fi-rr-users', 'href' => '#'],
        ['label' => 'Batches', 'icon' => 'fi fi-rr-layers', 'href' => '#'],
        ['label' => 'Internships', 'icon' => 'fi fi-rr-briefcase', 'href' => '#'],
        ['label' => 'Progress', 'icon' => 'fi fi-rr-chart-line-up', 'href' => '#'],
        ['label' => 'Certificates', 'icon' => 'fi fi-rr-diploma', 'href' => '#'],
        ['label' => 'Announcements', 'icon' => 'fi fi-rr-megaphone', 'href' => '#'],
        ['label' => 'Settings', 'icon' => 'fi fi-rr-settings', 'href' => '#'],
        ['label' => 'Logout', 'icon' => 'fi fi-rr-exit', 'href' => '#'],
    ];
@endphp

<aside class="bg-bgWhite border-r border-borderLight lg:sticky lg:top-0 lg:h-screen w-64">

    <div class="flex h-full flex-col px-5 py-6 sm:px-6">

        <!-- HEADER -->
        <div class="pb-6 border-b border-borderLight">
            <h1 class="text-xl font-semibold text-textPrimary">
                ABC College
            </h1>
            <p class="text-xs text-textMuted mt-1">
                Admin Dashboard
            </p>
        </div>

        <!-- MENU -->
        <nav class="mt-6 space-y-1.5">

            @foreach ($items as $item)
                @php
                    $isActive = !empty($item['route']) && request()->routeIs($item['route']);
                @endphp

                <a href="{{ $item['href'] }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition-all duration-200

                    {{ $isActive 
                        ? 'bg-brandSoft text-brand' 
                        : 'text-textSecondary hover:bg-bgSoft hover:text-textPrimary' }}">

                    <!-- ICON -->
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl transition

                        {{ $isActive 
                            ? 'bg-brandSoft text-brand' 
                            : 'text-textMuted group-hover:text-brand' }}">

                        <i class="{{ $item['icon'] }} mt-1 text-base"></i>
                    </span>

                    <!-- TEXT -->
                    <span class="tracking-wide">
                        {{ $item['label'] }}
                    </span>

                    <!-- ACTIVE INDICATOR -->
                    @if($isActive)
                        <span class="ml-auto w-1.5 h-6 bg-brand rounded-full"></span>
                    @endif

                </a>
            @endforeach

        </nav>

        <!-- FOOTER -->
        <div class="mt-auto pt-6 border-t border-borderLight">
            <div class="flex items-center gap-3">

                <div class="w-10 h-10 rounded-full bg-brandSoft flex items-center justify-center text-sm font-semibold text-brand">
                    A
                </div>

                <div>
                    <p class="text-sm font-medium text-textPrimary">ABC College</p>
                    <p class="text-xs text-textMuted">Admin Panel</p>
                </div>

            </div>
        </div>

    </div>
</aside>