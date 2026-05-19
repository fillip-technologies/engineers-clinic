@extends('Admin.layouts.layout')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-brand">{{ $courseWorkspace->course->title ?? 'Course workspace' }}</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-950">{{ $courseWorkspace->title }}</h1>
            <p class="mt-1 text-sm text-gray-500">{{ $courseWorkspace->headline ?: $courseWorkspace->summary }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.course-workspaces.index') }}" class="rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50">Back</a>
            <a href="{{ route('admin.course-workspaces.edit', $courseWorkspace) }}" class="rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brandLight">Edit Workspace</a>
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Progress</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950">{{ $courseWorkspace->progress }}%</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Steps</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950">{{ $courseWorkspace->steps->count() }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Resources</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950">{{ $courseWorkspace->resources->count() }}</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">Goals</p>
            <p class="mt-2 text-2xl font-semibold text-gray-950">{{ $courseWorkspace->goals->count() }}</p>
        </div>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="border-b border-gray-200 px-5 py-4">
            <h2 class="font-semibold text-gray-950">Workspace Steps</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($courseWorkspace->steps as $step)
                <div class="px-5 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <p class="font-semibold text-gray-950">{{ $step->step_no }}. {{ $step->title }}</p>
                            <p class="mt-1 text-sm text-gray-500">{{ $step->description }}</p>
                        </div>
                        <span class="w-fit rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">{{ $step->status }}</span>
                    </div>
                </div>
            @empty
                <p class="px-5 py-8 text-center text-sm text-gray-500">No steps added.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
