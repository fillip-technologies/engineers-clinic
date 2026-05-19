@if($errors->any())
    <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-semibold">Please fix the form errors.</p>
        <ul class="mt-2 list-disc pl-5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ $action }}" method="POST" class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="space-y-5">
        <div>
            <label for="course_id" class="text-sm font-semibold text-gray-700">Course</label>
            <select name="course_id" id="course_id" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $quiz->course_id ?? null) == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="title" class="text-sm font-semibold text-gray-700">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $quiz->title ?? '') }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
        </div>

        <div>
            <label for="total_marks" class="text-sm font-semibold text-gray-700">Total Marks</label>
            <input type="number" name="total_marks" id="total_marks" min="1" value="{{ old('total_marks', $quiz->total_marks ?? '') }}" required class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:border-brand focus:outline-none focus:ring-2 focus:ring-brand/20">
        </div>
    </div>

    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Cancel</a>
        <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brandLight">
            {{ $buttonText }}
        </button>
    </div>
</form>
