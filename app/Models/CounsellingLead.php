<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounsellingLead extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
    ];
}
