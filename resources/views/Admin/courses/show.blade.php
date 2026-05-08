@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Course Details: {{ $course->title }}</h3>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back
                    </a>
                    <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-600 border border-transparent rounded-md shadow-sm hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                        <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="px-6 py-4">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-2">
                        <h4 class="text-md font-semibold text-gray-900">Basic Information</h4>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->id }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Title</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->title }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Slug</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->slug }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Level</label>
                        <div class="mt-1">
                            <span class="inline-flex px-2 text-xs font-semibold leading-5 rounded-full
                                {{ $course->level == 'Beginner' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $course->level == 'Intermediate' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $course->level == 'Advanced' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ $course->level }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Category</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->category }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Duration</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->duration_months }} months</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Fee</label>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($course->fee, 2) }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Hero Badge</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->hero_badge ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Career Path</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->career_path ?? 'N/A' }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Image</label>
                        @if($course->image)
                            <a href="{{ $course->image }}" target="_blank" class="inline-flex items-center mt-1 text-sm text-indigo-600 hover:text-indigo-900">
                                View Image
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                            </a>
                        @else
                            <p class="mt-1 text-sm text-gray-500">N/A</p>
                        @endif
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Created At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Updated At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $course->updated_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-4">
                    <div class="border-b border-gray-200 pb-2">
                        <h4 class="text-md font-semibold text-gray-900">Description</h4>
                    </div>
                    <div class="prose max-w-none">
                        <p class="text-sm text-gray-700">{{ $course->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Program Overview -->
            @if($course->program_overview)
            <div class="mt-6">
                <div class="border-b border-gray-200 pb-2">
                    <h4 class="text-md font-semibold text-gray-900">Program Overview</h4>
                </div>
                <div class="mt-3 p-4 bg-gray-50 rounded-md">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($course->program_overview, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

            <!-- Learning Outcomes -->
            @if($course->outcome)
            <div class="mt-6">
                <div class="border-b border-gray-200 pb-2">
                    <h4 class="text-md font-semibold text-gray-900">Learning Outcomes</h4>
                </div>
                <div class="mt-3 p-4 bg-gray-50 rounded-md">
                    <pre class="text-sm text-gray-700 whitespace-pre-wrap">{{ json_encode($course->outcome, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
