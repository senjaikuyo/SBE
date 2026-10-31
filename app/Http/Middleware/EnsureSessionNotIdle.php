<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSessionNotIdle
{
    private const SESSION_KEY = 'greengrid.last_activity_at';

    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $timeout = (int) config('greengrid.session_idle_minutes');
        $lastActivity = $request->session()->get(self::SESSION_KEY);

        if ($lastActivity !== null
            && Carbon::parse($lastActivity)->addMinutes($timeout)->isPast()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Your session ended after '.$timeout.' minutes of inactivity.');
        }

        $request->session()->put(self::SESSION_KEY, now()->toIso8601String());

        $user = $request->user();

        if ($user->last_activity_at === null || $user->last_activity_at->diffInMinutes(now()) >= 1) {
            $user->forceFill(['last_activity_at' => now()])->saveQuietly();
        }

        return $next($request);
    }
}
