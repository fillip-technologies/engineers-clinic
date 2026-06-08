<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class College extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'college_name',
        'address',
        'contact_number',
        'payment_mode',
        'utr_number',
        'payment_status',
        'payment_submitted_at',
        'payment_reviewed_by',
        'payment_reviewed_at',
        'payment_rejection_reason',
    ];

    protected $casts = [
        'payment_submitted_at' => 'datetime',
        'payment_reviewed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function paymentReviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payment_reviewed_by');
    }
}
