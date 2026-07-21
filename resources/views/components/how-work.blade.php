<style>
    .ec-pipeline-section {
        background:
            radial-gradient(circle at 12% 0%, rgba(109, 93, 246, 0.12), transparent 28%),
            radial-gradient(circle at 76% 28%, rgba(34, 201, 151, 0.1), transparent 30%),
            linear-gradient(135deg, #ffffff 0%, #fbfaff 48%, #f7fbff 100%);
    }

    .ec-pipeline-grid {
        background-image:
            linear-gradient(rgba(109, 93, 246, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(109, 93, 246, 0.045) 1px, transparent 1px);
        background-size: 44px 44px;
        animation: ecPipelineGrid 18s linear infinite;
    }

    .ec-pipeline-card {
        min-height: 188px;
        border-color: #ECEBFF;
        background: linear-gradient(180deg, #ffffff, #fbfaff);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 22px 55px rgba(15, 10, 42, 0.08);
    }

    .ec-pipeline-heading-card {
        border: 1px solid #ECEBFF;
        background: rgba(255, 255, 255, 0.78);
        box-shadow: 0 18px 48px rgba(15, 10, 42, 0.07);
    }

    .ec-pipeline-heading-icon {
        border: 1px solid rgba(109, 93, 246, 0.24);
        background: rgba(109, 93, 246, 0.1);
        color: #6D5DF6;
    }

    .ec-pipeline-label,
    .ec-pipeline-status {
        color: #22c997;
    }

    .ec-pipeline-copy {
        color: #6B7280;
    }

    .ec-pipeline-status {
        border: 1px solid rgba(34, 201, 151, 0.2);
        background: rgba(34, 201, 151, 0.1);
        box-shadow: 0 0 28px rgba(34, 201, 151, 0.12);
    }

    .ec-pipeline-status-dot {
        background: #22c997;
        box-shadow: 0 0 14px rgba(34, 201, 151, 0.9);
    }

    .ec-pipeline-ambient-orange {
        background: rgba(109, 93, 246, 0.12);
    }

    .ec-pipeline-ambient-green {
        background: rgba(34, 201, 151, 0.1);
    }

    .ec-pipeline-card-active {
        border-color: rgba(109, 93, 246, 0.55);
        box-shadow: 0 0 0 1px rgba(109, 93, 246, 0.16), 0 22px 65px rgba(109, 93, 246, 0.16);
    }

    .ec-pipeline-icon {
        animation: ecPipelinePulse 2.7s ease-in-out infinite;
    }

    .ec-pipeline-connector {
        position: absolute;
        left: calc(100% + 0.05rem);
        top: 50%;
        z-index: 30;
        display: none;
        width: 3.85rem;
        height: 1.25rem;
        align-items: center;
        transform: translateY(-50%);
    }

    .ec-pipeline-connector::before {
        content: "";
        position: absolute;
        left: 0.08rem;
        top: 50%;
        width: 0.5rem;
        height: 0.5rem;
        border-radius: 999px;
        background: #22c997;
        box-shadow: 0 0 16px rgba(34, 201, 151, 0.85);
        transform: translateY(-50%);
        animation: ecPipelineTravel 1.9s ease-in-out infinite;
    }

    .ec-pipeline-connector::after {
        content: "";
        height: 2px;
        flex: 1;
        margin-left: 0.18rem;
        background: #22c997;
        box-shadow: 0 0 14px rgba(34, 201, 151, 0.38);
    }

    .ec-pipeline-flow {
        position: absolute;
        left: 0.18rem;
        right: 0.45rem;
        top: 50%;
        height: 2px;
        overflow: hidden;
        transform: translateY(-50%);
    }

    .ec-pipeline-flow::before {
        content: "";
        position: absolute;
        inset-block: 0;
        left: -55%;
        width: 55%;
        border-radius: 999px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.95), transparent);
        animation: ecPipelineSweep 1.9s ease-in-out infinite;
    }

    .ec-pipeline-arrow {
        width: 0;
        height: 0;
        border-bottom: 4px solid transparent;
        border-left: 7px solid #22c997;
        border-top: 4px solid transparent;
        filter: drop-shadow(0 0 8px rgba(34, 201, 151, 0.85));
    }

    .ec-pipeline-card-active .ec-pipeline-connector::before {
        background: #6D5DF6;
        box-shadow: 0 0 18px rgba(109, 93, 246, 0.65);
    }

    .ec-pipeline-card-active .ec-pipeline-connector::after {
        background: #6D5DF6;
        box-shadow: 0 0 16px rgba(109, 93, 246, 0.34);
    }

    .ec-pipeline-card-active .ec-pipeline-arrow {
        border-left-color: #6D5DF6;
        filter: drop-shadow(0 0 8px rgba(109, 93, 246, 0.58));
    }

    .ec-pipeline-connector-mobile {
        position: absolute;
        bottom: -2.25rem;
        left: 50%;
        z-index: 30;
        display: flex;
        height: 2.25rem;
        flex-direction: column;
        align-items: center;
        transform: translateX(-50%);
    }

    .ec-pipeline-connector-mobile::before {
        content: "";
        position: absolute;
        top: 0;
        left: 50%;
        width: 0.45rem;
        height: 0.45rem;
        border-radius: 999px;
        background: #22c997;
        box-shadow: 0 0 16px rgba(34, 201, 151, 0.85);
        transform: translateX(-50%);
        animation: ecPipelineTravelY 1.9s ease-in-out infinite;
    }

    .ec-pipeline-connector-mobile::after {
        content: "";
        width: 2px;
        flex: 1;
        margin-top: 0.15rem;
        background: linear-gradient(180deg, rgba(34, 201, 151, 0.9), rgba(34, 201, 151, 0.2));
        box-shadow: 0 0 14px rgba(34, 201, 151, 0.38);
    }

    .ec-how-work-ready .ec-how-animate {
        opacity: 0;
        transform: translateY(24px) scale(0.97);
    }

    .ec-how-work-ready.ec-how-visible .ec-how-animate {
        animation: ecPipelineReveal 700ms cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        animation-delay: var(--ec-delay, 0ms);
    }

    @keyframes ecPipelineReveal {
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes ecPipelineGrid {
        from {
            background-position: 0 0, 0 0;
        }

        to {
            background-position: 44px 44px, 44px 44px;
        }
    }

    @keyframes ecPipelinePulse {
        0%, 100% {
            transform: translateY(0) scale(1);
        }

        50% {
            transform: translateY(-3px) scale(1.04);
        }
    }

    @keyframes ecPipelineTravel {
        0%, 100% {
            opacity: 0.2;
            transform: translate(0, -50%) scale(0.85);
        }

        50% {
            opacity: 1;
            transform: translate(2.9rem, -50%) scale(1.15);
        }
    }

    @keyframes ecPipelineTravelY {
        0%, 100% {
            opacity: 0.2;
            transform: translate(-50%, 0) scale(0.85);
        }

        50% {
            opacity: 1;
            transform: translate(-50%, 1.5rem) scale(1.15);
        }
    }

    @keyframes ecPipelineSweep {
        0% {
            transform: translateX(0);
            opacity: 0;
        }

        30%, 70% {
            opacity: 0.85;
        }

        100% {
            transform: translateX(280%);
            opacity: 0;
        }
    }

    @media (min-width: 1024px) {
        .ec-pipeline-connector {
            display: flex;
        }

        .ec-pipeline-connector-mobile {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .ec-pipeline-grid,
        .ec-pipeline-icon,
        .ec-pipeline-connector::before,
        .ec-pipeline-connector-mobile::before,
        .ec-pipeline-flow::before,
        .ec-how-work-ready.ec-how-visible .ec-how-animate {
            animation: none;
        }

        .ec-how-work-ready .ec-how-animate {
            opacity: 1;
            transform: none;
        }
    }
</style>

<section id="how-it-works" class="ec-how-section ec-pipeline-section relative isolate overflow-hidden py-16 sm:py-20 lg:py-24">
    <div class="pointer-events-none absolute inset-0 -z-10">
        <div class="ec-pipeline-grid absolute inset-0 opacity-70"></div>
        <div class="ec-pipeline-ambient-orange absolute -left-24 top-0 h-80 w-80 rounded-full blur-[110px]"></div>
        <div class="ec-pipeline-ambient-green absolute right-1/4 top-24 h-80 w-80 rounded-full blur-[120px]"></div>
    </div>

    <div class="container-main">
        <div class="ec-how-animate mx-auto flex max-w-3xl flex-col items-center text-center">
            <div class="max-w-3xl">
                <div class="ec-pipeline-heading-card inline-flex items-center gap-3 rounded-2xl px-4 py-3">
                    <span class="ec-pipeline-heading-icon grid h-10 w-10 place-items-center rounded-xl text-lg">
                        <i class="fi fi-rr-bolt"></i>
                    </span>
                    <div>
                        <p class="ec-pipeline-label text-xs font-black uppercase tracking-[0.18em]">How It Works</p>
                        <h2 class="mt-1 text-2xl font-black leading-tight text-[#161326] sm:text-3xl lg:text-4xl">Project completion pipeline</h2>
                    </div>
                </div>
                <p class="ec-pipeline-copy mt-5 max-w-2xl text-base leading-8">
                    Move step by step from project selection to workspace tasks, GitHub proof, review, and certificate.
                </p>
            </div>

            <span class="ec-pipeline-status mt-5 inline-flex w-fit items-center gap-2 rounded-full px-4 py-2 text-sm font-black">
                <span class="ec-pipeline-status-dot h-2 w-2 rounded-full"></span>
                Running
            </span>
        </div>

        <div class="mt-12 grid gap-10 lg:grid-cols-6 lg:gap-12">
            @foreach ([
                ['step' => '01', 'icon' => 'fi-rr-folder-open', 'title' => 'Choose', 'desc' => 'Select project'],
                ['step' => '02', 'icon' => 'fi-rr-apps', 'title' => 'Workspace', 'desc' => 'Open board'],
                ['step' => '03', 'icon' => 'fi-rr-list-check', 'title' => 'Tasks', 'desc' => 'Complete work'],
                ['step' => '04', 'icon' => 'fi-rr-code-branch', 'title' => 'GitHub', 'desc' => 'Submit repo'],
                ['step' => '05', 'icon' => 'fi-rr-comment-check', 'title' => 'Review', 'desc' => 'Get approval'],
                ['step' => '06', 'icon' => 'fi-rr-badge-check', 'title' => 'Certificate', 'desc' => 'Earn proof'],
            ] as $index => $step)
                @php
                    $active = $index === 3;
                    $tone = $active ? '#6D5DF6' : '#22C997';
                @endphp

                <article class="ec-how-animate ec-pipeline-card {{ $active ? 'ec-pipeline-card-active' : '' }} group relative rounded-xl border p-5 text-center transition duration-300 hover:-translate-y-1" style="--ec-delay: {{ 120 + ($index * 110) }}ms">
                    <p class="text-xs font-black tracking-[0.14em]" style="color: {{ $tone }}">{{ $step['step'] }}</p>

                    <span class="ec-pipeline-icon mx-auto mt-4 grid h-14 w-14 place-items-center rounded-full text-2xl" style="background: {{ $tone }}18; color: {{ $tone }}; box-shadow: 0 0 24px {{ $tone }}35;">
                        <i class="fi {{ $step['icon'] }}"></i>
                    </span>

                    <h3 class="mt-4 text-base font-black text-[#161326]">{{ $step['title'] }}</h3>
                    <p class="mt-2 text-sm font-medium text-[#6B7280]">{{ $step['desc'] }}</p>

                    @if (!$loop->last)
                        <span class="ec-pipeline-connector" aria-hidden="true">
                            <span class="ec-pipeline-flow"></span>
                            <span class="ec-pipeline-arrow"></span>
                        </span>
                        <span class="ec-pipeline-connector-mobile" aria-hidden="true"></span>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const section = document.querySelector(".ec-how-section");
        if (!section) return;

        section.classList.add("ec-how-work-ready");

        const reveal = () => section.classList.add("ec-how-visible");

        if (!("IntersectionObserver" in window)) {
            reveal();
            return;
        }

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                reveal();
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.25 });

        observer.observe(section);
    });
</script>
