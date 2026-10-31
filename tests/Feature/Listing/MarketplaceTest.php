<?php

use App\Models\Certificate;
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

it('(a) narrows results for each filter individually', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    $solarIndo = Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 10,
        'volume_available_mw' => 10,
        'price_per_kwh' => 0.08,
        'duration_years' => 12,
        'location' => 'Sumba, Indonesia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $solarMalay = Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 50,
        'volume_available_mw' => 50,
        'price_per_kwh' => 0.15,
        'duration_years' => 20,
        'location' => 'Johor, Malaysia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $carbonCredit = Listing::create([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'quantity_tco2e' => 500,
        'vintage' => 2024,
        'registry_standard' => 'VERRA',
        'serial_number' => 'VCS-TEST-001',
        'price_per_ton' => 25.00,
        'location' => 'Central Kalimantan, Indonesia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    // Filter 1: type=energy
    $this->actingAs($user)->get(route('marketplace.index', ['type' => 'energy']))
        ->assertOk()
        ->assertSee('Sumba, Indonesia')
        ->assertSee('Johor, Malaysia')
        ->assertDontSee('VCS-TEST-001');

    // Filter 2: country=Malaysia
    $this->actingAs($user)->get(route('marketplace.index', ['country' => 'Malaysia']))
        ->assertOk()
        ->assertSee('Johor, Malaysia')
        ->assertDontSee('Sumba, Indonesia');

    // Filter 3: min capacity = 30 MW
    $this->actingAs($user)->get(route('marketplace.index', ['capacity' => 30]))
        ->assertOk()
        ->assertSee('Johor, Malaysia')
        ->assertDontSee('Sumba, Indonesia');

    // Filter 4: max price = 0.10 USD
    $this->actingAs($user)->get(route('marketplace.index', ['price' => 0.10]))
        ->assertOk()
        ->assertSee('Sumba, Indonesia')
        ->assertDontSee('Johor, Malaysia')
        ->assertDontSee('Central Kalimantan');

    // Filter 5: min duration = 15 years
    $this->actingAs($user)->get(route('marketplace.index', ['duration' => 15]))
        ->assertOk()
        ->assertSee('Johor, Malaysia')
        ->assertDontSee('Sumba, Indonesia');
});

it('(b) narrows results with a combination of multiple filters', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 40,
        'price_per_kwh' => 0.07,
        'duration_years' => 15,
        'location' => 'Sumatra, Indonesia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 10,
        'price_per_kwh' => 0.07,
        'duration_years' => 15,
        'location' => 'Java, Indonesia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $this->actingAs($user)->get(route('marketplace.index', [
        'type' => 'energy',
        'country' => 'Indonesia',
        'capacity' => 20,
        'price' => 0.10,
        'duration' => 10,
    ]))
        ->assertOk()
        ->assertSee('Sumatra, Indonesia')
        ->assertDontSee('Java, Indonesia');
});

it('(c) shows empty state when no listings match the filters', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 10,
        'price_per_kwh' => 0.10,
        'location' => 'Indonesia',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    $this->actingAs($user)->get(route('marketplace.index', ['country' => 'Vietnam']))
        ->assertOk()
        ->assertSee('No listings match these filters')
        ->assertSee('Save this search')
        ->assertSee('Phase 2');
});

it('(d) does not produce 500 errors on invalid filter values', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    $this->actingAs($user)->get(route('marketplace.index', [
        'capacity' => 'not-a-number',
        'price' => 'invalid-price',
        'duration' => 'ten-years',
        'country' => 'AtlantisKingdom',
        'type' => 'unsupported_type_xyz',
    ]))
        ->assertOk();
});

it('(e) never displays DRAFT or unverified listings in marketplace', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'location' => 'Visible Active Energy',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'location' => 'Hidden Draft Energy',
        'status' => 'DRAFT',
        'version' => 1,
    ]);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'location' => 'Hidden Pending Listing',
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ]);

    $unverifiedCarbon = Listing::create([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'location' => 'Hidden Unverified Certificate',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);
    $unverifiedCarbon->certificates()->create([
        'type' => 'carbon_credit',
        'registry' => 'VERRA',
        'serial_number' => 'UNVERIFIED-1',
        'serial_normalized' => 'UNVERIFIED-1',
        'status' => 'PENDING_REVIEW',
    ]);

    $this->actingAs($user)->get(route('marketplace.index'))
        ->assertOk()
        ->assertSee('Visible Active Energy')
        ->assertDontSee('Hidden Draft Energy')
        ->assertDontSee('Hidden Pending Listing')
        ->assertDontSee('Hidden Unverified Certificate');
});

it('(f) does not filter carbon credits based on MW capacity', function () {
    $user = makeCompanyUser('buyer');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 10,
        'location' => 'Small Solar 10MW',
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    Listing::create([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'location' => 'Borneo Peatland Carbon',
        'quantity_tco2e' => 1000,
        'status' => 'ACTIVE',
        'version' => 1,
    ]);

    // When filtering capacity = 50 MW:
    // Small Solar 10MW should be hidden.
    // Borneo Peatland Carbon should still appear because carbon credit has no MW.
    $this->actingAs($user)->get(route('marketplace.index', ['capacity' => 50]))
        ->assertOk()
        ->assertDontSee('Small Solar 10MW')
        ->assertSee('Borneo Peatland Carbon');
});
