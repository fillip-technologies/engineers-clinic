<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Course Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $course->title }}</h5>
                <p class="card-text"><strong>Description:</strong> {{ $course->description }}</p>
                <p class="card-text"><strong>Duration:</strong> {{ $course->duration_months }} months</p>
                <p class="card-text"><strong>Fee:</strong> ${{ number_format($course->fee, 2) }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $course->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>