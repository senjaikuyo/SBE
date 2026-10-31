<?php

use App\Models\Company;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeUser(array $attributes = []): User
{
    return User::factory()->create(array_merge([
        'password' => Hash::make('correct-horse-battery'),
    ], $attributes));
}

function makeConfirmedUser(array $attributes = []): User
{
    return makeUser(array_merge([
        'two_factor_secret' => encrypt('JBSWY3DPEHPK3PXP'),
        'two_factor_recovery_codes' => encrypt(json_encode(['aaaaa-bbbbb'])),
        'two_factor_confirmed_at' => now(),
    ], $attributes));
}

function makeCompany(array $attributes = []): Company
{
    return Company::create(array_merge([
        'name' => 'Nusantara Solar Prima',
        'country' => 'ID',
    ], $attributes));
}

function addMember(User $user, Company $company, string $role = 'company_admin'): Membership
{
    return Membership::create([
        'user_id' => $user->id,
        'company_id' => $company->id,
        'role' => $role,
    ]);
}

function makeCompanyUser(string $role = 'company_admin', array $attributes = []): User
{
    $user = makeConfirmedUser($attributes);
    addMember($user, makeCompany(), $role);

    return $user;
}
