<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(): View
    {
        return view('marketplace.index', [
            'results' => Listing::query()
                ->where('status', 'ACTIVE')
                ->latest('id')
                ->get(),
        ]);
    }

    public function show(\Illuminate\Http\Request $request, Listing $listing): View
    {
        if ($listing->status !== 'ACTIVE') {
            abort(404);
        }

        if (! $listing->company->isApproved()) {
            abort(403);
        }

        return view('marketplace.show', [
            'listing' => $listing,
        ]);
    }
}
