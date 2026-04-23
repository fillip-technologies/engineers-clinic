<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Engineers Clinic') }}</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            cursor: none;
        }

        #cursor {
            width: 10px;
            height: 10px;
            background: #15803d;
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9999;
            transform: translate(-50%, -50%);
            transition: transform 0.08s ease;
        }

        #cursor-glow {
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.18) 0%, transparent 70%);
            border-radius: 50%;
            position: fixed;
            pointer-events: none;
            z-index: 9998;
            transform: translate(-50%, -50%);
            transition: all 0.12s ease;
        }
    </style>

    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {

                        /* 🎯 BRAND (MAIN CONTROL) */
                        brand: '#15803d', // main green
                        brandLight: '#22c55e', // hover / lighter
                        brandDark: '#166534', // darker shade
                        brandSoft: 'rgba(34,197,94,0.15)',

                        /* 🌿 SECONDARY (warm tone like SkillForge) */
                        secondary: '#f97316', // orange
                        secondarySoft: 'rgba(249,115,22,0.15)',

                        /* 🧱 BACKGROUND SYSTEM */
                        bgMain: '#f8f6f4', // main bg (hero/header)
                        bgSoft: '#f1f5f9', // light sections
                        bgWhite: '#ffffff',

                        /* 📝 TEXT SYSTEM */
                        textPrimary: '#111827', // main text
                        textSecondary: '#4b5563', // paragraph
                        textMuted: '#9ca3af',

                        /* 🧊 UI ELEMENTS */
                        borderLight: '#e5e7eb',
                        cardBg: 'rgba(255,255,255,0.9)',

                        /* ✨ EFFECTS */
                        glowGreen: 'rgba(34,197,94,0.18)',
                        glowOrange: 'rgba(249,115,22,0.18)'
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body>

    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <div id="cursor"></div>
    <div id="cursor-glow"></div>

    <script>
        const cursor = document.getElementById("cursor");
        const glow = document.getElementById("cursor-glow");

        document.addEventListener("mousemove", (e) => {
            const x = e.clientX;
            const y = e.clientY;

            cursor.style.left = x + "px";
            cursor.style.top = y + "px";

            glow.style.left = x + "px";
            glow.style.top = y + "px";
        });

        // Hover effect
        document.querySelectorAll("a, button").forEach(el => {
            el.addEventListener("mouseenter", () => {
                cursor.style.transform = "translate(-50%, -50%) scale(1.8)";
                glow.style.transform = "translate(-50%, -50%) scale(1.3)";
            });

            el.addEventListener("mouseleave", () => {
                cursor.style.transform = "translate(-50%, -50%) scale(1)";
                glow.style.transform = "translate(-50%, -50%) scale(1)";
            });
        });

        // Disable on mobile
        if (window.innerWidth < 768) {
            cursor.style.display = "none";
            glow.style.display = "none";
        }
    </script>

</body>

</html>