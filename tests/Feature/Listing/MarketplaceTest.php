<?php

use App\Models\Listing;
use Illuminate\Http\UploadedFile;

it('lists only active listings and hides drafts', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 120,
        'price_per_kwh' => 0.12,
        'duration_years' => 10,
        'start_date' => '2027-01-01',
        'location' => 'Pembangungan Jawa',
        'permit_reference' => 'PERMIT-1',
        'photo' => 'pending',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 1,
        'volume_available_mw' => 2,
        'price_per_kwh' => 0.2,
        'duration_years' => 5,
        'start_date' => '2028-01-01',
        'location' => 'Draft Place',
        'permit_reference' => 'PERMIT-2',
        'photo' => 'pending',
        'status' => 'DRAFT',
        'version' => 1,
    ]);

    $this->actingAs($user)->get(route('marketplace.index'))
        ->assertOk()
        ->assertSee('Pembangungan Jawa')
        ->assertDontSee('Draft Place');

    $this->actingAs($user)->get(route('marketplace.index'))
        ->assertOk();
});

it('shows listing detail fields per type', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    $energy = Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 120,
        'price_per_kwh' => 0.12,
        'duration_years' => 10,
        'start_date' => '2027-01-01',
        'location' => 'Pembangungan Jawa',
        'permit_reference' => 'PERMIT-1',
        'photo' => 'pending',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $carbon = Listing::create([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'quantity_tco2e' => 100,
        'vintage' => 2020,
        'registry_standard' => 'VERRA',
        'serial_number' => 'VERRA-1234',
        'price_per_ton' => 15,
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $this->actingAs($user)->get(route('marketplace.show', $energy))->assertOk()->assertSee('Pembangungan Jawa');
    $this->actingAs($user)->get(route('marketplace.show', $carbon))->assertOk()->assertSee('VERRA-1234');
});

it('returns 404 for a missing listing', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    $this->actingAs($user)->get(route('marketplace.show', 999999))->assertNotFound();
});
