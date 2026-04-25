<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Edit Notification</h1>
        <form action="{{ route('notifications.update', $notification->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="user_id" class="form-label">User</label>
                <select name="user_id" id="user_id" class="form-control" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $notification->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea name="message" id="message" class="form-control" required>{{ $notification->message }}</textarea>
            </div>
            <div class="mb-3">
                <label for="is_read" class="form-label">Read</label>
                <select name="is_read" id="is_read" class="form-control">
                    <option value="0" {{ !$notification->is_read ? 'selected' : '' }}>No</option>
                    <option value="1" {{ $notification->is_read ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('notifications.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>