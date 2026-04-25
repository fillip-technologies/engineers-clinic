<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Create New Quiz Result</h1>
        <form action="{{ route('quiz-results.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control" required>
                    <option value="">Select Student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}">{{ $student->user->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quiz_id" class="form-label">Quiz</label>
                <select name="quiz_id" id="quiz_id" class="form-control" required>
                    <option value="">Select Quiz</option>
                    @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}">{{ $quiz->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="score" class="form-label">Score</label>
                <input type="number" name="score" id="score" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="passed" class="form-label">Passed</label>
                <select name="passed" id="passed" class="form-control" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('quiz-results.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>