<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipPayment extends Model
{
    protected $fillable = [
        'student_id',
        'level',
        'amount',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'receipt',
        'enrolled_projects',
        'paid_at',
    ];

    protected $casts = [
        'amount'            => 'decimal:2',
        'enrolled_projects' => 'array',
        'paid_at'           => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
