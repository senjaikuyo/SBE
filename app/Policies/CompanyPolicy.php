<?php

namespace App\Policies;

use App\Models\Company;
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
}
