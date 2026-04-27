@extends('Admin.layouts.layout')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-textPrimary">Roles</h1>
            <p class="text-textSecondary mt-1">Manage user roles</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primaryLight transition">
            + Create New Role
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-glassBorder overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($roles as $role)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-textPrimary">{{ $role->id }}</td>
                    <td class="px-6 py-4 text-textPrimary font-medium">{{ $role->name }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.roles.show', $role->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fi fi-rr-eye"></i>
                            </a>
                            <a href="{{ route('admin.roles.edit', $role->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                <i class="fi fi-rr-pencil"></i>
                            </a>
                            <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection