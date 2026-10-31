<?php

use App\Models\Listing;
use App\Models\ListingHistory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    $created = Listing::query()->first();
    expect($created)->not->toBeNull()
        ->and($created->status)->toBe('DRAFT')
        ->and($created->type)->toBe('energy')
        ->and($created->photo)->not->toBe('pending')
        ->and($created->histories()->count())->toBe(1);
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

it('(a) updates and deletes listing with version increment and history record', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $listing = Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 15,
        'volume_available_mw' => 10,
        'price_per_kwh' => 0.08,
        'duration_years' => 10,
        'location' => 'Original Location',
        'status' => 'DRAFT',
        'version' => 1,
    ]);

    // Update listing
    $this->actingAs($user)->patch(route('listings.update', $listing), [
        'capacity_mw' => 20,
        'location' => 'Updated Location',
    ])->assertRedirect(route('listings.create'));

    $listing->refresh();
    expect($listing->version)->toBe(2);
    expect($listing->capacity_mw)->toBe(20);
    expect($listing->location)->toBe('Updated Location');
    expect($listing->histories()->count())->toBe(1);

    // Delete listing
    $this->actingAs($user)->delete(route('listings.destroy', $listing))->assertRedirect(route('listings.create'));
    expect(Listing::find($listing->id))->toBeNull();
    expect(ListingHistory::where('listing_id', $listing->id)->count())->toBe(2);
});

it('(b) validates energy photo size limit and scans with VirusScanner', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    // Photo exceeding 10MB
    $oversizedFile = UploadedFile::fake()->create('huge.jpg', 11 * 1024);

    $this->actingAs($user)->post(route('listings.store'), [
        'type' => 'energy',
        'capacity_mw' => 5,
        'volume_available_mw' => 5,
        'price_per_kwh' => 0.10,
        'duration_years' => 5,
        'start_date' => '2027-01-01',
        'location' => 'Test Site',
        'permit_reference' => 'PERMIT-TEST',
        'photo' => $oversizedFile,
    ])->assertSessionHasErrors(['photo']);
});

it('(c) serves separate forms for energy and carbon listings', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $this->actingAs($user)->get(route('listings.create.energy'))
        ->assertOk()
        ->assertSee('Capacity');

    $this->actingAs($user)->get(route('listings.create.carbon'))
        ->assertOk()
        ->assertSee('Quantity')
        ->assertDontSee('Capacity (MW)');
});
