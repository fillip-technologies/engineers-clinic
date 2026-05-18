@php
    $techStack = [
        ['name' => 'React', 'slug' => 'react', 'color' => '61DAFB'],
        ['name' => 'Node.js', 'slug' => 'nodedotjs', 'color' => '5FA04E'],
        ['name' => 'Docker', 'slug' => 'docker', 'color' => '2496ED'],
        ['name' => 'GitHub', 'slug' => 'github', 'color' => '181717'],
        ['name' => 'AWS', 'slug' => 'amazonwebservices', 'color' => '232F3E'],
        ['name' => 'MongoDB', 'slug' => 'mongodb', 'color' => '47A248'],
        ['name' => 'Figma', 'slug' => 'figma', 'color' => 'F24E1E'],
        ['name' => 'VS Code', 'slug' => 'visualstudiocode', 'color' => '007ACC'],
        ['name' => 'Python', 'slug' => 'python', 'color' => '3776AB'],
        ['name' => 'Linux', 'slug' => 'linux', 'color' => 'FCC624'],
        ['name' => 'Jira', 'slug' => 'jira', 'color' => '0052CC'],
        ['name' => 'Notion', 'slug' => 'notion', 'color' => '000000'],
    ];
@endphp

<style>
    .stack-marquee-wrapper {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .stack-marquee {
        display: flex;
        width: max-content;
    }

    .stack-marquee-track {
        display: flex;
        flex-shrink: 0;
        align-items: center;
        gap: 18px;
        padding-right: 18px;
    }

    .stack-card {
        display: flex;
        min-width: 168px;
        align-items: center;
        gap: 14px;
        border: 1px solid rgba(124, 92, 252, 0.14);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.78);
        padding: 18px;
        color: #160840;
        box-shadow: 0 18px 42px rgba(22, 8, 64, 0.07);
        backdrop-filter: blur(16px);
        transition: all 0.35s ease;
    }

    .stack-card:nth-child(3n + 1) {
        transform: translateY(10px);
    }

    .stack-card:nth-child(3n + 2) {
        transform: translateY(-8px);
    }

    .stack-card:hover {
        border-color: rgba(124, 92, 252, 0.32);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 24px 52px rgba(124, 92, 252, 0.14);
        transform: translateY(-4px) scale(1.02);
    }

    .stack-icon-shell {
        display: grid;
        height: 48px;
        width: 48px;
        flex: none;
        place-items: center;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(124, 92, 252, 0.12), rgba(238, 245, 255, 0.92));
    }

    .stack-icon {
        height: 27px;
        width: 27px;
        object-fit: contain;
    }

    @keyframes stackScroll {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    @keyframes stackParticleFloat {
        0%, 100% {
            transform: translate3d(0, 0, 0);
            opacity: 0.45;
        }

        50% {
            transform: translate3d(0, -12px, 0);
            opacity: 0.8;
        }
    }

    .stack-scroll {
        animation: stackScroll 34s linear infinite;
    }

    .stack-scroll:hover {
        animation-play-state: paused;
    }

    .stack-particle {
        animation: stackParticleFloat 7s ease-in-out infinite;
    }

    @media (max-width: 640px) {
        .stack-scroll {
            animation-duration: 26s;
        }

        .stack-card {
            min-width: 152px;
        }
    }
</style>

<section class="relative overflow-hidden bg-gradient-to-br from-white via-white to-bgSoft py-16 sm:py-20">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -left-24 top-4 h-64 w-64 rounded-full bg-glowPurple blur-[110px] sm:h-[320px] sm:w-[320px]"></div>
        <div class="absolute -right-24 bottom-0 h-64 w-64 rounded-full bg-glowBlue blur-[110px] sm:h-[320px] sm:w-[320px]"></div>
        <div class="stack-particle absolute left-[18%] top-14 h-2 w-2 rounded-full bg-brand/25"></div>
        <div class="stack-particle absolute right-[20%] top-24 h-2.5 w-2.5 rounded-full bg-secondary/40 [animation-delay:1.4s]"></div>
        <div class="stack-particle absolute bottom-16 left-[28%] h-2 w-2 rounded-full bg-brandLight/35 [animation-delay:2.2s]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-6">
        <div class="mx-auto max-w-3xl text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-brand">
                Tech Stack Ecosystem
            </p>

            <h2 class="mt-4 text-2xl font-semibold leading-tight text-textPrimary sm:text-3xl md:text-4xl">
                Build skills with modern tech stacks.
            </h2>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-8 text-textSecondary">
                Work on practical projects using tools, frameworks, and platforms commonly used across modern digital products and engineering workflows.
            </p>
        </div>

        <div class="stack-marquee-wrapper mt-10 sm:mt-12">
            <div class="absolute left-0 top-0 z-10 h-full w-10 bg-gradient-to-r from-white to-transparent sm:w-24"></div>
            <div class="absolute right-0 top-0 z-10 h-full w-10 bg-gradient-to-l from-bgSoft to-transparent sm:w-24"></div>

            <div class="stack-marquee stack-scroll py-4">
                <div class="stack-marquee-track">
                    @foreach ($techStack as $tool)
                        <article class="stack-card">
                            <div class="stack-icon-shell">
                                <img
                                    src="https://cdn.simpleicons.org/{{ $tool['slug'] }}/{{ $tool['color'] }}"
                                    alt="{{ $tool['name'] }}"
                                    class="stack-icon"
                                />
                            </div>
                            <h3 class="text-sm font-semibold">{{ $tool['name'] }}</h3>
                        </article>
                    @endforeach
                </div>

                <div class="stack-marquee-track" aria-hidden="true">
                    @foreach ($techStack as $tool)
                        <article class="stack-card">
                            <div class="stack-icon-shell">
                                <img
                                    src="https://cdn.simpleicons.org/{{ $tool['slug'] }}/{{ $tool['color'] }}"
                                    alt=""
                                    class="stack-icon"
                                />
                            </div>
                            <h3 class="text-sm font-semibold">{{ $tool['name'] }}</h3>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
