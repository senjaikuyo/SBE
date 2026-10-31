<?php

use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;
use App\Mail\KycSubmittedMail;
use App\Mail\ListingApprovedMail;
use App\Mail\ListingRejectedMail;
use App\Mail\MemberInvitationMail;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
    $this->admin = makeUser(['is_platform_admin' => true]);
    $this->companyUser = makeCompanyUser('company_admin');
    $this->company = $this->companyUser->company();
});

it('queues listing approved mail only to company members upon approval', function () {
    $this->company->update(['kyc_status' => 'APPROVED']);
    $listing = Listing::create([
        'company_id' => $this->company->id,
        'type' => 'carbon_credit',
        'serial_number' => 'CERT-100',
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ]);
    $listing->certificates()->create([
        'type' => 'carbon_credit',
        'registry' => 'VERRA',
        'serial_number' => 'CERT-100',
        'serial_normalized' => 'CERT-100',
        'status' => 'VALID',
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.listings.approve', $listing));
    $response->assertSessionHasNoErrors();

    Mail::assertQueued(ListingApprovedMail::class, function ($mail) {
        return $mail->hasTo($this->companyUser->email);
    });
});

it('queues listing rejected mail with reason to company members upon rejection', function () {
    $this->company->update(['kyc_status' => 'APPROVED']);
    $listing = Listing::create([
        'company_id' => $this->company->id,
        'type' => 'energy',
        'location' => 'Java Solar',
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.listings.reject', $listing), [
        'reason' => 'Invalid generation license attached.',
    ]);
    $response->assertSessionHasNoErrors();

    expect($listing->fresh()->status)->toBe('REJECTED');

    Mail::assertQueued(ListingRejectedMail::class, function ($mail) {
        return $mail->hasTo($this->companyUser->email) && $mail->reason === 'Invalid generation license attached.';
    });
});

it('does not send listing rejection mail when validation fails', function () {
    $listing = Listing::create([
        'company_id' => $this->company->id,
        'type' => 'energy',
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.listings.reject', $listing), [
        'reason' => '',
    ]);
    $response->assertSessionHasErrors(['reason']);

    Mail::assertNothingQueued();
});
