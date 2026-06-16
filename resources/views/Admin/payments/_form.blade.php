@php
    $isEdit = isset($payment);
@endphp

<div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <div>
        <label for="student_id" class="block text-sm font-medium text-gray-700">Student *</label>
        <select name="student_id" id="student_id" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('student_id') border-red-300 @enderror">
            <option value="">Select student</option>
            @foreach($students as $student)
                <option value="{{ $student->id }}" @selected((string) old('student_id', $payment->student_id ?? '') === (string) $student->id)>
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
                <option value="{{ $course->id }}" @selected((string) old('course_id', $payment->course_id ?? '') === (string) $course->id)>
                    {{ $course->title }}
                </option>
            @endforeach
        </select>
        @error('course_id')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="amount" class="block text-sm font-medium text-gray-700">Amount *</label>
        <input type="number" name="amount" id="amount" step="0.01" min="0" required
            value="{{ old('amount', $payment->amount ?? '') }}"
            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('amount') border-red-300 @enderror">
        @error('amount')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
        <select name="status" id="status" required class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
            <option value="pending" @selected(old('status', $payment->status ?? 'pending') === 'pending')>Pending</option>
            <option value="completed" @selected(old('status', $payment->status ?? '') === 'completed')>Completed</option>
            <option value="failed" @selected(old('status', $payment->status ?? '') === 'failed')>Failed</option>
        </select>
        @error('status')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center justify-end gap-3 px-6 py-4 mt-6 -mx-6 -mb-4 border-t border-gray-200 bg-gray-50">
    <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
        Cancel
    </a>
    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
        {{ $isEdit ? 'Update Payment' : 'Create Payment' }}
    </button>
</div>
