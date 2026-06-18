<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'type',
        'is_sponsorable',
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
        'fee'            => 'decimal:2',
        'is_sponsorable' => 'boolean',
        'program_overview' => 'json',
        'why_choose' => 'json',
        'testimonials' => 'json',
        'faq' => 'json',
        'curriculum' => 'json',
        'modules' => 'json',
        'phases' => 'json',
        'outcome' => 'json',
    ];

    protected static function booted(): void
    {
        static::creating(function (Course $course) {
            if (blank($course->slug) && filled($course->title)) {
                $course->slug = static::uniqueSlug($course->title);
            }
        });
    }

    private static function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $slug = $slug !== '' ? $slug : 'course';
        $candidate = $slug;
        $count = 2;

        while (static::where('slug', $candidate)->exists()) {
            $candidate = "{$slug}-{$count}";
            $count++;
        }

        return $candidate;
    }

    public function scopeInternships(Builder $query): Builder
    {
        return $query->where('type', 'internship');
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function collegeInternshipPurchases(): HasMany
    {
        return $this->hasMany(CollegeInternshipPurchase::class);
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

    public function workspaces(): HasMany
    {
        return $this->hasMany(CourseWorkspace::class);
    }

    public function taskProgress(): HasMany
    {
        return $this->hasMany(TaskProgress::class);
    }
}
