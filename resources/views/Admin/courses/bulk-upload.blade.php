@extends('Admin.layouts.layout')

@section('content')
<div class="mx-auto max-w-5xl px-4 py-6 sm:px-6 lg:px-8">
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Bulk Upload Courses</h3>
                    <p class="mt-1 text-sm text-gray-500">Upload one or more course JSON files.</p>
                </div>
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                    Back to Courses
                </a>
            </div>
        </div>

        <form action="{{ route('admin.courses.bulk-upload.store') }}" method="POST" enctype="multipart/form-data" class="px-6 py-6">
            @csrf

            <div>
                <label for="json_files" class="block text-sm font-medium text-gray-700">JSON Files *</label>
                <input type="file" name="json_files[]" id="json_files" accept=".json,application/json" multiple required
                       class="mt-2 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('json_files') border-red-300 @enderror @error('json_files.*') border-red-300 @enderror">
                <p class="mt-2 text-xs text-gray-500">Each file should contain a single course object with at least a title. Existing courses are updated by slug.</p>
                @error('json_files')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('json_files.*')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Upload Courses
                </button>
            </div>
        </form>

        @if(session('results'))
            <div class="border-t border-gray-200 px-6 py-6">
                <h4 class="text-sm font-semibold uppercase tracking-wide text-gray-500">Upload Results</h4>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">File</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Course / Message</th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Slug</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach(session('results') as $result)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $result['file'] }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex rounded-full px-2 text-xs font-semibold leading-5 {{ $result['status'] === 'Success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $result['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $result['course'] ?? $result['message'] ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $result['slug'] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
