<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Student Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>User:</strong> {{ $student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>College:</strong> {{ $student->college->college_name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Course:</strong> {{ $student->course_name }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $student->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>