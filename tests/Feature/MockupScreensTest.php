<?php

it('renders every design mockup screen', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    '/',
    '/register',
    '/register/company',
    '/login',
    '/forgot-password',
    '/reset-password/example-token',
    '/privacy',
    '/terms',
    '/invitations/example-token',
    '/invitations/expired',
    '/two-factor-setup',
    '/two-factor-challenge',
    '/dashboard',
    '/profile',
    '/company/members',
    '/company/members/invite',
    '/company/kyc',
    '/company/kyc/submit',
    '/company/kyc/owners',
    '/company/kyc/resubmit',
    '/company/kyc/submitted',
    '/company/listings',
    '/company/listings/create',
    '/company/listings/create/energy',
    '/company/listings/create/carbon',
    '/company/listings/1',
    '/company/listings/1/edit',
    '/listings',
    '/listings/empty',
    '/admin',
    '/admin/kyc',
    '/admin/kyc/1',
    '/admin/listings',
    '/admin/listings/1',
    '/admin/certificates',
    '/admin/certificates/1',
    '/admin/settings',
    '/admin/approvals',
]);

it('renders every error page', function (string $view) {
    expect(view($view)->render())->toBeString()->not->toBeEmpty();
})->with(['errors.403', 'errors.404', 'errors.419', 'errors.429', 'errors.500']);
