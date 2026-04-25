<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Student Task Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $studentTask->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Task:</strong> {{ $studentTask->task->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $studentTask->status }}</p>
                <p class="card-text"><strong>Completed At:</strong> {{ $studentTask->completed_at }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $studentTask->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('student-tasks.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>