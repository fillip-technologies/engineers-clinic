@extends('layouts.app')

@section('content')
@php
    $levelColors = [
        'Beginner'     => 'emerald',
        'Intermediate' => 'blue',
        'Advanced'     => 'violet',
    ];
    $color = $levelColors[$level] ?? 'indigo';
    $formattedAmount = '₹' . number_format($amount);
@endphp

<div class="min-h-screen bg-[#F5F0FF] py-16 px-4">
    <div class="mx-auto max-w-lg" x-data="enrollmentPay()" x-init="init()">

        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-[#5B5BF6]/10">
                <svg class="h-7 w-7 text-[#5B5BF6]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-[#111827]">Complete Your Enrollment</h1>
            <p class="mt-1 text-sm text-[#64748B]">One payment to unlock your internship track.</p>
        </div>

        <!-- Error -->
        <template x-if="error">
            <div class="mb-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="error"></span>
            </div>
        </template>

        <!-- Summary Card -->
        <div class="overflow-hidden rounded-3xl border border-[#E2E8F0] bg-white shadow-[0_8px_40px_rgba(15,23,42,0.06)]">

            <!-- Course row -->
            <div class="border-b border-[#F1F5F9] px-6 py-5">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#5B5BF6]">Selected Track</p>
                <p class="mt-1.5 text-base font-bold text-[#111827]">{{ $pending['course_title'] ?? 'Internship Track' }}</p>
                @if(!empty($pending['stream']))
                <p class="mt-0.5 text-xs text-[#64748B]">Topic: <span class="font-semibold">{{ $pending['stream'] }}</span></p>
                @endif
            </div>

            <!-- Level + Amount row -->
            <div class="flex items-center justify-between border-b border-[#F1F5F9] px-6 py-4">
                <div>
                    <p class="text-xs text-[#94A3B8]">Level</p>
                    <span class="mt-1 inline-flex items-center rounded-full
                        @if($color === 'emerald') bg-emerald-100 text-emerald-700
                        @elseif($color === 'blue') bg-blue-100 text-blue-700
                        @else bg-violet-100 text-violet-700
                        @endif
                        px-3 py-1 text-xs font-bold">
                        {{ $level }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-xs text-[#94A3B8]">Internship Fee</p>
                    <p class="mt-0.5 text-2xl font-bold text-[#111827]">{{ $formattedAmount }}</p>
                </div>
            </div>

            <!-- Projects -->
            @php
                $projectNos = $pending['selected_project_nos'] ?? [];
            @endphp
            @if(!empty($projectNos))
            <div class="border-b border-[#F1F5F9] px-6 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#94A3B8]">Selected Projects</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($projectNos as $no)
                    <span class="rounded-xl border border-[#5B5BF6]/20 bg-[#F8F8FF] px-3 py-1 text-xs font-semibold text-[#5B5BF6]">
                        Project {{ $no }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- What you get -->
            <div class="bg-[#F8FAFC] px-6 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-[#94A3B8]">What's included</p>
                <ul class="mt-3 space-y-2">
                    @foreach(['Internship certificate on completion', 'Mentor-guided learning path', 'Real-world project portfolio', 'Dashboard access & progress tracking'] as $item)
                    <li class="flex items-center gap-2 text-xs text-[#475569]">
                        <svg class="h-3.5 w-3.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            <!-- Pay button -->
            <div class="px-6 py-5">
                <button
                    @click="pay()"
                    :disabled="loading"
                    class="w-full rounded-2xl bg-[#5B5BF6] py-4 text-sm font-bold text-white shadow-lg shadow-[#5B5BF6]/25 transition duration-300 hover:bg-[#4F46E5] disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!loading" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Pay {{ $formattedAmount }} &amp; Enroll
                    </span>
                    <span x-show="loading" class="flex items-center justify-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Processing…
                    </span>
                </button>
                <p class="mt-3 text-center text-xs text-[#94A3B8]">
                    Secured by Razorpay · Your information is encrypted
                </p>
            </div>

        </div>

    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function enrollmentPay() {
    return {
        loading: false,
        error: null,

        init() {},

        async post(url, data) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            return res.json();
        },

        async pay() {
            this.loading = true;
            this.error = null;

            // Step 1: create Razorpay order via checkoutStart
            let orderData;
            try {
                orderData = await this.post(@js($startUrl), {
                    level:            @js($level),
                    stream:           @js($pending['stream'] ?? ''),
                    selected_courses: @js($pending['selected_courses']),
                });
            } catch (e) {
                this.error = 'Could not connect. Please try again.';
                this.loading = false;
                return;
            }

            if (!orderData.order_id) {
                this.error = orderData.message || 'Could not create payment order.';
                this.loading = false;
                return;
            }

            // Step 2: open Razorpay modal
            const rzp = new Razorpay({
                key:         @js($razorpayKey),
                amount:      orderData.amount_paise,
                currency:    'INR',
                order_id:    orderData.order_id,
                name:        'Engineers Clinic',
                description: 'Internship Enrollment – ' + @js($level),
                prefill: {
                    name:  @js(Auth::user()->name),
                    email: @js(Auth::user()->email),
                },
                theme: { color: '#5B5BF6' },
                modal: {
                    ondismiss: () => { this.loading = false; }
                },
                handler: async (response) => {
                    // Step 3: verify payment
                    let verifyData;
                    try {
                        verifyData = await this.post(@js($verifyUrl), {
                            internship_payment_id: orderData.internship_payment_id,
                            razorpay_order_id:     response.razorpay_order_id,
                            razorpay_payment_id:   response.razorpay_payment_id,
                            razorpay_signature:    response.razorpay_signature,
                        });
                    } catch (e) {
                        this.error = 'Payment done but verification failed. Contact support.';
                        this.loading = false;
                        return;
                    }

                    if (verifyData.success) {
                        window.location.href = verifyData.redirect_url || @js($dashboardUrl);
                    } else {
                        this.error = verifyData.message || 'Payment verification failed.';
                        this.loading = false;
                    }
                },
            });

            rzp.open();
        },
    };
}
</script>
@endsection
