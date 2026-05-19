<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Student Tasks</h1>
        <a href="{{ route('admin.student-tasks.create') }}" class="btn btn-primary mb-3">Create New Student Task</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Task</th>
                    <th>Status</th>
                    <th>Completed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentTasks as $st)
                <tr>
                    <td>{{ $st->id }}</td>
                    <td>{{ $st->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $st->task->title ?? 'N/A' }}</td>
                    <td>{{ $st->status }}</td>
                    <td>{{ $st->completed_at }}</td>
                    <td>
                        <a href="{{ route('admin.student-tasks.show', $st->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.student-tasks.edit', $st->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.student-tasks.destroy', $st->id) }}" method="POST" style="display:inline;">
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
