<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? config('app.name', 'Engineers Clinic') }}</title>

    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

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
