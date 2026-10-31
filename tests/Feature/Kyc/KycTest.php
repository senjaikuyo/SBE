<?php

use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;
use App\Mail\KycSubmittedMail;
use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\User;
use App\Support\SanctionsScreener;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

function makeKycSubmission(Company $company, User $submittedBy, string $status = 'PENDING_VERIFICATION'): KycSubmission
{
    return KycSubmission::create([
        'company_id' => $company->id,
        'country' => $company->country,
        'requested_role' => 'seller',
        'documents' => [],
        'beneficial_owners' => [],
        'status' => $status,
        'submitted_by' => $submittedBy->id,
        'submitted_at' => now(),
    ]);
}

it('submits company verification and queues the confirmation email', function () {
    Mail::fake();

    $user = makeCompanyUser('company_admin');
    $company = $user->company();

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => [
            'company_registration' => '1',
            'tax_identification' => '1',
            'company_deed' => '1',
            'director_identity' => '1',
            'beneficial_owner_documents' => '1',
            'bank_statement' => '1',
            'signatory_appointment' => '1',
        ],
        'document_expiries' => [
            'company_registration' => '2030-01-01',
            'tax_identification' => '2030-01-01',
        ],
        'beneficial_owners' => [
            ['name' => 'Budi Santoso', 'nationality' => 'ID', 'ownership' => '60', 'document' => 'npwp-scan.pdf'],
        ],
    ])->assertRedirect(route('kyc.index'));

    expect($company->fresh()->kyc_status)->toBe('PENDING_VERIFICATION')
        ->and(KycSubmission::query()->count())->toBe(1);

    $submission = KycSubmission::query()->first();
    expect($submission->status)->toBe('PENDING_VERIFICATION')
        ->and($submission->screening)->toBeArray()
        ->and($submission->screening['result'])->toBe('clear');

    Mail::assertQueued(KycSubmittedMail::class);
});

it('rejects an expired document with a reason', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => [
            'company_registration' => '1',
            'tax_identification' => '1',
            'company_deed' => '1',
            'director_identity' => '1',
            'beneficial_owner_documents' => '1',
            'bank_statement' => '1',
            'signatory_appointment' => '1',
        ],
        'document_expiries' => [
            'company_registration' => '2030-01-01',
            'tax_identification' => '2000-01-01',
        ],
        'beneficial_owners' => [],
    ])->assertRedirect(route('kyc.index'));

    expect($company->fresh()->kyc_status)->toBe('REJECTED');

    $submission = KycSubmission::query()->first();
    expect($submission->status)->toBe('REJECTED')
        ->and($submission->decision_reason)->toContain('expired');
});

it('rejects a submit when a required document is missing', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => [
            'company_deed' => '1',
            'director_identity' => '1',
            'beneficial_owner_documents' => '1',
            'bank_statement' => '1',
            'signatory_appointment' => '1',
        ],
        'document_expiries' => [],
        'beneficial_owners' => [],
    ])->assertSessionHasErrors('documents');

    expect($company->fresh()->kyc_status)->toBe('NOT_SUBMITTED');
});

it('rejects a submit while a verification is already pending', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertRedirect(route('kyc.index'));

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertSessionHasErrors('kyc');
});

it('allows resubmission after rejection and blocks resubmission after approval', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();

    $this->actingAs($user)->post(route('kyc.submit'), [
        'requested_role' => 'seller',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertRedirect(route('kyc.index'));

    // Reject, then resubmit.
    KycSubmission::query()->update(['status' => 'REJECTED', 'decision' => 'REJECTED']);
    $company->update(['kyc_status' => 'REJECTED']);

    $this->actingAs($user)->post(route('kyc.resubmit'), [
        'requested_role' => 'seller',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertRedirect(route('kyc.index'));

    expect($company->fresh()->kyc_status)->toBe('PENDING_VERIFICATION');

    // Approve, then resubmit must fail.
    $company->update(['kyc_status' => 'APPROVED']);
    KycSubmission::query()->update(['status' => 'APPROVED', 'decision' => 'APPROVED']);

    $this->actingAs($user)->post(route('kyc.resubmit'), [
        'requested_role' => 'seller',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertSessionHasErrors('kyc');
});

it('allows a legal member to submit and blocks a viewer', function () {
    $legal = makeUser();
    addMember($legal, makeCompany(), 'legal');

    $this->actingAs($legal)->post(route('kyc.submit'), [
        'requested_role' => 'buyer',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertRedirect(route('kyc.index'));

    $viewer = makeUser();
    addMember($viewer, makeCompany(), 'viewer');

    $this->actingAs($viewer)->post(route('kyc.submit'), [
        'requested_role' => 'buyer',
        'documents' => ['company_registration' => '1', 'tax_identification' => '1', 'company_deed' => '1', 'director_identity' => '1', 'beneficial_owner_documents' => '1', 'bank_statement' => '1', 'signatory_appointment' => '1'],
        'document_expiries' => ['company_registration' => '2030-01-01', 'tax_identification' => '2030-01-01'],
        'beneficial_owners' => [],
    ])->assertForbidden();
});

it('validates uploaded documents by content and size', function () {
    $user = makeCompanyUser('company_admin');
    $company = $user->company();
    makeKycSubmission($company, $user);
    Storage::fake();

    $this->actingAs($user)->post(route('kyc.document.store'), [
        'type' => 'director_identity',
        'expiry' => '2030-01-01',
        'document' => UploadedFile::fake()->create('scan.txt', 10, 'text/plain'),
    ])->assertSessionHasErrors('document');

    $this->actingAs($user)->post(route('kyc.document.store'), [
        'type' => 'director_identity',
        'expiry' => '2030-01-01',
        'document' => UploadedFile::fake()->create('big.pdf', 10241, 'application/pdf'),
    ])->assertSessionHasErrors('document');

    $this->actingAs($user)->post(route('kyc.document.store'), [
        'type' => 'director_identity',
        'expiry' => '2030-01-01',
        'document' => UploadedFile::fake()->create('ok.pdf', 10, 'application/pdf'),
    ])->assertRedirect(route('kyc.index'));
});

it('lets a platform admin approve a company and queues the approval email', function () {
    Mail::fake();

    $admin = makeUser(['is_platform_admin' => true]);
    $companyUser = makeCompanyUser('company_admin');
    $company = $companyUser->company();
    $submission = makeKycSubmission($company, $companyUser);

    $this->actingAs($admin)->post(route('kyc.approve', $submission))
        ->assertRedirect(route('admin.kyc.queue'));

    expect($company->fresh()->kyc_status)->toBe('APPROVED')
        ->and($company->fresh()->company_role)->toBe('seller');

    Mail::assertQueued(KycApprovedMail::class);
});

it('rejects a company decision without a reason', function () {
    Mail::fake();

    $admin = makeUser(['is_platform_admin' => true]);
    $companyUser = makeCompanyUser('company_admin');
    $company = $companyUser->company();
    $submission = makeKycSubmission($company, $companyUser);

    $this->actingAs($admin)->post(route('kyc.reject', $submission), ['decision_reason' => ''])
        ->assertSessionHasErrors('decision_reason');

    expect($company->fresh()->kyc_status)->toBe('NOT_SUBMITTED');
});

it('queues the rejection email with the reason when an admin rejects', function () {
    Mail::fake();

    $admin = makeUser(['is_platform_admin' => true]);
    $companyUser = makeCompanyUser('company_admin');
    $company = $companyUser->company();
    $submission = makeKycSubmission($company, $companyUser);

    $this->actingAs($admin)->post(route('kyc.reject', $submission), [
        'decision_reason' => 'Beneficial owner check failed.',
    ])->assertRedirect(route('admin.kyc.queue'));

    expect($company->fresh()->kyc_status)->toBe('REJECTED');

    Mail::assertQueued(KycRejectedMail::class);
});

it('creates a platform admin only with a strong password', function () {
    $this->artisan('greengrid:create-admin', ['email' => 'admin@example.com'])
        ->expectsQuestion('Password for the platform admin (min 12 characters):', 'password-123456')
        ->assertExitCode(0);

    $admin = User::query()->where('email', 'admin@example.com')->first();

    expect($admin)->not->toBeNull()
        ->and($admin->isPlatformAdmin())->toBeTrue();

    $this->artisan('greengrid:create-admin', ['email' => 'weak@example.com'])
        ->expectsQuestion('Password for the platform admin (min 12 characters):', 'short')
        ->assertExitCode(1);

    expect(User::query()->where('email', 'weak@example.com')->exists())->toBeFalse();
});
