<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Quiz Results</h1>
        <a href="{{ route('quiz-results.create') }}" class="btn btn-primary mb-3">Create New Quiz Result</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Quiz</th>
                    <th>Score</th>
                    <th>Passed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quizResults as $qr)
                <tr>
                    <td>{{ $qr->id }}</td>
                    <td>{{ $qr->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $qr->quiz->title ?? 'N/A' }}</td>
                    <td>{{ $qr->score }}</td>
                    <td>{{ $qr->passed ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('quiz-results.show', $qr->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('quiz-results.edit', $qr->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('quiz-results.destroy', $qr->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>