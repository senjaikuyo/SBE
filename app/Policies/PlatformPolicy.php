<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlatformPolicy
{
    use AuthorizesRequests;

    public function manage(Authenticatable $user, ?Company $company = null): bool
    {
        return $user->isPlatformAdmin() === true;
    }

    public function handle(Request $request, Closure $next, string $ability, ...$args): Response
    {
        if (! $this->manage($request->user())) {
            abort(403);
        }

        return $next($request, ...$args);
    }
}
