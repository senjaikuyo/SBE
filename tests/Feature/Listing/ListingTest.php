<?php

use App\Models\Listing;
use Illuminate\Http\UploadedFile;

it('creates an energy listing for an approved seller company', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $this->actingAs($user)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 120,
        'price_per_kwh' => 0.12,
        'duration_years' => 10,
        'start_date' => '2027-01-01',
        'location' => 'Pembangungan Jawa',
        'permit_reference' => 'PERMIT-1',
        'photo' => UploadedFile::fake()->image('panorama.jpg'),
    ])->assertRedirect(route('listings.create'));

    expect(Listing::query()->count())->toBe(1)
        ->and(Listing::query()->first()->status)->toBe('DRAFT')
        ->and(Listing::query()->first()->type)->toBe('energy');
});

it('rejects an energy listing missing a required field', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $this->actingAs($user)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 120,
        'price_per_kwh' => 0.12,
        'duration_years' => 10,
    ])->assertSessionHasErrors(['location', 'start_date']);

    expect(Listing::query()->count())->toBe(0);
});

it('does not create a listing when kyc is not approved', function () {
    $user = makeCompanyUser('company_admin');

    $this->actingAs($user)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 120,
        'price_per_kwh' => 0.12,
        'duration_years' => 10,
        'start_date' => '2027-01-01',
        'location' => 'Pembangungan Jawa',
        'permit_reference' => 'PERMIT-1',
        'photo' => UploadedFile::fake()->image('panorama.jpg'),
    ])->assertForbidden();

    expect(Listing::query()->count())->toBe(0);
});
