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
                Refund Policy
            </h1>

            <p class="mt-4 text-base leading-8 text-textSecondary">
                Last updated: {{ date('F d, Y') }}
            </p>
        </div>

        <div class="space-y-8">
            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Overview</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Engineers Clinic provides digital learning programs, internship projects, workspace access, reviews, and certificate-related services. This Refund Policy explains when refunds may be considered after a payment is made on our platform.
                </p>
            </div>

            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Refund Eligibility</h2>
                <ul class="mt-4 space-y-3">
                    @foreach ([
                        'Refund requests may be considered if payment was made twice for the same course, internship, or service.',
                        'Refunds may be considered if access to the purchased program was not provided due to a technical or administrative issue from our side.',
                        'Refunds are not generally applicable once project workspace access, learning material, mentorship, review, or certificate processing has started.',
                        'Any request must be raised within 7 days of the payment date with valid payment details and the registered email address.',
                    ] as $item)
                        <li class="flex items-start gap-3 text-sm leading-7 text-textSecondary">
                            <span class="mt-2.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand"></span>
                            <span>{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Non-Refundable Cases</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Fees paid for completed enrollments, activated dashboards, issued certificates, reviewed submissions, consumed services, or missed participation after access has been granted are non-refundable unless required by applicable law or approved by Engineers Clinic at its sole discretion.
                </p>
            </div>

            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Processing Timeline</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    Approved refunds will be processed to the original payment method where possible. Bank or payment gateway timelines may vary, but refunds typically take 7 to 10 business days after approval.
                </p>
            </div>

            <div class="rounded-lg border border-borderLight bg-bgWhite p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">How to Request a Refund</h2>
                <p class="mt-4 text-sm leading-7 text-textSecondary">
                    To request a refund review, email us with your registered name, email address, payment ID, payment date, course or service name, and reason for the request.
                </p>
            </div>

            <div class="rounded-lg border border-brand/20 bg-gradient-to-r from-brand/5 via-bgWhite to-secondary/5 p-6 shadow-sm sm:p-8">
                <h2 class="text-xl font-semibold text-textPrimary">Contact Us</h2>
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

        <div class="mt-10 text-center">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-semibold text-brand transition hover:text-brandDark">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
