<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use LogicException;

/**
 * Append-only audit trail. Rows are never updated or deleted.
 */
class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = [
        'actor_id',
        'action',
        'entity',
        'entity_id',
        'reason',
        'hash_prev',
        'hash',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (): never {
            throw new LogicException('audit_logs is append-only: updates are not allowed.');
        });

        static::deleting(function (): never {
            throw new LogicException('audit_logs is append-only: deletes are not allowed.');
        });
    }

    /**
     * Canonical string that is hashed for a row.
     *
     * Kept in one place so the writer and `audit:verify` always agree.
     */
    public static function canonicalPayload(
        ?int $actorId,
        string $action,
        string $entity,
        ?string $entityId,
        ?string $reason,
        string $createdAt,
        ?string $hashPrev,
    ): string {
        return json_encode([
            'actor_id' => $actorId,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'reason' => $reason,
            'created_at' => $createdAt,
            'hash_prev' => $hashPrev,
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
