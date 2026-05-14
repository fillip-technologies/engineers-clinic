<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegePartnershipDiscussion extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'institution_name',
        'official_email',
        'phone',
        'designation',
        'number_of_students',
        'department_stream',
        'message',
    ];
}
