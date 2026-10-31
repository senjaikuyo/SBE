<?php

it('renders every remaining design mockup screen', function (string $uri) {
    $this->get($uri)->assertOk();
})->with([
    '/',
    '/privacy',
    '/terms',
    '/invitations/expired',
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
