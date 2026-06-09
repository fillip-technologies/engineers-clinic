<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Engineers Clinic') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Engineers-clinic-logo.png') }}">

    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@400,500,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --color-brand: #7C5CFC;
            --color-brand-light: #A78BFA;
            --color-brand-dark: #160840;
            --color-brand-soft: rgba(124, 92, 252, 0.15);
            --color-secondary: #F5C842;
            --color-secondary-soft: rgba(245, 200, 66, 0.18);
            --color-aurora-left: #DDD0FF;
            --color-aurora-mid: #B8DEFF;
            --color-aurora-right: #FFD0E8;
            --color-bg-main: #F5F0FF;
            --color-bg-soft: #EEF5FF;
            --color-bg-white: #ffffff;
            --color-surface: #FAF7FF;
            --color-surface-dark: #12052E;
            --color-card-bg: rgba(255, 255, 255, 0.60);
            --color-card-border: rgba(255, 255, 255, 0.85);
            --color-text-primary: #160840;
            --color-text-secondary: #3D2090;
            --color-text-muted: #8B7FBF;
            --color-border-light: #E2D9FF;
            --color-glow-purple: rgba(124, 92, 252, 0.20);
            --color-glow-blue: rgba(184, 222, 255, 0.35);
            --color-glow-pink: rgba(255, 208, 232, 0.35);
            --color-success: #22C55E;
            --color-warning: #F59E0B;
            --color-danger: #EF4444;
            --font-sans: 'Satoshi', ui-sans-serif, system-ui, sans-serif;
            --container-main: 1280px;
            --section-padding: 120px;
            --section-padding-sm: 80px;
            --radius-card: 24px;
            --radius-control: 14px;
            --shadow-card: 0 18px 48px rgba(22, 8, 64, 0.08);
            --shadow-glass: 0 24px 70px rgba(22, 8, 64, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--color-bg-main);
            color: var(--color-text-primary);
            font-family: var(--font-sans);
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            font-feature-settings: "kern";
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            color: var(--color-text-primary);
            letter-spacing: -0.04em;
        }

        p {
            color: var(--color-text-secondary);
        }

        a,
        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        [x-cloak] {
            display: none !important;
        }

        .container-main {
            width: 100%;
            max-width: var(--container-main);
            margin-inline: auto;
            padding-inline: 1.5rem;
        }

        .section-padding {
            padding-block: var(--section-padding);
        }

        .section-padding-sm {
            padding-block: var(--section-padding-sm);
        }

        .section-surface {
            background: linear-gradient(135deg, var(--color-bg-main), var(--color-bg-white) 46%, var(--color-bg-soft));
        }

        .section-white {
            background: var(--color-bg-white);
        }

        .text-hero {
            color: var(--color-text-primary);
            font-size: clamp(3rem, 7vw, 4.5rem);
            font-weight: 800;
            line-height: 0.92;
            letter-spacing: -0.06em;
            max-width: 10ch;
        }

        .text-section {
            color: var(--color-text-primary);
            font-size: clamp(2.25rem, 5vw, 3.25rem);
            font-weight: 700;
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .text-card-title {
            color: var(--color-text-primary);
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .text-body-lg {
            color: var(--color-text-secondary);
            font-size: 1.25rem;
            font-weight: 400;
            line-height: 1.7;
        }

        .text-body {
            color: var(--color-text-secondary);
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.7;
        }

        .text-label {
            color: var(--color-brand);
            font-size: 0.8125rem;
            font-weight: 600;
            line-height: 1;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .text-caption {
            color: var(--color-text-muted);
            font-size: 0.8125rem;
            font-weight: 500;
            line-height: 1.5;
        }

        .gradient-text {
            background: linear-gradient(90deg, var(--color-brand), var(--color-brand-light), var(--color-secondary));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .btn-primary,
        .btn-secondary {
            display: inline-flex;
            min-height: 3rem;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            border-radius: var(--radius-control);
            padding: 0.875rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 700;
            line-height: 1;
            transition: transform 180ms ease, box-shadow 180ms ease, background 180ms ease, border-color 180ms ease;
        }

        .btn-primary {
            border: 1px solid var(--color-brand);
            background: var(--color-brand);
            color: var(--color-bg-white);
            box-shadow: 0 16px 34px rgba(124, 92, 252, 0.20);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            background: var(--color-brand-dark);
            border-color: var(--color-brand-dark);
        }

        .btn-secondary {
            border: 1px solid var(--color-border-light);
            background: rgba(255, 255, 255, 0.78);
            color: var(--color-text-primary);
            backdrop-filter: blur(16px);
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
            border-color: var(--color-brand);
            color: var(--color-brand);
            box-shadow: var(--shadow-card);
        }

        .card-primary,
        .glass-card {
            border: 1px solid var(--color-border-light);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
        }

        .card-primary {
            background: var(--color-bg-white);
        }

        .glass-card {
            border-color: var(--color-card-border);
            background: var(--color-card-bg);
            backdrop-filter: blur(20px);
        }

        .input-primary {
            width: 100%;
            border: 1px solid var(--color-border-light);
            border-radius: var(--radius-control);
            background: var(--color-bg-white);
            padding: 0.875rem 1rem;
            color: var(--color-text-primary);
            outline: none;
            transition: border-color 180ms ease, box-shadow 180ms ease, background 180ms ease;
        }

        .input-primary::placeholder {
            color: var(--color-text-muted);
        }

        .input-primary:focus {
            border-color: var(--color-brand);
            box-shadow: 0 0 0 4px var(--color-brand-soft);
        }

        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid var(--color-border-light);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.72);
            padding: 0.625rem 0.875rem;
            color: var(--color-brand);
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            backdrop-filter: blur(16px);
        }

        .nav-shell {
            border: 1px solid var(--color-border-light);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.78);
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(20px);
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border-radius: 999px;
            padding: 0.75rem 1.125rem;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
            font-weight: 600;
            transition: background 180ms ease, color 180ms ease, box-shadow 180ms ease;
        }

        .nav-link:hover,
        .nav-link-active {
            background: var(--color-brand);
            color: var(--color-bg-white);
            box-shadow: 0 12px 24px rgba(124, 92, 252, 0.16);
        }

        .dropdown-panel {
            border: 1px solid var(--color-border-light);
            border-radius: var(--radius-card);
            background: rgba(255, 255, 255, 0.94);
            box-shadow: var(--shadow-glass);
            backdrop-filter: blur(20px);
        }

        @media (max-width: 768px) {
            :root {
                --section-padding: 80px;
                --section-padding-sm: 56px;
            }

            .container-main {
                padding-inline: 1.25rem;
            }

            .text-body-lg {
                font-size: 1.0625rem;
            }
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('form.college-partnership-discussion')
    @include('form.referral-popup')
    @include('partials.footer')
    @include('partials.chatbot')
</body>

</html>
