<?php

namespace App\Providers;

use App\Models\KycDocument;
use App\Models\Listing;
use App\Models\User;
use App\Policies\CompanyPolicy;
use App\Policies\PlatformPolicy;
use App\Support\AuditLogger;
use App\Support\StubSanctionsScreener;
use App\Support\StubVirusScanner;
use App\Support\VirusScanner;
use App\Support\SanctionsScreener;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PasswordRule::defaults(fn () => PasswordRule::min(
            (int) config('greengrid.password_min_length')
        ));

        Gate::policy(User::class, PlatformPolicy::class);
        Gate::policy(KycDocument::class, CompanyPolicy::class);
        Gate::policy(Listing::class, ListingPolicy::class);

        $this->app->bind(SanctionsScreener::class, StubSanctionsScreener::class);
        $this->app->bind(VirusScanner::class, StubVirusScanner::class);

        Schedule::call('kyc:screening')->cron('0 3 15 1,4,7,10 *');

        Event::listen(Login::class, function (Login $event): void {
            app(AuditLogger::class)->record(
                action: 'auth.login',
                entity: 'user',
                entityId: (string) $event->user->getAuthIdentifier(),
                actor: $event->user,
            );
        });
    }
}
