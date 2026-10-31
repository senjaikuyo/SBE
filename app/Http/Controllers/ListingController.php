<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Company;
use App\Models\Listing;
use App\Policies\ListingPolicy;
use App\Support\AuditLogger;
use App\Support\VirusScanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public function createEnergy(Request $request): View
    {
        $company = $request->user()->company();

        if (! app(ListingPolicy::class)->create($request->user(), $company)) {
            abort(403);
        }

        return view('company.listings.create-energy', [
            'company' => $company,
        ]);
    }

    public function createCarbon(Request $request): View
    {
        $company = $request->user()->company();

        if (! app(ListingPolicy::class)->create($request->user(), $company)) {
            abort(403);
        }

        return view('company.listings.create-carbon', [
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
                'photo' => ['required', 'file', 'mimes:jpg,png,pdf', 'max:10240'],
            ]);
        } else {
            $carbon = $request->validate([
                'quantity_tco2e' => ['required', 'integer', 'min:1'],
                'vintage' => ['required', 'integer', 'min:1900', 'max:2100'],
                'registry_standard' => ['required', 'string', Rule::in(['VERRA', 'GOLD_STANDARD'])],
                'serial_number' => ['required', 'string', 'max:255', 'unique:listings,serial_number'],
                'price_per_ton' => ['required', 'numeric', 'min:0'],
            ]);

            $normalized = Certificate::normalizeSerial($carbon['serial_number']);
            if (Certificate::where('registry', $carbon['registry_standard'])->where('serial_normalized', $normalized)->exists()) {
                throw ValidationException::withMessages([
                    'serial_number' => 'The serial number is already in use for this registry.',
                ]);
            }
        }

        $attributes = [
            'company_id' => $company->id,
            'type' => $validated['type'],
            'status' => 'DRAFT',
            'version' => 1,
        ];

        if ($validated['type'] === 'energy') {
            $file = $request->file('photo');
            $virus = app(VirusScanner::class)->scan($file->getRealPath() ?: '');
            if (! ($virus['clean'] ?? false)) {
                throw ValidationException::withMessages(['photo' => 'File failed virus scan.']);
            }

            $disk = config('greengrid.files.disk', 'local');
            $path = Storage::disk($disk)->putFile('listings', $file);

            $attributes['capacity_mw'] = $energy['capacity_mw'];
            $attributes['volume_available_mw'] = $energy['volume_available_mw'];
            $attributes['price_per_kwh'] = $energy['price_per_kwh'];
            $attributes['duration_years'] = $energy['duration_years'];
            $attributes['start_date'] = $energy['start_date'];
            $attributes['location'] = $energy['location'];
            $attributes['permit_reference'] = $energy['permit_reference'];
            $attributes['photo'] = $path;
        } else {
            $attributes['quantity_tco2e'] = $carbon['quantity_tco2e'];
            $attributes['vintage'] = $carbon['vintage'];
            $attributes['registry_standard'] = $carbon['registry_standard'];
            $attributes['serial_number'] = $carbon['serial_number'];
            $attributes['price_per_ton'] = $carbon['price_per_ton'];
        }

        $listing = Listing::create($attributes);

        $listing->histories()->create([
            'version' => 1,
            'snapshot' => $listing->toArray(),
            'changed_by_id' => $request->user()->id,
        ]);

        if ($listing->type === 'carbon_credit' && ! empty($listing->serial_number)) {
            $listing->certificates()->create([
                'type' => 'carbon_credit',
                'registry' => $listing->registry_standard,
                'serial_number' => $listing->serial_number,
                'serial_normalized' => Certificate::normalizeSerial($listing->serial_number),
                'status' => 'PENDING_REVIEW',
            ]);
        }

        $this->audit->record(
            action: 'listing.created',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
        );

        return redirect()->route('listings.create')->with('status', 'Listing saved as draft.');
    }

    public function edit(Request $request, Listing $listing): View
    {
        $company = $request->user()->company();

        if ($listing->company_id !== $company->id || ! app(ListingPolicy::class)->update($request->user(), $company)) {
            abort(403);
        }

        return view('company.listings.edit', [
            'listing' => $listing,
        ]);
    }

    public function update(Request $request, Listing $listing): RedirectResponse
    {
        $company = $request->user()->company();

        if ($listing->company_id !== $company->id || ! app(ListingPolicy::class)->update($request->user(), $company)) {
            abort(403);
        }

        // Save previous snapshot in history
        $listing->histories()->create([
            'version' => $listing->version,
            'snapshot' => $listing->toArray(),
            'changed_by_id' => $request->user()->id,
        ]);

        if ($listing->type === 'energy') {
            $data = $request->validate([
                'capacity_mw' => ['sometimes', 'integer', 'min:0'],
                'volume_available_mw' => ['sometimes', 'numeric', 'min:0'],
                'price_per_kwh' => ['sometimes', 'numeric', 'min:0'],
                'duration_years' => ['sometimes', 'integer', 'min:1'],
                'location' => ['sometimes', 'string', 'max:255'],
            ]);
        } else {
            $data = $request->validate([
                'quantity_tco2e' => ['sometimes', 'integer', 'min:1'],
                'price_per_ton' => ['sometimes', 'numeric', 'min:0'],
            ]);
        }

        $data['version'] = $listing->version + 1;
        $listing->update($data);

        $this->audit->record(
            action: 'listing.updated',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
        );

        return redirect()->route('listings.create')->with('status', 'Listing updated.');
    }

    public function destroy(Request $request, Listing $listing): RedirectResponse
    {
        $company = $request->user()->company();

        if ($listing->company_id !== $company->id || ! app(ListingPolicy::class)->update($request->user(), $company)) {
            abort(403);
        }

        // Record history before delete
        $listing->histories()->create([
            'version' => $listing->version,
            'snapshot' => $listing->toArray(),
            'changed_by_id' => $request->user()->id,
        ]);

        $this->audit->record(
            action: 'listing.deleted',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
        );

        $listing->delete();

        return redirect()->route('listings.create')->with('status', 'Listing deleted.');
    }
}
