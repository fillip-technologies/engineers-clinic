<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkspaceResource extends Model
{
    use HasFactory;

    protected $fillable = [
        'workspace_id',
        'category',
        'label',
        'description',
        'icon',
        'href',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(CourseWorkspace::class, 'workspace_id');
    }
}
