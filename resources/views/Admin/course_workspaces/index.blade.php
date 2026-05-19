@extends('Admin.layouts.layout')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-medium text-brand">Learning Ops</p>
            <h1 class="mt-1 text-2xl font-semibold text-gray-950">Course Workspaces</h1>
            <p class="mt-1 text-sm text-gray-500">Build the project workspace students see inside each course.</p>
        </div>
        <a href="{{ route('admin.course-workspaces.create') }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brandLight">
            <i class="fi fi-rr-plus"></i>
            Create Workspace
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Workspace</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Course</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Content</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Progress</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($workspaces as $workspace)
                        <tr class="hover:bg-gray-50/70">
                            <td class="px-5 py-4">
                                <div class="font-semibold text-gray-950">{{ $workspace->title }}</div>
                                <div class="mt-1 max-w-sm truncate text-sm text-gray-500">{{ $workspace->headline ?: $workspace->next_milestone }}</div>
                            </td>
                            <td class="px-5 py-4 text-sm text-gray-700">{{ $workspace->course->title ?? 'No course' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2 text-xs font-medium">
                                    <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-indigo-700">{{ $workspace->steps->count() }} steps</span>
                                    <span class="rounded-full bg-sky-50 px-2.5 py-1 text-sky-700">{{ $workspace->resources->count() }} resources</span>
                                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-amber-700">{{ $workspace->goals->count() }} goals</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-2 w-28 rounded-full bg-gray-100">
                                        <div class="h-2 rounded-full bg-brand" style="width: {{ min(100, max(0, $workspace->progress)) }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">{{ $workspace->progress }}%</span>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.course-workspaces.show', $workspace) }}" class="rounded-lg p-2 text-indigo-600 transition hover:bg-indigo-50" title="View">
                                        <i class="fi fi-rr-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.course-workspaces.edit', $workspace) }}" class="rounded-lg p-2 text-amber-600 transition hover:bg-amber-50" title="Edit">
                                        <i class="fi fi-rr-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.course-workspaces.destroy', $workspace) }}" method="POST" onsubmit="return confirm('Delete this workspace and all related steps, resources, goals, and progress?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg p-2 text-red-600 transition hover:bg-red-50" title="Delete">
                                            <i class="fi fi-rr-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                                    <i class="fi fi-rr-briefcase text-xl"></i>
                                </div>
                                <p class="mt-3 font-semibold text-gray-950">No workspaces yet</p>
                                <p class="mt-1 text-sm text-gray-500">Create the first workspace for a course.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{ $workspaces->links() }}
</div>
@endsection
