<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollegeInternshipPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'college_id',
        'course_id',
        'transaction_id',
        'seats_purchased',
        'seats_used',
        'price_per_seat',
    ];

    protected $casts = [
        'price_per_seat'  => 'decimal:2',
        'seats_purchased' => 'integer',
        'seats_used'      => 'integer',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(CollegePaymentTransaction::class, 'transaction_id');
    }

    public function seatAllocations(): HasMany
    {
        return $this->hasMany(CollegeInternshipSeatAllocation::class, 'purchase_id');
    }

    public function seatsRemaining(): int
    {
        return max(0, $this->seats_purchased - $this->seats_used);
    }
}
