<?php

use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\Listing;
use App\Models\User;
use Database\Seeders\DemoSeeder;

it('runs demo seeder and produces correct number of active listings and demo data', function () {
    $this->seed(DemoSeeder::class);

    // Active listings: 3 energy + 2 carbon credit = 5 total
    expect(Listing::where('status', 'ACTIVE')->count())->toBe(5);
    expect(Listing::where('status', 'ACTIVE')->where('type', 'energy')->count())->toBe(3);
    expect(Listing::where('status', 'ACTIVE')->where('type', 'carbon_credit')->count())->toBe(2);

    // Draft listings: 1
    expect(Listing::where('status', 'DRAFT')->count())->toBe(1);

    // Two platform admins
    expect(User::where('is_platform_admin', true)->count())->toBe(2);

    // One pending KYC submission
    expect(KycSubmission::where('status', 'PENDING_VERIFICATION')->count())->toBe(1);

    // Seller & Buyer companies approved
    expect(Company::where('kyc_status', 'APPROVED')->where('company_role', 'seller')->count())->toBe(1);
    expect(Company::where('kyc_status', 'APPROVED')->where('company_role', 'buyer')->count())->toBe(1);
});
