@extends('layouts.app')

@section('content')
@php
    $amount = (float) $course->fee;
    $isFree = $amount <= 0;
    $intentName = $checkoutIntent['name'] ?? auth()->user()->name;
    $intentEmail = $checkoutIntent['email'] ?? auth()->user()->email;
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
                        Review your course details and confirm your seat. Your enrollment will be activated after payment confirmation.
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

                @if(session('checkout_intent') && auth()->check())
                    <div class="rounded-card border border-brand/20 bg-brandSoft p-5">
                        <p class="font-black text-textPrimary">Your student account is ready.</p>
                        <p class="mt-2 text-sm font-semibold text-textSecondary">
                            We sent the login password to {{ auth()->user()->email }}. You can change it later from your dashboard.
                        </p>
                    </div>
                @endif

                @if($existingEnrollment)
                    <div class="rounded-card border border-green-200 bg-green-50 p-5">
                        <p class="font-black text-green-800">You are already enrolled in this course.</p>
                        <p class="mt-2 text-sm font-semibold text-green-700">You can continue from your enrolled courses dashboard.</p>
                    </div>
                @elseif($completedPayment)
                    <div class="rounded-card border border-green-200 bg-green-50 p-5">
                        <p class="font-black text-green-800">Payment is already completed.</p>
                        <p class="mt-2 text-sm font-semibold text-green-700">Your enrollment is ready in your dashboard.</p>
                    </div>
                @endif
            </div>

            <aside
                class="card-primary h-fit p-6"
                x-data="courseCheckout({
                    courseId: {{ $course->id }},
                    courseTitle: @js($course->title),
                    amount: {{ $amount }},
                    isFree: @js($isFree),
                    razorpayKey: @js($razorpayKey),
                    createOrderUrl: @js(route('payments.create-order')),
                    verifyUrl: @js(route('payments.verify')),
                    freeEnrollUrl: @js(route('payments.free-enroll')),
                    successUrl: @js(route('dashboard.enrolled-courses')),
                    csrfToken: @js(csrf_token()),
                    studentName: @js($intentName),
                    studentEmail: @js($intentEmail)
                })"
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

                <template x-if="success">
                    <div class="mt-5 rounded-control border border-green-100 bg-green-50 px-4 py-3 text-sm font-bold text-green-700" x-text="success"></div>
                </template>

                @if(!$existingEnrollment && !$completedPayment)
                    @if(!$isFree && blank($razorpayKey))
                        <div class="mt-5 rounded-control border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-bold text-amber-800">
                            Razorpay key is missing. Add RAZORPAY_KEY and RAZORPAY_SECRET to enable paid checkout.
                        </div>
                    @endif

                    <button
                        type="button"
                        @click="{{ $isFree ? 'completeFreeEnrollment()' : 'pay()' }}"
                        :disabled="processing || (!isFree && !razorpayKey)"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-control bg-brand px-5 py-4 text-sm font-black text-white shadow-card transition hover:bg-brandDark disabled:cursor-not-allowed disabled:opacity-60"
                    >
                        <span x-show="!processing">{{ $isFree ? 'Confirm Enrollment' : 'Pay Securely' }}</span>
                        <span x-show="processing">Processing...</span>
                    </button>
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

@if(!$isFree)
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<script>
    function courseCheckout(config) {
        return {
            ...config,
            processing: false,
            error: '',
            success: '',

            async pay() {
                this.error = '';
                this.success = '';
                this.processing = true;

                try {
                    const order = await this.postJson(this.createOrderUrl, { course_id: this.courseId });

                    const checkout = new Razorpay({
                        key: order.key,
                        amount: Math.round(Number(order.amount) * 100),
                        currency: order.currency,
                        name: 'Engineers Clinic',
                        description: this.courseTitle,
                        order_id: order.order_id,
                        prefill: {
                            name: this.studentName,
                            email: this.studentEmail,
                        },
                        handler: async (response) => {
                            const result = await this.postJson(this.verifyUrl, {
                                payment_id: order.payment_id,
                                razorpay_payment_id: response.razorpay_payment_id,
                                razorpay_order_id: response.razorpay_order_id,
                                razorpay_signature: response.razorpay_signature,
                            });

                            this.success = result.message || 'Enrollment completed.';
                            window.location.href = this.successUrl;
                        },
                        modal: {
                            ondismiss: () => {
                                this.processing = false;
                            },
                        },
                    });

                    checkout.open();
                } catch (error) {
                    this.error = error.message || 'Unable to start payment.';
                    this.processing = false;
                }
            },

            async completeFreeEnrollment() {
                this.error = '';
                this.success = '';
                this.processing = true;

                try {
                    const result = await this.postJson(this.freeEnrollUrl, { course_id: this.courseId });

                    this.success = result.message || 'Enrollment completed.';
                    window.location.href = this.successUrl;
                } catch (error) {
                    this.error = error.message || 'Unable to complete enrollment.';
                    this.processing = false;
                }
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
                    throw new Error(data.error || data.message || 'Request failed.');
                }

                return data;
            },
        };
    }
</script>
@endsection
