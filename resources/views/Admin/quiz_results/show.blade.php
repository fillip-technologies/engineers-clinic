<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Quiz Result Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $quizResult->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Quiz:</strong> {{ $quizResult->quiz->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Score:</strong> {{ $quizResult->score }}</p>
                <p class="card-text"><strong>Passed:</strong> {{ $quizResult->passed ? 'Yes' : 'No' }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $quizResult->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('admin.quiz-results.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>
