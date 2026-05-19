<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Student Task</h1>
        <form action="{{ route('admin.student-tasks.update', $studentTask->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="student_id" class="form-label">Student</label>
                <select name="student_id" id="student_id" class="form-control" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ $studentTask->student_id == $student->id ? 'selected' : '' }}>{{ $student->user->name ?? 'N/A' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="task_id" class="form-label">Task</label>
                <select name="task_id" id="task_id" class="form-control" required>
                    @foreach($tasks as $task)
                        <option value="{{ $task->id }}" {{ $studentTask->task_id == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="pending" {{ $studentTask->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ $studentTask->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.student-tasks.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
