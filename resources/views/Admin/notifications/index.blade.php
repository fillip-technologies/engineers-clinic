<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Notifications</h1>
        <a href="{{ route('admin.notifications.create') }}" class="btn btn-primary mb-3">Create New Notification</a>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Message</th>
                    <th>Read</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                <tr>
                    <td>{{ $notification->id }}</td>
                    <td>{{ $notification->user->name ?? 'N/A' }}</td>
                    <td>{{ substr($notification->message, 0, 50) }}{{ strlen($notification->message) > 50 ? '...' : '' }}</td>
                    <td>{{ $notification->is_read ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn btn-sm btn-info">View</a>
                        <a href="{{ route('admin.notifications.edit', $notification->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.notifications.destroy', $notification->id) }}" method="POST" style="display:inline;">
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