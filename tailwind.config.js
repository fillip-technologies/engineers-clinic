/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './app/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Satoshi', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            colors: {
                // Brand
                brand: '#7C5CFC',
                brandLight: '#A78BFA',
                brandDark: '#160840',
                brandSoft: 'rgba(124,92,252,0.15)',
                // Primary (admin)
                primary: '#7C5CFC',
                primaryLight: '#A78BFA',
                primarySoft: 'rgba(124,92,252,0.15)',
                // Secondary
                secondary: '#F5C842',
                secondaryLight: '#A78BFA',
                secondaryDark: '#160840',
                secondarySoft: 'rgba(245,200,66,0.18)',
                // Accent
                accent: '#ffffff',
                accentLight: '#FAF7FF',
                accentSoft: 'rgba(184,222,255,0.35)',
                // Aurora
                auroraLeft: '#DDD0FF',
                auroraMid: '#B8DEFF',
                auroraRight: '#FFD0E8',
                // Backgrounds
                bgMain: '#F5F0FF',
                bgSoft: '#EEF5FF',
                bgWhite: '#ffffff',
                bgDark: '#F5F0FF',
                bgDarkSoft: '#ffffff',
                bgIndigo: '#EEF5FF',
                // Surfaces / glass / cards
                surface: '#FAF7FF',
                surfaceDark: '#12052E',
                cardBg: 'rgba(255,255,255,0.60)',
                cardBorder: 'rgba(255,255,255,0.85)',
                glass: '#ffffff',
                glassBorder: '#E2D9FF',
                // Text
                textPrimary: '#160840',
                textSecondary: '#3D2090',
                textMuted: '#8B7FBF',
                // Borders
                borderLight: '#E2D9FF',
                // Glows
                glowPurple: 'rgba(124,92,252,0.20)',
                glowBlue: 'rgba(184,222,255,0.35)',
                glowPink: 'rgba(255,208,232,0.35)',
                // Status
                success: '#22C55E',
                warning: '#F59E0B',
                danger: '#EF4444',
            },
            maxWidth: {
                container: '1280px',
            },
            borderRadius: {
                card: '24px',
                control: '14px',
            },
            boxShadow: {
                card: '0 18px 48px rgba(22,8,64,0.08)',
                glass: '0 24px 70px rgba(22,8,64,0.10)',
            },
        },
    },
    plugins: [],
};
