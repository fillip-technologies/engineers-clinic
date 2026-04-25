<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>College Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">College Name: {{ $college->college_name }}</h5>
                <p class="card-text"><strong>User:</strong> {{ $college->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Address:</strong> {{ $college->address }}</p>
                <p class="card-text"><strong>Contact:</strong> {{ $college->contact_number }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $college->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('colleges.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>