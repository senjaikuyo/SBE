@php
    $rows = ($listings ?? collect())->map(function ($listing) {
        $cert = $listing->certificates->first();
        return [
            $listing->location ?? 'Listing #' . $listing->id,
            $listing->company->name ?? 'Company',
            $listing->type,
            $listing->type === 'energy' ? ($listing->capacity_mw . ' MW') : ($listing->quantity_tco2e . ' tCO2e'),
            $listing->created_at->diffForHumans(),
            $listing->status,
            $listing->id,
            $cert !== null && $cert->status === 'VALID',
        ];
    });
@endphp

<x-layouts.admin title="Listing moderation">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Listing moderation</h2>
            <p class="mt-1 text-[13px] text-ink-600">Approve to activate, or reject with a reason the seller can act on.</p>
        </div>
        <div class="flex gap-2 text-[13px]">
            <span class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">
                Total listings <span class="tabular">{{ $rows->count() }}</span>
            </span>
        </div>
    </div>

    <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Listings awaiting moderation</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Listing</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Seller</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Type</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Volume / Capacity</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Created</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as [$title, $seller, $type, $volume, $submitted, $status, $id, $hasValidCert])
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3">
                            <span class="font-medium text-ink-900">{{ $title }}</span>
                            <p class="text-[12px] text-ink-500">Certificate {{ $hasValidCert ? 'verified' : 'not verified / pending' }}</p>
                        </td>
                        <td class="px-4 py-3 text-ink-600">{{ $seller }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $type === 'energy' ? 'Solar energy' : 'Carbon credits' }}</td>
                        <td class="px-4 py-3 tabular">{{ $volume }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $submitted }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$status" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/listings/{{ $id }}" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-ink-500">
                            No listings in system.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <p class="mt-3 text-[12px] leading-5 text-ink-500">
        A listing cannot be approved while its certificate is not verified. Verify the serial first, then approve the listing.
    </p>
</x-layouts.admin>
