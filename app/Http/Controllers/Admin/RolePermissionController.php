<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RolePermission;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $rolePermissions = RolePermission::with('role', 'permission')->get();
        return view('admin.role_permissions.index', compact('rolePermissions'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.role_permissions.create', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        RolePermission::create($request->all());

        return redirect()->route('admin.role-permissions.index')->with('success', 'Role permission created successfully.');
    }

    public function show(RolePermission $rolePermission)
    {
        $rolePermission->load('role', 'permission');
        return view('admin.role_permissions.show', compact('rolePermission'));
    }

    public function edit(RolePermission $rolePermission)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.role_permissions.edit', compact('rolePermission', 'roles', 'permissions'));
    }

    public function update(Request $request, RolePermission $rolePermission)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);

        $rolePermission->update($request->all());

        return redirect()->route('admin.role-permissions.index')->with('success', 'Role permission updated successfully.');
    }

    public function destroy(RolePermission $rolePermission)
    {
        $rolePermission->delete();

        return redirect()->route('admin.role-permissions.index')->with('success', 'Role permission deleted successfully.');
    }
}
