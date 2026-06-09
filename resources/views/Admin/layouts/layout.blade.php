<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name', 'Engineers Clinic') }}</title>

    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,800,900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <style>
        body {
            font-family: 'Satoshi', ui-sans-serif, system-ui, sans-serif;
        }

        .sidebar-transition {
            transition: all 0.3s ease;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

@php
$navigationGroups = [
'Overview' => [
['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard', 'icon' => 'fi-rr-dashboard'],
],
'Access' => [
['label' => 'Roles', 'route' => 'admin.roles.index', 'active' => 'admin.roles.*', 'icon' => 'fi-rr-shield-user'],
['label' => 'Permissions', 'route' => 'admin.permissions.index', 'active' => 'admin.permissions.*', 'icon' => 'fi-rr-lock'],
['label' => 'Role Permissions', 'route' => 'admin.role-permissions.index', 'active' => 'admin.role-permissions.*', 'icon' => 'fi-rr-keys'],
],
'Academy' => [
['label' => 'Colleges', 'route' => 'admin.colleges.index', 'active' => 'admin.colleges.*', 'icon' => 'fi-rr-building'],
['label' => 'Students', 'route' => 'admin.students.index', 'active' => 'admin.students.*', 'icon' => 'fi-rr-user'],
['label' => 'Courses', 'route' => 'admin.courses.index', 'active' => 'admin.courses.*', 'icon' => 'fi-rr-book-bookmark'],
['label' => 'Workspaces', 'route' => 'admin.course-workspaces.index', 'active' => 'admin.course-workspaces.*', 'icon' => 'fi-rr-briefcase'],
['label' => 'Enrollments', 'route' => 'admin.enrollments.index', 'active' => 'admin.enrollments.*', 'icon' => 'fi-rr-clipboard-list'],
],
'Learning Ops' => [
['label' => 'Tasks', 'route' => 'admin.tasks.index', 'active' => 'admin.tasks.*', 'icon' => 'fi-rr-task'],
['label' => 'Quizzes', 'route' => 'admin.quizzes.index', 'active' => 'admin.quizzes.*', 'icon' => 'fi-rr-form'],
['label' => 'Quiz Results', 'route' => 'admin.quiz-results.index', 'active' => 'admin.quiz-results.*', 'icon' => 'fi-rr-chart'],
['label' => 'Certificates', 'route' => 'admin.certificates.index', 'active' => 'admin.certificates.*', 'icon' => 'fi-rr-medal'],
],
'Business' => [
['label' => 'Payments', 'route' => 'admin.payments.index', 'active' => 'admin.payments.*', 'icon' => 'fi-rr-credit-card'],
['label' => 'Attendance', 'route' => 'admin.attendances.index', 'active' => 'admin.attendances.*', 'icon' => 'fi-rr-calendar-check'],
['label' => 'Notifications', 'route' => 'admin.notifications.index', 'active' => 'admin.notifications.*', 'icon' => 'fi-rr-bell'],
],

'Form Data' => [
['label' => 'Counselling Form', 'route' => 'admin.counselling.index', 'active' => 'admin.counselling.*', 'icon' => 'fi-rr-task'],
['label' => 'Course Enquiries', 'route' => 'admin.course-enquiries.index', 'active' => 'admin.course-enquiries.*', 'icon' => 'fi-rr-comment-alt'],
['label' => 'College Partnership', 'route' => 'admin.counselling.partner', 'active' => 'admin.counselling.partner', 'icon' => 'fi-rr-form'],
['label' => 'Chat Logs', 'route' => 'admin.chat-logs.index', 'active' => 'admin.chat-logs.*', 'icon' => 'fi-rr-comment-dots'],
],
];
@endphp

<body class="min-h-screen bg-gray-50 text-textPrimary" x-data="{ sidebarOpen: false, sidebarCollapsed: false }" @keydown.escape.window="sidebarOpen = false">
    <div class="min-h-screen flex">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" x-cloak
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-40 lg:hidden">
        </div>

        <!-- Sidebar -->
        <aside
            :class="[
                sidebarCollapsed ? 'lg:w-20' : 'lg:w-64',
                sidebarOpen ? '!translate-x-0' : ''
            ]"
            class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full transform flex-col border-r border-gray-200 bg-white shadow-sm sidebar-transition lg:translate-x-0"
            aria-label="Admin navigation"
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">

            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand flex items-center justify-center shadow-sm">
                        <span class="text-white font-bold text-sm">EC</span>
                    </div>
                    <span x-show="!sidebarCollapsed" x-transition class="min-w-0">
                        <span class="block text-gray-950 font-semibold text-sm leading-5">Engineers Clinic</span>
                        <span class="block text-xs font-medium text-gray-500">Admin workspace</span>
                    </span>
                </a>
                <button type="button" @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-950"
                    aria-label="Close admin menu">
                    <i class="fi fi-rr-cross"></i>
                </button>
            </div>

            <!-- Toggle Button -->
            <button @click="sidebarCollapsed = !sidebarCollapsed"
                class="hidden lg:flex items-center justify-center h-10 border-b border-gray-200 text-gray-500 transition hover:bg-gray-50 hover:text-gray-950">
                <i class="fi" :class="sidebarCollapsed ? 'fi-rr-angle-right' : 'fi-rr-angle-left'"></i>
            </button>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto px-3 py-5">
                @foreach($navigationGroups as $group => $items)
                <div class="{{ !$loop->first ? 'mt-6' : '' }}">
                    <p x-show="!sidebarCollapsed" x-transition
                        class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.14em] text-gray-400">
                        {{ $group }}
                    </p>
                    <ul class="space-y-1">
                        @foreach($items as $item)
                        @php($isActive = request()->routeIs($item['active']))
                        <li>
                            <a href="{{ route($item['route']) }}" @click="sidebarOpen = false"
                                class="group relative flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $isActive ? 'bg-brandSoft text-brand' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-950' }}">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg transition {{ $isActive ? 'bg-white text-brand shadow-sm' : 'text-gray-500 group-hover:text-gray-700' }}">
                                    <i class="fi {{ $item['icon'] }} text-base leading-none"></i>
                                </span>
                                <span x-show="!sidebarCollapsed" x-transition class="truncate">{{ $item['label'] }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </nav>

            <!-- Logout -->
            <div class="px-3 py-4 border-t border-gray-200">
                <div x-show="!sidebarCollapsed" x-transition
                    class="mb-3 rounded-lg border border-gray-200 bg-gray-50 p-3">
                    <p class="text-sm font-semibold text-gray-950">{{ Auth::user()->name ?? 'Admin' }}</p>
                    <p class="mt-1 truncate text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@engineersclinic.in' }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-gray-600 transition hover:bg-red-50 hover:text-red-700">
                        <span class="flex h-8 w-8 items-center justify-center rounded-lg">
                            <i class="fi fi-rr-sign-out text-base leading-none"></i>
                        </span>
                        <span x-show="!sidebarCollapsed" x-transition>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="relative flex-1 lg:ml-64" :class="sidebarCollapsed ? 'lg:!ml-20' : 'lg:!ml-64'">
            <!-- Mobile Header -->
            <header class="sticky top-0 z-30 lg:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-glassBorder">
                <button type="button"
                    @click="sidebarCollapsed = false; sidebarOpen = true"
                    class="flex h-10 w-10 items-center justify-center rounded-2xl border border-borderLight bg-white text-textPrimary shadow-sm transition-colors duration-200 hover:bg-brandSoft hover:text-brand"
                    aria-label="Open admin menu">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <span class="text-lg font-semibold text-textPrimary">Engineers Clinic</span>
                <div class="w-8"></div>
            </header>

            <!-- Page Content -->
            <main class="relative p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>

</html>
