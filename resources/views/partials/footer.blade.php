@php
$footerPrograms = [
['label' => 'UI/UX & Product Design Professional', 'slug' => 'ui-ux-product-design-professional'],
['label' => 'Data Science & Analytics Expert', 'slug' => 'data-science-analytics-expert'],
['label' => 'B2B Digital Marketing & Automation (MBA/BBA)', 'slug' => 'b2b-digital-marketing-automation-mba-bba'],
['label' => 'AWS Cloud Solutions Architect', 'slug' => 'aws-cloud-solutions-architect'],
['label' => 'B.Tech Civil Engineering (Smart City & BIM Infrastructure)', 'slug' => 'btech-civil-engineering-smart-city-bim-infrastructure'],
['label' => 'B.Tech Mechanical Engineering (Digital Twin & Automation)', 'slug' => 'btech-mechanical-engineering-digital-twin-automation'],
['label' => 'B.Tech Electrical & Electronics (IoT & Power Grids)', 'slug' => 'btech-electrical-electronics-iot-power-grids'],
['label' => 'LLB & Corporate Law (Legal Tech & Tech Law)', 'slug' => 'llb-corporate-law-legal-tech-tech-law'],
['label' => 'Mass Communication & Journalism (Digital Media & PR Tech)', 'slug' => 'mass-communication-journalism-digital-media-pr-tech'],
];
@endphp

<style>
    .footer-aurora-bg {
        background:
            /* radial-gradient(circle at 15% 20%, rgba(147, 135, 184, 0.36), transparent 32%),
            radial-gradient(circle at 85% 15%, rgba(245, 200, 66, 0.22), transparent 30%), */
            /* radial-gradient(circle at 70% 85%, rgba(184, 222, 255, 0.34), transparent 34%), */
            linear-gradient(135deg, #160840, #3D2090, #7C5CFC, #A78BFA, #160840);
        background-size: 180% 180%;
        animation: footerAuroraShift 9s ease-in-out infinite;
    }

    .footer-tech-grid {
        background-image:
            linear-gradient(rgba(238, 245, 255, 0.08) 1px, transparent 1px),
            linear-gradient(90deg, rgba(238, 245, 255, 0.08) 1px, transparent 1px),
            linear-gradient(115deg, transparent 0%, transparent 42%, rgba(245, 200, 66, 0.16) 50%, transparent 58%, transparent 100%);
        background-size: 46px 46px, 46px 46px, 220% 220%;
        animation: footerScanShift 7s linear infinite;
        mask-image: linear-gradient(to bottom, transparent, black 18%, black 82%, transparent);
    }

    .footer-circuit-line {
        background: linear-gradient(90deg, transparent, rgba(245, 200, 66, 0.65), rgba(184, 222, 255, 0.55), transparent);
        animation: footerCircuitPulse 4.8s ease-in-out infinite;
    }

    @keyframes footerAuroraShift {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes footerScanShift {
        0% {
            background-position: 0 0, 0 0, 0% 50%;
        }

        100% {
            background-position: 46px 46px, 46px 46px, 100% 50%;
        }
    }

    @keyframes footerCircuitPulse {

        0%,
        100% {
            opacity: 0.24;
            transform: translateX(-4%);
        }

        50% {
            opacity: 0.72;
            transform: translateX(4%);
        }
    }
</style>

<footer class="footer-aurora-bg relative isolate overflow-hidden text-white {{ request()->is('login') ? 'mt-0' : 'mt-16' }}">

    <!-- AURORA GLOW -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute -left-24 top-0 h-64 w-64 rounded-full bg-[rgba(124,92,252,0.20)] blur-[100px] sm:h-[400px] sm:w-[400px] sm:blur-[140px]"></div>
        <div class="absolute -right-24 bottom-0 h-64 w-64 rounded-full bg-[rgba(184,222,255,0.35)] blur-[100px] sm:h-[400px] sm:w-[400px] sm:blur-[140px]"></div>
        <div class="footer-tech-grid absolute inset-0 opacity-80"></div>
        <div class="footer-circuit-line absolute left-0 top-10 h-px w-full"></div>
        <div class="footer-circuit-line absolute bottom-16 left-0 h-px w-full [animation-delay:1.7s]"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-5 py-12 sm:px-6 sm:py-16">

        <!-- GRID -->
        <div class="grid min-w-0 gap-10 md:grid-cols-2 lg:grid-cols-[1.1fr_1.8fr_1fr_1fr]">

            <!-- BRAND -->
            <div class="min-w-0">
                <div class="inline-flex rounded-xl  px-3 py-2 shadow-sm backdrop-blur">
                    <img src="/images/Engineers-clinics.png" alt="Engineers Clinic" class="h-12 w-auto" />
                </div>

                <!-- <h2 class="text-lg font-semibold tracking-tight">
                    Engineers <span class="text-[#F5C842]">Clinic</span>
                </h2> -->

                <p class="mt-4 text-sm text-[#EEF5FF]/80 leading-relaxed max-w-xs">
                    Build real skills through internships, AI tools, and structured learning designed for engineers.
                </p>
            </div>

            <!-- PROGRAMS -->
            <div class="min-w-0">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                    Internship Programs
                </h3>

                <div class="mt-4 grid min-w-0 gap-x-6 gap-y-2 text-sm text-[#EEF5FF]/80 sm:grid-cols-2">
                    @foreach ($footerPrograms as $program)
                    <a href="{{ route('course.detail', $program['slug']) }}"
                        class="break-words hover:text-[#F5C842] transition">{{ $program['label'] }}</a>
                    @endforeach
                </div>
            </div>

            <!-- COMPANY -->
            <div class="min-w-0">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                    Company
                </h3>

                <ul class="mt-4 space-y-2 text-sm text-[#EEF5FF]/80">
                    <li><a href="#" class="hover:text-[#F5C842] transition">About</a></li>
                    <li><a href="#" class="hover:text-[#F5C842] transition">Contact</a></li>
                    <li><a href="#" class="hover:text-[#F5C842] transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-[#F5C842] transition">Terms</a></li>
                </ul>
            </div>

            <!-- CONTACT -->
            <div class="min-w-0">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                    Get in Touch
                </h3>

                <ul class="mt-4 space-y-2 text-sm text-[#EEF5FF]/80">
                    <li>support@engineersclinic.com</li>
                    <li>+91 98765 43210</li>
                    <li>India</li>
                </ul>

                <!-- CTA -->
                <a href="#"
                    class="inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-[#A78BFA] to-[#F5C842] px-5 py-2.5 text-sm font-medium text-[#160840] shadow transition hover:opacity-90 sm:w-auto">
                    Contact Us
                </a>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="mt-12 border-t border-[#E2D9FF]/20 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left text-sm text-[#EEF5FF]/70">

            <p>
                © {{ date('Y') }} Engineers Clinic. All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-[#F5C842] transition">Privacy</a>
                <a href="#" class="hover:text-[#F5C842] transition">Terms</a>
            </div>

        </div>

    </div>

</footer>