@php
    $items = [
        ['label' => 'Dashboard', 'icon' => 'fi fi-rr-apps', 'href' => route('dashboard'), 'route' => 'dashboard'],
        ['label' => 'My Profile', 'icon' => 'fi fi-rr-user', 'href' => '#'],
        ['label' => 'Enrolled Courses', 'icon' => 'fi fi-rr-book-alt', 'href' => route('dashboard.enrolled-courses'), 'route' => 'dashboard.enrolled-courses'],
        ['label' => 'My Quiz Attempts', 'icon' => 'fi fi-rr-document-signed', 'href' => '#'],
        ['label' => 'Order History', 'icon' => 'fi fi-rr-receipt', 'href' => '#'],
        ['label' => 'Question & Answer', 'icon' => 'fi fi-rr-interrogation', 'href' => '#'],
        ['label' => 'Settings', 'icon' => 'fi fi-rr-settings', 'href' => '#'],
        ['label' => 'Logout', 'icon' => 'fi fi-rr-exit', 'href' => '#'],
    ];
@endphp

<aside class="border-b border-glassBorder bg-white lg:sticky lg:top-0 lg:h-screen lg:border-b-0 lg:border-r">
    <div class="flex h-full flex-col px-5 py-6 sm:px-6">
        <div class="border-b border-glassBorder pb-6">
            <h1 class="text-xl font-semibold text-textPrimary">Aman Kumar</h1>
        </div>

        <nav class="mt-6 space-y-1.5">
            @foreach ($items as $item)
                @php
                    $isActive = !empty($item['route']) && request()->routeIs($item['route']);
                @endphp
                <a href="{{ $item['href'] }}"
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium transition {{ $isActive ? 'bg-primarySoft text-secondary' : 'text-secondaryLight hover:bg-slate-100 hover:text-secondary' }}">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $isActive ? 'bg-white text-primaryLight ring-1 ring-slate-200' : 'text-secondaryLight transition group-hover:text-primaryLight' }}">
                        <i class="{{ $item['icon'] }} mt-1 text-base"></i>
                    </span>
                    <span>{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</aside>
