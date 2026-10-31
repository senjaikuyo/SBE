<?php

it('renders the auth screens', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    '/login',
    '/register',
    '/forgot-password',
]);

it('sends guests to sign in for protected screens', function (string $uri) {
    $this->get($uri)->assertRedirect(route('login'));
})->with([
    '/company/create',
    '/two-factor-setup',
    '/profile',
    '/dashboard',
    '/company/members',
]);

it('renders the reset password screen from a signed link', function () {
    $url = \Illuminate\Support\Facades\URL::temporarySignedRoute(
        'password.reset',
        now()->addMinutes(60),
        ['token' => 'test-token', 'email' => 'user@example.com'],
    );

    $this->get($url)->assertOk()->assertSee('user@example.com');
});
