<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Role Permission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Role Permission</h1>
        <form action="{{ route('admin.role-permissions.update', $rolePermission->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="role_id" class="form-label">Role</label>
                <select name="role_id" id="role_id" class="form-control" required>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $rolePermission->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="permission_id" class="form-label">Permission</label>
                <select name="permission_id" id="permission_id" class="form-control" required>
                    @foreach($permissions as $permission)
                        <option value="{{ $permission->id }}" {{ $rolePermission->permission_id == $permission->id ? 'selected' : '' }}>{{ $permission->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
