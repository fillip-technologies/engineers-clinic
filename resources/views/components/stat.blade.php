@php
    $stats = [
        ['value' => 6000, 'display' => '6K+', 'label' => 'Students enrolled', 'note' => 'Building real project portfolios'],
        ['value' => 320, 'display' => '320+', 'label' => 'Project options', 'note' => 'Across multiple domains and levels'],
        ['value' => 4100, 'display' => '4.1K+', 'label' => 'Certificates issued', 'note' => 'Unlocked after submission review'],
        ['value' => 98, 'display' => '98%', 'label' => 'Completion rate', 'note' => 'Guided by milestone workflows'],
    ];
@endphp

<section class="bg-[#FAFBFF] py-14 sm:py-16 lg:py-20">
    <div class="container-main">
        <div class="overflow-hidden rounded-[2rem] border border-[#ECEBFF] bg-white shadow-[0_24px_80px_rgba(15,10,42,0.08)]">
            <div class="grid lg:grid-cols-[0.82fr_1.18fr]">
                <div class="relative isolate bg-gradient-to-br from-white to-[#F5F3FF] p-6 sm:p-8 lg:p-10">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_18%_22%,rgba(109,93,246,0.12),transparent_32%),radial-gradient(circle_at_85%_78%,rgba(168,85,247,0.10),transparent_30%)]"></div>
                    <span class="inline-flex rounded-full bg-[#F5F3FF] px-4 py-2 text-xs font-black uppercase tracking-[0.16em] text-[#6D5DF6]">Success Numbers</span>
                    <h2 class="mt-5 text-3xl font-black leading-tight text-[#161326] sm:text-4xl">Proof that structure creates outcomes.</h2>
                    <p class="mt-4 text-base leading-8 text-[#6B7280]">Students move from project selection to GitHub proof and verified certificates with a clear completion system.</p>
                </div>

                <div class="grid sm:grid-cols-2">
                    @foreach ($stats as $index => $stat)
                        <div class="group border-[#ECEBFF] p-6 transition duration-300 hover:bg-[#FAFBFF] sm:p-8 {{ $index > 0 ? 'border-t' : '' }} {{ $index === 1 ? 'sm:border-l sm:border-t-0' : '' }} {{ $index === 3 ? 'sm:border-l' : '' }}">
                            <p class="stat-counter text-4xl font-black leading-none text-[#161326] transition group-hover:scale-[1.03] sm:text-5xl" data-target="{{ $stat['value'] }}" data-display="{{ $stat['display'] }}">0</p>
                            <p class="mt-4 text-base font-black text-[#161326]">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-sm leading-6 text-[#6B7280]">{{ $stat['note'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const counters = document.querySelectorAll(".stat-counter");
        const formatNumber = (value, finalDisplay) => {
            if (finalDisplay.includes("K+")) return `${(value / 1000).toFixed(finalDisplay.includes(".") ? 1 : 0)}K+`;
            if (finalDisplay.includes("%")) return `${Math.floor(value)}%`;
            if (finalDisplay.includes("+")) return `${Math.floor(value)}+`;
            return Math.floor(value).toString();
        };
        const animateCounter = (counter) => {
            const target = parseFloat(counter.dataset.target);
            const finalDisplay = counter.dataset.display;
            const duration = 1400;
            const startTime = performance.now();
            const update = (currentTime) => {
                const progress = Math.min((currentTime - startTime) / duration, 1);
                const easedProgress = 1 - Math.pow(1 - progress, 3);
                counter.textContent = progress === 1 ? finalDisplay : formatNumber(target * easedProgress, finalDisplay);
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
        };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.4 });
        counters.forEach((counter) => observer.observe(counter));
    });
</script>
