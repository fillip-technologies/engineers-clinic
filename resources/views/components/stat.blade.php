<section class="relative bg-bgMain py-24">

    <!-- 🔥 RIGHT ORANGE GLOW -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute bottom-0 right-0 w-[420px] h-[420px] bg-glowOrange blur-[160px] rounded-full opacity-60"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-6">

        <!-- CARD -->
        <div class="bg-bgWhite border border-borderLight rounded-3xl shadow-lg px-12 py-14">

            <div class="grid grid-cols-2 md:grid-cols-4 text-center">

                <!-- ITEM -->
                <div class="flex flex-col items-center px-6 py-6">
                    <p class="text-4xl md:text-5xl font-bold text-textPrimary counter" data-target="100000">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Active Learners</p>
                </div>

                <!-- ITEM -->
                <div class="flex flex-col items-center px-6 py-6 border-l border-borderLight">
                    <p class="text-4xl md:text-5xl font-bold text-textPrimary counter" data-target="500">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Expert Courses</p>
                </div>

                <!-- ITEM -->
                <div class="flex flex-col items-center px-6 py-6 border-t md:border-t-0 md:border-l border-borderLight">
                    <p class="text-4xl md:text-5xl font-bold text-textPrimary counter" data-target="95">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Completion Rate</p>
                </div>

                <!-- ITEM -->
                <div class="flex flex-col items-center px-6 py-6 border-t md:border-t-0 md:border-l border-borderLight">
                    <p class="text-4xl md:text-5xl font-bold text-textPrimary counter" data-target="4.9">0</p>
                    <p class="text-sm text-textSecondary mt-3 tracking-wide">Average Rating</p>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- ✨ SMALL ENHANCEMENT CSS -->
<style>
.counter {
    transition: transform 0.3s ease;
}

.counter:hover {
    transform: scale(1.05);
}
</style>

<!-- ⚡ COUNTER SCRIPT -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    const counters = document.querySelectorAll('.counter');

    const animateCounter = (counter) => {
        const target = parseFloat(counter.getAttribute('data-target'));
        let count = 0;

        const duration = 1500; // speed control
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

    // Trigger on scroll
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.6 });

    counters.forEach(counter => observer.observe(counter));

});
</script>