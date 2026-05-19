<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Certificate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Certificate Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $certificate->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Course:</strong> {{ $certificate->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Issued Date:</strong> {{ $certificate->issued_date }}</p>
                <p class="card-text"><strong>Certificate URL:</strong> {{ $certificate->certificate_url }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $certificate->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>