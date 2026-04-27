<section id="heroSection"
    class="relative overflow-hidden bg-bgMain min-h-[85vh] flex items-center pt-16 pb-10">

    <!-- PARTICLES BACKGROUND -->
    <div id="particles-js"></div>

    <!-- SOFT BACKGROUND GLOW -->
    <div class="absolute inset-0 pointer-events-none z-[1]">
        <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-green-100 blur-[140px] rounded-full"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-orange-100 blur-[140px] rounded-full"></div>
    </div>

    <div class="relative z-[2] max-w-7xl mx-auto px-6 w-full">

        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">

            <!-- LEFT -->
            <div>

                <div class="mb-6">
                    <span
                        class="inline-flex items-center gap-2 text-sm px-4 py-2 rounded-full bg-green-50 text-green-700 border border-green-200">
                        ● New: AI & Machine Learning Track
                    </span>
                </div>

                <h1 class="text-4xl md:text-6xl font-bold leading-tight mb-6 text-gray-900">
                    <span
                        class="bg-gradient-to-r from-green-700 via-emerald-600 to-orange-500 bg-clip-text text-transparent">
                        Forge Your Future
                    </span>
                    <br>
                    with Expert-Led Courses
                </h1>

                <p class="text-lg text-gray-600 mb-8 max-w-xl">
                    Master in-demand skills with hands-on projects and real-world applications.
                    Join thousands of learners advancing their careers.
                </p>

                <div class="flex gap-5 mb-10">
                    <button
                        class="px-8 py-4 bg-green-700 text-white rounded-xl font-semibold shadow-md hover:bg-green-800 hover:scale-[1.02] transition">
                        Browse Courses →
                    </button>

                    <button
                        class="px-8 py-4 bg-white border border-gray-300 text-gray-800 rounded-xl font-semibold hover:bg-gray-100 transition">
                        View Pricing
                    </button>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex -space-x-3">
                        <img src="https://i.pravatar.cc/40?img=1" class="w-9 h-9 rounded-full border-2 border-white">
                        <img src="https://i.pravatar.cc/40?img=2" class="w-9 h-9 rounded-full border-2 border-white">
                        <img src="https://i.pravatar.cc/40?img=3" class="w-9 h-9 rounded-full border-2 border-white">
                        <img src="https://i.pravatar.cc/40?img=4" class="w-9 h-9 rounded-full border-2 border-white">
                    </div>

                    <p class="text-sm text-gray-600">
                        <span class="font-semibold text-gray-900">100K+</span> learners
                    </p>

                    <p class="text-sm text-gray-600">
                        ⭐ <span class="font-semibold text-gray-900">4.9</span> avg. rating
                    </p>
                </div>

            </div>

            <!-- RIGHT (FORM SAME) -->
            <div class="relative flex justify-center">

                <!-- BLOBS -->
                <div class="absolute -top-10 -left-10 w-[300px] h-[300px] bg-green-100 rounded-full blur-3xl opacity-60"></div>
                <div class="absolute -bottom-10 -right-10 w-[300px] h-[300px] bg-orange-100 rounded-full blur-3xl opacity-60"></div>

                <div class="relative w-full max-w-md">

                    <div
                        class="bg-white/90 backdrop-blur rounded-2xl shadow-[0_20px_60px_rgba(0,0,0,0.08)] border border-gray-100 p-8">

                        <!-- Badge -->
                        <div class="mb-4">
                            <span
                                class="inline-flex items-center gap-2 text-xs px-3 py-1 rounded-full bg-green-50 text-green-700 border border-green-200">
                                ● Free Career Guidance
                            </span>
                        </div>

                        <!-- Heading -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-1">
                            Book a Free Call
                        </h3>

                        <p class="text-sm text-gray-500 mb-6">
                            Talk to our expert & plan your career path
                        </p>

                        <!-- FORM (UNCHANGED) -->
                        <form class="space-y-4">

                            <input type="text" placeholder="Full Name"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-400 outline-none transition">

                            <input type="tel" placeholder="Phone Number"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-400 outline-none transition">

                            <input type="email" placeholder="Email Address (optional)"
                                class="w-full border border-gray-200 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-400 outline-none transition">

                            <button
                                class="w-full bg-green-700 text-white py-3 rounded-lg font-semibold shadow-md hover:bg-green-800 transition">
                                Get Free Counselling
                            </button>

                        </form>

                        <p class="text-xs text-gray-400 mt-4 text-center">
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
        z-index: 0;
        opacity: 1;

        /* 🔥 DARK GREEN GLOW */
        filter: drop-shadow(0 0 10px rgba(22, 101, 52, 0.6));
    }

    /* ensure content stays above */
    #heroSection>*:not(#particles-js) {
        position: relative;
        z-index: 2;
    }
</style>

<!-- PARTICLES JS -->
<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        particlesJS("particles-js", {
            particles: {
                number: {
                    value: 60
                },

                /* 🔥 DARK BRAND COLORS */
                color: {
                    value: ["#166534", "#15803d"]
                },

                shape: {
                    type: "circle"
                },

                opacity: {
                    value: 0.75,
                    random: true
                },

                size: {
                    value: 5,
                    random: true
                },

                line_linked: {
                    enable: true,
                    distance: 170,
                    color: "#166534",
                    opacity: 0.6,
                    width: 1.3
                },

                move: {
                    enable: true,
                    speed: 1.6
                }
            },

            interactivity: {
                detect_on: "canvas",
                events: {
                    onhover: {
                        enable: true,
                        mode: "grab"
                    },
                    onclick: {
                        enable: true,
                        mode: "push"
                    }
                },
                modes: {
                    grab: {
                        distance: 220,
                        line_linked: {
                            opacity: 1
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },

            retina_detect: true
        });
    });
</script>