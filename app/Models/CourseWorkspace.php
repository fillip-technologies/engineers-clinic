<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseWorkspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'track',
        'headline',
        'summary',
        'progress',
        'next_milestone',
        'status',
    ];

    protected $casts = [
        'progress' => 'integer',
        'status' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(WorkspaceStep::class, 'workspace_id')->orderBy('sort_order')->orderBy('step_no');
    }

    public function resources(): HasMany
    {
        return $this->hasMany(WorkspaceResource::class, 'workspace_id')->orderBy('sort_order');
    }

    public function goals(): HasMany
    {
        return $this->hasMany(WorkspaceGoal::class, 'workspace_id');
    }
}
