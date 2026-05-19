@extends('Admin.layouts.layout')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-brand">Quiz Details</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-950">{{ $quiz->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $quiz->course->title ?? 'No course assigned' }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.quizzes.index') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Back</a>
            <a href="{{ route('admin.quizzes.edit', $quiz->id) }}" class="rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brandLight">Edit</a>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <dl class="grid gap-5 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Course</dt>
                <dd class="mt-2 text-sm font-semibold text-gray-950">{{ $quiz->course->title ?? 'N/A' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Total Marks</dt>
                <dd class="mt-2 text-sm font-semibold text-gray-950">{{ $quiz->total_marks }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Created</dt>
                <dd class="mt-2 text-sm font-semibold text-gray-950">{{ $quiz->created_at?->format('M d, Y') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Updated</dt>
                <dd class="mt-2 text-sm font-semibold text-gray-950">{{ $quiz->updated_at?->format('M d, Y') }}</dd>
            </div>
        </dl>
    </div>
</div>
@endsection
