<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Student</h1>
        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $student->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="college_id" class="form-label">College</label>
                <select name="college_id" id="college_id" class="form-control" required>
                    @foreach($colleges as $college)
                        <option value="{{ $college->id }}" {{ $student->college_id == $college->id ? 'selected' : '' }}>{{ $college->college_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="course_name" class="form-label">Course Name</label>
                <input type="text" name="course_name" id="course_name" class="form-control" value="{{ $student->course_name }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>