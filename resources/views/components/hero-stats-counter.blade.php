<section class="relative bg-[#FAFBFF] pb-8 pt-0">
    <div class="container-main">
        <div class="grid gap-4 rounded-[2rem] border border-[#ECEBFF] bg-white/80 p-4 shadow-[0_24px_70px_rgba(15,10,42,0.08)] backdrop-blur-2xl sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['icon' => 'fi-rr-users-alt', 'value' => 6000, 'suffix' => '+', 'label' => 'Students', 'note' => 'Building portfolio projects'],
                ['icon' => 'fi-rr-folder-open', 'value' => 320, 'suffix' => '+', 'label' => 'Projects', 'note' => 'Across beginner to advanced'],
                ['icon' => 'fi-rr-badge-check', 'value' => 4100, 'suffix' => '+', 'label' => 'Certificates', 'note' => 'Issued after review'],
                ['icon' => 'fi-rr-chart-histogram', 'value' => 98, 'suffix' => '%', 'label' => 'Completion Rate', 'note' => 'With milestone guidance'],
            ] as $stat)
                <div class="group rounded-[1.5rem] border border-[#ECEBFF] bg-gradient-to-br from-white to-[#FAFBFF] p-5 transition duration-300 hover:scale-[1.02] hover:border-[#6D5DF6] hover:bg-[#FCFBFF] hover:shadow-[0_18px_45px_rgba(109,93,246,0.14)]">
                    <div class="flex items-start justify-between gap-4">
                        <span class="grid h-12 w-12 shrink-0 place-items-center rounded-2xl bg-[#F5F3FF] text-xl text-[#6D5DF6] transition duration-300 group-hover:scale-105 group-hover:bg-[#EEE9FF] group-hover:text-[#5A4AE3]">
                            <i class="fi {{ $stat['icon'] }}"></i>
                        </span>
                        <span class="rounded-full bg-[#22C55E]/10 px-2.5 py-1 text-[11px] font-black text-[#22C55E]">Verified</span>
                    </div>
                    <p class="mt-5 text-3xl font-black leading-none text-[#161326]">
                        <span class="hero-counter" data-target="{{ $stat['value'] }}">0</span>{{ $stat['suffix'] }}
                    </p>
                    <p class="mt-2 text-sm font-black text-[#161326]">{{ $stat['label'] }}</p>
                    <p class="mt-1 text-sm leading-6 text-[#6B7280]">{{ $stat['note'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const counters = document.querySelectorAll('.hero-counter');

        const animateCounter = (counter) => {
            const target = Number(counter.dataset.target || 0);
            const duration = 1200;
            const startTime = performance.now();

            const tick = (now) => {
                const progress = Math.min((now - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                counter.textContent = Math.floor(target * eased).toLocaleString();

                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            };

            requestAnimationFrame(tick);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting || entry.target.dataset.counted === 'true') {
                    return;
                }

                entry.target.dataset.counted = 'true';
                animateCounter(entry.target);
            });
        }, {
            threshold: 0.4
        });

        counters.forEach((counter) => observer.observe(counter));
    });
</script>
