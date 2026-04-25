<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Payment Details</h1>
        <div class="card">
            <div class="card-body">
                <p class="card-text"><strong>Student:</strong> {{ $payment->student->user->name ?? 'N/A' }}</p>
                <p class="card-text"><strong>Course:</strong> {{ $payment->course->title ?? 'N/A' }}</p>
                <p class="card-text"><strong>Amount:</strong> ${{ number_format($payment->amount, 2) }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $payment->status }}</p>
                <p class="card-text"><strong>Payment Date:</strong> {{ $payment->payment_date }}</p>
                <p class="card-text"><strong>Created:</strong> {{ $payment->created_at }}</p>
            </div>
        </div>
        <a href="{{ route('payments.index') }}" class="btn btn-secondary mt-3">Back</a>
    </div>
</body>
</html>