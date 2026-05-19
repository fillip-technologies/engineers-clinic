<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Enrollment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Enrollment Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $enrollment->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Course:</strong> {{ $enrollment->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Enrollment Date:</strong> {{ $enrollment->enrollment_date }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $enrollment->status }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $enrollment->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>