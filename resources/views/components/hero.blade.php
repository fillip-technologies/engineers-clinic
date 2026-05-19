<section id="heroSection" class="section-surface relative isolate overflow-hidden section-padding-sm">
    <div id="particles-js" class="absolute inset-0 z-0"></div>

    <div class="pointer-events-none absolute inset-0 z-0">
        <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-glowPurple blur-3xl"></div>
        <div class="absolute -right-24 bottom-0 h-72 w-72 rounded-full bg-glowPink blur-3xl"></div>
    </div>

    <div class="container-main relative z-10">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
            <div>
                <span class="badge-pill">
                    <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                    Industry-Led Internship Platform
                </span>

                <h1 class="text-hero mt-6 max-w-4xl">
                    Learn by building
                    <span class="gradient-text block">real-world</span>
                    workflows
                </h1>

                <p class="text-body-lg mt-6 max-w-2xl">
                    Choose your level, work on real projects, complete tasks, build practical skills, and earn industry-focused certifications.
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    <a href="#courses" class="btn-primary">Start Internship</a>
                    <a href="#courses" class="btn-secondary">Explore Tracks</a>
                </div>

                <div class="mt-9 flex flex-wrap items-center gap-5">
                    <div class="flex -space-x-3">
                        @foreach ([1, 2, 3, 4] as $image)
                        <img src="https://i.pravatar.cc/48?img={{ $image }}" alt="" class="h-10 w-10 rounded-full border-2 border-cardBorder object-cover">
                        @endforeach
                    </div>

                    <p class="text-caption">
                        <span class="font-bold text-textPrimary">100K+</span> learners
                    </p>

                    <p class="text-caption">
                        <span class="font-bold text-textPrimary">4.9</span> average rating
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="glass-card mx-auto max-w-md p-6 sm:p-8">
                    <span class="badge-pill">
                        <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                        Free Career Guidance
                    </span>

                    <h2 class="text-card-title mt-5">Book a Free Call</h2>
                    <p class="text-body mt-2">Talk to our expert and plan your career path.</p>

                    <form action="{{ route('counselling.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf

                        <input type="text" name="name" placeholder="Full Name" required class="input-primary">
                        <input type="tel" name="phone" placeholder="Phone Number" required class="input-primary">
                        <input type="email" name="email" placeholder="Email Address (optional)" class="input-primary">

                        <button type="submit" class="btn-primary w-full">
                            Get Free Counselling
                        </button>
                    </form>

                    <p class="text-caption mt-4 text-center">
                        No spam. Only helpful guidance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    #particles-js {
        height: 100%;
        width: 100%;
        opacity: 1;
        overflow: hidden;
        filter: drop-shadow(0 0 10px var(--color-glow-purple));
    }

    #particles-js canvas {
        display: block;
        height: 100% !important;
        width: 100% !important;
    }

    @media (max-width: 640px) {
        #particles-js {
            opacity: 0.6;
            filter: none;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (typeof particlesJS === "undefined") {
            return;
        }

        const colors = tailwind.config.theme.extend.colors;

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

            window.pJSDom.forEach((instance) => instance.pJS.fn.vendors.destroypJS());
            window.pJSDom = [];
        };

        const initHeroParticles = () => {
            const settings = getParticleSettings();
            destroyParticles();

            particlesJS("particles-js", {
                particles: {
                    number: {
                        value: settings.count
                    },
                    color: {
                        value: [colors.brand, colors.brandLight, colors.auroraMid, colors.auroraRight]
                    },
                    shape: {
                        type: "circle"
                    },
                    opacity: {
                        value: 0.55,
                        random: true
                    },
                    size: {
                        value: settings.size,
                        random: true
                    },
                    line_linked: {
                        enable: true,
                        distance: settings.distance,
                        color: colors.brand,
                        opacity: settings.lineOpacity,
                        width: 1.2
                    },
                    move: {
                        enable: true,
                        speed: settings.speed
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: settings.hover,
                            mode: "grab"
                        },
                        onclick: {
                            enable: true,
                            mode: "push"
                        },
                        resize: true
                    },
                    modes: {
                        grab: {
                            distance: 220,
                            line_linked: {
                                opacity: 1
                            }
                        },
                        push: {
                            particles_nb: settings.hover ? 4 : 2
                        }
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
