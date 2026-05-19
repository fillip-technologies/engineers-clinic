@php
$internshipLevels = [
'Beginner' => [
['label' => 'Web Ecosystems & Frontend Architecture', 'slug' => 'web-ecosystems-frontend'],
['label' => 'Core Python & Computational Logic', 'slug' => 'core-python-computational-logic'],
['label' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
['label' => 'Data Analytics', 'slug' => 'data-analytics'],
['label' => 'AutoCAD Drafting', 'slug' => 'autocad-drafting'],
['label' => 'Manufacturing Basics', 'slug' => 'manufacturing-basics'],
['label' => 'Civil Drafting', 'slug' => 'civil-drafting'],
['label' => 'Site Surveying', 'slug' => 'site-surveying'],
['label' => 'Legal Research', 'slug' => 'legal-research'],
['label' => 'Digital Journalism', 'slug' => 'digital-journalism'],
],
'Intermediate' => [
['label' => 'Cloud & Backend Systems', 'slug' => 'cloud-backend-systems'],
['label' => 'Machine Learning', 'slug' => 'machine-learning'],
['label' => 'Ethical Hacking', 'slug' => 'ethical-hacking'],
['label' => 'Mobile Development', 'slug' => 'mobile-development'],
['label' => 'CAD/CAM', 'slug' => 'cad-cam'],
['label' => 'HVAC Design', 'slug' => 'hvac-design'],
['label' => 'Structural Design', 'slug' => 'structural-design'],
['label' => 'Project Estimation', 'slug' => 'project-estimation'],
['label' => 'Corporate Law', 'slug' => 'corporate-law'],
['label' => 'PR Strategy', 'slug' => 'pr-strategy'],
],
'Advanced' => [
['label' => 'Generative AI', 'slug' => 'generative-ai'],
['label' => 'Cloud Architecture', 'slug' => 'cloud-architecture'],
['label' => 'Blockchain Systems', 'slug' => 'blockchain-systems'],
['label' => 'Big Data Systems', 'slug' => 'big-data-systems'],
['label' => 'CFD & FEA', 'slug' => 'cfd-fea'],
['label' => 'Robotics Automation', 'slug' => 'robotics-automation'],
['label' => 'BIM Infrastructure', 'slug' => 'bim-infrastructure'],
['label' => 'Geotechnical Engineering', 'slug' => 'geotechnical-engineering'],
['label' => 'Digital Law', 'slug' => 'digital-law'],
['label' => 'Corporate Communication', 'slug' => 'corporate-communication'],
],
];

$enterpriseLinks = [
['label' => 'Company For Branding', 'href' => route('company.branding')],
['label' => 'For Selling Services & Products', 'href' => '#'],
];

$moreLinks = [
['label' => 'AI Tools', 'href' => '#'],
['label' => 'Refer & Earn', 'href' => '#'],
['label' => 'Blog', 'href' => '#'],
['label' => 'About Us', 'href' => route('about')],
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

<footer class="footer-aurora-bg relative isolate overflow-hidden text-white {{ request()->is('login') || request()->is('course/*') ? 'mt-0' : 'mt-16' }}">

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
        <div class="grid min-w-0 gap-10 lg:grid-cols-[1.05fr_2.2fr_1fr]">

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

                <div class="mt-6 space-y-3 text-sm text-[#EEF5FF]/80">
                    <a href="mailto:info@engineersclinic.com" class="flex items-start gap-3 transition hover:text-[#F5C842]">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[#F5C842]"></span>
                        <span class="break-all">info@engineersclinic.com</span>
                    </a>
                    <a href="tel:+917545999990" class="flex items-start gap-3 transition hover:text-[#F5C842]">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[#F5C842]"></span>
                        <span>+91-75459-99990</span>
                    </a>
                    <a href="tel:+917979030298" class="flex items-start gap-3 transition hover:text-[#F5C842]">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[#F5C842]"></span>
                        <span>+91-79790-30298</span>
                    </a>
                    <p class="flex items-start gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[#F5C842]"></span>
                        <span>Property No. 22, Second Floor, Gurunanak Market, Lajpat Nagar, New Delhi - 110024</span>
                    </p>
                    <p class="flex items-start gap-3">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-[#F5C842]"></span>
                        <span>Ground Floor, A3, Kangkar Bagh Road, beside Kalyan Jewellers, near Chandan Hero, PC Colony, RBI Flats Colony, Bankman Colony, Patna, Bihar 800020</span>
                    </p>
                </div>
            </div>

            <!-- INTERNSHIP MEGA MENU -->
            <div class="min-w-0">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                    Internship Mega Menu
                </h3>

                <div class="mt-4 grid min-w-0 gap-6 md:grid-cols-3">
                    @foreach ($internshipLevels as $level => $programs)
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-white">{{ $level }}</p>
                        <div class="mt-3 space-y-2 text-sm text-[#EEF5FF]/80">
                            @foreach ($programs as $program)
                            <a href="{{ route('course.detail', $program['slug']) }}"
                                class="block break-words leading-5 transition hover:text-[#F5C842]">{{ $program['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- MENU DETAILS -->
            <div class="min-w-0">
                <div>
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                        Main Links
                    </h3>

                    <ul class="mt-4 space-y-2 text-sm text-[#EEF5FF]/80">
                        <li><a href="/" class="hover:text-[#F5C842] transition">Home</a></li>
                        <li><a href="{{ route('college.tieup') }}" class="hover:text-[#F5C842] transition">College Tie-ups</a></li>
                        <li><a href="{{ url('/login') }}" class="hover:text-[#F5C842] transition">Login</a></li>
                    </ul>
                </div>

                <div class="mt-8">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                        For Enterprises & Employers
                    </h3>

                    <ul class="mt-4 space-y-2 text-sm text-[#EEF5FF]/80">
                        @foreach ($enterpriseLinks as $link)
                        <li><a href="{{ $link['href'] }}" class="hover:text-[#F5C842] transition">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="mt-8">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-[#A78BFA]">
                        More
                    </h3>

                    <ul class="mt-4 space-y-2 text-sm text-[#EEF5FF]/80">
                        @foreach ($moreLinks as $link)
                        <li><a href="{{ $link['href'] }}" class="hover:text-[#F5C842] transition">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <a href="mailto:info@engineersclinic.com"
                    class="mt-8 inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-r from-[#A78BFA] to-[#F5C842] px-5 py-2.5 text-sm font-medium text-[#160840] shadow transition hover:opacity-90 sm:w-auto">
                    Contact Us
                </a>
            </div>

        </div>

        <!-- BOTTOM -->
        <div class="mt-12 border-t border-[#E2D9FF]/20 pt-6 flex flex-col md:flex-row items-center justify-between gap-4 text-center md:text-left text-sm text-[#EEF5FF]/70">

            <p>
                &copy; {{ date('Y') }} Engineers Clinic. All rights reserved.
            </p>

            <div class="flex items-center gap-6">
                <a href="#" class="hover:text-[#F5C842] transition">Privacy</a>
                <a href="#" class="hover:text-[#F5C842] transition">Terms</a>
            </div>

        </div>

    </div>

</footer>
