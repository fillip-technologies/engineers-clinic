@extends('Admin.layouts.layout')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-textPrimary">Colleges</h1>
            <p class="text-textSecondary mt-1">Manage college accounts</p>
        </div>
        <a href="{{ route('admin.colleges.create') }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primaryLight transition">
            + Create New College
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
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">User</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">College Name</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Contact</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-textMuted">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($colleges as $college)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 text-textPrimary">{{ $college->id }}</td>
                    <td class="px-6 py-4 text-textPrimary">{{ $college->user->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 text-textPrimary font-medium">{{ $college->college_name }}</td>
                    <td class="px-6 py-4 text-textSecondary">{{ $college->address }}</td>
                    <td class="px-6 py-4 text-textSecondary">{{ $college->contact_number }}</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.colleges.show', $college->id) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <i class="fi fi-rr-eye"></i>
                            </a>
                            <a href="{{ route('admin.colleges.edit', $college->id) }}" class="p-2 text-yellow-600 hover:bg-yellow-50 rounded-lg transition">
                                <i class="fi fi-rr-pencil"></i>
                            </a>
                            <form action="{{ route('admin.colleges.destroy', $college->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Are you sure?')">
                                    <i class="fi fi-rr-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-textMuted">No colleges found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection