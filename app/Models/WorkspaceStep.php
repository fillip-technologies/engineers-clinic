<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkspaceStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'step_no',
        'slug',
        'nav_label',
        'title',
        'description',
        'status',
        'state',
        'active',
        'build_goal',
        'why_text',
        'lesson',
        'file_name',
        'code_snippet',
        'expected_output',
        'preview_title',
        'task',
        'hint',
        'mentor_tip',
        'preview_points',
        'mistakes',
        'tips',
        'sort_order',
    ];

    protected $casts = [
        'step_no' => 'integer',
        'active' => 'boolean',
        'preview_points' => 'array',
        'mistakes' => 'array',
        'tips' => 'array',
        'sort_order' => 'integer',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(CourseWorkspace::class, 'workspace_id');
    }

    public function taskProgress(): HasMany
    {
        return $this->hasMany(TaskProgress::class, 'step_id');
    }
}
