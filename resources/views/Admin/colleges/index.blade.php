<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colleges</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Colleges</h1>
        <a href="{{ route('colleges.create') }}" class="btn btn-primary mb-3">Create New College</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>College Name</th>
                    <th>Address</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($colleges as $college)
                <tr>
                    <td>{{ $college->id }}</td>
                    <td>{{ $college->user->name ?? 'N/A' }}</td>
                    <td>{{ $college->college_name }}</td>
                    <td>{{ $college->address }}</td>
                    <td>{{ $college->contact_number }}</td>
                    <td>
                        <a href="{{ route('colleges.show', $college->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('colleges.edit', $college->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('colleges.destroy', $college->id) }}" method="POST" style="display:inline;">
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