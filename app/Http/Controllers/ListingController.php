<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Listing;
use App\Policies\ListingPolicy;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ListingController extends Controller
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function create(Request $request): View
    {
        $company = $request->user()->company();

        $listingPolicy = app(ListingPolicy::class);

        if (! $listingPolicy->create($request->user(), $company)) {
            abort(403);
        }

        return view('listings.create', [
            'company' => $company,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $company = $request->user()->company();

        $listingPolicy = app(ListingPolicy::class);

        if (! $listingPolicy->create($request->user(), $company)) {
            abort(403);
        }

        $validated = $request->validate([
            'type' => ['required', Rule::in(['energy', 'carbon_credit'])],
        ]);

        if ($validated['type'] === 'energy') {
            $energy = $request->validate([
                'capacity_mw' => ['required', 'integer', 'min:0'],
                'volume_available_mw' => ['required', 'numeric', 'min:0'],
                'price_per_kwh' => ['required', 'numeric', 'min:0'],
                'duration_years' => ['required', 'integer', 'min:1'],
                'start_date' => ['required', 'date'],
                'location' => ['required', 'string', 'max:255'],
                'permit_reference' => ['required', 'string', 'max:255'],
                'photo' => ['required', 'file', 'mimes:jpg,png,pdf'],
            ]);
        } else {
            $carbon = $request->validate([
                'quantity_tco2e' => ['required', 'integer', 'min:1'],
                'vintage' => ['required', 'integer', 'min:1900', 'max:2100'],
                'registry_standard' => ['required', 'string', Rule::in(['VERRA', 'GOLD_STANDARD'])],
                'serial_number' => ['required', 'string', 'max:255', 'unique:listings,serial_number'],
                'price_per_ton' => ['required', 'numeric', 'min:0'],
            ]);
        }

        $attributes = [
            'company_id' => $company->id,
            'type' => $validated['type'],
            'status' => 'DRAFT',
            'version' => 1,
        ];

        if ($validated['type'] === 'energy') {
            $attributes['capacity_mw'] = $energy['capacity_mw'];
            $attributes['volume_available_mw'] = $energy['volume_available_mw'];
            $attributes['price_per_kwh'] = $energy['price_per_kwh'];
            $attributes['duration_years'] = $energy['duration_years'];
            $attributes['start_date'] = $energy['start_date'];
            $attributes['location'] = $energy['location'];
            $attributes['permit_reference'] = $energy['permit_reference'];
            $attributes['photo'] = 'pending';
        } else {
            $attributes['quantity_tco2e'] = $carbon['quantity_tco2e'];
            $attributes['vintage'] = $carbon['vintage'];
            $attributes['registry_standard'] = $carbon['registry_standard'];
            $attributes['serial_number'] = $carbon['serial_number'];
            $attributes['price_per_ton'] = $carbon['price_per_ton'];
        }

        $listing = Listing::create($attributes);

        $this->audit->record(
            action: 'listing.created',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
        );

        return redirect()->route('listings.create')->with('status', 'Listing saved as draft.');
    }
}
