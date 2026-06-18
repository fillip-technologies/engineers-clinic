@extends('Admin.layouts.layout')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold text-textPrimary">College Payment Transactions</h1>
    <p class="text-textSecondary mt-1">Review and approve seat-purchase payments from colleges.</p>
</div>

@if(session('success'))
    <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-700">{{ session('success') }}</div>
@endif

<!-- Pending review -->
<h2 class="mb-3 text-lg font-semibold text-textPrimary">Pending Review ({{ $pending->count() }})</h2>

@if($pending->isEmpty())
    <div class="mb-8 rounded-xl border border-dashed border-gray-200 py-10 text-center text-sm text-textMuted">
        No transactions awaiting review.
    </div>
@else
    <div class="mb-8 space-y-4">
        @foreach($pending as $tx)
        <div class="rounded-xl border border-glassBorder bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="font-semibold text-textPrimary">{{ $tx->college?->college_name }}</p>
                    <p class="text-sm text-textSecondary">
                        Purpose: <strong>{{ ucfirst(str_replace('_', ' ', $tx->purpose)) }}</strong>
                        &bull; Amount: <strong>Rs. {{ number_format((float)$tx->amount, 2) }}</strong>
                        &bull; Mode: <strong>{{ ucfirst($tx->payment_mode) }}</strong>
                    </p>
                    @if($tx->utr_number)
                        <p class="mt-1 text-sm text-textSecondary">UTR: <strong>{{ $tx->utr_number }}</strong></p>
                    @endif
                    @if($tx->payment_proof_path)
                        <a href="{{ Storage::url($tx->payment_proof_path) }}" target="_blank"
                            class="mt-1 inline-flex items-center gap-1 text-xs font-semibold text-primary hover:underline">
                            <i class="fi fi-rr-paperclip"></i> View payment proof
                        </a>
                    @endif
                    @foreach($tx->internshipPurchases as $purchase)
                        <p class="mt-1 text-xs text-textMuted">
                            Internship: <strong>{{ $purchase->course?->title }}</strong>
                            &bull; {{ $purchase->seats_purchased }} seats @ Rs. {{ number_format((float)$purchase->price_per_seat, 2) }}
                        </p>
                    @endforeach
                    <p class="mt-1 text-xs text-textMuted">Submitted: {{ $tx->submitted_at?->format('M d, Y h:i A') }}</p>
                </div>

                <div class="flex flex-col gap-2 min-w-[220px]">
                    <form action="{{ route('admin.college-transactions.approve', $tx->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                            Approve
                        </button>
                    </form>

                    <form action="{{ route('admin.college-transactions.reject', $tx->id) }}" method="POST" class="space-y-2">
                        @csrf @method('PATCH')
                        <input type="text" name="rejection_reason" placeholder="Reason for rejection (required)"
                            class="w-full rounded-lg border border-glassBorder px-3 py-2 text-xs outline-none focus:border-primary"
                            required />
                        <button type="submit"
                            class="w-full rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700">
                            Reject
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif

<!-- Recent resolved -->
<h2 class="mb-3 text-lg font-semibold text-textPrimary">Recently Reviewed</h2>

<div class="overflow-hidden rounded-xl border border-glassBorder bg-white">
    <table class="min-w-full">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">College</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Purpose</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Amount</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Mode</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Status</th>
                <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Reviewed</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($recent as $tx)
            @php
                $statusClass = match($tx->status) {
                    'approved' => 'bg-emerald-100 text-emerald-700',
                    'rejected' => 'bg-red-100 text-red-700',
                    default => 'bg-gray-100 text-gray-700',
                };
            @endphp
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-4 font-medium text-textPrimary">{{ $tx->college?->college_name }}</td>
                <td class="px-5 py-4 text-sm text-textSecondary">{{ ucfirst(str_replace('_', ' ', $tx->purpose)) }}</td>
                <td class="px-5 py-4 text-sm text-textSecondary">Rs. {{ number_format((float)$tx->amount, 2) }}</td>
                <td class="px-5 py-4 text-sm text-textSecondary">{{ ucfirst($tx->payment_mode) }}</td>
                <td class="px-5 py-4">
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClass }}">
                        {{ ucfirst($tx->status) }}
                    </span>
                    @if($tx->rejection_reason)
                        <p class="mt-1 text-xs text-red-500">{{ $tx->rejection_reason }}</p>
                    @endif
                </td>
                <td class="px-5 py-4 text-sm text-textMuted">{{ $tx->reviewed_at?->format('M d, Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-8 text-center text-textMuted">No reviewed transactions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
