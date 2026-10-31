<?php

it('renders every design mockup screen', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    '/',
    '/register',
    '/register/company',
    '/privacy',
    '/terms',
    '/two-factor-setup',
    '/dashboard',
    '/company/kyc',
    '/company/kyc/submit',
    '/company/listings',
    '/company/listings/create',
    '/company/listings/create/energy',
    '/admin',
    '/admin/kyc',
    '/admin/kyc/1',
]);
