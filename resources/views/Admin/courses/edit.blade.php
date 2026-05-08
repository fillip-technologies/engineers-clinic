@extends('Admin.layouts.layout')

@section('content')
<div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="overflow-hidden bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Edit Course: {{ $course->title }}</h3>
                <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-4 h-4 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Course
                </a>
            </div>
        </div>

        <form action="{{ route('admin.courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 space-y-6">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                        <input type="text" name="title" id="title" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('title') border-red-300 @enderror"
                               value="{{ old('title', $course->title) }}" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Slug *</label>
                        <input type="text" name="slug" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('slug') border-red-300 @enderror"
                               value="{{ old('slug', $course->slug) }}" required>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700">Level *</label>
                        <select name="level" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('level') border-red-300 @enderror" required>
                            <option value="Beginner" {{ old('level', $course->level) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="Intermediate" {{ old('level', $course->level) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="Advanced" {{ old('level', $course->level) == 'Advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category *</label>
                        <input type="text" name="category" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('category') border-red-300 @enderror"
                               value="{{ old('category', $course->category) }}" required>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_months" class="block text-sm font-medium text-gray-700">Duration (Months) *</label>
                        <input type="number" name="duration_months" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('duration_months') border-red-300 @enderror"
                               value="{{ old('duration_months', $course->duration_months) }}" required min="1">
                        @error('duration_months')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="fee" class="block text-sm font-medium text-gray-700">Fee ($) *</label>
                        <input type="number" step="0.01" name="fee" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('fee') border-red-300 @enderror"
                               value="{{ old('fee', $course->fee) }}" required min="0">
                        @error('fee')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Image URL</label>
                    <input type="url" name="image" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('image') border-red-300 @enderror"
                           value="{{ old('image', $course->image) }}" placeholder="https://example.com/image.jpg">
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="hero_badge" class="block text-sm font-medium text-gray-700">Hero Badge</label>
                    <input type="text" name="hero_badge" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('hero_badge') border-red-300 @enderror"
                           value="{{ old('hero_badge', $course->hero_badge) }}">
                    @error('hero_badge')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="career_path" class="block text-sm font-medium text-gray-700">Career Path</label>
                    <input type="text" name="career_path" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('career_path') border-red-300 @enderror"
                           value="{{ old('career_path', $course->career_path) }}">
                    @error('career_path')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" rows="4" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="program_overview" class="block text-sm font-medium text-gray-700">Program Overview (JSON)</label>
                        <textarea name="program_overview" rows="6" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('program_overview') border-red-300 @enderror"
                                  placeholder='[{"title":"Module 1","description":"Description here"}]'>{{ old('program_overview', is_array($course->program_overview) ? json_encode($course->program_overview, JSON_PRETTY_PRINT) : $course->program_overview) }}</textarea>
                        @error('program_overview')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="outcome" class="block text-sm font-medium text-gray-700">Learning Outcomes (JSON)</label>
                        <textarea name="outcome" rows="6" class="block w-full mt-1 font-mono text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('outcome') border-red-300 @enderror"
                                  placeholder='["Outcome 1","Outcome 2","Outcome 3"]'>{{ old('outcome', is_array($course->outcome) ? json_encode($course->outcome, JSON_PRETTY_PRINT) : $course->outcome) }}</textarea>
                        @error('outcome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.courses.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Course
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
