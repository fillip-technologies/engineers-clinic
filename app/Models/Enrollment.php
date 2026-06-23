<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'progress',
        'status',
        'sponsor_type',
        'seat_allocation_id',
        'enrolled_projects',
    ];

    protected $casts = [
        'enrollment_date'   => 'date',
        'enrolled_projects' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function seatAllocation(): BelongsTo
    {
        return $this->belongsTo(CollegeInternshipSeatAllocation::class, 'seat_allocation_id');
    }
}
