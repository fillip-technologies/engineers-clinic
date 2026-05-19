<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskProgress extends Model
{
    use HasFactory;

    protected $table = 'task_progress';

    protected $fillable = [
        'student_id',
        'course_id',
        'step_id',
        'completed',
        'submitted',
        'github_link',
        'live_link',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'completed' => 'boolean',
        'submitted' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function step(): BelongsTo
    {
        return $this->belongsTo(WorkspaceStep::class, 'step_id');
    }
}
