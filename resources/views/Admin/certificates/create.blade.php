<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Create New Certificate</h1>
        <form action="{{ route('certificates.store') }}" method="POST">
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
                <label for="course_id" class="form-label">Course</label>
                <select name="course_id" id="course_id" class="form-control" required>
                    <option value="">Select Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="issued_date" class="form-label">Issued Date</label>
                <input type="date" name="issued_date" id="issued_date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="certificate_url" class="form-label">Certificate URL</label>
                <input type="text" name="certificate_url" id="certificate_url" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('certificates.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>