<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Student Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Create New Student Task</h1>
        <form action="{{ route('admin.student-tasks.store') }}" method="POST">
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
                <label for="task_id" class="form-label">Task</label>
                <select name="task_id" id="task_id" class="form-control" required>
                    <option value="">Select Task</option>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}">{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Create</button>
            <a href="{{ route('admin.student-tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
