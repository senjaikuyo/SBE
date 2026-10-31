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

it('rejects buyer companies with 403 and accepts seller companies for listing creation', function () {
    // Buyer company attempt
    $buyerUser = makeCompanyUser('company_admin');
    $buyerCompany = $buyerUser->company();
    $buyerCompany->update(['kyc_status' => 'APPROVED', 'company_role' => 'buyer']);

    $this->actingAs($buyerUser)->get(route('listings.create'))->assertForbidden();
    $this->actingAs($buyerUser)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 10,
        'volume_available_mw' => 10,
        'price_per_kwh' => 0.10,
        'duration_years' => 5,
        'start_date' => '2027-01-01',
        'location' => 'Buyer Place',
        'permit_reference' => 'PERMIT-BUYER',
        'photo' => UploadedFile::fake()->image('buyer.jpg'),
    ])->assertForbidden();

    // Seller company attempt
    $sellerUser = makeCompanyUser('procurement');
    $sellerCompany = $sellerUser->company();
    $sellerCompany->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $this->actingAs($sellerUser)->get(route('listings.create'))->assertOk();
    $this->actingAs($sellerUser)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 10,
        'volume_available_mw' => 10,
        'price_per_kwh' => 0.10,
        'duration_years' => 5,
        'start_date' => '2027-01-01',
        'location' => 'Seller Place',
        'permit_reference' => 'PERMIT-SELLER',
        'photo' => UploadedFile::fake()->image('seller.jpg'),
    ])->assertRedirect(route('listings.create'));

    expect(Listing::where('location', 'Seller Place')->count())->toBe(1);
});
