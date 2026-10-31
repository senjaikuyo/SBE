<?php

use App\Models\ApprovalRequest;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\Listing;
use App\Models\Setting;
use App\Models\User;

beforeEach(function () {
    $this->admin = makeUser(['is_platform_admin' => true]);
    $this->secondAdmin = makeUser(['is_platform_admin' => true]);
    $this->companyUser = makeCompanyUser('company_admin');
});

it('(a) platform admin can view each built admin page', function () {
    $this->actingAs($this->admin)->get(route('admin.overview'))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.kyc.index'))->assertOk();
    $this->actingAs($this->admin)->get(route('preview.admin.kyc.show', 1))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.listings.index'))->assertOk();
    $this->actingAs($this->admin)->get(route('preview.admin.listings.show', 1))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.settings'))->assertOk();
    $this->actingAs($this->admin)->get(route('admin.approvals.index'))->assertOk();
});

it('(b) regular company user gets 403 on every admin page', function () {
    $this->actingAs($this->companyUser)->get(route('admin.overview'))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('admin.kyc.index'))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('preview.admin.kyc.show', 1))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('admin.listings.index'))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('preview.admin.listings.show', 1))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('admin.settings'))->assertForbidden();
    $this->actingAs($this->companyUser)->get(route('admin.approvals.index'))->assertForbidden();
});

it('(c) unauthenticated guest is redirected to login for each admin page', function () {
    $this->get(route('admin.overview'))->assertRedirect(route('login'));
    $this->get(route('admin.kyc.index'))->assertRedirect(route('login'));
    $this->get(route('preview.admin.kyc.show', 1))->assertRedirect(route('login'));
    $this->get(route('admin.listings.index'))->assertRedirect(route('login'));
    $this->get(route('preview.admin.listings.show', 1))->assertRedirect(route('login'));
    $this->get(route('admin.settings'))->assertRedirect(route('login'));
    $this->get(route('admin.approvals.index'))->assertRedirect(route('login'));
});

it('(d) AC 1: displays KYC queue with remaining decision SLA', function () {
    $company = $this->companyUser->company();
    $sub = KycSubmission::create([
        'company_id' => $company->id,
        'country' => $company->country ?? 'ID',
        'submitted_by' => $this->companyUser->id,
        'requested_role' => 'seller',
        'status' => 'PENDING_VERIFICATION',
        'submitted_at' => now()->subHours(10),
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.kyc.index'));
    $response->assertOk();
    $response->assertSee($company->name);
    $response->assertSee('h left');
});

it('(d) AC 2: displays listings moderation queue', function () {
    $company = $this->companyUser->company();
    $listing = Listing::create([
        'company_id' => $company->id,
        'type' => 'energy',
        'capacity_mw' => 20,
        'location' => 'Java Admin Solar',
        'status' => 'PENDING_REVIEW',
        'version' => 1,
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.listings.index'));
    $response->assertOk();
    $response->assertSee('Java Admin Solar');
});

it('(d) AC 3: fee setting is initially empty and stored in settings table', function () {
    expect(Setting::get('marketplace_fee_percentage'))->toBeNull();

    $response = $this->actingAs($this->admin)->get(route('admin.settings'));
    $response->assertOk();
    $response->assertSee('Not set');
});

it('(d) AC 5: four-eyes approval works with two different admins and records audit log', function () {
    // Admin 1 proposes fee change
    $proposeResponse = $this->actingAs($this->admin)->post(route('admin.approvals.propose'), [
        'action' => 'set_fee_percentage',
        'payload' => ['fee_percentage' => '2.50'],
        'reason' => 'Proposed pilot platform fee of 2.50%',
    ]);
    $proposeResponse->assertRedirect(route('admin.approvals.index'));

    $approval = ApprovalRequest::where('action', 'set_fee_percentage')->first();
    expect($approval)->not->toBeNull();
    expect($approval->status)->toBe('PENDING_REVIEW');
    expect($approval->proposed_by_id)->toBe($this->admin->id);

    // Verify audit log for proposal
    $proposedLog = AuditLog::where('action', 'admin.approval_proposed')->latest('id')->first();
    expect($proposedLog)->not->toBeNull();
    expect($proposedLog->reason)->toBe('Proposed pilot platform fee of 2.50%');

    // Admin 2 approves the request
    $approveResponse = $this->actingAs($this->secondAdmin)->post(route('admin.approvals.approve', $approval), [
        'reason' => 'Confirmed 2.50% fee rate with leadership.',
    ]);
    $approveResponse->assertRedirect(route('admin.approvals.index'));

    $approval->refresh();
    expect($approval->status)->toBe('APPROVED');
    expect($approval->reviewed_by_id)->toBe($this->secondAdmin->id);
    expect(Setting::get('marketplace_fee_percentage'))->toBe('2.50');

    // Verify audit log for approval
    $approvedLog = AuditLog::where('action', 'admin.approval_approved')->latest('id')->first();
    expect($approvedLog)->not->toBeNull();
    expect($approvedLog->reason)->toBe('Confirmed 2.50% fee rate with leadership.');
});

it('(e) failure path: proposing an approval request without reason fails validation', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.approvals.propose'), [
        'action' => 'set_fee_percentage',
        'payload' => ['fee_percentage' => '1.50'],
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['reason']);
    expect(ApprovalRequest::count())->toBe(0);
});

it('(e) failure path: admin cannot approve their own proposal (four-eyes rule)', function () {
    $approval = ApprovalRequest::create([
        'action' => 'set_fee_percentage',
        'payload' => ['fee_percentage' => '3.00'],
        'reason' => 'Proposing 3% fee',
        'status' => 'PENDING_REVIEW',
        'proposed_by_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->admin)->post(route('admin.approvals.approve', $approval), [
        'reason' => 'Attempting self-approval',
    ]);

    $response->assertForbidden();
    expect($approval->fresh()->status)->toBe('PENDING_REVIEW');
});

it('(e) failure path: admin cannot process request for company where they have an interest (conflict of interest)', function () {
    $company = $this->companyUser->company();

    // Make secondAdmin also a member of that company
    $this->secondAdmin->memberships()->create([
        'company_id' => $company->id,
        'role' => 'finance',
    ]);

    $approval = ApprovalRequest::create([
        'action' => 'reapprove_company',
        'entity_type' => 'company',
        'entity_id' => $company->id,
        'reason' => 'Proposing reapproval of company',
        'status' => 'PENDING_REVIEW',
        'proposed_by_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->secondAdmin)->post(route('admin.approvals.approve', $approval), [
        'reason' => 'Trying to approve company where I am a member',
    ]);

    $response->assertForbidden();
    expect($approval->fresh()->status)->toBe('PENDING_REVIEW');

    // Audit log records conflict of interest
    $conflictLog = AuditLog::where('action', 'admin.conflict_of_interest')->latest('id')->first();
    expect($conflictLog)->not->toBeNull();
    expect($conflictLog->reason)->toBe('conflict of interest');
});

it('(e) failure path: decision without reason fails validation', function () {
    $approval = ApprovalRequest::create([
        'action' => 'set_fee_percentage',
        'payload' => ['fee_percentage' => '4.00'],
        'reason' => 'Fee proposal',
        'status' => 'PENDING_REVIEW',
        'proposed_by_id' => $this->admin->id,
    ]);

    $response = $this->actingAs($this->secondAdmin)->post(route('admin.approvals.approve', $approval), [
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['reason']);
    expect($approval->fresh()->status)->toBe('PENDING_REVIEW');
});
