@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Edit Payment</h3>
                    <p class="mt-1 text-sm text-gray-500">Update the student, course, amount, or status for this payment.</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                    Back to Payments
                </a>
            </div>
        </div>

        <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4">
                @include('Admin.payments._form', ['payment' => $payment])
            </div>
        </form>
    </div>
</div>
@endsection
