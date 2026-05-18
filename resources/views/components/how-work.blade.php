<style>
    /* PREMIUM AURORA */
    @keyframes auroraFloat {
        0% {
            transform: translate3d(0, 0, 0) scale(1);
        }

        25% {
            transform: translate3d(40px, -30px, 0) scale(1.08);
        }

        50% {
            transform: translate3d(-20px, 20px, 0) scale(0.96);
        }

        75% {
            transform: translate3d(30px, 10px, 0) scale(1.04);
        }

        100% {
            transform: translate3d(0, 0, 0) scale(1);
        }
    }

    /* MESH LIGHT */
    @keyframes meshMove {
        0% {
            transform: translateX(0px) translateY(0px);
        }

        50% {
            transform: translateX(-20px) translateY(20px);
        }

        100% {
            transform: translateX(0px) translateY(0px);
        }
    }

    /* SHIMMER */
    @keyframes shimmerLine {
        0% {
            transform: translateX(-100%);
            opacity: 0;
        }

        50% {
            opacity: 1;
        }

        100% {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    .aurora-1 {
        animation: auroraFloat 16s ease-in-out infinite;
    }

    .aurora-2 {
        animation: auroraFloat 18s ease-in-out infinite reverse;
    }

    .mesh-bg {
        animation: meshMove 18s ease-in-out infinite;
    }

    .line-shimmer::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg,
                transparent,
                rgba(124, 92, 252, 0.35),
                transparent);
        animation: shimmerLine 5s linear infinite;
    }
</style>

<section class="relative overflow-hidden bg-gradient-to-r from-[#160840] via-[#1B0A4F] to-[#24105E] py-20">

    {{-- PREMIUM BACKGROUND --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">

        {{-- Main Aurora --}}
        <div class="aurora-1 absolute top-[-140px] left-[-100px] w-[420px] h-[420px] rounded-full bg-[#7C5CFC]/[0.10] blur-[120px]"></div>

        {{-- Secondary Aurora --}}
        <div class="aurora-2 absolute bottom-[-160px] right-[-120px] w-[460px] h-[460px] rounded-full bg-[#A78BFA]/[0.10] blur-[130px]"></div>

        {{-- Center Light --}}
        <div class="absolute top-[20%] left-1/2 -translate-x-1/2 w-[500px] h-[500px] rounded-full bg-white/[0.08] blur-[120px]"></div>

        {{-- Animated Mesh --}}
        <div class="mesh-bg absolute inset-0 opacity-[0.025]"
            style="
                background-image:
                radial-gradient(circle at 1px 1px, rgba(124,92,252,0.16) 1px, transparent 0);
                background-size: 36px 36px;
            ">
        </div>

    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-6">

        {{-- Heading --}}
        <div class="text-center max-w-3xl mx-auto mb-16">

            <div class="flex items-center justify-center gap-3 mb-4">

                <div class="h-px w-8 bg-[#7C5CFC]/30"></div>

                <span class="text-[11px] font-semibold text-[#C4B5FD] uppercase tracking-[3px]">
                    Internship Workflow
                </span>

                <div class="h-px w-8 bg-[#7C5CFC]/30"></div>

            </div>

            <h2 class="text-3xl md:text-5xl font-semibold tracking-tight text-white leading-tight">
                How the internship
                <span class="text-[#7C5CFC]">journey works.</span>
            </h2>

            <p class="mt-5 text-base md:text-lg text-white/70 leading-8">
                Learn practical skills through projects, tasks,
                and portfolio-based internship experiences.
            </p>

        </div>

        {{-- Timeline --}}
        <div class="relative">

            {{-- Animated Line --}}
            <div class="hidden xl:block line-shimmer absolute top-12 left-[10%] right-[10%] h-px overflow-hidden bg-gradient-to-r from-transparent via-[#7C5CFC]/20 to-transparent"></div>

            {{-- Steps --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-8 relative z-10">

                @php
                $steps = [
                ['num'=>'01','title'=>'Choose Your Level','desc'=>'Beginner, Intermediate, or Advanced based on your current skills.'],
                ['num'=>'02','title'=>'Select Projects','desc'=>'Pick real-world projects based on your interests and domain.'],
                ['num'=>'03','title'=>'Complete Tasks','desc'=>'Work independently and execute practical internship assignments.'],
                ['num'=>'04','title'=>'Submit Projects','desc'=>'Upload demos, reports, files, or GitHub repository links.'],
                ['num'=>'05','title'=>'Earn Certificates','desc'=>'Receive verified certificates after successful completion.'],
                ];
                @endphp

                @foreach($steps as $step)

                <div class="group relative flex flex-col items-center text-center">

                    {{-- Background Number --}}
                    <div class="absolute -top-2 text-[68px] font-bold text-white/[0.08] leading-none select-none">
                        {{ $step['num'] }}
                    </div>

                    {{-- Circle --}}
                    <div class="relative mt-8 w-20 h-20 rounded-full border border-white/15 bg-white/10 backdrop-blur-xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:-translate-y-1.5 group-hover:border-[#C4B5FD]/40 group-hover:shadow-[0_15px_40px_rgba(124,92,252,0.24)]">

                        {{-- Hover Glow --}}
                        <div class="absolute inset-0 rounded-full bg-[#7C5CFC]/0 group-hover:bg-[#7C5CFC]/[0.06] blur-2xl transition duration-500"></div>

                        <span class="relative z-10 text-xl font-bold text-white">
                            {{ $step['num'] }}
                        </span>

                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-semibold text-white mb-3 transition duration-300 group-hover:text-[#C4B5FD]">
                        {{ $step['title'] }}
                    </h3>

                    {{-- Description --}}
                    <p class="text-white/70 leading-7 text-sm">
                        {{ $step['desc'] }}
                    </p>

                </div>

                @endforeach

            </div>

        </div>
    </div>
</section>
