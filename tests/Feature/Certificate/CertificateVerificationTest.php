<?php

use App\Models\AuditLog;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\Listing;
use App\Models\User;

function makeListingWithCertificate(array $listingAttrs = [], array $certAttrs = []): array
{
    $companyUser = makeCompanyUser('company_admin');
    $company = $companyUser->company();
    $company->update(['kyc_status' => 'APPROVED', 'company_role' => 'seller']);

    $listing = Listing::create(array_merge([
        'company_id' => $company->id,
        'type' => 'carbon_credit',
        'quantity_tco2e' => 100,
        'vintage' => 2025,
        'registry_standard' => 'VERRA',
        'serial_number' => 'VCS-1520-2025-001234',
        'price_per_ton' => 15.50,
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ], $listingAttrs));

    $certificate = $listing->certificates()->create(array_merge([
        'type' => $listing->type,
        'registry' => $listing->registry_standard ?? 'VERRA',
        'serial_number' => $listing->serial_number ?? 'VCS-1520-2025-001234',
        'serial_normalized' => Certificate::normalizeSerial($listing->serial_number ?? 'VCS-1520-2025-001234'),
        'status' => 'PENDING_REVIEW',
    ], $certAttrs));

    return [$listing, $certificate, $companyUser];
}

it('(a) lets platform admin approve a certificate and transitions listing to ACTIVE', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    [$listing, $certificate] = makeListingWithCertificate();

    expect($listing->status)->toBe('PENDING_REVIEW')
        ->and($certificate->status)->toBe('PENDING_REVIEW');

    $response = $this->actingAs($admin)->post(route('certificates.approve', $certificate), [
        'note' => 'Serial verified in Verra registry.',
    ]);

    $response->assertRedirect(route('admin.certificates.queue'));

    $certificate->refresh();
    $listing->refresh();

    expect($certificate->status)->toBe('VALID')
        ->and($certificate->reviewed_by)->toBe($admin->id)
        ->and($certificate->reviewed_at)->not->toBeNull()
        ->and($certificate->note)->toBe('Serial verified in Verra registry.')
        ->and($listing->status)->toBe('ACTIVE');

    expect(AuditLog::query()->where('action', 'certificate.approved')->where('entity_id', (string) $certificate->id)->exists())->toBeTrue()
        ->and(AuditLog::query()->where('action', 'listing.activated')->where('entity_id', (string) $listing->id)->exists())->toBeTrue();
});

it('(b) fails to reject certificate without a reason', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    [$listing, $certificate] = makeListingWithCertificate();

    $response = $this->actingAs($admin)->post(route('certificates.reject', $certificate), [
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['reason']);

    $certificate->refresh();
    $listing->refresh();

    expect($certificate->status)->toBe('PENDING_REVIEW')
        ->and($listing->status)->toBe('PENDING_REVIEW');
});

it('rejects certificate when reason is provided and updates listing to REJECTED', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    [$listing, $certificate] = makeListingWithCertificate();

    $response = $this->actingAs($admin)->post(route('certificates.reject', $certificate), [
        'reason' => 'Serial number already retired in registry.',
    ]);

    $response->assertRedirect(route('admin.certificates.queue'));

    $certificate->refresh();
    $listing->refresh();

    expect($certificate->status)->toBe('REJECTED')
        ->and($certificate->rejection_reason)->toBe('Serial number already retired in registry.')
        ->and($certificate->reviewed_by)->toBe($admin->id)
        ->and($certificate->reviewed_at)->not->toBeNull()
        ->and($listing->status)->toBe('REJECTED');

    expect(AuditLog::query()->where('action', 'certificate.rejected')->where('entity_id', (string) $certificate->id)->exists())->toBeTrue()
        ->and(AuditLog::query()->where('action', 'listing.rejected')->where('entity_id', (string) $listing->id)->exists())->toBeTrue();
});

it('(c) forbids non-admin users from accessing or modifying certificates', function () {
    $user = makeCompanyUser('company_admin');
    [$listing, $certificate] = makeListingWithCertificate();

    // Index / queue
    $this->actingAs($user)->get(route('admin.certificates.queue'))->assertForbidden();

    // Show
    $this->actingAs($user)->get(route('admin.certificates.show', $certificate))->assertForbidden();

    // Approve
    $this->actingAs($user)->post(route('certificates.approve', $certificate))->assertForbidden();

    // Reject
    $this->actingAs($user)->post(route('certificates.reject', $certificate), [
        'reason' => 'Unauthorized attempt',
    ])->assertForbidden();

    // Decision
    $this->actingAs($user)->post(route('certificates.decision', $certificate), [
        'decision' => 'valid',
    ])->assertForbidden();

    // Certificate and listing remain unchanged
    $certificate->refresh();
    $listing->refresh();
    expect($certificate->status)->toBe('PENDING_REVIEW')
        ->and($listing->status)->toBe('PENDING_REVIEW');
});

it('(d) prevents a listing without valid verified certificate from becoming ACTIVE', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    [$listing, $certificate] = makeListingWithCertificate();

    // Case 1: Certificate is PENDING_REVIEW
    expect($listing->canBeActive())->toBeFalse();
    expect(fn () => $listing->activate())->toThrow(\DomainException::class);

    // Moderate listing endpoint should fail with validation error
    $this->actingAs($admin)
        ->post(route('admin.listings.approve', $listing))
        ->assertSessionHasErrors(['certificate']);

    expect($listing->fresh()->status)->toBe('PENDING_REVIEW');

    // Case 2: Certificate is REJECTED
    $certificate->update(['status' => 'REJECTED']);
    $listing->refresh();

    expect($listing->canBeActive())->toBeFalse();
    expect(fn () => $listing->activate())->toThrow(\DomainException::class);

    $this->actingAs($admin)
        ->post(route('admin.listings.approve', $listing))
        ->assertSessionHasErrors(['certificate']);

    // Case 3: Listing without any certificate
    $listingNoCert = Listing::create([
        'company_id' => $listing->company_id,
        'type' => 'energy',
        'capacity_mw' => 10,
        'volume_available_mw' => 50,
        'price_per_kwh' => 0.08,
        'duration_years' => 5,
        'start_date' => '2027-01-01',
        'location' => 'Bali Solar',
        'permit_reference' => 'BALI-01',
        'photo' => 'pending',
        'status' => 'DRAFT',
        'version' => 1,
    ]);

    expect($listingNoCert->canBeActive())->toBeFalse();
    expect(fn () => $listingNoCert->activate())->toThrow(\DomainException::class);

    $this->actingAs($admin)
        ->post(route('admin.listings.approve', $listingNoCert))
        ->assertSessionHasErrors(['certificate']);

    // Case 4: Once certificate is VALID, listing can become ACTIVE
    $certificate->update(['status' => 'VALID']);
    $listing->refresh();

    expect($listing->canBeActive())->toBeTrue();
    $listing->activate();
    expect($listing->fresh()->status)->toBe('ACTIVE');
});

it('normalizes serial numbers by stripping spaces and converting to uppercase', function () {
    expect(Certificate::normalizeSerial('vcs 1520 2025 001234'))->toBe('VCS15202025001234')
        ->and(Certificate::normalizeSerial(' irec id - 2027 '))->toBe('IRECID-2027');
});

it('enforces unique serial_normalized per registry', function () {
    [$listing1] = makeListingWithCertificate([
        'serial_number' => 'VCS 1520 2025',
    ], [
        'registry' => 'VERRA',
        'serial_number' => 'VCS 1520 2025',
        'serial_normalized' => Certificate::normalizeSerial('VCS 1520 2025'),
    ]);

    // Creating another certificate with same registry and equivalent normalized serial must fail unique constraint
    expect(function () use ($listing1) {
        Certificate::create([
            'listing_id' => $listing1->id,
            'type' => 'carbon_credit',
            'registry' => 'VERRA',
            'serial_number' => 'vcs15202025',
            'serial_normalized' => Certificate::normalizeSerial('vcs15202025'),
            'status' => 'PENDING_REVIEW',
        ]);
    })->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);

    // But same serial on a different registry is allowed
    $otherRegistryCert = Certificate::create([
        'listing_id' => $listing1->id,
        'type' => 'carbon_credit',
        'registry' => 'GOLD_STANDARD',
        'serial_number' => 'vcs15202025',
        'serial_normalized' => Certificate::normalizeSerial('vcs15202025'),
        'status' => 'PENDING_REVIEW',
    ]);

    expect($otherRegistryCert->id)->not->toBeNull();
});

it('handles decision form submission from UI with valid and rejected decisions', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    [$listing, $certificate] = makeListingWithCertificate();

    // Approve via decision endpoint
    $this->actingAs($admin)->post(route('certificates.decision', $certificate), [
        'decision' => 'valid',
        'note' => 'Approved via UI decision form',
    ])->assertRedirect(route('admin.certificates.queue'));

    expect($certificate->fresh()->status)->toBe('VALID')
        ->and($listing->fresh()->status)->toBe('ACTIVE');

    // Reset to test rejection via decision
    $certificate->update(['status' => 'PENDING_REVIEW']);
    $listing->update(['status' => 'PENDING_REVIEW']);

    // Rejection without note/reason fails
    $this->actingAs($admin)->post(route('certificates.decision', $certificate), [
        'decision' => 'rejected',
        'note' => '',
    ])->assertSessionHasErrors(['reason']);

    // Rejection with note succeeds
    $this->actingAs($admin)->post(route('certificates.decision', $certificate), [
        'decision' => 'rejected',
        'note' => 'Serial not found in external database',
    ])->assertRedirect(route('admin.certificates.queue'));

    expect($certificate->fresh()->status)->toBe('REJECTED')
        ->and($certificate->fresh()->rejection_reason)->toBe('Serial not found in external database')
        ->and($listing->fresh()->status)->toBe('REJECTED');
});
