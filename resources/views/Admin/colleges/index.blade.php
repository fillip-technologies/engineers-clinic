@extends('Admin.layouts.layout')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-textPrimary">Colleges</h1>
            <p class="text-textSecondary mt-1">Manage college accounts</p>
        </div>
        <a href="{{ route('admin.colleges.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primaryLight transition">
            + Create New College
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-glassBorder overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">College Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">UTR Approval</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($colleges as $college)
                @php
                    $statusClasses = [
                        'approved' => 'bg-green-100 text-green-700',
                        'rejected' => 'bg-red-100 text-red-700',
                        'pending' => 'bg-amber-100 text-amber-700',
                    ];
                    $paymentStatus = $college->payment_status ?? 'pending';
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-textPrimary">{{ $college->id }}</td>
                    <td class="px-6 py-4 text-textPrimary">{{ $college->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-textPrimary font-medium">{{ $college->college_name }}</td>
                    <td class="px-6 py-4 text-textSecondary">{{ $college->address }}</td>
                    <td class="px-6 py-4 text-textSecondary">{{ $college->contact_number }}</td>
                    <td class="px-6 py-4">
                        @if($college->payment_mode)
                            <div class="space-y-1">
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses[$paymentStatus] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                                <p class="text-xs font-medium text-textMuted">{{ ucfirst($college->payment_mode) }}</p>
                                @if($college->utr_number)
                                    <p class="text-xs text-textSecondary">UTR: <span class="font-semibold">{{ $college->utr_number }}</span></p>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-textMuted">Not submitted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($college->payment_mode === 'offline' && filled($college->utr_number))
                            @if($paymentStatus === 'approved')
                                <p class="text-sm font-semibold text-green-700">Approved</p>
                                <p class="text-xs text-textMuted">{{ $college->payment_reviewed_at?->format('M d, Y h:i A') }}</p>
                            @else
                                <div class="space-y-2">
                                    <form action="{{ route('admin.colleges.payment.approve', $college->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-lg bg-green-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-green-700">
                                            Approve UTR
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.colleges.payment.reject', $college->id) }}" method="POST" class="flex max-w-xs flex-col gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="payment_status" value="rejected">
                                        <input
                                            type="text"
                                            name="payment_rejection_reason"
                                            placeholder="Reason for rejection"
                                            class="rounded-lg border border-glassBorder px-3 py-2 text-xs outline-none focus:border-primary"
                                            required
                                        >
                                        <button type="submit" class="rounded-lg bg-red-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-red-700">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <span class="text-sm text-textMuted">No offline UTR</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.colleges.show', $college->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fi fi-rr-eye"></i>
                            </a>
                            <a href="{{ route('admin.colleges.edit', $college->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                <i class="fi fi-rr-pencil"></i>
                            </a>
                            <form action="{{ route('admin.colleges.destroy', $college->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-textMuted">No colleges found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
