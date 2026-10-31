<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Mockup preview routes
|--------------------------------------------------------------------------
| Static screens for design review. Replaced by real controllers per FR.
| Every route below maps to a screen in design/sitemap-fase1.md.
*/

Route::view('/', 'landing')->name('landing');

// Public (A)
Route::view('/register', 'auth.register')->name('register');
Route::view('/register/company', 'auth.register-company')->name('register.company');
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');

// Onboarding (B)
Route::view('/two-factor-setup', 'auth.two-factor-setup')->name('two-factor.setup');
Route::view('/dashboard', 'dashboard')->name('dashboard');

// KYC (D)
Route::view('/company/kyc', 'dashboard')->name('kyc.status');
Route::view('/company/kyc/submit', 'company.kyc.submit')->name('kyc.submit');

// Listings (E)
Route::view('/company/listings', 'dashboard')->name('listings.index');
Route::view('/company/listings/create', 'dashboard')->name('listings.create');
Route::view('/company/listings/create/energy', 'company.listings.create-energy')->name('listings.create.energy');

// Admin (G)
Route::view('/admin', 'admin.kyc.index')->name('admin.dashboard');
Route::view('/admin/kyc', 'admin.kyc.index')->name('admin.kyc.index');
Route::view('/admin/kyc/{id}', 'admin.kyc.show')->name('admin.kyc.show');
