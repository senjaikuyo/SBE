<?php

namespace App\Providers;

use App\Support\AuditLogger;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
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
