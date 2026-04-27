<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name', 'Engineers Clinic') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bgDark: '#F8FAFC',
                        bgDarkSoft: '#FFFFFF',
                        bgIndigo: '#EEF2FF',
                        primary: '#2563EB',
                        primaryLight: '#1D4ED8',
                        primarySoft: 'rgba(37,99,235,0.08)',
                        secondary: '#0F172A',
                        secondaryLight: '#334155',
                        secondaryDark: '#020617',
                        secondarySoft: 'rgba(15,23,42,0.05)',
                        accent: '#FFFFFF',
                        accentLight: '#F8FAFC',
                        accentSoft: 'rgba(148,163,184,0.10)',
                        textPrimary: '#0F172A',
                        textSecondary: '#475569',
                        textMuted: '#64748B',
                        glass: '#FFFFFF',
                        glassBorder: '#E2E8F0'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-bgDark text-textPrimary" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">
    <div class="min-h-screen flex">
        <!-- Mobile Overlay -->
        <div x-show="sidebarOpen" 
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
            :class="sidebarCollapsed ? 'w-20' : 'w-64'"
            class="fixed lg:static inset-y-0 left-0 z-50 flex flex-col bg-secondaryDark sidebar-transition transform -translate-x-full lg:translate-x-0"
            x-show="sidebarOpen || true"
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full">
            
            <!-- Logo -->
            <div class="flex items-center justify-between h-16 px-4 border-b border-secondaryLight">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand to-secondary flex items-center justify-center">
                        <span class="text-white font-bold text-lg">EC</span>
                    </div>
                    <span x-show="!sidebarCollapsed" class="text-white font-semibold text-lg">Admin Panel</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white">
                    <i class="fi fi-rr-cross"></i>
                </button>
            </div>

            <!-- Toggle Button -->
            <button @click="sidebarCollapsed = !sidebarCollapsed" 
                    class="hidden lg:flex items-center justify-center h-10 border-b border-secondaryLight text-white/50 hover:text-white transition">
                <i class="fi" :class="sidebarCollapsed ? 'fi-rr-angle-right' : 'fi-rr-angle-left'"></i>
            </button>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-1 px-3">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-dashboard text-lg"></i>
                            <span x-show="!sidebarCollapsed">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.roles.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.roles.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-shield-user text-lg"></i>
                            <span x-show="!sidebarCollapsed">Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.permissions.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.permissions.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-lock text-lg"></i>
                            <span x-show="!sidebarCollapsed">Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.role-permissions.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.role-permissions.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-keys text-lg"></i>
                            <span x-show="!sidebarCollapsed">Role Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.colleges.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.colleges.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-building text-lg"></i>
                            <span x-show="!sidebarCollapsed">Colleges</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.students.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-user text-lg"></i>
                            <span x-show="!sidebarCollapsed">Students</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.courses.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.courses.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-book-bookmark text-lg"></i>
                            <span x-show="!sidebarCollapsed">Courses</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.enrollments.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.enrollments.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-clipboard-list text-lg"></i>
                            <span x-show="!sidebarCollapsed">Enrollments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.tasks.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.tasks.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-task text-lg"></i>
                            <span x-show="!sidebarCollapsed">Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.quizzes.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.quizzes.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-form text-lg"></i>
                            <span x-show="!sidebarCollapsed">Quizzes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.quiz-results.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.quiz-results.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-chart text-lg"></i>
                            <span x-show="!sidebarCollapsed">Quiz Results</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.certificates.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.certificates.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-medal text-lg"></i>
                            <span x-show="!sidebarCollapsed">Certificates</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.payments.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.payments.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-credit-card text-lg"></i>
                            <span x-show="!sidebarCollapsed">Payments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.attendances.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.attendances.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-calendar-check text-lg"></i>
                            <span x-show="!sidebarCollapsed">Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.notifications.index') }}" 
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 hover:bg-secondaryLight hover:text-white transition {{ request()->routeIs('admin.notifications.*') ? 'bg-secondaryLight text-white' : '' }}">
                            <i class="fi fi-rr-bell text-lg"></i>
                            <span x-show="!sidebarCollapsed">Notifications</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Logout -->
            <div class="px-3 py-4 border-t border-secondaryLight">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-white/70 hover:bg-red-600 hover:text-white transition">
                        <i class="fi fi-rr-sign-out text-lg"></i>
                        <span x-show="!sidebarCollapsed">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-0" :class="sidebarCollapsed ? 'lg:ml-20' : 'lg:ml-64'">
            <!-- Mobile Header -->
            <header class="lg:hidden flex items-center justify-between h-16 px-4 bg-white border-b border-glassBorder">
                <button @click="sidebarOpen = true" class="text-textSecondary hover:text-textPrimary">
                    <i class="fi fi-rr-bars text-xl"></i>
                </button>
                <span class="text-lg font-semibold text-textPrimary">Admin Panel</span>
                <div class="w-8"></div>
            </header>

            <!-- Page Content -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>