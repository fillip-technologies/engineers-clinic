<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Role Permission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Role Permission Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Role:</strong> {{ $rolePermission->role->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Permission:</strong> {{ $rolePermission->permission->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $rolePermission->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>
