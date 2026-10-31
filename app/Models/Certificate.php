<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'type',
        'registry',
        'serial_number',
        'serial_normalized',
        'status',
        'rejection_reason',
        'note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Certificate $certificate) {
            if ($certificate->serial_number && ! $certificate->serial_normalized) {
                $certificate->serial_normalized = static::normalizeSerial($certificate->serial_number);
            }
        });
    }

    public static function normalizeSerial(string $serial): string
    {
        return strtoupper(preg_replace('/\s+/', '', $serial) ?? '');
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isValid(): bool
    {
        return $this->status === 'VALID';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING_REVIEW';
    }
}
