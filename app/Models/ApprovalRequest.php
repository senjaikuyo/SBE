<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalRequest extends Model
{
    protected $fillable = [
        'action',
        'entity_type',
        'entity_id',
        'payload',
        'reason',
        'status',
        'proposed_by_id',
        'reviewed_by_id',
        'reviewed_at',
        'review_reason',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function proposedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposed_by_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }
}
