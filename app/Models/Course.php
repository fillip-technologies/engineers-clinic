<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'level',
        'category',
        'image',
        'hero_badge',
        'career_path',
        'duration_months',
        'fee',
        'program_overview',
        'why_choose',
        'testimonials',
        'faq',
        'curriculum',
        'modules',
        'phases',
        'outcome',
    ];

    protected $casts = [
        'fee' => 'decimal:2',
        'program_overview' => 'json',
        'why_choose' => 'json',
        'testimonials' => 'json',
        'faq' => 'json',
        'curriculum' => 'json',
        'modules' => 'json',
        'phases' => 'json',
        'outcome' => 'json',
    ];

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
