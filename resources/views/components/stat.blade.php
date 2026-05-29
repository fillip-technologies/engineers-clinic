@php
    $stats = [
        [
            'value' => 100000,
            'display' => '100K+',
            'label' => 'Active learners',
            'note' => 'Students building career-ready skills',
        ],
        [
            'value' => 500,
            'display' => '500+',
            'label' => 'Expert courses',
            'note' => 'Practical tracks across domains',
        ],
        [
            'value' => 95,
            'display' => '95%',
            'label' => 'Completion rate',
            'note' => 'Guided flow from task to certificate',
        ],
        [
            'value' => 4.9,
            'display' => '4.9',
            'label' => 'Average rating',
            'note' => 'Trusted by motivated learners',
        ],
    ];
@endphp

<section class="section-white py-12 sm:py-16">
    <div class="container-main">
        <div class="overflow-hidden rounded-card border border-borderLight bg-white shadow-glass">
            <div class="grid lg:grid-cols-[0.8fr_1.2fr]">
                <div class="relative isolate flex flex-col justify-between gap-8 bg-brandDark p-6 sm:p-8 lg:p-10">
                    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_20%_20%,rgba(124,92,252,0.45),transparent_34%),radial-gradient(circle_at_85%_80%,rgba(245,200,66,0.28),transparent_30%)]"></div>

                    <div>
                        <span class="inline-flex rounded-full bg-white/10 px-3 py-2 text-xs font-bold uppercase tracking-[0.12em] text-white/80">
                            Trusted learning outcomes
                        </span>

                        <h2 class="mt-5 text-3xl font-bold leading-tight text-white sm:text-4xl">
                            Numbers that make the first step easier
                        </h2>

                        <p class="mt-4 text-base leading-7 text-white">
                            Engineers Clinic combines structured courses, guided tasks, and practical certification paths for students who want visible progress.
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-brandDark">Project based</span>
                        <span class="rounded-full bg-white/10 px-4 py-2 text-sm font-bold text-white">Certificate focused</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2">
                    @foreach ($stats as $index => $stat)
                        <div class="group border-borderLight p-6 transition hover:bg-bgSoft/70 sm:p-8
                            {{ $index > 0 ? 'border-t' : '' }}
                            {{ $index === 1 ? 'sm:border-l sm:border-t-0' : '' }}
                            {{ $index === 3 ? 'sm:border-l' : '' }}">
                            <p
                                class="stat-counter text-4xl font-black leading-none text-textPrimary transition group-hover:scale-[1.03] sm:text-5xl"
                                data-target="{{ $stat['value'] }}"
                                data-display="{{ $stat['display'] }}"
                            >
                                0
                            </p>

                            <p class="mt-4 text-base font-bold text-textPrimary">{{ $stat['label'] }}</p>
                            <p class="mt-2 text-sm leading-6 text-textSecondary">{{ $stat['note'] }}</p>
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
            if (finalDisplay.includes("K+")) {
                return `${Math.floor(value / 1000)}K+`;
            }

            if (finalDisplay.includes("%")) {
                return `${Math.floor(value)}%`;
            }

            if (Number(value) % 1 !== 0 || finalDisplay.includes(".")) {
                return Number(value).toFixed(1);
            }

            if (finalDisplay.includes("+")) {
                return `${Math.floor(value)}+`;
            }

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
                const currentValue = target * easedProgress;

                counter.textContent = progress === 1 ? finalDisplay : formatNumber(currentValue, finalDisplay);

                if (progress < 1) {
                    requestAnimationFrame(update);
                }
            };

            requestAnimationFrame(update);
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                animateCounter(entry.target);
                observer.unobserve(entry.target);
            });
        }, {
            threshold: 0.4
        });

        counters.forEach((counter) => observer.observe(counter));
    });
</script>
