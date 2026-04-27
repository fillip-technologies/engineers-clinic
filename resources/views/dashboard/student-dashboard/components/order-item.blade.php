@props([
    'order',
])

@php
    $paymentStatusClasses = match ($order['payment_status']) {
        'Paid' => 'bg-emerald-100 text-emerald-700',
        'Pending' => 'bg-amber-100 text-amber-700',
        'Failed' => 'bg-rose-100 text-rose-700',
        default => 'bg-slate-100 text-slate-700',
    };

    $accessStatusClasses = $order['access_status'] === 'Active'
        ? 'bg-blue-100 text-blue-700'
        : 'bg-slate-100 text-slate-600';
@endphp

<article class="flex flex-col gap-5 rounded-[1.5rem] bg-white px-5 py-5 transition hover:bg-slate-50/70 lg:flex-row lg:items-center lg:justify-between">
    <div class="min-w-0 flex-1">
        <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:gap-4">
            <h3 class="text-lg font-semibold text-slate-900">{{ $order['title'] }}</h3>
            <div class="flex flex-wrap gap-2">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $paymentStatusClasses }}">
                    {{ $order['payment_status'] }}
                </span>
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $accessStatusClasses }}">
                    {{ $order['access_status'] }}
                </span>
            </div>
        </div>

        <div class="mt-3 flex flex-wrap gap-x-6 gap-y-2 text-sm text-slate-500">
            <span>Order ID: {{ $order['order_id'] }}</span>
            <span>Purchased: {{ $order['purchase_date'] }}</span>
            <span>Price: {{ $order['price'] }}</span>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="#"
            class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-primaryLight">
            View Course
        </a>
        <a href="#"
            class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
            Download Invoice
        </a>
    </div>
</article>
