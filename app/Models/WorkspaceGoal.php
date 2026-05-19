<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'title',
        'body',
        'duration',
        'type',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(CourseWorkspace::class, 'workspace_id');
    }
}
