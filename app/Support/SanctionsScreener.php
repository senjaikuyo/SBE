<?php

namespace App\Support;

use App\Models\Company;

interface SanctionsScreener
{
    public function screen(?Company $company, ?string $country = null): array;
}
