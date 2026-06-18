@extends('layouts.frontend-admin')

@section('content')
<div class="mx-auto max-w-2xl">
    <a href="{{ route('college.internships') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-900">
        <i class="fi fi-rr-arrow-left text-sm leading-none"></i>
        Back to catalog
    </a>

    <div class="mt-5 rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-xs font-semibold uppercase tracking-widest text-primary">Seat Purchase</p>
        <h1 class="mt-2 text-2xl font-semibold text-slate-950">{{ $course->title }}</h1>
        <p class="mt-1 text-sm text-slate-500">{{ $course->description }}</p>

        <dl class="mt-5 grid grid-cols-2 gap-3">
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <dt class="text-xs text-slate-500">Level</dt>
                <dd class="mt-1 font-semibold text-slate-950">{{ $course->level ?? 'All' }}</dd>
            </div>
            <div class="rounded-lg bg-slate-50 px-4 py-3">
                <dt class="text-xs text-slate-500">Duration</dt>
                <dd class="mt-1 font-semibold text-slate-950">{{ $course->duration_months ? $course->duration_months . ' months' : 'Self paced' }}</dd>
            </div>
            <div class="rounded-lg bg-primary/5 px-4 py-3 col-span-2">
                <dt class="text-xs text-primary font-semibold">Price per seat</dt>
                <dd class="mt-1 text-xl font-bold text-slate-950">Rs. {{ number_format((float)($course->fee ?? 0), 2) }}</dd>
            </div>
        </dl>

        @if(session('success'))
            <div class="mt-5 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('college.internships.purchase.store', $course->id) }}"
            enctype="multipart/form-data"
            class="mt-7 space-y-5"
            x-data="{ mode: 'online', seats: 1, pricePerSeat: {{ (float)($course->fee ?? 0) }} }"
            @submit.prevent="mode === 'online' ? initiateRazorpay() : $el.submit()">
            @csrf

            <div>
                <label class="text-sm font-medium text-slate-700">Number of seats</label>
                <input type="number" name="seats" min="1" max="500"
                    x-model.number="seats"
                    class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100"
                    required />
                <p class="mt-1.5 text-sm text-slate-500">
                    Total: <strong x-text="'Rs. ' + (seats * pricePerSeat).toLocaleString('en-IN', { minimumFractionDigits: 2 })"></strong>
                </p>
            </div>

            <div>
                <label class="text-sm font-medium text-slate-700">Payment mode</label>
                <div class="mt-2 grid grid-cols-2 gap-3">
                    <label class="relative flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 transition"
                        :class="mode === 'online' ? 'border-primary bg-primary/5' : 'hover:bg-slate-50'">
                        <input type="radio" name="payment_mode" value="online" x-model="mode" class="sr-only" />
                        <i class="fi fi-rr-credit-card text-primary leading-none"></i>
                        <span class="text-sm font-medium text-slate-900">Online (Razorpay)</span>
                    </label>
                    <label class="relative flex cursor-pointer items-center gap-3 rounded-lg border border-slate-200 px-4 py-3 transition"
                        :class="mode === 'offline' ? 'border-primary bg-primary/5' : 'hover:bg-slate-50'">
                        <input type="radio" name="payment_mode" value="offline" x-model="mode" class="sr-only" />
                        <i class="fi fi-rr-bank text-primary leading-none"></i>
                        <span class="text-sm font-medium text-slate-900">Bank Transfer (UTR)</span>
                    </label>
                </div>
            </div>

            <div x-show="mode === 'offline'" x-cloak class="space-y-4">
                <div>
                    <label class="text-sm font-medium text-slate-700">UTR / Reference number</label>
                    <input type="text" name="utr_number" placeholder="Enter UTR number"
                        class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 outline-none transition focus:border-primary focus:ring-4 focus:ring-blue-100" />
                </div>
                <div>
                    <label class="text-sm font-medium text-slate-700">Payment proof (optional)</label>
                    <input type="file" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf"
                        class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3.5 py-2 text-sm text-slate-600 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-sm file:font-semibold" />
                    <p class="mt-1 text-xs text-slate-400">JPG, PNG or PDF · max 2 MB</p>
                </div>
            </div>

            <button type="submit"
                class="mt-2 flex w-full items-center justify-center gap-2 rounded-lg bg-primary px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-primaryLight">
                <i class="fi fi-rr-check text-base leading-none"></i>
                <span x-text="mode === 'online' ? 'Proceed to Payment' : 'Submit for Approval'"></span>
            </button>
        </form>
    </div>
</div>

@if(session('open_razorpay'))
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
(function() {
    var options = {
        key: "{{ $razorpayKey }}",
        amount: {{ session('razorpay_amount', 0) * 100 }},
        currency: "INR",
        name: "Engineers Clinic",
        description: "Internship Seat Purchase — {{ $course->title }}",
        order_id: "{{ session('razorpay_order_id') }}",
        handler: function(response) {
            fetch("{{ route('college.internships.purchase.verify', $course->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '',
                },
                body: JSON.stringify(response)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) { window.location = data.redirect_url; }
            });
        },
        prefill: { email: "{{ $college->user?->email ?? '' }}" },
        theme: { color: "#7C5CFC" }
    };
    new Razorpay(options).open();
})();
</script>
@endif
@endsection
