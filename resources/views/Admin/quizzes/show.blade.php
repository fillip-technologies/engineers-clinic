@extends('Admin.layouts.layout')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
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
                <dt class="text-xs font-semibold uppercase tracking-wide text-gray-400">Questions</dt>
                <dd class="mt-2 text-sm font-semibold text-gray-950">{{ $quiz->questions->count() }}</dd>
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

    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-gray-950">Questions</h2>
            <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">{{ $quiz->questions->count() }} total</span>
        </div>

        <div class="mt-5 space-y-4">
            @forelse($quiz->questions as $question)
                <div class="rounded-lg border border-gray-200 bg-gray-50/60 p-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <p class="text-sm font-semibold text-gray-950">{{ $loop->iteration }}. {{ $question->question_text }}</p>
                        <span class="shrink-0 rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">{{ $question->marks }} marks</span>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-2">
                        @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                            <div class="rounded-lg border {{ $question->correct_option === $key ? 'border-emerald-200 bg-emerald-50 text-emerald-800' : 'border-gray-200 bg-white text-gray-700' }} px-3 py-2 text-sm">
                                <span class="font-semibold">{{ $label }}.</span>
                                {{ $question->{'option_' . $key} }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 px-4 py-8 text-center">
                    <p class="font-semibold text-gray-950">No questions added</p>
                    <p class="mt-1 text-sm text-gray-500">Edit this quiz to add questions manually or by JSON upload.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
