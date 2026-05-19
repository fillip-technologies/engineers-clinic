<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name', 'Engineers Clinic') }}</title>

    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,800,900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bgDark: '#F5F0FF',
                        bgDarkSoft: '#ffffff',
                        bgIndigo: '#EEF5FF',
                        primary: '#7C5CFC',
                        primaryLight: '#A78BFA',
                        primarySoft: 'rgba(124,92,252,0.15)',
                        secondary: '#F5C842',
                        secondaryLight: '#A78BFA',
                        secondaryDark: '#160840',
                        secondarySoft: 'rgba(245,200,66,0.18)',
                        accent: '#ffffff',
                        accentLight: '#FAF7FF',
                        accentSoft: 'rgba(184,222,255,0.35)',
                        textPrimary: '#160840',
                        textSecondary: '#3D2090',
                        textMuted: '#8B7FBF',
                        glass: '#ffffff',
                        glassBorder: '#E2D9FF',
                        brand: '#7C5CFC',
                        brandDark: '#160840',
                        brandSoft: 'rgba(124,92,252,0.15)',
                        bgWhite: '#ffffff',
                        bgSoft: '#EEF5FF',
                        borderLight: '#E2D9FF',
                        success: '#22C55E',
                        warning: '#F59E0B',
                        danger: '#EF4444'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Satoshi', ui-sans-serif, system-ui, sans-serif;
        }

        [x-cloak] {
            display: none !important;
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

        <div class="min-w-0 border-l border-glassBorder bg-bgWhite">
            <x-common.navbar :user-name="$navbarUserName ?? 'Guest'" />

            <main class="px-6 pb-10 pt-6 sm:px-8 lg:px-10">
                @yield('content')
            </main>
        </div>
    </div>
</body>

</html>
