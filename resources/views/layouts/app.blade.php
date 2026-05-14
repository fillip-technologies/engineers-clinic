<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config( 'Engineers Clinic') }}</title>

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- <style>
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
    </style> -->

    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/uicons-regular-rounded/css/uicons-regular-rounded.css">


    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        /* 🎯 BRAND (Aurora Primary) */
                        brand: '#7C5CFC', // aurora violet
                        brandLight: '#A78BFA', // hover / lighter violet
                        brandDark: '#160840', // deep navy-purple (headings)
                        brandSoft: 'rgba(124,92,252,0.15)',
                        /* 🌸 SECONDARY (Aurora Accent) */
                        secondary: '#F5C842', // gold — CTA accent
                        secondarySoft: 'rgba(245,200,66,0.18)',
                        /* 🌈 AURORA GRADIENT STOPS */
                        auroraLeft: '#DDD0FF', // gradient start — soft purple
                        auroraMid: '#B8DEFF', // gradient mid — sky blue
                        auroraRight: '#FFD0E8', // gradient end — blush pink
                        /* 🧱 BACKGROUND SYSTEM */
                        bgMain: '#F5F0FF', // subtle purple-tinted page bg
                        bgSoft: '#EEF5FF', // sky-tinted section bg
                        bgWhite: '#ffffff',
                        /* 🧊 GLASS / FROSTED CARDS */
                        cardBg: 'rgba(255,255,255,0.60)', // frosted glass card
                        cardBorder: 'rgba(255,255,255,0.85)', // glass border
                        /* 📝 TEXT SYSTEM */
                        textPrimary: '#160840', // deep navy-purple (not black)
                        textSecondary: '#3D2090', // mid purple for paragraphs
                        textMuted: '#8B7FBF', // muted purple-gray
                        /* 🧱 UI ELEMENTS */
                        borderLight: '#E2D9FF', // soft lavender border
                        /* ✨ GLOW EFFECTS */
                        glowPurple: 'rgba(124,92,252,0.20)',
                        glowBlue: 'rgba(184,222,255,0.35)',
                        glowPink: 'rgba(255,208,232,0.35)'
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

    @include('form.college-partnership-discussion')

    @include('partials.footer')

    <!-- <div id="cursor"></div>
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
    </script> -->



</body>

</html>