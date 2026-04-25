<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Permissions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Role Permissions</h1>
        <a href="{{ route('admin.role-permissions.create') }}" class="btn btn-primary mb-3">Create New Role Permission</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Role</th>
                    <th>Permission</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rolePermissions as $rp)
                <tr>
                    <td>{{ $rp->id }}</td>
                    <td>{{ $rp->role->name ?? 'N/A' }}</td>
                    <td>{{ $rp->permission->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('admin.role-permissions.show', $rp->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.role-permissions.edit', $rp->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.role-permissions.destroy', $rp->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
