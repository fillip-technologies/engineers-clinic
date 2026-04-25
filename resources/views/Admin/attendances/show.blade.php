<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Attendance Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $attendance->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Course:</strong> {{ $attendance->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Date:</strong> {{ $attendance->date }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $attendance->status }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $attendance->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('attendances.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>