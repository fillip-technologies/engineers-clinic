@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Payment Details</h3>
                    <p class="mt-1 text-sm text-gray-500">Review payment status and linked enrollment.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.payments.edit', $payment) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                        Edit
                    </a>
                    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $payment->student->user->name ?? 'N/A' }}</dd>
                    <dd class="mt-1 text-xs text-gray-500">{{ $payment->student->user->email ?? 'No email' }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Course</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $payment->course->title ?? 'N/A' }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Amount</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">${{ number_format($payment->amount, 2) }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Status</dt>
                    <dd class="mt-2">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Payment Date</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $payment->payment_date?->format('M d, Y h:i A') ?? '-' }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Created</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $payment->created_at?->format('M d, Y h:i A') }}</dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
