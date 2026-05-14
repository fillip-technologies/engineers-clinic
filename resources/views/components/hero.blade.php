<section id="heroSection"
    class="relative overflow-hidden flex items-center pt-10 pb-12 sm:min-h-[85vh] sm:pt-16"
    style="background: linear-gradient(135deg, var(--color-auroraLeft) 0%, var(--color-auroraMid) 50%, var(--color-auroraRight) 100%);">

    <!-- PARTICLES BACKGROUND -->
    <div id="particles-js"></div>

    <!-- SOFT AURORA GLOW -->
    <div class="absolute inset-0 pointer-events-none z-[1]">
        <div class="absolute top-0 left-0 h-64 w-64 rounded-full blur-[100px] sm:h-[400px] sm:w-[400px] sm:blur-[140px]"
            style="background: theme('colors.glowPurple');"></div>
        <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full blur-[100px] sm:h-[400px] sm:w-[400px] sm:blur-[140px]"
            style="background: theme('colors.glowPink');"></div>
    </div>

    <div class="relative z-[2] max-w-7xl mx-auto px-5 sm:px-6 w-full">

        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 items-center">

            <!-- LEFT -->
            <div>

                <div class="mb-6">
                    <span class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-full border"
                        style="background: bg-cardBg; border-color: theme('colors.cardBorder'); color: theme('colors.textPrimary');">
                        ● New: AI & Machine Learning Track
                    </span>
                </div>

                <h1 class="text-3xl sm:text-5xl md:text-6xl font-bold leading-tight mb-6 text-textPrimary">
                    <span class="bg-gradient-to-r from-brand via-brandLight to-secondary bg-clip-text text-transparent">
                        Forge Your Future
                    </span>
                    <br>
                    with Expert-Led Internship
                </h1>

                <p class="text-base leading-7 sm:text-lg mb-8 max-w-xl text-textSecondary">
                    Master in-demand skills with hands-on projects and real-world applications.
                    Join thousands of learners advancing their careers.
                </p>

                <div class="flex flex-col gap-4 mb-10 sm:flex-row sm:gap-5">
                    <button
                        class="w-full px-8 py-4 rounded-xl font-semibold shadow-md hover:scale-[1.02] transition bg-secondary text-textPrimary sm:w-auto">
                        Browse Internship →
                    </button>

                    <button
                        class="w-full px-8 py-4 rounded-xl font-semibold transition border bg-cardBg border-cardBorder text-textPrimary sm:w-auto">
                        View Pricing
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <div class="flex -space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="w-9 h-9 rounded-full border-2 border-cardBorder">
                        <img src="https://i.pravatar.cc/40?img=2" class="w-9 h-9 rounded-full border-2 border-cardBorder">
                        <img src="https://i.pravatar.cc/40?img=3" class="w-9 h-9 rounded-full border-2 border-cardBorder">
                        <img src="https://i.pravatar.cc/40?img=4" class="w-9 h-9 rounded-full border-2 border-cardBorder">
                    </div>

                    <p class="text-sm text-textSecondary">
                        <span class="font-semibold text-textPrimary">100K+</span> learners
                    </p>

                    <p class="text-sm text-textSecondary">
                        ⭐ <span class="font-semibold text-textPrimary">4.9</span> avg. rating
                    </p>
                </div>

            </div>

            <!-- RIGHT (FORM) -->
            <div class="relative flex justify-center">

                <!-- BLOBS -->
                <div class="absolute -top-10 -left-10 h-48 w-48 rounded-full blur-3xl opacity-60 bg-glowPurple sm:h-[300px] sm:w-[300px]"></div>
                <div class="absolute -bottom-10 -right-10 h-48 w-48 rounded-full blur-3xl opacity-60 bg-glowPink sm:h-[300px] sm:w-[300px]"></div>

                <div class="relative w-full max-w-md">

                    <div class="backdrop-blur rounded-2xl shadow-[0_20px_60px_rgba(22,8,64,0.10)] border p-6 sm:p-8
                                bg-cardBg border-cardBorder">

                        <!-- Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center gap-2 text-xs px-3 py-1 rounded-full border
                                         bg-cardBg border-borderLight text-textSecondary">
                                ● Free Career Guidance
                            </span>
                        </div>

                        <!-- Heading -->
                        <h3 class="text-xl font-semibold mb-1 text-textPrimary">
                            Book a Free Call
                        </h3>

                        <p class="text-sm mb-6 text-textMuted">
                            Talk to our expert & plan your career path
                        </p>

                        <!-- FORM -->
                        <form action="{{ route('counselling.store') }}" method="POST" class="space-y-4">
                            @csrf

                            <input type="text"
                                name="name"
                                placeholder="Full Name"
                                required
                                class="w-full rounded-lg px-4 py-3 outline-none transition
                                    border border-borderLight bg-bgWhite text-textPrimary
                                    focus:ring-2 focus:ring-brand focus:border-brand
                                    placeholder:text-textMuted">

                            <input type="tel"
                                name="phone"
                                placeholder="Phone Number"
                                required
                                class="w-full rounded-lg px-4 py-3 outline-none transition
                                    border border-borderLight bg-bgWhite text-textPrimary
                                    focus:ring-2 focus:ring-brand focus:border-brand
                                    placeholder:text-textMuted">

                            <input type="email"
                                name="email"
                                placeholder="Email Address (optional)"
                                class="w-full rounded-lg px-4 py-3 outline-none transition
                                    border border-borderLight bg-bgWhite text-textPrimary
                                    focus:ring-2 focus:ring-brand focus:border-brand
                                    placeholder:text-textMuted">

                            <button
                                type="submit"
                                class="w-full py-3 rounded-lg font-semibold shadow-md transition
                                    hover:scale-[1.02] bg-secondary text-textPrimary">
                                Get Free Counselling
                            </button>
                        </form>

                        <p class="text-xs mt-4 text-center text-textMuted">
                            🔒 No spam. Only helpful guidance.
                        </p>

                    </div>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- PARTICLES CSS -->
<style>
    #particles-js {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
        opacity: 1;
        filter: drop-shadow(0 0 10px theme('colors.glowPurple'));
    }

    #particles-js canvas {
        display: block;
        width: 100% !important;
        height: 100% !important;
    }

    #heroSection > *:not(#particles-js) {
        position: relative;
        z-index: 2;
    }

    @media (max-width: 640px) {
        #particles-js {
            opacity: 0.6;
            filter: none;
        }
    }
</style>

<!-- PARTICLES JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (typeof particlesJS === "undefined") {
            return;
        }

        const colors = {
            brand:        tailwind.config.theme.extend.colors.brand,
            brandLight:   tailwind.config.theme.extend.colors.brandLight,
            auroraLeft:   tailwind.config.theme.extend.colors.auroraLeft,
            auroraMid:    tailwind.config.theme.extend.colors.auroraMid,
            auroraRight:  tailwind.config.theme.extend.colors.auroraRight,
        };

        const getParticleSettings = () => {
            const isMobile = window.matchMedia("(max-width: 640px)").matches;
            const isTablet = window.matchMedia("(max-width: 1024px)").matches;

            return {
                count: isMobile ? 22 : (isTablet ? 38 : 60),
                size: isMobile ? 3 : (isTablet ? 4 : 5),
                distance: isMobile ? 95 : (isTablet ? 130 : 170),
                speed: isMobile ? 0.8 : 1.6,
                lineOpacity: isMobile ? 0.22 : 0.40,
                hover: !isMobile
            };
        };

        const destroyParticles = () => {
            if (!window.pJSDom || !window.pJSDom.length) {
                return;
            }

            window.pJSDom.forEach((instance) => {
                instance.pJS.fn.vendors.destroypJS();
            });

            window.pJSDom = [];
        };

        const initHeroParticles = () => {
            const settings = getParticleSettings();

            destroyParticles();

            particlesJS("particles-js", {
                particles: {
                    number: { value: settings.count },

                    color: { value: [colors.brand, colors.brandLight, colors.auroraMid, colors.auroraRight] },

                    shape: { type: "circle" },

                    opacity: { value: 0.55, random: true },

                    size: { value: settings.size, random: true },

                    line_linked: {
                        enable: true,
                        distance: settings.distance,
                        color: colors.brand,
                        opacity: settings.lineOpacity,
                        width: 1.2
                    },

                    move: { enable: true, speed: settings.speed }
                },

                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: { enable: settings.hover, mode: "grab" },
                        onclick: { enable: true, mode: "push" },
                        resize: true
                    },
                    modes: {
                        grab: { distance: 220, line_linked: { opacity: 1 } },
                        push: { particles_nb: settings.hover ? 4 : 2 }
                    }
                },

                retina_detect: true
            });
        };

        initHeroParticles();

        let resizeTimer;
        window.addEventListener("resize", () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(initHeroParticles, 250);
        });
    });
</script>
