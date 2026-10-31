<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DraftController;
use App\Http\Controllers\InvitationAcceptanceController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\MembersController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TwoFactorSetupController;
use App\Http\Controllers\AdminCertificateController;
use App\Http\Controllers\AdminKycController;
use App\Http\Controllers\KycController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::view('/', 'landing')->name('landing');
Route::view('/privacy', 'legal.privacy')->name('privacy');
Route::view('/terms', 'legal.terms')->name('terms');

Route::view('/invitations/expired', 'auth.invitation-expired')->name('invitations.expired');

Route::get('/invitations/{token}', [InvitationAcceptanceController::class, 'show'])
    ->middleware('throttle:20,1')
    ->name('invitations.accept');

Route::post('/invitations/{token}', [InvitationAcceptanceController::class, 'store'])
    ->middleware('throttle:20,1')
    ->name('invitations.store');

/*
|--------------------------------------------------------------------------
| Onboarding (authenticated, before company and 2FA gates)
|--------------------------------------------------------------------------
| Order from the sitemap: register -> create company -> set up 2FA -> dashboard.
*/

Route::middleware('auth')->group(function (): void {
    Route::get('/company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('/company/create', [CompanyController::class, 'store'])->name('company.store');

    Route::get('/two-factor-setup', [TwoFactorSetupController::class, 'show'])->name('two-factor.setup');
    Route::post('/two-factor-setup', [TwoFactorSetupController::class, 'store'])->name('two-factor.setup.store');
    Route::get('/two-factor-recovery-codes', [TwoFactorSetupController::class, 'recoveryCodes'])->name('two-factor.recovery-codes.show');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::get('/drafts/{key}', [DraftController::class, 'show'])->name('drafts.show');
    Route::post('/drafts/{key}', [DraftController::class, 'store'])->name('drafts.store');
});

/*
|--------------------------------------------------------------------------
| Application (company selected and 2FA confirmed)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'company', 'two-factor'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/listings/create', [ListingController::class, 'create'])->name('listings.create');
    Route::post('/listings', [ListingController::class, 'store'])->name('listings.store');

    Route::get('/company/members', [MembersController::class, 'index'])->name('members.index');
    Route::get('/company/members/invite', [MembersController::class, 'create'])->name('members.invite');
    Route::post('/company/members/invite', [MembersController::class, 'store'])->name('members.store');
    Route::patch('/company/members/{membership}', [MembersController::class, 'update'])->name('members.update');
    Route::delete('/company/members/{membership}', [MembersController::class, 'destroy'])->name('members.destroy');
});

Route::get('/admin/kyc/queue', [AdminKycController::class, 'queue'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.kyc.queue');

Route::post('/admin/kyc/submissions/{kyc}/approve', [AdminKycController::class, 'approve'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('kyc.approve');

Route::post('/admin/kyc/submissions/{kyc}/reject', [AdminKycController::class, 'reject'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('kyc.reject');

Route::get('/admin/certificates', [AdminCertificateController::class, 'queue'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.certificates.queue');

Route::get('/admin/certificates/queue', [AdminCertificateController::class, 'queue'])
    ->middleware(['auth', 'can:manage,App\\Models\\User']);

Route::get('/admin/certificates/{certificate}', [AdminCertificateController::class, 'show'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.certificates.show');

Route::post('/admin/certificates/{certificate}/approve', [AdminCertificateController::class, 'approve'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('certificates.approve');

Route::post('/admin/certificates/{certificate}/approve-admin', [AdminCertificateController::class, 'approve'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.certificates.approve');

Route::post('/admin/certificates/{certificate}/reject', [AdminCertificateController::class, 'reject'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('certificates.reject');

Route::post('/admin/certificates/{certificate}/reject-admin', [AdminCertificateController::class, 'reject'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.certificates.reject');

Route::post('/admin/certificates/{certificate}/decision', [AdminCertificateController::class, 'decision'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('certificates.decision');

Route::post('/admin/certificates/{certificate}/decision-admin', [AdminCertificateController::class, 'decision'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.certificates.decision');

Route::post('/admin/listings/{listing}/approve', [AdminCertificateController::class, 'approveListing'])
    ->middleware(['auth', 'can:manage,App\\Models\\User'])
    ->name('admin.listings.approve');

/*
|--------------------------------------------------------------------------
| Design previews for screens not implemented yet
|--------------------------------------------------------------------------
| Static mockups only. Each is replaced by a real controller in its own FR.
*/

Route::middleware(['auth', 'company'])->group(function (): void {
    Route::get('/company/kyc', [KycController::class, 'index'])->name('kyc.index');
    Route::get('/company/kyc/submit', [KycController::class, 'form'])->name('kyc.form');
    Route::post('/company/kyc/submit', [KycController::class, 'submit'])->name('kyc.submit');
    Route::post('/company/kyc/resubmit', [KycController::class, 'resubmit'])->name('kyc.resubmit');
    Route::post('/company/kyc/documents', [KycController::class, 'storeDocument'])->name('kyc.document.store');
    Route::get('/company/kyc/documents/{document}/download', [KycController::class, 'download'])->name('kyc.document.download');
});

Route::view('/company/kyc/owners', 'company.kyc.owners')->name('preview.kyc.owners');
Route::view('/company/kyc/resubmit', 'company.kyc.resubmit')->name('preview.kyc.resubmit');
Route::view('/company/kyc/submitted', 'company.kyc.submitted')->name('preview.kyc.submitted');

Route::view('/company/listings', 'company.listings.index')->name('preview.listings.index');
Route::view('/company/listings/create', 'company.listings.create')->name('preview.listings.create');
Route::view('/company/listings/create/energy', 'company.listings.create-energy')->name('preview.listings.create.energy');
Route::view('/company/listings/create/carbon', 'company.listings.create-carbon')->name('preview.listings.create.carbon');
Route::view('/company/listings/{id}/edit', 'company.listings.edit')->name('preview.listings.edit');
Route::view('/company/listings/{id}', 'company.listings.show')->name('preview.listings.show');

Route::get('/listings', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::view('/listings/empty', 'marketplace.empty')->name('preview.marketplace.empty');
Route::get('/listings/{listing}', [MarketplaceController::class, 'show'])->name('marketplace.show');

Route::view('/admin', 'admin.overview')->name('preview.admin.overview');
Route::view('/admin/kyc', 'admin.kyc.index')->name('preview.admin.kyc.index');
Route::view('/admin/kyc/{id}', 'admin.kyc.show')->name('preview.admin.kyc.show');
Route::view('/admin/listings', 'admin.listings.index')->name('preview.admin.listings.index');
Route::view('/admin/listings/{id}', 'admin.listings.show')->name('preview.admin.listings.show');
Route::view('/admin/settings', 'admin.settings')->name('preview.admin.settings');
Route::view('/admin/approvals', 'admin.approvals.index')->name('preview.admin.approvals.index');
