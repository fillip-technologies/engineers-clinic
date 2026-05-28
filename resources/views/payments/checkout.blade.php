@extends('layouts.app')

@section('content')
@php
    $amount = (float) $course->fee;
    $isFree = $amount <= 0;
    $intentName = $checkoutIntent['name'] ?? auth()->user()?->name ?? '';
    $intentEmail = $checkoutIntent['email'] ?? auth()->user()?->email ?? '';
    $canPay = $order && !$completedPayment && !$hasCompletedEnrollment && !$isFree;
@endphp

<section class="section-white py-20">
    <div class="container-main">
        <div class="grid gap-8 lg:grid-cols-[1fr_420px]">
            <div class="space-y-6">
                <a href="{{ route('course.detail', $course->slug) }}" class="inline-flex items-center gap-2 text-sm font-bold text-brand transition hover:text-brandDark">
                    <span aria-hidden="true">&larr;</span>
                    Back to course
                </a>

                <div>
                    <p class="text-label">Secure checkout</p>
                    <h1 class="mt-3 text-[clamp(2.2rem,5vw,4rem)] font-black leading-none tracking-tight text-textPrimary">
                        Complete your enrollment
                    </h1>
                    <p class="mt-5 max-w-2xl text-body-lg">
                        Razorpay checkout will open automatically. Your course access is activated as soon as payment is verified.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="card-primary p-5">
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-brand">Student</p>
                        <p class="mt-3 text-lg font-black text-textPrimary">{{ $intentName }}</p>
                        <p class="mt-1 text-sm font-semibold text-textMuted">{{ $intentEmail }}</p>
                    </div>

                    <div class="card-primary p-5">
                        <p class="text-xs font-black uppercase tracking-[0.14em] text-brand">Course</p>
                        <p class="mt-3 text-lg font-black text-textPrimary">{{ $course->title }}</p>
                        <p class="mt-1 text-sm font-semibold text-textMuted">{{ $course->level }} | {{ $course->category }}</p>
                    </div>
                </div>

                @if(($checkoutIntent['new_user'] ?? false) && auth()->check())
                    <div class="rounded-card border border-brand/20 bg-brandSoft p-5">
                        <p class="font-black text-textPrimary">Your student account is ready.</p>
                        <p class="mt-2 text-sm font-semibold text-textSecondary">
                            We queued your login credentials email. You are already signed in for this purchase.
                        </p>
                    </div>
                @endif

                @if($completedPayment || $hasCompletedEnrollment)
                    <div class="rounded-card border border-green-200 bg-green-50 p-5">
                        <p class="font-black text-green-800">Your enrollment is active.</p>
                        <p class="mt-2 text-sm font-semibold text-green-700">You can continue from your enrolled courses dashboard.</p>
                    </div>
                @endif
            </div>

            <aside
                class="card-primary h-fit p-6"
                x-data="courseCheckout({
                    orderId: @js($order?->id),
                    razorpayOrderId: @js($order?->razorpay_order_id),
                    courseTitle: @js($course->title),
                    amount: @js($amount),
                    amountPaise: @js((int) round($amount * 100)),
                    isFree: @js($isFree),
                    canPay: @js($canPay),
                    razorpayKey: @js($razorpayKey),
                    verifyUrl: @js(route('payments.verify')),
                    failureUrl: @js($order ? route('payments.failure', ['order' => $order->id]) : route('course.detail', $course->slug)),
                    successUrl: @js(route('dashboard.enrolled-courses')),
                    csrfToken: @js(csrf_token()),
                    studentName: @js($intentName),
                    studentEmail: @js($intentEmail),
                    studentPhone: @js($checkoutIntent['phone'] ?? '')
                })"
                x-init="openWhenReady()"
            >
                <p class="text-sm font-black uppercase tracking-[0.14em] text-brand">Order summary</p>

                <div class="mt-5 space-y-4 border-b border-borderLight pb-5">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-black text-textPrimary">{{ $course->title }}</p>
                            <p class="mt-1 text-sm font-semibold text-textMuted">{{ $course->duration_months }} month program</p>
                        </div>
                        <p class="shrink-0 text-base font-black text-textPrimary">
                            {{ $isFree ? 'Free' : 'Rs. ' . number_format($amount, 2) }}
                        </p>
                    </div>
                </div>

                <div class="mt-5 flex items-center justify-between">
                    <p class="text-sm font-bold text-textMuted">Total</p>
                    <p class="text-2xl font-black text-textPrimary">
                        {{ $isFree ? 'Free' : 'Rs. ' . number_format($amount, 2) }}
                    </p>
                </div>

                <template x-if="error">
                    <div class="mt-5 rounded-control border border-red-100 bg-red-50 px-4 py-3 text-sm font-bold text-red-700" x-text="error"></div>
                </template>

                @if($canPay)
                    @if(blank($razorpayKey))
                        <div class="mt-5 rounded-control border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-800">
                            Razorpay key is missing. Add RAZORPAY_KEY and RAZORPAY_SECRET to enable paid checkout.
                        </div>
                    @else
                        <button
                            type="button"
                            @click="pay()"
                            :disabled="processing"
                            class="mt-6 inline-flex w-full items-center justify-center rounded-control bg-brand px-5 py-4 text-sm font-black text-white shadow-card transition hover:bg-brandDark disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span x-show="!processing">Open Razorpay Checkout</span>
                            <span x-show="processing">Processing...</span>
                        </button>
                    @endif
                @else
                    <a href="{{ route('dashboard.enrolled-courses') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-control bg-brand px-5 py-4 text-sm font-black text-white shadow-card transition hover:bg-brandDark">
                        Go to Dashboard
                    </a>
                @endif

                <p class="mt-4 text-center text-xs font-semibold text-textMuted">
                    Payments are processed through Razorpay.
                </p>
            </aside>
        </div>
    </div>
</section>

@if($canPay)
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<script>
    function courseCheckout(config) {
        return {
            ...config,
            processing: false,
            opened: false,
            error: '',

            openWhenReady() {
                if (!this.canPay || !this.razorpayKey) {
                    return;
                }

                this.$nextTick(() => this.pay());
            },

            async pay() {
                if (this.processing || this.opened) {
                    return;
                }

                this.error = '';
                this.processing = true;
                this.opened = true;

                if (typeof Razorpay === 'undefined') {
                    this.error = 'Razorpay checkout could not be loaded. Please refresh and try again.';
                    this.processing = false;
                    this.opened = false;
                    return;
                }

                const checkout = new Razorpay({
                    key: this.razorpayKey,
                    amount: this.amountPaise,
                    currency: 'INR',
                    name: 'Engineers Clinic',
                    description: this.courseTitle,
                    order_id: this.razorpayOrderId,
                    prefill: {
                        name: this.studentName,
                        email: this.studentEmail,
                        contact: this.studentPhone,
                    },
                    handler: async (response) => {
                        try {
                            const result = await this.postJson(this.verifyUrl, {
                                order_id: this.orderId,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                            });

                            window.location.href = result.redirect_url || this.successUrl;
                        } catch (error) {
                            this.error = error.message || 'Payment verification failed.';
                            this.processing = false;
                        }
                    },
                    modal: {
                        ondismiss: () => {
                            window.location.href = this.failureUrl;
                        },
                    },
                    theme: {
                        color: '#5B5BF6',
                    },
                });

                checkout.open();
            },

            async postJson(url, payload) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(data.message || data.error || 'Request failed.');
                }

                return data;
            },
        };
    }
</script>
@endsection
