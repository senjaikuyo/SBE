<?php

use App\Models\User;
use PragmaRX\Google2FA\Google2FA;

it('requires two-factor authentication before application features', function () {
    $user = makeUser();
    addMember($user, makeCompany());

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('two-factor.setup'));
});

it('still allows profile access before two-factor is set up', function () {
    $user = makeUser();
    addMember($user, makeCompany());

    $this->actingAs($user)->get('/profile')->assertOk();
});

it('generates a secret and shows the setup screen', function () {
    $user = makeUser();
    addMember($user, makeCompany());

    $this->actingAs($user)->get('/two-factor-setup')->assertOk();

    $user->refresh();
    expect($user->two_factor_secret)->not->toBeNull()
        ->and($user->two_factor_confirmed_at)->toBeNull();
});

it('confirms two-factor authentication with a valid code', function () {
    $user = makeUser();
    addMember($user, makeCompany());

    $this->actingAs($user)->get('/two-factor-setup');

    $user->refresh();
    $code = app(Google2FA::class)->getCurrentOtp(decrypt($user->two_factor_secret));

    $this->actingAs($user)
        ->post('/two-factor-setup', ['code' => $code])
        ->assertRedirect(route('two-factor.recovery-codes.show'));

    expect($user->fresh()->two_factor_confirmed_at)->not->toBeNull();

    $this->actingAs($user->fresh())->get('/dashboard')->assertOk();
});

it('rejects an invalid confirmation code', function () {
    $user = makeUser();
    addMember($user, makeCompany());

    $this->actingAs($user)->get('/two-factor-setup');

    $this->actingAs($user)
        ->from('/two-factor-setup')
        ->post('/two-factor-setup', ['code' => '000000'])
        ->assertRedirect('/two-factor-setup')
        ->assertSessionHasErrors('code');

    expect($user->fresh()->two_factor_confirmed_at)->toBeNull();
});

it('verifies a login challenge with a valid totp code', function () {
    $user = makeConfirmedUser(['email' => 'challenge@example.com']);
    addMember($user, makeCompany());

    $this->post('/login', [
        'email' => 'challenge@example.com',
        'password' => 'correct-horse-battery',
    ])->assertRedirect();

    $code = app(Google2FA::class)->getCurrentOtp(decrypt(User::query()->first()->two_factor_secret));

    $this->post('/two-factor-challenge', ['code' => $code])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();
});

it('shows recovery codes for a confirmed user', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)
        ->get(route('two-factor.recovery-codes.show'))
        ->assertOk()
        ->assertSee('aaaaa-bbbbb');
});
