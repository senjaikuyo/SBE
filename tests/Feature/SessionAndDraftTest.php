<?php

it('ends a session after the configured idle time', function () {
    $user = makeCompanyUser();

    $this->actingAs($user)
        ->withSession([
            'greengrid.last_activity_at' => now()->subMinutes(31)->toIso8601String(),
        ])
        ->get('/dashboard')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('keeps a session alive within the idle time', function () {
    $user = makeCompanyUser();

    $this->actingAs($user)
        ->withSession([
            'greengrid.last_activity_at' => now()->subMinutes(5)->toIso8601String(),
        ])
        ->get('/dashboard')
        ->assertOk();
});

it('saves and restores a form draft for the signed-in user', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)->postJson('/drafts/company.create', [
        'payload' => ['name' => 'Draft Co', 'country' => 'ID'],
    ])->assertOk();

    $this->actingAs($user)->getJson('/drafts/company.create')
        ->assertOk()
        ->assertJsonPath('payload.name', 'Draft Co')
        ->assertJsonPath('payload.country', 'ID');
});

it('keeps drafts private to their owner', function () {
    $owner = makeConfirmedUser();
    $other = makeConfirmedUser();

    $this->actingAs($owner)->postJson('/drafts/company.create', [
        'payload' => ['name' => 'Secret Draft'],
    ])->assertOk();

    $this->actingAs($other)->getJson('/drafts/company.create')
        ->assertOk()
        ->assertJsonPath('payload', null);
});

it('rejects an invalid draft key', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)
        ->postJson('/drafts/Not A Key', ['payload' => ['a' => 'b']])
        ->assertNotFound();
});

it('restores a saved draft into the company form', function () {
    $user = makeConfirmedUser();

    $this->actingAs($user)->postJson('/drafts/company.create', [
        'payload' => ['name' => 'Draft Company Ltd', 'country' => 'MY'],
    ])->assertOk();

    $this->actingAs($user)
        ->get('/company/create')
        ->assertOk()
        ->assertSee('Draft Company Ltd');
});
