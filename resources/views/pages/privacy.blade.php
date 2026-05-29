@extends('layouts.app')

@section('content')
<section class="relative overflow-hidden bg-bgWhite">
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-borderLight to-transparent"></div>
    <div class="pointer-events-none absolute inset-0 bg-[linear-gradient(115deg,rgba(245,240,255,0.88),rgba(255,255,255,0.94)_44%,rgba(238,245,255,0.82))]"></div>

    <div class="relative mx-auto max-w-4xl px-6 py-14 sm:py-16 lg:py-20">
        <div class="mb-10">
            <div class="inline-flex items-center gap-3 rounded-full border border-borderLight bg-bgWhite/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-brand shadow-sm">
                <span class="h-1.5 w-1.5 rounded-full bg-secondary"></span>
                Legal
            </div>

            <h1 class="mt-6 text-4xl font-semibold tracking-tight text-textPrimary sm:text-5xl">
                Privacy Policy
            </h1>

            <p class="mt-4 text-base leading-8 text-textSecondary">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <div class="space-y-8">
            {{-- Introduction --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Introduction</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Engineers Clinic ("we", "our", or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and use our services, including internship programs, courses, and college tie-up platforms.
                </p>
                <p class="mt-3 text-sm leading-7 text-textSecondary">
                    Please read this privacy policy carefully. By accessing or using our platform, you acknowledge that you have read, understood, and agree to be bound by all the terms outlined here.
                </p>
            </div>

            {{-- Information We Collect --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Information We Collect</h2>

                <h3 class="mt-5 text-base font-semibold text-textPrimary">Personal Information</h3>
                <p class="mt-2 text-sm leading-7 text-textSecondary">
                    When you register on our platform, enroll in a course, or interact with our services, we may collect the following personal information:
                </p>
                <ul class="mt-3 space-y-2 pl-5">
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        Full name, email address, and phone number
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        College or university name, course, and year of study
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        Payment information (processed securely through third-party payment gateways)
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        Resume, portfolio links, and project submissions
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        Communication preferences and consent records
                    </li>
                </ul>

                <h3 class="mt-6 text-base font-semibold text-textPrimary">Automatically Collected Information</h3>
                <p class="mt-2 text-sm leading-7 text-textSecondary">
                    When you access our website, we may automatically collect certain information including your IP address, browser type, operating system, referring URLs, access times, and pages viewed. This helps us improve platform performance and user experience.
                </p>
            </div>

            {{-- How We Use Your Information --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">How We Use Your Information</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    We use the information we collect for the following purposes:
                </p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @php
                        $usages = [
                            'To provide, operate, and maintain our learning platform',
                            'To process enrollments, payments, and certifications',
                            'To personalize your learning experience and recommendations',
                            'To communicate with you about courses, updates, and offers',
                            'To respond to your inquiries and support requests',
                            'To improve our website, services, and educational content',
                            'To comply with legal obligations and protect our rights',
                            'To facilitate college tie-up programs and partnerships',
                        ];
                    @endphp
                    @foreach ($usages as $usage)
                    <div class="flex items-start gap-3 rounded-lg border border-borderLight bg-bgSoft/50 p-3">
                        <span class="mt-1 grid h-5 w-5 shrink-0 place-items-center rounded-full bg-brand/10">
                            <svg class="h-3 w-3 text-brand" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </span>
                        <span class="text-sm leading-6 text-textSecondary">{{ $usage }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Cookies --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Cookies & Tracking Technologies</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    We use cookies and similar tracking technologies to enhance your experience on our platform. Cookies are small data files stored on your device that help us remember your preferences, analyze site traffic, and understand usage patterns.
                </p>
                <p class="mt-3 text-sm leading-7 text-textSecondary">
                    You can control cookie settings through your browser preferences. However, disabling cookies may affect the functionality of certain features on our platform, including login sessions and personalized content.
                </p>
            </div>

            {{-- Third-Party Sharing --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Third-Party Sharing</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:
                </p>
                <ul class="mt-4 space-y-3 pl-5">
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        <span><strong class="text-textPrimary">Partner Colleges:</strong> When you enroll through a college tie-up program, relevant academic information may be shared with your institution for progress tracking and placement coordination.</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        <span><strong class="text-textPrimary">Payment Processors:</strong> Secure payment gateways (such as Razorpay) process your financial transactions. We do not store your card or banking details on our servers.</span>
                    </li>
                    <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                        <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                        <span><strong class="text-textPrimary">Legal Requirements:</strong> We may disclose information if required by law, subpoena, or governmental request, or to protect our rights and safety.</span>
                    </li>
                </ul>
            </div>

            {{-- Data Security --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Data Security</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    We implement industry-standard security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. These include encrypted data transmission (SSL/TLS), secure server infrastructure, and regular security audits.
                </p>
                <p class="mt-3 text-sm leading-7 text-textSecondary">
                    While we strive to use commercially acceptable means to protect your data, no method of electronic transmission or storage is 100% secure. We cannot guarantee absolute security but are committed to continuously improving our safeguards.
                </p>
            </div>

            {{-- Children's Privacy --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Children's Privacy</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Our services are not directed to individuals under the age of 16. We do not knowingly collect personal information from children under 16. If we become aware that we have collected data from a child under 16 without parental consent, we will take steps to remove that information from our servers promptly.
                </p>
            </div>

            {{-- Your Rights --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Your Rights</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Depending on your location, you may have the following rights regarding your personal data:
                </p>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    @php
                        $rights = [
                            ['title' => 'Access', 'desc' => 'Request a copy of the personal data we hold about you.'],
                            ['title' => 'Correction', 'desc' => 'Request correction of any inaccurate or incomplete personal data.'],
                            ['title' => 'Deletion', 'desc' => 'Request deletion of your personal data, subject to legal obligations.'],
                            ['title' => 'Withdrawal', 'desc' => 'Withdraw consent for data processing at any time.'],
                        ];
                    @endphp
                    @foreach ($rights as $right)
                    <div class="rounded-lg border border-borderLight bg-bgSoft/50 p-4">
                        <h3 class="text-sm font-semibold text-textPrimary">{{ $right['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-textSecondary">{{ $right['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Changes to This Policy --}}
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Changes to This Policy</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    We may update this Privacy Policy from time to time to reflect changes in our practices, technology, legal requirements, or other factors. We will notify you of any material changes by posting the updated policy on this page with a revised "Last updated" date. Your continued use of the platform after such changes constitutes your acceptance of the updated policy.
                </p>
            </div>

            {{-- Contact --}}
            <div class="rounded-lg border border-brand/20 bg-gradient-to-r from-brand/5 via-bgWhite to-secondary/5 p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Contact Us</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    If you have any questions or concerns about this Privacy Policy, or if you wish to exercise any of your data rights, please contact us:
                </p>
                <div class="mt-5 space-y-3">
                    <a href="mailto:info@engineersclinic.com" class="flex items-center gap-3 text-sm font-semibold text-brand transition hover:text-brandDark">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand/10">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        info@engineersclinic.com
                    </a>
                    <a href="tel:+917545999990" class="flex items-center gap-3 text-sm font-semibold text-brand transition hover:text-brandDark">
                        <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand/10">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </span>
                        +91-75459-99990
                    </a>
                </div>
            </div>
        </div>

        {{-- Back to home --}}
        <div class="mt-10 text-center">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-brand transition hover:text-brandDark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
