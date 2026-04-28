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
                        glassBorder: '#E2E8F0',
                        brand: '#2563EB',
                        brandDark: '#1D4ED8',
                        brandSoft: 'rgba(37,99,235,0.08)',
                        bgWhite: '#FFFFFF',
                        bgSoft: '#F8FAFC',
                        borderLight: '#E2E8F0'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-bgDark text-textPrimary">
    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
        <x-frontend-admin.sidebar
            :sections="$sidebarSections ?? []"
            :active-page="$activeDashboardPage ?? null"
            :user-name="$sidebarUserName ?? 'Guest'"
            :user-meta="$sidebarUserMeta ?? null" />

        <div class="min-w-0 border-l border-glassBorder bg-[#FCFDFE]">
            <x-common.navbar :user-name="$navbarUserName ?? 'Guest'" />

            <main class="px-6 pb-10 pt-6 sm:px-8 lg:px-10">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
