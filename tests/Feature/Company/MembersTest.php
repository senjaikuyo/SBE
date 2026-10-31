<?php

use App\Mail\MemberInvitationMail;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

function makeInvitation(Company $company, User $inviter, string $email = 'new@example.com', string $role = 'procurement', ?string $expiresAt = null): array
{
    $token = Str::random(64);

    $invitation = Invitation::create([
        'company_id' => $company->id,
        'invited_by' => $inviter->id,
        'email' => $email,
        'role' => $role,
        'token' => hash('sha256', $token),
        'expires_at' => $expiresAt ? now()->parse($expiresAt) : now()->addDays(7),
    ]);

    return [$invitation, $token];
}

it('lets a company admin invite a member by email', function () {
    Mail::fake();

    $admin = makeCompanyUser('company_admin');
    $company = $admin->company();

    $this->actingAs($admin)->post(route('members.store'), [
        'email' => 'sari@example.com',
        'role' => 'procurement',
    ])->assertRedirect(route('members.index'));

    $invitation = Invitation::query()->firstWhere('email', 'sari@example.com');

    expect($invitation)->not->toBeNull()
        ->and($invitation->role)->toBe('procurement')
        ->and($invitation->company_id)->toBe($company->id)
        ->and($invitation->expires_at->isSameDay(now()->addDays(7)))->toBeTrue();

    Mail::assertQueued(MemberInvitationMail::class);
});

it('does not let other roles invite members', function () {
    Mail::fake();

    $viewer = makeCompanyUser('viewer');

    $this->actingAs($viewer)->get(route('members.invite'))->assertForbidden();
    $this->actingAs($viewer)->post(route('members.store'), [
        'email' => 'nope@example.com',
        'role' => 'procurement',
    ])->assertForbidden();

    expect(Invitation::query()->count())->toBe(0);
    Mail::assertNothingQueued();
});

it('refuses to invite an existing member', function () {
    Mail::fake();

    $admin = makeCompanyUser('company_admin');
    $member = makeConfirmedUser(['email' => 'existing@example.com']);
    addMember($member, $admin->company(), 'viewer');

    $this->actingAs($admin)->from(route('members.invite'))->post(route('members.store'), [
        'email' => 'existing@example.com',
        'role' => 'procurement',
    ])->assertSessionHasErrors('email');

    expect(Invitation::query()->count())->toBe(0);
});

it('lets an invited guest create an account and join the company', function () {
    $admin = makeCompanyUser('company_admin');
    [$invitation, $token] = makeInvitation($admin->company(), $admin);

    $this->get(route('invitations.accept', $token))->assertOk();

    $this->post(route('invitations.store', $token), [
        'name' => 'Sari Dewi',
        'password' => 'twelve-chars-ok',
        'password_confirmation' => 'twelve-chars-ok',
    ])->assertRedirect(route('two-factor.setup'));

    $this->assertAuthenticated();

    $user = User::query()->firstWhere('email', 'new@example.com');

    expect($user)->not->toBeNull()
        ->and($user->membershipFor($admin->company())?->role)->toBe('procurement')
        ->and($invitation->fresh()->accepted_at)->not->toBeNull();
});

it('rejects an expired invitation', function () {
    $admin = makeCompanyUser('company_admin');
    [$invitation, $token] = makeInvitation($admin->company(), $admin, expiresAt: '-1 day');

    $this->get(route('invitations.accept', $token))
        ->assertOk()
        ->assertSee('This invitation is no longer valid');

    $this->post(route('invitations.store', $token), [
        'name' => 'Too Late',
        'password' => 'twelve-chars-ok',
        'password_confirmation' => 'twelve-chars-ok',
    ])->assertRedirect(route('invitations.expired'));

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
    expect($invitation->fresh()->accepted_at)->toBeNull();
});

it('tells an existing account to sign in before accepting', function () {
    $admin = makeCompanyUser('company_admin');
    $existing = makeConfirmedUser(['email' => 'existing@example.com']);
    [, $token] = makeInvitation($admin->company(), $admin, email: 'existing@example.com');

    $this->get(route('invitations.accept', $token))
        ->assertOk()
        ->assertSee('An account already exists for this email');

    $this->actingAs($existing)->post(route('invitations.store', $token))
        ->assertRedirect(route('two-factor.setup'));

    expect($existing->membershipFor($admin->company())?->role)->toBe('procurement');
});

it('refuses an invitation acceptance for a different signed-in email', function () {
    $admin = makeCompanyUser('company_admin');
    $other = makeConfirmedUser(['email' => 'other@example.com']);
    [, $token] = makeInvitation($admin->company(), $admin, email: 'invited@example.com');

    $this->actingAs($other)->post(route('invitations.store', $token))->assertForbidden();
});

it('changes a member role and records it in the audit log', function () {
    $admin = makeCompanyUser('company_admin');
    $member = makeConfirmedUser(['email' => 'member@example.com']);
    $membership = addMember($member, $admin->company(), 'viewer');

    $this->actingAs($admin)->patch(route('members.update', $membership), [
        'role' => 'finance',
    ])->assertRedirect();

    expect($membership->fresh()->role)->toBe('finance');

    $log = AuditLog::query()->where('action', 'member.role_changed')->latest('id')->first();

    expect($log)->not->toBeNull()
        ->and($log->entity_id)->toBe((string) $membership->id)
        ->and($log->actor_id)->toBe($admin->id);
});

it('does not let a non-admin change roles', function () {
    $owner = makeCompanyUser('company_admin');
    $viewer = makeConfirmedUser();
    addMember($viewer, $owner->company(), 'viewer');
    $target = makeConfirmedUser();
    $membership = addMember($target, $owner->company(), 'procurement');

    $this->actingAs($viewer)->patch(route('members.update', $membership), [
        'role' => 'finance',
    ])->assertForbidden();

    expect($membership->fresh()->role)->toBe('procurement');
});

it('removes a member and records it in the audit log', function () {
    $admin = makeCompanyUser('company_admin');
    $member = makeConfirmedUser();
    $membership = addMember($member, $admin->company(), 'viewer');

    $this->actingAs($admin)
        ->delete(route('members.destroy', $membership))
        ->assertRedirect();

    $this->assertDatabaseMissing('memberships', ['id' => $membership->id]);
    expect(AuditLog::query()->where('action', 'member.removed')->exists())->toBeTrue();
});

it('rejects member actions on another company', function () {
    $admin = makeCompanyUser('company_admin');
    $otherCompany = makeCompany(['name' => 'Other Co']);
    $membership = addMember(makeConfirmedUser(), $otherCompany, 'viewer');

    $this->actingAs($admin)
        ->patch(route('members.update', $membership), ['role' => 'finance'])
        ->assertNotFound();

    $this->actingAs($admin)
        ->delete(route('members.destroy', $membership))
        ->assertNotFound();
});
