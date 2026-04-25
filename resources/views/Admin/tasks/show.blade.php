<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Task Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $task->title }}</h5>
                <p class="card-text"><strong>Course:</strong> {{ $task->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Description:</strong> {{ $task->description }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $task->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>