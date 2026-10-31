<?php

namespace App\Support;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuditLogger
{
    /**
     * Append a row to the audit trail, chaining it to the previous hash.
     *
     * The transaction and row lock keep the chain linear when two writers
     * race; the whole chain is verified by `php artisan audit:verify`.
     */
    public function record(
        string $action,
        string $entity,
        ?string $entityId = null,
        ?string $reason = null,
        ?User $actor = null,
    ): AuditLog {
        return DB::transaction(function () use ($action, $entity, $entityId, $reason, $actor) {
            $previous = AuditLog::query()->orderByDesc('id')->lockForUpdate()->first();

            $createdAt = now()->utc()->format('Y-m-d H:i:s');

            $payload = AuditLog::canonicalPayload(
                $actor?->id,
                $action,
                $entity,
                $entityId,
                $reason,
                $createdAt,
                $previous?->hash,
            );

            return AuditLog::create([
                'actor_id' => $actor?->id,
                'action' => $action,
                'entity' => $entity,
                'entity_id' => $entityId,
                'reason' => $reason,
                'hash_prev' => $previous?->hash,
                'hash' => hash('sha256', $payload),
                'created_at' => $createdAt,
            ]);
        });
    }
}
