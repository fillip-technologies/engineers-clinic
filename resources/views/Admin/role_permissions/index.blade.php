@extends('Admin.layouts.layout')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand">Access Control</p>
                <h1 class="mt-2 text-3xl font-semibold tracking-tight text-textPrimary">Role Permissions</h1>
                <p class="mt-2 text-sm text-textSecondary">
                    Manage which permissions are assigned to each platform role.
                </p>
            </div>

            <a href="{{ route('admin.role-permissions.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-brand to-secondary px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand/20 transition hover:-translate-y-0.5 hover:shadow-xl hover:shadow-brand/25">
                <i class="fi fi-rr-plus leading-none"></i>
                Create Role Permission
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-hidden rounded-[1.75rem] border border-white/75 bg-white/75 shadow-xl shadow-brand/5 backdrop-blur-xl">
            <div class="border-b border-glassBorder px-5 py-4">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-textPrimary">Permission Matrix</h2>
                        <p class="mt-1 text-sm text-textMuted">{{ $rolePermissions->count() }} assignments found</p>
                    </div>
                    <div class="hidden rounded-2xl bg-bgDark px-4 py-2 text-sm font-semibold text-textSecondary sm:block">
                        Admin Access
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-glassBorder">
                    <thead class="bg-bgDark/70">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">ID</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Role</th>
                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Permission</th>
                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-[0.16em] text-textMuted">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-glassBorder bg-white/60">
                        @forelse($rolePermissions as $rp)
                            <tr class="transition hover:bg-bgDark/70">
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-textPrimary">
                                    #{{ $rp->id }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <span class="inline-flex rounded-full bg-brandSoft px-3 py-1 text-sm font-semibold text-brand">
                                        {{ $rp->role->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-textSecondary">
                                    {{ $rp->permission->name ?? 'N/A' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.role-permissions.show', $rp->id) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-sky-50 text-sky-600 transition hover:bg-sky-100"
                                            aria-label="View role permission">
                                            <i class="fi fi-rr-eye leading-none"></i>
                                        </a>
                                        <a href="{{ route('admin.role-permissions.edit', $rp->id) }}"
                                            class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition hover:bg-amber-100"
                                            aria-label="Edit role permission">
                                            <i class="fi fi-rr-edit leading-none"></i>
                                        </a>
                                        <form action="{{ route('admin.role-permissions.destroy', $rp->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition hover:bg-rose-100"
                                                aria-label="Delete role permission"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fi fi-rr-trash leading-none"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-brandSoft text-brand">
                                        <i class="fi fi-rr-key text-xl leading-none"></i>
                                    </div>
                                    <p class="mt-4 font-semibold text-textPrimary">No role permissions yet</p>
                                    <p class="mt-1 text-sm text-textMuted">Create the first assignment to start building access rules.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
