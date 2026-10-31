<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $company = $user->company();
        $membership = $company ? $user->membershipFor($company) : null;

        return view('dashboard', [
            'company' => $company,
            'membership' => $membership,
            'memberCount' => $company?->memberships()->count() ?? 0,
            'pendingInvitations' => $company
                ? Invitation::query()->where('company_id', $company->id)->pending()->count()
                : 0,
        ]);
    }
}
