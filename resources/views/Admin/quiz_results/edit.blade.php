<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quiz Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Quiz Result</h1>
        <form action="{{ route('admin.quiz-results.update', $quizResult->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $quizResult->student_id == $student->id ? 'selected' : '' }}>{{ $student->user->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quiz_id" class="form-label">Quiz</label>
                <select name="quiz_id" id="quiz_id" class="form-control" required>
                    @foreach($quizzes as $quiz)
                        <option value="{{ $quiz->id }}" {{ $quizResult->quiz_id == $quiz->id ? 'selected' : '' }}>{{ $quiz->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="score" class="form-label">Score</label>
                <input type="number" name="score" id="score" class="form-control" value="{{ $quizResult->score }}" required>
            </div>
            <div class="mb-3">
                <label for="passed" class="form-label">Passed</label>
                <select name="passed" id="passed" class="form-control" required>
                    <option value="1" {{ $quizResult->passed ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$quizResult->passed ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.quiz-results.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
