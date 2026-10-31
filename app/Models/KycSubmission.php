<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KycSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'country',
        'requested_role',
        'documents',
        'beneficial_owners',
        'status',
        'decision',
        'decision_reason',
        'submitted_by',
        'decided_by',
        'submitted_at',
        'decided_at',
        'screened_at',
        'screening',
    ];

    protected function casts(): array
    {
        return [
            'documents' => 'array',
            'beneficial_owners' => 'array',
            'submitted_at' => 'datetime',
            'decided_at' => 'datetime',
            'screened_at' => 'datetime',
            'screening' => 'array',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decided_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING_VERIFICATION';
    }

    public function isApproved(): bool
    {
        return $this->status === 'APPROVED';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }
}
