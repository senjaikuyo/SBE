<?php

namespace App\Console\Commands;

use App\Models\KycSubmission;
use App\Support\SanctionsScreener;
use Illuminate\Console\Command;

class ScreenSanctions extends Command
{
    protected $signature = 'kyc:screening';

    protected $description = 'Run sanctions screening for approved KYC companies (Phase 1 stub)';

    public function handle(SanctionsScreener $screener): int
    {
        $checked = KycSubmission::query()
            ->where('status', 'APPROVED')
            ->get()
            ->each(function (KycSubmission $submission) use ($screener): void {
                $submission->forceFill([
                    'screened_at' => now(),
                    'screening' => $screener->screen($submission->company, $submission->country),
                ])->save();
            });

        $this->info('Sanctions screening completed for '.$checked->count().' approved KYC submissions (Phase 1 stub: clear).');

        return self::SUCCESS;
    }
}
