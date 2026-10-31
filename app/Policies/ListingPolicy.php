<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class ListingPolicy
{
    public function create(User $user, Company $company): bool
    {
        if (! $company->isApproved() || $company->company_role !== 'seller') {
            return false;
        }

        $membership = $user->membershipFor($company);

        return $membership !== null
            && in_array($membership->role, ['company_admin', 'procurement'], true);
    }

    public function update(User $user, Company $company): bool
    {
        if (! $company->isApproved() || $company->company_role !== 'seller') {
            return false;
        }

        $membership = $user->membershipFor($company);

        return $membership !== null
            && in_array($membership->role, ['company_admin', 'procurement'], true);
    }
}
