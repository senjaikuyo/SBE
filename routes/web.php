<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mockup preview routes
|--------------------------------------------------------------------------
| Static screens for design review. Replaced by real controllers per FR.
| Every route below maps to a screen in design/sitemap-fase1.md.
*/

// Public (A)
Route::view('/', 'landing')->name('landing');
Route::view('/register', 'auth.register')->name('register');
Route::view('/register/company', 'auth.register-company')->name('register.company');
Route::view('/login', 'auth.login')->name('login');
Route::view('/forgot-password', 'auth.forgot-password')->name('password.request');
Route::view('/reset-password/{token}', 'auth.reset-password')->name('password.reset');
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');

// Invitations (C, H2)
Route::view('/invitations/expired', 'auth.invitation-expired')->name('invitations.expired');
Route::view('/invitations/{token}', 'auth.accept-invitation')->name('invitations.accept');

// Onboarding (B)
Route::view('/two-factor-setup', 'auth.two-factor-setup')->name('two-factor.setup');
Route::view('/two-factor-challenge', 'auth.two-factor-challenge')->name('two-factor.challenge');
Route::view('/dashboard', 'dashboard')->name('dashboard');
Route::view('/profile', 'company.profile')->name('profile');

// Members (B5, C2)
Route::view('/company/members', 'company.members.index')->name('members.index');
Route::view('/company/members/invite', 'company.members.invite')->name('members.invite');

// KYC (D)
Route::view('/company/kyc', 'company.kyc.status')->name('kyc.status');
Route::view('/company/kyc/submit', 'company.kyc.submit')->name('kyc.submit');
Route::view('/company/kyc/owners', 'company.kyc.owners')->name('kyc.owners');
Route::view('/company/kyc/resubmit', 'company.kyc.resubmit')->name('kyc.resubmit');
Route::view('/company/kyc/submitted', 'company.kyc.submitted')->name('kyc.submitted');

// Listings (E)
Route::view('/company/listings', 'company.listings.index')->name('listings.index');
Route::view('/company/listings/create', 'company.listings.create')->name('listings.create');
Route::view('/company/listings/create/energy', 'company.listings.create-energy')->name('listings.create.energy');
Route::view('/company/listings/create/carbon', 'company.listings.create-carbon')->name('listings.create.carbon');
Route::view('/company/listings/{id}/edit', 'company.listings.edit')->name('listings.edit');
Route::view('/company/listings/{id}', 'company.listings.show')->name('listings.show');

// Marketplace (F)
Route::view('/listings', 'marketplace.index')->name('marketplace.index');
Route::view('/listings/empty', 'marketplace.empty')->name('marketplace.empty');

// Admin (G)
Route::view('/admin', 'admin.overview')->name('admin.overview');
Route::view('/admin/kyc', 'admin.kyc.index')->name('admin.kyc.index');
Route::view('/admin/kyc/{id}', 'admin.kyc.show')->name('admin.kyc.show');
Route::view('/admin/listings', 'admin.listings.index')->name('admin.listings.index');
Route::view('/admin/listings/{id}', 'admin.listings.show')->name('admin.listings.show');
Route::view('/admin/certificates', 'admin.certificates.index')->name('admin.certificates.index');
Route::view('/admin/certificates/{id}', 'admin.certificates.show')->name('admin.certificates.show');
Route::view('/admin/settings', 'admin.settings')->name('admin.settings');
Route::view('/admin/approvals', 'admin.approvals.index')->name('admin.approvals.index');
