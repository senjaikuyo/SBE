<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use Illuminate\Console\Command;

class VerifyAuditChain extends Command
{
    protected $signature = 'audit:verify';

    protected $description = 'Verify the audit log hash chain and report broken rows';

    public function handle(): int
    {
        $previousHash = null;
        $broken = 0;
        $count = 0;

        foreach (AuditLog::query()->orderBy('id')->cursor() as $row) {
            $count++;

            $expected = hash('sha256', AuditLog::canonicalPayload(
                $row->actor_id,
                $row->action,
                $row->entity,
                $row->entity_id,
                $row->reason,
                $row->created_at->utc()->format('Y-m-d H:i:s'),
                $row->hash_prev,
            ));

            $problems = [];

            if ($row->hash_prev !== $previousHash) {
                $problems[] = 'hash_prev does not match the previous row';
            }

            if (! hash_equals($expected, $row->hash)) {
                $problems[] = 'hash does not match the row content';
            }

            if ($problems !== []) {
                $broken++;
                $this->error(sprintf('Row %d (%s): %s', $row->id, $row->action, implode('; ', $problems)));
            }

            $previousHash = $row->hash;
        }

        if ($broken > 0) {
            $this->newLine();
            $this->error("Audit chain broken: {$broken} of {$count} rows failed verification.");

            return self::FAILURE;
        }

        $this->info("Audit chain verified: {$count} rows, no breaks.");

        return self::SUCCESS;
    }
}
