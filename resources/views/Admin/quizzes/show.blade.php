<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Quiz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Quiz Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $quiz->title }}</h5>
                <p class="card-text"><strong>Course:</strong> {{ $quiz->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Total Marks:</strong> {{ $quiz->total_marks }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $quiz->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('quizzes.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>