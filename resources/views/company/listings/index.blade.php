@php
    $listings = [
        ['Sumba 20 MW Solar Farm — Phase 1', 'energy', '8.500 MW', 'USD 0.072500 / kWh', 'ACTIVE', '20 yrs', 3, '2 d ago'],
        ['Sumba 20 MW Solar Farm — Phase 2', 'energy', '12.000 MW', 'USD 0.074000 / kWh', 'PENDING_REVIEW', '20 yrs', 1, '1 d ago'],
        ['Aceh Coastal Solar', 'energy', '4.000 MW', 'USD 0.069500 / kWh', 'DRAFT', '15 yrs', 1, '5 h ago'],
        ['Riau Biomass Carbon 2025', 'carbon', '18,000 tCO2e', 'USD 6.40 / t', 'REJECTED', '—', 2, '2 d ago'],
        ['Kalimantan Reforestation 2024', 'carbon', '6,500 tCO2e', 'USD 8.10 / t', 'INACTIVE', '—', 2, '1 week ago'],
        ['Sulawesi Hydro REC 2026', 'energy', '2.000 MW', 'USD 0.081000 / kWh', 'EXPIRED', '10 yrs', 1, '3 weeks ago'],
    ];
@endphp

<x-layouts.company title="My listings" kyc="APPROVED">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">My listings</h2>
            <p class="mt-1 text-[13px] text-ink-600">6 listings · only your company's listings are visible here in Phase 1.</p>
        </div>
        <a href="/company/listings/create" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Create listing
        </a>
    </div>

    <div class="mt-4 flex flex-wrap gap-2 text-[12px]">
        <a href="#" class="rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">All <span class="tabular">6</span></a>
        @foreach (['Draft' => 1, 'Pending review' => 1, 'Active' => 1, 'Rejected' => 1, 'Inactive' => 1, 'Expired' => 1] as $label => $count)
            <a href="#" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">{{ $label }} <span class="tabular">{{ $count }}</span></a>
        @endforeach
    </div>

    <div class="mt-4 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Listings owned by your company</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Listing</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Type</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Volume</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Price</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Duration</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listings as [$title, $type, $volume, $price, $status, $duration, $version, $updated])
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3">
                            <a href="/company/listings/1" class="font-medium hover:text-brand-700">{{ $title }}</a>
                            <p class="text-[12px] text-ink-500">Version {{ $version }} · updated {{ $updated }}</p>
                        </td>
                        <td class="px-4 py-3 text-ink-600">{{ $type === 'energy' ? 'Solar energy' : 'Carbon credits' }}</td>
                        <td class="px-4 py-3 tabular">{{ $volume }}</td>
                        <td class="px-4 py-3 tabular">{{ $price }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $duration }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$status" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="/company/listings/1/edit" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                {{ $status === 'DRAFT' || $status === 'REJECTED' ? 'Edit' : 'View' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between border-t border-ink-200 px-4 py-3 text-[12px] text-ink-500">
            <span>Showing 6 of 6</span>
            <div class="flex gap-1">
                <button type="button" disabled class="rounded-sm border border-ink-300 px-2.5 py-1 text-ink-400">Previous</button>
                <button type="button" disabled class="rounded-sm border border-ink-300 px-2.5 py-1 text-ink-400">Next</button>
            </div>
        </div>
    </div>

    <div class="mt-4 rounded-md border border-ink-200 bg-white px-4 py-3 text-[12px] leading-5 text-ink-600">
        A listing can only become active after its certificate serial is verified by our team.
        Editing a listing creates a new version and preserves the history.
    </div>
</x-layouts.company>
