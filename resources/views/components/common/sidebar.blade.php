@php
    $role = auth()->user()->role ?? 'student';

    // 🎓 STUDENT MENU
    $studentItems = [
        ['label' => 'Dashboard', 'icon' => 'fi fi-rr-apps'],
        ['label' => 'My Profile', 'icon' => 'fi fi-rr-user'],
        ['label' => 'Enrolled Courses', 'icon' => 'fi fi-rr-book-alt'],
        ['label' => 'My Quiz Attempts', 'icon' => 'fi fi-rr-document-signed'],
        ['label' => 'Order History', 'icon' => 'fi fi-rr-receipt'],
        ['label' => 'Question & Answer', 'icon' => 'fi fi-rr-interrogation'],
        ['label' => 'Settings', 'icon' => 'fi fi-rr-settings'],
        ['label' => 'Logout', 'icon' => 'fi fi-rr-exit'],
    ];

    // 🏫 COLLEGE MENU
    $collegeItems = [
        ['label' => 'Dashboard', 'icon' => 'fi fi-rr-apps'],
        ['label' => 'Students', 'icon' => 'fi fi-rr-users'],
        ['label' => 'Batches', 'icon' => 'fi fi-rr-layers'],
        ['label' => 'Internships', 'icon' => 'fi fi-rr-book-alt'],
        ['label' => 'Progress', 'icon' => 'fi fi-rr-chart-line'],
        ['label' => 'Certificates', 'icon' => 'fi fi-rr-diploma'],
        ['label' => 'Announcements', 'icon' => 'fi fi-rr-megaphone'],
        ['label' => 'Settings', 'icon' => 'fi fi-rr-settings'],
        ['label' => 'Logout', 'icon' => 'fi fi-rr-exit'],
    ];

    // 🎯 SWITCH
    $items = $role === 'college' ? $collegeItems : $studentItems;
@endphp

<aside class="bg-black text-white w-64 h-screen sticky top-0">
    <div class="flex flex-col h-full px-5 py-6">

        <!-- USER -->
        <div class="pb-6 border-b border-white/10">
            <h1 class="text-lg font-semibold">
                {{ auth()->user()->name ?? 'User' }}
            </h1>
        </div>

        <!-- MENU -->
        <nav class="mt-6 space-y-1.5">

            @foreach ($items as $item)
                <div
                    class="group flex items-center gap-3 rounded-xl px-3 py-3 text-sm font-medium cursor-pointer transition hover:bg-white/10">

                    <!-- ICON -->
                    <span
                        class="inline-flex h-10 w-10 items-center justify-center rounded-xl text-white/70 group-hover:text-white">
                        <i class="{{ $item['icon'] }} mt-1 text-base"></i>
                    </span>

                    <!-- LABEL -->
                    <span class="text-white/80 group-hover:text-white">
                        {{ $item['label'] }}
                    </span>

                </div>
            @endforeach

        </nav>

    </div>
</aside>