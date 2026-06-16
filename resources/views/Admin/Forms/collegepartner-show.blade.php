@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-3xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Partnership Discussion #{{ $partnership->id }}</h3>
                <a href="{{ route('admin.counselling.partner') }}" class="text-sm text-indigo-600 hover:text-indigo-900">&larr; Back to list</a>
            </div>
        </div>

        <dl class="grid grid-cols-1 gap-6 px-6 py-6 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Full Name</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->full_name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Institution</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->institution_name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Official Email</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->official_email }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Phone</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->phone }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Designation</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->designation ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Department / Stream</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->department_stream ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Number of Students</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->number_of_students ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Submitted At</dt>
                <dd class="mt-1 text-sm text-gray-900">{{ $partnership->created_at->format('d M Y h:i A') }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-medium tracking-wider text-gray-500 uppercase">Message</dt>
                <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $partnership->message }}</dd>
            </div>
        </dl>

        <div class="flex justify-end px-6 py-4 border-t border-gray-200">
            <form action="{{ route('admin.partnerships.destroy', $partnership) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this partnership request?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
