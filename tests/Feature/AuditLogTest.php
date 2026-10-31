<?php

use App\Models\AuditLog;
use App\Support\AuditLogger;
use Illuminate\Support\Facades\DB;

it('records a successful login in the audit log', function () {
    $user = makeUser(['email' => 'login@example.com']);

    $this->post('/login', [
        'email' => 'login@example.com',
        'password' => 'correct-horse-battery',
    ])->assertRedirect();

    $log = AuditLog::query()->where('action', 'auth.login')->first();

    expect($log)->not->toBeNull()
        ->and($log->actor_id)->toBe($user->id)
        ->and($log->entity)->toBe('user');
});

it('builds a hash chain and verifies it', function () {
    $logger = app(AuditLogger::class);

    $logger->record('test.first', 'things', '1');
    $logger->record('test.second', 'things', '2');
    $logger->record('test.third', 'things', '3');

    $rows = AuditLog::query()->orderBy('id')->get();

    expect($rows)->toHaveCount(3)
        ->and($rows[0]->hash_prev)->toBeNull()
        ->and($rows[1]->hash_prev)->toBe($rows[0]->hash)
        ->and($rows[2]->hash_prev)->toBe($rows[1]->hash);

    $this->artisan('audit:verify')->assertSuccessful();
});

it('reports a tampered row', function () {
    app(AuditLogger::class)->record('test.one', 'things', '1');
    app(AuditLogger::class)->record('test.two', 'things', '2');

    DB::table('audit_logs')->where('id', 1)->update(['reason' => 'rewritten outside the model']);

    $this->artisan('audit:verify')
        ->expectsOutputToContain('hash does not match the row content')
        ->assertFailed();
});

it('reports a removed row as a broken chain', function () {
    app(AuditLogger::class)->record('test.one', 'things', '1');
    app(AuditLogger::class)->record('test.two', 'things', '2');

    DB::table('audit_logs')->where('id', 1)->delete();

    $this->artisan('audit:verify')
        ->expectsOutputToContain('hash_prev does not match the previous row')
        ->assertFailed();
});

it('refuses updates through the model', function () {
    $log = app(AuditLogger::class)->record('test.one', 'things', '1');

    expect(fn () => $log->update(['reason' => 'nope']))
        ->toThrow(LogicException::class, 'audit_logs is append-only');
});

it('refuses deletes through the model', function () {
    $log = app(AuditLogger::class)->record('test.one', 'things', '1');

    expect(fn () => $log->delete())
        ->toThrow(LogicException::class, 'audit_logs is append-only');
});

it('records all required categories with reasons and actor IDs', function () {
    $admin = makeUser(['is_platform_admin' => true]);
    $logger = app(AuditLogger::class);

    // 1. Role change
    $logger->record('membership.role_changed', 'membership', '10', 'Promotion to procurement', $admin);
    // 2. KYC action
    $logger->record('kyc.approved', 'kyc_submission', '20', 'Documents verified against ACRA', $admin);
    // 3. Listing action
    $logger->record('listing.created', 'listing', '30', 'New solar project draft', $admin);
    // 4. Certificate action
    $logger->record('certificate.approved', 'certificate', '40', 'Serial checked on Verra registry', $admin);
    // 5. Admin action
    $logger->record('admin.approval_approved', 'approval_request', '50', 'Four-eyes approval confirmed', $admin);

    $this->artisan('audit:verify')->assertSuccessful();

    $logs = AuditLog::whereIn('action', [
        'membership.role_changed',
        'kyc.approved',
        'listing.created',
        'certificate.approved',
        'admin.approval_approved',
    ])->get();

    expect($logs)->toHaveCount(5);
    foreach ($logs as $l) {
        expect($l->hash)->not->toBeEmpty();
        expect($l->reason)->not->toBeEmpty();
        expect($l->actor_id)->toBe($admin->id);
    }
});
