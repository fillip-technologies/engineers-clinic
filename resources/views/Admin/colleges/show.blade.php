<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View College</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>College Details</h1>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">College Name: {{ $college->college_name }}</h5>
                <p class="card-text"><strong>User:</strong> {{ $college->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Address:</strong> {{ $college->address }}</p>
                <p class="card-text"><strong>Contact:</strong> {{ $college->contact_number }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $college->created_at }}</p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title">Payment Details</h5>
                <p class="card-text"><strong>Mode:</strong> {{ $college->payment_mode ? ucfirst($college->payment_mode) : 'Not submitted' }}</p>
                <p class="card-text"><strong>Status:</strong> {{ ucfirst($college->payment_status ?? 'pending') }}</p>
                <p class="card-text"><strong>UTR Number:</strong> {{ $college->utr_number ?? 'N/A' }}</p>
                <p class="card-text"><strong>Submitted At:</strong> {{ $college->payment_submitted_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                <p class="card-text"><strong>Reviewed By:</strong> {{ $college->paymentReviewer->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Reviewed At:</strong> {{ $college->payment_reviewed_at?->format('M d, Y h:i A') ?? 'N/A' }}</p>
                @if($college->payment_rejection_reason)
                    <p class="card-text text-danger"><strong>Rejection Reason:</strong> {{ $college->payment_rejection_reason }}</p>
                @endif

                @if($college->payment_mode === 'offline' && filled($college->utr_number) && $college->payment_status !== 'approved')
                    <div class="d-flex flex-wrap gap-2">
                        <form action="{{ route('admin.colleges.payment.approve', $college->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success">Approve UTR</button>
                        </form>

                        <form action="{{ route('admin.colleges.payment.reject', $college->id) }}" method="POST" class="d-flex flex-wrap gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="payment_status" value="rejected">
                            <input type="text" name="payment_rejection_reason" class="form-control" placeholder="Reason for rejection" required>
                            <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
        <a href="{{ route('admin.colleges.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>
