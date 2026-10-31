<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\KycDocument;
use App\Models\User;

class CompanyPolicy
{
    public function view(User $user, Company $company): bool
    {
        return $user->membershipFor($company) !== null;
    }

    public function manageMembers(User $user, Company $company): bool
    {
        return $user->membershipFor($company)?->isCompanyAdmin() === true;
    }

    public function viewKyc(User $user, Company $company): bool
    {
        $membership = $user->membershipFor($company);

        return $membership !== null
            && in_array($membership->role, ['company_admin', 'legal'], true);
    }

    public function submitKyc(User $user, Company $company): bool
    {
        $membership = $user->membershipFor($company);

        return $membership !== null
            && in_array($membership->role, ['company_admin', 'legal'], true);
    }

    public function downloadKycDocument(User $user, KycDocument $document): bool
    {
        if ($user->isPlatformAdmin()) {
            return true;
        }

        $membership = $user->membershipFor($document->company);

        return $membership !== null
            && in_array($membership->role, ['company_admin', 'legal'], true);
    }
}
