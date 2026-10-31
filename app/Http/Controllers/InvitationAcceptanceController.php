<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\Membership;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class InvitationAcceptanceController extends Controller
{
    public function show(Request $request, string $token): View
    {
        $invitation = $this->findByToken($token);

        if ($invitation === null || ! $invitation->isPending()) {
            return view('auth.invitation-expired');
        }

        return view('auth.accept-invitation', [
            'invitation' => $invitation,
            'token' => $token,
            'existingUser' => User::query()->where('email', $invitation->email)->exists(),
        ]);
    }

    public function store(Request $request, string $token, AuditLogger $audit): RedirectResponse
    {
        $invitation = $this->findByToken($token);

        if ($invitation === null || ! $invitation->isPending()) {
            return redirect()->route('invitations.expired');
        }

        $user = $request->user();

        if ($user !== null && Str::lower($user->email) !== $invitation->email) {
            abort(403, 'This invitation belongs to a different email address.');
        }

        if ($user === null) {
            $existing = User::query()->where('email', $invitation->email)->exists();

            if ($existing) {
                return redirect()->route('login')
                    ->with('status', 'Sign in with '.$invitation->email.' to accept this invitation.');
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', Password::default(), 'confirmed'],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
            ]);
        }

        DB::transaction(function () use ($invitation, $user, $audit): void {
            Membership::firstOrCreate(
                ['user_id' => $user->id, 'company_id' => $invitation->company_id],
                ['role' => $invitation->role],
            );

            $invitation->update(['accepted_at' => now()]);

            $audit->record(
                action: 'invitation.accepted',
                entity: 'invitation',
                entityId: (string) $invitation->id,
                actor: $user,
            );
        });

        if (! Auth::check()) {
            Auth::login($user);
        }

        return redirect()->route('two-factor.setup');
    }

    private function findByToken(string $token): ?Invitation
    {
        return Invitation::query()->where('token', hash('sha256', $token))->first();
    }
}
