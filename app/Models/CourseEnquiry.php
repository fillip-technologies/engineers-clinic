<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseEnquiry extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'course_slug',
        'course_title',
        'consent',
    ];

    protected $casts = [
        'consent' => 'boolean',
    ];
}
