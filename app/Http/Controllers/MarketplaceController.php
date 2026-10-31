<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MarketplaceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Listing::query()
            ->where('status', 'ACTIVE')
            ->where(function ($q) {
                $q->whereDoesntHave('certificates')
                    ->orWhereHas('certificates', fn ($c) => $c->where('status', 'VALID'));
            });

        // 1. Asset Type Filter
        $typeParam = $request->query('type', $request->query('asset_type'));
        if ($typeParam !== null && $typeParam !== '' && strtolower($typeParam) !== 'all' && strtolower($typeParam) !== 'all types') {
            $normalizedType = match (strtolower(str_replace([' ', '-'], '_', $typeParam))) {
                'energy', 'solar', 'solar_energy' => 'energy',
                'carbon', 'carbon_credit', 'carbon_credits' => 'carbon_credit',
                default => $typeParam,
            };
            $query->where('type', $normalizedType);
        }

        // 2. Country / Location Filter
        $country = $request->query('country', $request->query('location'));
        if ($country !== null && $country !== '' && strtolower($country) !== 'all' && strtolower($country) !== 'all countries') {
            $query->where('location', 'like', '%' . trim($country) . '%');
        }

        // 3. Capacity Filter (Min Capacity MW) - Carbon credits are not filtered by MW capacity
        $capacity = $request->query('capacity', $request->query('min_capacity', $request->query('capacity_mw')));
        if ($capacity !== null && $capacity !== '' && is_numeric($capacity)) {
            $capVal = (float) $capacity;
            $query->where(function ($q) use ($capVal) {
                $q->where('type', 'carbon_credit')
                    ->orWhere('capacity_mw', '>=', $capVal);
            });
        }

        // 4. Price Filter (Max price USD)
        $price = $request->query('price', $request->query('max_price', $request->query('price_max')));
        if ($price !== null && $price !== '' && is_numeric($price)) {
            $priceVal = (float) $price;
            $query->where(function ($q) use ($priceVal) {
                $q->where(function ($sub) use ($priceVal) {
                    $sub->where('type', 'energy')->where('price_per_kwh', '<=', $priceVal);
                })->orWhere(function ($sub) use ($priceVal) {
                    $sub->where('type', 'carbon_credit')->where('price_per_ton', '<=', $priceVal);
                });
            });
        }

        // 5. Duration Filter (Min duration in years)
        $duration = $request->query('duration', $request->query('min_duration', $request->query('duration_years')));
        if ($duration !== null && $duration !== '' && is_numeric($duration)) {
            $durVal = (int) $duration;
            $query->where('duration_years', '>=', $durVal);
        }

        $results = $query->latest('id')->get();

        return view('marketplace.index', [
            'results' => $results,
        ]);
    }

    public function show(Request $request, Listing $listing): View
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
