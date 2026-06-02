@php
    $services = [
        [
            'title' => 'AI-Powered Services',
            'copy' => 'Practical AI capabilities designed to support smarter digital experiences.',
        ],
        [
            'title' => 'Automation',
            'copy' => 'Modern workflows that help simplify repetitive processes and operations.',
        ],
        [
            'title' => 'Digital Solutions',
            'copy' => 'Future-ready systems that extend the Engineers Clinic ecosystem.',
        ],
    ];
@endphp

<style>
    .services-grid-bg {
        background:
            radial-gradient(circle at 18% 18%, rgba(124, 92, 252, 0.14), transparent 28%),
            radial-gradient(circle at 82% 78%, rgba(245, 200, 66, 0.08), transparent 26%),
            linear-gradient(135deg, #160840 0%, #24105e 100%);
    }

    .services-tech-grid {
        background-image:
            linear-gradient(rgba(238, 245, 255, 0.05) 1px, transparent 1px),
            linear-gradient(90deg, rgba(238, 245, 255, 0.05) 1px, transparent 1px),
            linear-gradient(115deg, transparent 0%, transparent 44%, rgba(245, 200, 66, 0.08) 50%, transparent 56%, transparent 100%);
        background-size: 46px 46px, 46px 46px, 220% 220%;
        animation: servicesGridShift 10s linear infinite;
        mask-image: linear-gradient(to bottom, transparent, black 18%, black 82%, transparent);
    }

    @keyframes servicesGridShift {
        0% {
            background-position: 0 0, 0 0, 0% 50%;
        }

        100% {
            background-position: 46px 46px, 46px 46px, 100% 50%;
        }
    }
</style>

<section class="services-grid-bg relative isolate overflow-hidden py-16 text-white sm:py-20">
    <div class="pointer-events-none absolute inset-0">
        <div class="services-tech-grid absolute inset-0 opacity-60"></div>
        <div class="absolute -left-20 top-8 h-56 w-56 rounded-full bg-[#7C5CFC]/15 blur-[110px]"></div>
        <div class="absolute -right-16 bottom-0 h-56 w-56 rounded-full bg-[#A78BFA]/12 blur-[110px]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="grid gap-8 lg:grid-cols-[0.78fr_1.22fr] lg:items-start">
            <div class="max-w-md">
                <p class="text-sm font-semibold uppercase tracking-[0.18em] text-[#A78BFA]">
                    Beyond Learning
                </p>

                <h2 class="mt-4 text-3xl font-semibold tracking-tight text-white sm:text-4xl">
                    Modern services and solutions.
                </h2>

                <p class="mt-4 text-base leading-8 text-[#EEF5FF]">
                    Alongside internships and practical learning, Engineers Clinic is expanding into AI-powered services, automation, and modern digital solutions.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($services as $service)
                    <article class="rounded-xl border border-white/12 bg-white/[0.07] p-5 backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:border-[#A78BFA]/35 hover:bg-white/[0.1]">
                        <div class="mb-5 h-px w-10 bg-gradient-to-r from-[#F5C842] to-transparent"></div>
                        <h3 class="text-lg font-semibold text-white">{{ $service['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[#EEF5FF]">{{ $service['copy'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
