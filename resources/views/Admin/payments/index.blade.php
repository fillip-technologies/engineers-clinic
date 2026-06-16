@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Payments</h3>
                    <p class="mt-1 text-sm text-gray-500">Track and manage student payments for courses.</p>
                </div>
                <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Payment
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 m-4 rounded-md bg-green-50">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Student</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Course</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Payment Date</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">#{{ $payment->id }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->student->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->student->user->email ?? 'No email' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $payment->course->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap">${{ number_format($payment->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $payment->payment_date?->format('M d, Y') ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    <a href="{{ route('admin.payments.edit', $payment) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this payment?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-sm text-center text-gray-500">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
