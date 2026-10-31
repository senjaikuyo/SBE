<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;

class TwoFactorSetupController extends Controller
{
    public function show(Request $request, EnableTwoFactorAuthentication $enable): View|RedirectResponse
    {
        $user = $request->user();

        if ($user->hasTwoFactorEnabled()) {
            return redirect()->route('dashboard');
        }

        if ($user->two_factor_secret === null) {
            $enable($user);
            $user->refresh();
        }

        return view('auth.two-factor-setup', [
            'qrCodeSvg' => $user->twoFactorQrCodeSvg(),
            'secret' => decrypt($user->two_factor_secret),
        ]);
    }

    public function store(Request $request, ConfirmTwoFactorAuthentication $confirm): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string'],
        ]);

        try {
            $confirm($request->user(), $validated['code']);
        } catch (ValidationException $e) {
            return back()->withErrors(['code' => 'That code is not valid. Check the time on your device and try again.']);
        }

        return redirect()->route('two-factor.recovery-codes.show')
            ->with('status', 'Two-factor authentication is on. Save these recovery codes now.');
    }

    public function recoveryCodes(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->hasTwoFactorEnabled()) {
            return redirect()->route('two-factor.setup');
        }

        if ($user->two_factor_recovery_codes === null) {
            app(GenerateNewRecoveryCodes::class)($user);
            $user->refresh();
        }

        return view('auth.two-factor-recovery-codes', [
            'codes' => $user->recoveryCodes(),
        ]);
    }
}
