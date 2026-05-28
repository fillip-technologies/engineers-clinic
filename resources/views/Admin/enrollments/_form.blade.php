@php
    $isEdit = isset($enrollment);
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
        <select name="student_id" id="student_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('student_id') border-red-300 @enderror">
            <option value="">Select student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected((string) old('student_id', $enrollment->student_id ?? '') === (string) $student->id)>
                    {{ $student->user->name ?? 'Student #' . $student->id }}{{ $student->user?->email ? ' - ' . $student->user->email : '' }}
                </option>
            @endforeach
        </select>
        @error('student_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="course_id" class="block text-sm font-medium text-gray-700">Course *</label>
        <select name="course_id" id="course_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('course_id') border-red-300 @enderror">
            <option value="">Select course</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" @selected((string) old('course_id', $enrollment->course_id ?? '') === (string) $course->id)>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="enrollment_date" class="block text-sm font-medium text-gray-700">Enrollment Date *</label>
        <input type="date" name="enrollment_date" id="enrollment_date" required
            value="{{ old('enrollment_date', isset($enrollment) ? $enrollment->enrollment_date?->format('Y-m-d') : now()->format('Y-m-d')) }}"
            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('enrollment_date') border-red-300 @enderror">
        @error('enrollment_date')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
        <select name="status" id="status" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
            <option value="ongoing" @selected(old('status', $enrollment->status ?? 'ongoing') === 'ongoing')>Ongoing</option>
            <option value="completed" @selected(old('status', $enrollment->status ?? '') === 'completed')>Completed</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="progress" class="block text-sm font-medium text-gray-700">Progress *</label>
        <div class="flex items-center gap-4 mt-1">
            <input type="range" name="progress" id="progress" min="0" max="100"
                value="{{ old('progress', $enrollment->progress ?? 0) }}"
                oninput="document.getElementById('progress-value').textContent = this.value + '%'"
                class="w-full accent-indigo-600">
            <span id="progress-value" class="w-14 text-sm font-semibold text-gray-700">{{ old('progress', $enrollment->progress ?? 0) }}%</span>
        </div>
        @error('progress')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3 px-6 py-4 mt-6 -mx-6 -mb-4 border-t border-gray-200 bg-gray-50">
    <a href="{{ route('admin.enrollments.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
        Cancel
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
        {{ $isEdit ? 'Update Enrollment' : 'Create Enrollment' }}
    </button>
</div>
