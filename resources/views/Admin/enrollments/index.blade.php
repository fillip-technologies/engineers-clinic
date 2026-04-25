<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Enrollments</h1>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary mb-3">Create New Enrollment</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Enrollment Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->id }}</td>
                    <td>{{ $enrollment->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                    <td>{{ $enrollment->enrollment_date }}</td>
                    <td>{{ $enrollment->status }}</td>
                    <td>
                        <a href="{{ route('enrollments.show', $enrollment->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('enrollments.edit', $enrollment->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" style="display:inline;">
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