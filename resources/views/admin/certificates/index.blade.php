@php
    $rows = [
        ['IRECID-2027000457', 'I-REC', 'Sumba 20 MW — Phase 2', 'Nusantara Solar Prima', '1 d ago', 'PENDING_REVIEW'],
        ['VCS-1520-2025-001234', 'VERRA', 'Riau Biomass Carbon 2025', 'Nusantara Solar Prima', '2 d ago', 'PENDING_REVIEW'],
        ['GS-88231-2024-77', 'GOLD_STANDARD', 'Kalimantan Reforestation 2024', 'Andalan Solar', '3 d ago', 'PENDING_REVIEW'],
        ['IRECID-2027000123', 'I-REC', 'Sumba 20 MW — Phase 1', 'Nusantara Solar Prima', '5 d ago', 'VALID'],
        ['VCS-1520-2025-000981', 'VERRA', 'Sumatra Peat 2025', 'Mekong Renewables', '6 d ago', 'REJECTED'],
    ];
@endphp

<x-layouts.admin title="Certificates">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Certificate verification</h2>
            <p class="mt-1 text-[13px] text-ink-600">Check the serial in the registry, then mark it verified or rejected.</p>
        </div>
        <div class="flex gap-2 text-[13px]">
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">Pending <span class="tabular">3</span></a>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">Verified <span class="tabular">1</span></a>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">Rejected <span class="tabular">1</span></a>
        </div>
    </div>

    <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Certificate serials awaiting verification</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Serial (normalised)</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Registry</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Listing</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Seller</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Submitted</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as [$serial, $registry, $listing, $seller, $submitted, $status])
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3 font-mono text-[12px] font-medium">{{ $serial }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $registry }}</td>
                        <td class="px-4 py-3">
                            <a href="/admin/listings/1" class="hover:text-brand-700">{{ $listing }}</a>
                        </td>
                        <td class="px-4 py-3 text-ink-600">{{ $seller }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $submitted }}</td>
                        <td class="px-4 py-3"><x-status-badge :status="$status" /></td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/certificates/1" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                {{ $status === 'PENDING_REVIEW' ? 'Review' : 'View' }}
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 rounded-md border border-ink-200 bg-white px-4 py-3 text-[12px] leading-5 text-ink-600">
        Serial numbers are normalised before saving: spaces removed and letters upper-cased.
        A serial can appear on only one listing across the whole platform.
    </div>
</x-layouts.admin>
