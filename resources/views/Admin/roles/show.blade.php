<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Role</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Role Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Name: {{ $role->name }}</h5>
                <p class="card-text">ID: {{ $role->id }}</p>
                <p class="card-text">Created: {{ $role->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('roles.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>