<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Certificates</h1>
        <a href="{{ route('certificates.create') }}" class="btn btn-primary mb-3">Create New Certificate</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Student</th>
                    <th>Course</th>
                    <th>Issued Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificates as $certificate)
                <tr>
                    <td>{{ $certificate->id }}</td>
                    <td>{{ $certificate->student->user->name ?? 'N/A' }}</td>
                    <td>{{ $certificate->course->title ?? 'N/A' }}</td>
                    <td>{{ $certificate->issued_date }}</td>
                    <td>
                        <a href="{{ route('certificates.show', $certificate->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('certificates.edit', $certificate->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('certificates.destroy', $certificate->id) }}" method="POST" style="display:inline;">
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