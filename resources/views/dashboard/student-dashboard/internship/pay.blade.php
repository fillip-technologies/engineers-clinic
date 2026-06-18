@extends('layouts.frontend-admin')

@section('content')
@php
    $levelMeta = [
        'Beginner'     => ['icon' => 'fi fi-rr-seedling',       'color' => 'emerald'],
        'Intermediate' => ['icon' => 'fi fi-rr-chart-line-up',  'color' => 'blue'],
        'Advanced'     => ['icon' => 'fi fi-rr-rocket',         'color' => 'violet'],
    ];
    $meta = $levelMeta[$level] ?? $levelMeta['Beginner'];
@endphp

<div class="mx-auto max-w-3xl"
    x-data="internshipCheckout({
        createOrderUrl: @js($createOrderUrl),
        verifyUrl:      @js($verifyUrl),
        successUrl:     @js($successUrl),
        cancelUrl:      @js($cancelUrl),
        razorpayKey:    @js($razorpayKey),
        amount:         @js($amount),
        amountPaise:    @js((int) round($amount * 100)),
        level:          @js($level),
        studentName:    @js(Auth::user()?->name ?? ''),
        studentEmail:   @js(Auth::user()?->email ?? ''),
        csrfToken:      @js(csrf_token()),
    })"
>
    {{-- Header --}}
    <section class="rounded-[1.75rem] border border-slate-200/70 bg-white px-6 py-6 shadow-sm sm:px-8">
        <a href="{{ $cancelUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800">
            <i class="fi fi-rr-arrow-left text-xs leading-none"></i> Back to Browse Projects
        </a>
        <div class="mt-4">
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">Internship Access</p>
            <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900">Unlock your internship</h1>
            <p class="mt-2 text-base text-slate-500">Pay once to access the full <strong>{{ $level }}</strong> internship program. After payment, select any 3 projects from your topic and start building.</p>
        </div>
    </section>

    {{-- Main content --}}
    <div class="mt-6 grid gap-6 lg:grid-cols-[1fr_360px]">

        {{-- What you get --}}
        <section class="rounded-xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
            <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">What's included</p>

            <div class="mt-5 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-{{ $meta['color'] }}-50">
                    <i class="{{ $meta['icon'] }} text-xl text-{{ $meta['color'] }}-600 leading-none"></i>
                </div>
                <div>
                    <p class="text-lg font-semibold text-slate-900">{{ $level }} Internship Program</p>
                    <p class="text-sm text-slate-500">One-time access fee</p>
                </div>
            </div>

            <ul class="mt-6 space-y-3">
                @foreach([
                    'Choose any 3 projects from your internship topic',
                    'Full workspace access with guided step-by-step checkpoints',
                    'Submit projects and build a real portfolio',
                    'Track your progress and quiz attempts',
                    'Internship completion certificate on finishing all 3 projects',
                ] as $feature)
                <li class="flex items-start gap-3">
                    <i class="fi fi-rr-check-circle mt-0.5 shrink-0 text-sm text-emerald-500 leading-none"></i>
                    <span class="text-sm text-slate-700">{{ $feature }}</span>
                </li>
                @endforeach
            </ul>

            <div class="mt-6 rounded-xl border border-{{ $meta['color'] }}-100 bg-{{ $meta['color'] }}-50 px-4 py-3">
                <p class="text-xs font-semibold text-{{ $meta['color'] }}-800">
                    <i class="fi fi-rr-info mr-1"></i>
                    Your chosen level: <strong>{{ $level }}</strong>. You can set your internship topic from your profile before or after payment.
                </p>
            </div>
        </section>

        {{-- Payment card --}}
        <aside class="rounded-xl border border-slate-200 bg-white px-6 py-6 shadow-sm h-fit">
            <p class="text-sm font-semibold uppercase tracking-widest text-slate-500">Order summary</p>

            <div class="mt-5 border-b border-slate-100 pb-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">{{ $level }} Internship Access</p>
                        <p class="mt-0.5 text-xs text-slate-400">One-time, full program access</p>
                    </div>
                    <p class="shrink-0 text-sm font-bold text-slate-900">Rs. {{ number_format($amount, 0) }}</p>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <p class="text-sm font-medium text-slate-500">Total</p>
                <p class="text-2xl font-bold text-slate-900">Rs. {{ number_format($amount, 0) }}</p>
            </div>

            <template x-if="error">
                <div class="mt-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700" x-text="error"></div>
            </template>

            @if(blank($razorpayKey))
                <div class="mt-5 rounded-lg border border-amber-100 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800">
                    Razorpay is not configured. Add RAZORPAY_KEY and RAZORPAY_SECRET to .env to enable payments.
                </div>
            @else
                <button
                    type="button"
                    @click="pay()"
                    :disabled="processing"
                    class="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary px-5 py-3.5 text-sm font-bold text-white shadow-sm transition hover:bg-primaryLight disabled:cursor-not-allowed disabled:opacity-60"
                >
                    <span x-show="!processing"><i class="fi fi-rr-lock-open-alt text-sm leading-none"></i> Pay Rs. {{ number_format($amount, 0) }} &amp; Unlock</span>
                    <span x-show="processing">Processing...</span>
                </button>
            @endif

            <p class="mt-4 text-center text-xs text-slate-400">Secured by Razorpay</p>
        </aside>
    </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function internshipCheckout(config) {
    return {
        ...config,
        processing: false,
        error: '',

        async pay() {
            if (this.processing) return;
            this.error = '';
            this.processing = true;

            if (typeof Razorpay === 'undefined') {
                this.error = 'Razorpay could not be loaded. Please refresh and try again.';
                this.processing = false;
                return;
            }

            let orderData;
            try {
                const res = await this.postJson(this.createOrderUrl, {});
                orderData = res;
            } catch (e) {
                this.error = e.message || 'Could not create payment order. Please try again.';
                this.processing = false;
                return;
            }

            const checkout = new Razorpay({
                key: this.razorpayKey,
                amount: orderData.amount_paise,
                currency: 'INR',
                name: 'Engineers Clinic',
                description: this.level + ' Internship Access',
                order_id: orderData.order_id,
                prefill: {
                    name: this.studentName,
                    email: this.studentEmail,
                },
                handler: async (response) => {
                    try {
                        const result = await this.postJson(this.verifyUrl, {
                            internship_payment_id: orderData.internship_payment_id,
                            razorpay_payment_id:   response.razorpay_payment_id,
                            razorpay_order_id:     response.razorpay_order_id,
                            razorpay_signature:    response.razorpay_signature,
                        });
                        window.location.href = result.redirect_url || this.successUrl;
                    } catch (e) {
                        this.error = e.message || 'Payment verification failed. Contact support if money was debited.';
                        this.processing = false;
                    }
                },
                modal: {
                    ondismiss: () => {
                        this.processing = false;
                    },
                },
                theme: { color: '#5B5BF6' },
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
                throw new Error(data.message || 'Request failed.');
            }
            return data;
        },
    };
}
</script>
@endsection
