@php
    $rows = [
        ['Sumba 20 MW Solar Farm — Phase 2', 'Nusantara Solar Prima', 'energy', '12.000 MW', 'Submitted 1 d ago', 'PENDING_REVIEW'],
        ['Riau Biomass Carbon 2025', 'Nusantara Solar Prima', 'carbon', '18,000 tCO2e', 'Submitted 2 d ago', 'PENDING_REVIEW'],
        ['Johor Solar Park 15 MW', 'Johor Green Power', 'energy', '15.000 MW', 'Submitted 3 h ago', 'PENDING_REVIEW'],
        ['Aceh Coastal Solar', 'Andalan Solar', 'energy', '4.000 MW', 'Submitted 5 d ago', 'REJECTED'],
    ];
@endphp

<x-layouts.admin title="Listing moderation">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Listing moderation</h2>
            <p class="mt-1 text-[13px] text-ink-600">Approve to activate, or reject with a reason the seller can act on.</p>
        </div>
        <div class="flex gap-2 text-[13px]">
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">Pending <span class="tabular">3</span></a>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">Rejected <span class="tabular">1</span></a>
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
                    <th scope="col" class="px-4 py-2.5 font-medium">Volume</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Submitted</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as [$title, $seller, $type, $volume, $submitted, $status])
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3">
                            <a href="/admin/listings/1" class="font-medium hover:text-brand-700">{{ $title }}</a>
                            <p class="text-[12px] text-ink-500">Certificate {{ $status === 'PENDING_REVIEW' ? 'awaiting verification' : 'not valid' }}</p>
                        </td>
                        <td class="px-4 py-3 text-ink-600">{{ $seller }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $type === 'energy' ? 'Solar energy' : 'Carbon credits' }}</td>
                        <td class="px-4 py-3 tabular">{{ $volume }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $submitted }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$status" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/listings/1" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">Review</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <p class="mt-3 text-[12px] leading-5 text-ink-500">
        A listing cannot be approved while its certificate is not verified. Verify the serial first, then approve the listing.
    </p>
</x-layouts.admin>
