@extends('layouts.frontend-admin')

@section('title', 'College Payment')

@section('content')
@php
    $selectedMode = old('payment_mode', $college->payment_mode ?? 'online');
    $statusCopy = [
        'approved' => 'Approved by admin',
        'rejected' => 'Rejected by admin',
        'pending' => $college->payment_mode === 'offline' ? 'Pending admin approval' : 'Pending',
    ];
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-brand">College Signup</p>
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

    @if($college->payment_status === 'approved')
        <div class="rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-semibold text-green-700">
            Your offline UTR payment has been approved by admin.
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

    <form method="POST" action="{{ route('college.payment.store') }}" class="grid gap-6 lg:grid-cols-[1fr_360px]">
        @csrf

        <section class="rounded-2xl border border-borderLight bg-white p-5 shadow-sm">
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="flex cursor-pointer items-start gap-3 rounded-2xl border p-4 transition has-[:checked]:border-brand has-[:checked]:bg-brandSoft">
                    <input type="radio" name="payment_mode" value="online" class="mt-1 h-5 w-5 accent-brand" {{ $selectedMode === 'online' ? 'checked' : '' }}>
                    <span>
                        <span class="block text-base font-bold text-textPrimary">Online</span>
                        <span class="mt-1 block text-sm leading-6 text-textSecondary">Continue with online payment after selecting this option.</span>
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
            </div>

            <button type="submit" class="mt-5 inline-flex w-full items-center justify-center rounded-full bg-gradient-to-r from-brand to-secondary px-5 py-3 text-sm font-semibold text-white transition hover:from-brandDark hover:to-secondary">
                Continue to Payment
            </button>
        </aside>
    </form>
</div>

<script>
    const modeInputs = document.querySelectorAll('input[name="payment_mode"]');
    const offlineFields = document.getElementById('offline-payment-fields');
    const selectedMode = document.getElementById('selected-payment-mode');

    modeInputs.forEach((input) => {
        input.addEventListener('change', () => {
            offlineFields.classList.toggle('hidden', input.value !== 'offline');
            selectedMode.textContent = input.value.charAt(0).toUpperCase() + input.value.slice(1);
        });
    });
</script>
@endsection
