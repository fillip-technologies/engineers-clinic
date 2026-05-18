<section class="relative overflow-hidden py-14 sm:py-20 lg:py-24"
    style="background: linear-gradient(135deg, theme('colors.auroraLeft') 0%, theme('colors.auroraMid') 50%, theme('colors.auroraRight') 100%);">

    <!-- AURORA GLOW -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute bottom-0 right-0 h-64 w-64 rounded-full blur-[100px] opacity-60 bg-glowPink sm:h-[420px] sm:w-[420px] sm:blur-[160px]"></div>
        <div class="absolute top-0 left-0 h-64 w-64 rounded-full blur-[100px] opacity-60 bg-glowPurple sm:h-[420px] sm:w-[420px] sm:blur-[160px]"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-6">

        <!-- CARD -->
        <div class="backdrop-blur rounded-3xl shadow-lg px-4 py-6 sm:px-8 sm:py-10 lg:px-12 lg:py-14
                    bg-cardBg border border-cardBorder">

            <div class="grid grid-cols-2 md:grid-cols-4 text-center">

                <!-- ITEM 1 -->
                <div class="flex flex-col items-center px-3 py-5 sm:px-6 sm:py-6">
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold text-textPrimary counter"
                        data-target="100000">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Active Learners</p>
                </div>

                <!-- ITEM 2 -->
                <div class="flex flex-col items-center px-3 py-5 sm:px-6 sm:py-6 border-l border-borderLight">
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold text-textPrimary counter"
                        data-target="500">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Expert Courses</p>
                </div>

                <!-- ITEM 3 -->
                <div class="flex flex-col items-center px-3 py-5 sm:px-6 sm:py-6 border-t md:border-t-0 md:border-l border-borderLight">
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold text-textPrimary counter"
                        data-target="95">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Completion Rate</p>
                </div>

                <!-- ITEM 4 -->
                <div class="flex flex-col items-center px-3 py-5 sm:px-6 sm:py-6 border-t border-l md:border-t-0 md:border-l border-borderLight">
                    <p class="text-3xl sm:text-4xl md:text-5xl font-bold text-textPrimary counter"
                        data-target="4.9">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Average Rating</p>
                </div>

            </div>

        </div>

    </div>

</section>

<style>
    .counter {
        transition: transform 0.3s ease;
    }

    .counter:hover {
        transform: scale(1.05);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const counters = document.querySelectorAll('.counter');

        const animateCounter = (counter) => {
            const target = parseFloat(counter.getAttribute('data-target'));
            let count = 0;

            const duration = 1500;
            const startTime = performance.now();

            const update = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                count = target * progress;

                counter.innerText = formatNumber(count);

                if (progress < 1) {
                    requestAnimationFrame(update);
                } else {
                    counter.innerText = formatNumber(target);
                }
            };

            requestAnimationFrame(update);
        };

        const formatNumber = (num) => {
            if (num >= 100000) return (num / 1000).toFixed(0) + "K+";
            if (num >= 1000) return (num / 1000).toFixed(1) + "K+";
            if (num % 1 !== 0) return num.toFixed(1);
            return Math.floor(num);
        };

        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.6
        });

        counters.forEach(counter => observer.observe(counter));

    });
</script>