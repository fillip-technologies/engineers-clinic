@php
    $internshipLevels = [
        'Beginner' => [
            ['label' => 'Web Ecosystems & Frontend Architecture', 'slug' => 'web-ecosystems-frontend'],
            ['label' => 'Core Python & Computational Logic', 'slug' => 'core-python-computational-logic'],
            ['label' => 'UI/UX Design', 'slug' => 'ui-ux-design'],
            ['label' => 'Data Analytics', 'slug' => 'data-analytics'],
            ['label' => 'AutoCAD Drafting', 'slug' => 'autocad-drafting'],
        ],
        'Intermediate' => [
            ['label' => 'Cloud & Backend Systems', 'slug' => 'cloud-backend-systems'],
            ['label' => 'Machine Learning', 'slug' => 'machine-learning'],
            ['label' => 'Ethical Hacking', 'slug' => 'ethical-hacking'],
            ['label' => 'Mobile Development', 'slug' => 'mobile-development'],
            ['label' => 'Corporate Law', 'slug' => 'corporate-law'],
        ],
        'Advanced' => [
            ['label' => 'Generative AI', 'slug' => 'generative-ai'],
            ['label' => 'Cloud Architecture', 'slug' => 'cloud-architecture'],
            ['label' => 'Blockchain Systems', 'slug' => 'blockchain-systems'],
            ['label' => 'CFD & FEA', 'slug' => 'cfd-fea'],
            ['label' => 'Digital Law', 'slug' => 'digital-law'],
        ],
    ];

    $mainLinks = [
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'College Tie-ups', 'href' => route('college.tieup')],
        ['label' => 'Company Branding', 'href' => route('company.branding')],
        ['label' => 'About Us', 'href' => route('about')],
        ['label' => 'Login', 'href' => url('/login')],
    ];
@endphp

<footer class="bg-surfaceDark text-white {{ request()->is('login') || request()->is('course/*') ? 'mt-0' : 'mt-16' }}">
    <div class="container-main py-14 sm:py-16">
        <div class="grid gap-10 lg:grid-cols-[1.05fr_2.1fr_0.9fr]">
            <div>
                <img src="/images/Engineers-clinics.png" alt="Engineers Clinic" class="h-12 w-auto" />

                <p class="mt-5 max-w-sm text-sm leading-7 text-bgSoft/80">
                    A practical internship operating system for students, colleges, and career-focused learning teams.
                </p>

                <div class="mt-6 space-y-3 text-sm text-bgSoft/78">
                    <a href="mailto:info@engineersclinic.com" class="flex items-start gap-3 transition hover:text-secondary">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-secondary"></span>
                        <span class="break-all">info@engineersclinic.com</span>
                    </a>
                    <a href="tel:+917545999990" class="flex items-start gap-3 transition hover:text-secondary">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-secondary"></span>
                        <span>+91-75459-99990</span>
                    </a>
                    <a href="tel:+917979030298" class="flex items-start gap-3 transition hover:text-secondary">
                        <span class="mt-2 h-1.5 w-1.5 shrink-0 rounded-full bg-secondary"></span>
                        <span>+91-79790-30298</span>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-label text-brandLight">Internship Tracks</h3>

                <div class="mt-5 grid gap-6 md:grid-cols-3">
                    @foreach ($internshipLevels as $level => $programs)
                        <div>
                            <p class="text-sm font-bold text-white">{{ $level }}</p>
                            <div class="mt-3 space-y-2">
                                @foreach ($programs as $program)
                                    <a href="{{ route('course.detail', $program['slug']) }}" class="block text-sm leading-6 text-bgSoft/75 transition hover:text-secondary">
                                        {{ $program['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-label text-brandLight">Platform</h3>
                <ul class="mt-5 space-y-2">
                    @foreach ($mainLinks as $link)
                        <li>
                            <a href="{{ $link['href'] }}" class="text-sm text-bgSoft/75 transition hover:text-secondary">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>

                <a href="mailto:info@engineersclinic.com" class="btn-secondary mt-7 bg-white/10 text-white hover:text-secondary">
                    Contact Us
                </a>
            </div>
        </div>

        <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-white/10 pt-6 text-center text-sm text-bgSoft/65 md:flex-row md:text-left">
            <p>&copy; {{ date('Y') }} Engineers Clinic. All rights reserved.</p>
            <div class="flex items-center gap-6">
                <a href="#" class="transition hover:text-secondary">Privacy</a>
                <a href="#" class="transition hover:text-secondary">Terms</a>
            </div>
        </div>
    </div>
</footer>
