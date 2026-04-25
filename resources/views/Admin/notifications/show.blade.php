<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Notification Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>User:</strong> {{ $notification->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Message:</strong> {{ $notification->message }}</p>
                <p class="card-text"><strong>Read:</strong> {{ $notification->is_read ? 'Yes' : 'No' }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $notification->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('notifications.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>