@extends('Admin.layouts.layout')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-brand">Quizzes</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-950">Create Quiz</h1>
            <p class="mt-1 text-sm text-gray-500">Attach a quiz to a course, add questions, and define the total marks.</p>
        </div>
        <a href="{{ route('admin.quizzes.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">
            <i class="fi fi-rr-arrow-small-left"></i>
            Back
        </a>
    </div>

    @include('Admin.quizzes.partials.form', [
        'action' => route('admin.quizzes.store'),
        'method' => 'POST',
        'buttonText' => 'Create Quiz',
    ])
</div>
@endsection
