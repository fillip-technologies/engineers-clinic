@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-5xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Enrollment Details</h3>
                    <p class="mt-1 text-sm text-gray-500">Review student course access and progress.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                        Edit
                    </a>
                    <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <dl class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Student</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $enrollment->student->user->name ?? 'N/A' }}</dd>
                    <dd class="mt-1 text-xs text-gray-500">{{ $enrollment->student->user->email ?? 'No email' }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Course</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $enrollment->course->title ?? 'N/A' }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Enrollment Date</dt>
                    <dd class="mt-2 text-sm font-medium text-gray-900">{{ $enrollment->enrollment_date?->format('M d, Y') }}</dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Status</dt>
                    <dd class="mt-2">
                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-full {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </dd>
                </div>
                <div class="p-4 rounded-lg bg-gray-50 md:col-span-2">
                    <dt class="text-xs font-semibold tracking-wide text-gray-500 uppercase">Progress</dt>
                    <dd class="mt-3">
                        <div class="flex items-center gap-4">
                            <div class="flex-1 h-2 overflow-hidden bg-white rounded-full">
                                <div class="h-2 bg-indigo-600 rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ $enrollment->progress }}%</span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
@endsection
