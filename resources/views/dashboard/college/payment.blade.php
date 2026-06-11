@extends('layouts.frontend-admin')

@section('title', 'College Payment')

@section('content')
@php
    $selectedMode = old('payment_mode', $college->payment_mode ?? 'online');
    $statusCopy = [
        'approved' => $college->payment_mode === 'online' ? 'Payment complete' : 'Approved by admin',
        'rejected' => 'Rejected by admin',
        'pending' => $college->payment_mode === 'offline' ? 'Pending admin approval' : 'Pending',
    ];
    $canOpenRazorpay = $college->payment_mode === 'online'
        && $college->payment_status !== 'approved'
        && filled($college->razorpay_order_id)
        && filled($razorpayKey);
@endphp

<section class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">College Dashboard</p>
            <h1 class="mt-2 text-2xl font-bold text-textPrimary">Complete Payment</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-textSecondary">
                Choose online payment or submit your offline transfer details for verification.
            </p>
        </div>

        @if($college->payment_mode)
            <span class="inline-flex w-fit items-center rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-amber-700">
                {{ $statusCopy[$college->payment_status ?? 'pending'] ?? ucfirst($college->payment_status ?? 'pending') }}
            </span>
        @endif
    </div>

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($college->payment_status === 'approved')
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
            Your college payment is approved. Dashboard access is active.
        </div>
    @elseif($college->payment_status === 'rejected')
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            Your offline UTR payment was rejected. {{ $college->payment_rejection_reason ? 'Reason: ' . $college->payment_rejection_reason : 'Please submit the correct details again.' }}
        </div>
    @elseif($college->payment_mode === 'offline' && filled($college->utr_number))
        <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-700">
            Your UTR number is submitted and waiting for admin approval.
        </div>
    @endif

    @if($college->payment_mode)
        <section class="rounded-2xl border border-borderLight bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.14em] text-brand">Payment Details</p>
                    <h2 class="mt-2 text-xl font-semibold text-textPrimary">
                        {{ $statusCopy[$college->payment_status ?? 'pending'] ?? ucfirst($college->payment_status ?? 'pending') }}
                    </h2>
                </div>
                <span class="inline-flex w-fit rounded-full px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] {{ $college->payment_status === 'approved' ? 'bg-green-100 text-green-700' : ($college->payment_status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                    {{ ucfirst($college->payment_status ?? 'pending') }}
                </span>
            </div>

            <dl class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl bg-slate-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Mode</dt>
                    <dd class="mt-2 text-sm font-semibold text-slate-950">{{ ucfirst($college->payment_mode) }}</dd>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Amount</dt>
                    <dd class="mt-2 text-sm font-semibold text-slate-950">Rs. {{ number_format((float) ($college->payment_amount ?: $paymentAmount), 2) }}</dd>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Submitted</dt>
                    <dd class="mt-2 text-sm font-semibold text-slate-950">{{ $college->payment_submitted_at?->format('d M Y, h:i A') ?? 'Not submitted' }}</dd>
                </div>
                <div class="rounded-2xl bg-slate-50 p-4">
                    <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Reviewed</dt>
                    <dd class="mt-2 text-sm font-semibold text-slate-950">{{ $college->payment_reviewed_at?->format('d M Y, h:i A') ?? 'Pending' }}</dd>
                </div>

                @if($college->payment_mode === 'offline')
                    <div class="rounded-2xl bg-slate-50 p-4 md:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">UTR Number</dt>
                        <dd class="mt-2 text-sm font-semibold text-slate-950">{{ $college->utr_number ?: 'Not provided' }}</dd>
                    </div>
                @endif

                @if($college->payment_mode === 'online')
                    <div class="rounded-2xl bg-slate-50 p-4 md:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Razorpay Order ID</dt>
                        <dd class="mt-2 break-all text-sm font-semibold text-slate-950">{{ $college->razorpay_order_id ?: 'Not generated' }}</dd>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4 md:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-slate-500">Razorpay Payment ID</dt>
                        <dd class="mt-2 break-all text-sm font-semibold text-slate-950">{{ $college->razorpay_payment_id ?: 'Not verified yet' }}</dd>
                    </div>
                @endif

                @if($college->payment_rejection_reason)
                    <div class="rounded-2xl bg-red-50 p-4 md:col-span-2 xl:col-span-4">
                        <dt class="text-xs font-semibold uppercase tracking-[0.14em] text-red-500">Rejection Reason</dt>
                        <dd class="mt-2 text-sm font-semibold text-red-700">{{ $college->payment_rejection_reason }}</dd>
                    </div>
                @endif
            </dl>
        </section>
    @endif

    @if($college->payment_status === 'approved')
        <div class="flex justify-end">
            <a href="{{ route('college.dashboard') }}"
                class="inline-flex items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                Go to Dashboard
            </a>
        </div>
    @else
    <form method="POST" action="{{ route('college.payment.store') }}" class="grid gap-6 lg:grid-cols-[1fr_360px]">
        @csrf

        <section class="rounded-2xl border border-borderLight bg-white p-5 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border p-4 transition has-[:checked]:border-brand has-[:checked]:bg-brandSoft">
                    <input type="radio" name="payment_mode" value="online" class="mt-1 h-5 w-5 accent-brand" {{ $selectedMode === 'online' ? 'checked' : '' }}>
                    <span>
                        <span class="block text-base font-bold text-textPrimary">Online</span>
                        <span class="mt-1 block text-sm leading-6 text-textSecondary">Pay with Razorpay and unlock dashboard access immediately after verification.</span>
                    </span>
                </label>

                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border p-4 transition has-[:checked]:border-brand has-[:checked]:bg-brandSoft">
                    <input type="radio" name="payment_mode" value="offline" class="mt-1 h-5 w-5 accent-brand" {{ $selectedMode === 'offline' ? 'checked' : '' }}>
                    <span>
                        <span class="block text-base font-bold text-textPrimary">Offline</span>
                        <span class="mt-1 block text-sm leading-6 text-textSecondary">Enter your UTR number after bank transfer or UPI payment.</span>
                    </span>
                </label>
            </div>

            @error('payment_mode')
                <p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>
            @enderror

            <div id="offline-payment-fields" class="mt-6 {{ $selectedMode === 'offline' ? '' : 'hidden' }}">
                <label for="utr_number" class="text-sm font-semibold text-textSecondary">UTR Number</label>
                <input
                    id="utr_number"
                    name="utr_number"
                    type="text"
                    value="{{ old('utr_number', $college->utr_number) }}"
                    placeholder="Enter UTR / transaction reference number"
                    class="mt-2 w-full rounded-2xl border border-borderLight bg-bgSoft px-4 py-3 text-sm text-textPrimary outline-none transition placeholder:text-textMuted focus:border-brand focus:bg-white focus:ring-4 focus:ring-brandSoft"
                >
                @error('utr_number')
                    <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <aside class="h-fit rounded-2xl border border-borderLight bg-white p-5 shadow-sm">
            <p class="text-sm font-bold uppercase tracking-[0.14em] text-brand">Summary</p>
            <div class="mt-4 space-y-3 border-b border-borderLight pb-4 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-textMuted">College</span>
                    <span class="text-right font-semibold text-textPrimary">{{ $college->college_name }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-textMuted">Mode</span>
                    <span id="selected-payment-mode" class="font-semibold text-textPrimary">{{ ucfirst($selectedMode) }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-textMuted">Amount</span>
                    <span class="text-right font-semibold text-textPrimary">Rs. {{ number_format((float) ($college->payment_amount ?: $paymentAmount), 2) }}</span>
                </div>
            </div>

            <button type="submit" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-brand to-secondary px-5 py-3 text-sm font-semibold text-white transition hover:from-brandDark hover:to-secondary">
                <span id="payment-submit-label">{{ $selectedMode === 'offline' ? 'Submit UTR for Approval' : 'Pay Online' }}</span>
            </button>

            @if($college->payment_mode === 'online' && blank($razorpayKey))
                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs font-semibold text-amber-800">
                    Razorpay key is missing. Add RAZORPAY_KEY and RAZORPAY_SECRET to enable online payment.
                </div>
            @elseif($canOpenRazorpay)
                <button type="button" id="open-razorpay-button" class="mt-3 inline-flex w-full items-center justify-center rounded-full border border-brand px-5 py-3 text-sm font-semibold text-brand transition hover:bg-brandSoft">
                    Open Razorpay Checkout
                </button>
            @endif
        </aside>
    </form>
    @endif
</section>

@if($canOpenRazorpay)
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endif

<script>
    const modeInputs = document.querySelectorAll('input[name="payment_mode"]');
    const offlineFields = document.getElementById('offline-payment-fields');
    const selectedMode = document.getElementById('selected-payment-mode');
    const paymentSubmitLabel = document.getElementById('payment-submit-label');

    modeInputs.forEach((input) => {
        input.addEventListener('change', () => {
            offlineFields.classList.toggle('hidden', input.value !== 'offline');
            selectedMode.textContent = input.value.charAt(0).toUpperCase() + input.value.slice(1);
            paymentSubmitLabel.textContent = input.value === 'offline' ? 'Submit UTR for Approval' : 'Pay Online';
        });
    });
</script>

@if($canOpenRazorpay)
    <script>
        const openRazorpayButton = document.getElementById('open-razorpay-button');
        const shouldOpenRazorpay = @js((bool) session('open_razorpay'));

        function openCollegeRazorpay() {
            if (typeof Razorpay === 'undefined') {
                alert('Razorpay checkout could not be loaded. Please refresh and try again.');
                return;
            }

            const checkout = new Razorpay({
                key: @js($razorpayKey),
                amount: @js((int) round((float) ($college->payment_amount ?: $paymentAmount) * 100)),
                currency: 'INR',
                name: 'Engineers Clinic',
                description: 'College access payment',
                order_id: @js($college->razorpay_order_id),
                prefill: {
                    name: @js(auth()->user()?->name),
                    email: @js(auth()->user()?->email),
                    contact: @js($college->contact_number),
                },
                handler: async (response) => {
                    try {
                        const result = await postCollegePaymentVerification({
                            razorpay_payment_id: response.razorpay_payment_id,
                            razorpay_order_id: response.razorpay_order_id,
                            razorpay_signature: response.razorpay_signature,
                        });

                        window.location.href = result.redirect_url || @js(route('college.dashboard'));
                    } catch (error) {
                        alert(error.message || 'Payment verification failed. Please contact support if money was debited.');
                    }
                },
                theme: {
                    color: '#7C5CFC',
                },
            });

            checkout.open();
        }

        async function postCollegePaymentVerification(payload) {
            const response = await fetch(@js(route('college.payment.verify')), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': @js(csrf_token()),
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json().catch(() => ({}));

            if (! response.ok) {
                throw new Error(data.message || 'Request failed.');
            }

            return data;
        }

        openRazorpayButton?.addEventListener('click', openCollegeRazorpay);

        if (shouldOpenRazorpay) {
            window.addEventListener('load', openCollegeRazorpay);
        }
    </script>
@endif
@endsection
