<?php

use App\Models\AuditLog;
use App\Models\Company;
use App\Models\Membership;

it('registers a user with email and password', function () {
    $response = $this->post('/register', [
        'name' => 'Aditya Wirawan',
        'email' => 'aditya@nusantarasolar.co.id',
        'password' => 'twelve-chars-ok',
        'password_confirmation' => 'twelve-chars-ok',
    ]);

    $response->assertRedirect();
    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'email' => 'aditya@nusantarasolar.co.id',
    ]);
});

it('rejects a duplicate email', function () {
    makeUser(['email' => 'taken@example.com']);

    $response = $this->from('/register')->post('/register', [
        'name' => 'Someone Else',
        'email' => 'taken@example.com',
        'password' => 'twelve-chars-ok',
        'password_confirmation' => 'twelve-chars-ok',
    ]);

    $response->assertRedirect('/register');
    $response->assertSessionHasErrors('email');
    $this->assertGuest();
    expect(\App\Models\User::query()->where('email', 'taken@example.com')->count())->toBe(1);
});

it('requires a password of at least 12 characters', function () {
    $response = $this->from('/register')->post('/register', [
        'name' => 'Short Password',
        'email' => 'short@example.com',
        'password' => 'elevenchars',
        'password_confirmation' => 'elevenchars',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'short@example.com']);
});

it('accepts exactly 12 characters', function () {
    $this->post('/register', [
        'name' => 'Boundary User',
        'email' => 'boundary@example.com',
        'password' => 'twelve-chars',
        'password_confirmation' => 'twelve-chars',
    ])->assertSessionHasNoErrors();

    $this->assertDatabaseHas('users', ['email' => 'boundary@example.com']);
});

it('makes the first user of a company its company_admin', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)->post('/company/create', [
        'name' => 'Nusantara Solar Prima',
        'country' => 'ID',
        'registration_number' => 'NIB-123',
    ])->assertRedirect(route('two-factor.setup'));

    $company = Company::query()->firstWhere('name', 'Nusantara Solar Prima');
    expect($company)->not->toBeNull();

    $membership = Membership::query()
        ->where('user_id', $user->id)
        ->where('company_id', $company->id)
        ->first();

    expect($membership)->not->toBeNull()
        ->and($membership->role)->toBe('company_admin');
});

it('blocks dashboard access until a company exists', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertRedirect(route('company.create'));
});

it('validates the company country against configuration', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)->post('/company/create', [
        'name' => 'Nowhere Ltd',
        'country' => 'XX',
    ])->assertSessionHasErrors('country');

    expect(Company::query()->count())->toBe(0);
});
