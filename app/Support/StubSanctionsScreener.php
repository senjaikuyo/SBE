<?php

namespace App\Support;

use App\Models\Company;

class StubSanctionsScreener implements SanctionsScreener
{
    public function screen(?Company $company, ?string $country = null): array
    {
        return [
            'provider' => 'greengrid_stub',
            'result' => 'clear',
            'screened_at' => now(),
        ];
    }
}
