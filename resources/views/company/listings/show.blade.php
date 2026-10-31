@php
    $versions = [
        [3, '28 Oct 2026, 16:40 (GMT+7)', 'Aditya Wirawan', 'Volume 8.500 → 8.500 MW, price unchanged'],
        [2, '27 Oct 2026, 11:02 (GMT+7)', 'Aditya Wirawan', 'Price 0.072000 → 0.072500 USD/kWh'],
        [1, '26 Oct 2026, 09:15 (GMT+7)', 'Aditya Wirawan', 'Created as draft'],
    ];
@endphp

<x-layouts.company title="Listing detail" kyc="APPROVED">
    <div class="max-w-[960px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/listings" class="hover:text-ink-700">My listings</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Sumba 20 MW Solar Farm — Phase 1</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-[20px] font-semibold">Sumba 20 MW Solar Farm — Phase 1</h2>
                <p class="mt-1 text-[13px] text-ink-600">Solar energy · East Sumba, East Nusa Tenggara · Indonesia</p>
            </div>
            <div class="flex items-center gap-2">
                <x-status-badge status="PENDING_REVIEW" />
                <a href="/company/listings/1/edit" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                    Edit listing
                </a>
            </div>
        </div>

        <div class="mt-5 flex gap-1 border-b border-ink-200 text-[13px]">
            <a href="#" class="border-b-2 border-brand-500 px-3 py-2 font-medium text-brand-700">Overview</a>
            <a href="#" class="border-b-2 border-transparent px-3 py-2 text-ink-600 hover:text-ink-900">Certificate</a>
            <a href="#" class="border-b-2 border-transparent px-3 py-2 text-ink-600 hover:text-ink-900">Version history</a>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_320px]">

            <div class="space-y-5">
                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <h3 class="text-[15px] font-semibold">Listing details</h3>
                    <dl class="mt-3 grid gap-x-6 gap-y-3 text-[13px] sm:grid-cols-2">
                        @foreach ([
                            ['Type', 'Solar energy'],
                            ['Installed capacity', '20.000 MW'],
                            ['Volume available', '8.500 MW'],
                            ['Price', 'USD 0.072500 / kWh'],
                            ['Annual escalation', '0% (fixed)'],
                            ['Contract duration', '20 years'],
                            ['Commercial operation', '1 Mar 2027'],
                            ['Listing expires', '30 Sep 2027'],
                            ['Project permit', '541/PLTS/2026'],
                            ['Operating status', 'Operating'],
                        ] as [$label, $value])
                            <div class="flex justify-between gap-4 border-b border-ink-100 pb-2">
                                <dt class="text-ink-500">{{ $label }}</dt>
                                <dd class="text-right font-medium tabular">{{ $value }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </section>

                {{-- Certificate (FR-11) --}}
                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-[15px] font-semibold">Certificate</h3>
                            <p class="mt-1 text-[12px] text-ink-500">A listing cannot become active until the serial is verified.</p>
                        </div>
                        <x-status-badge status="PENDING_REVIEW" label="Awaiting review" />
                    </div>

                    <dl class="mt-4 grid gap-3 text-[13px] sm:grid-cols-2">
                        <div>
                            <dt class="text-[12px] text-ink-500">Registry</dt>
                            <dd class="mt-0.5 font-medium">I-REC</dd>
                        </div>
                        <div>
                            <dt class="text-[12px] text-ink-500">Serial number</dt>
                            <dd class="mt-0.5 font-mono text-[12px]">IRECID-2027000123 (normalised)</dd>
                        </div>
                    </dl>

                    <div class="mt-4 rounded-md bg-ink-50 px-4 py-3 text-[12px] leading-5 text-ink-600">
                        Reviewed manually against the registry. Rejection includes a note explaining what to correct.
                    </div>

                    <div class="mt-4">
                        <h4 class="text-[13px] font-medium">Uploaded evidence</h4>
                        <ul class="mt-2 divide-y divide-ink-200 text-[12px]">
                            @foreach ([['irec-certificate.pdf', '2.1 MB'], ['project-permit.pdf', '840 KB'], ['generation-licence.pdf', '1.3 MB']] as [$file, $size])
                                <li class="flex items-center justify-between py-2">
                                    <span class="font-mono text-ink-700">{{ $file }}</span>
                                    <span class="text-ink-500">{{ $size }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>

                <section class="overflow-hidden rounded-lg border border-ink-200 bg-white">
                    <div class="border-b border-ink-200 px-5 py-3">
                        <h3 class="text-[15px] font-semibold">Version history</h3>
                    </div>
                    <table class="w-full text-[13px]">
                        <thead>
                            <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                                <th scope="col" class="px-5 py-2 font-medium">Version</th>
                                <th scope="col" class="px-5 py-2 font-medium">Changed</th>
                                <th scope="col" class="px-5 py-2 font-medium">By</th>
                                <th scope="col" class="px-5 py-2 font-medium">Summary</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($versions as [$v, $when, $who, $summary])
                                <tr class="border-b border-ink-200 last:border-b-0">
                                    <td class="px-5 py-2.5 font-medium tabular">v{{ $v }}</td>
                                    <td class="px-5 py-2.5 text-ink-600">{{ $when }}</td>
                                    <td class="px-5 py-2.5">{{ $who }}</td>
                                    <td class="px-5 py-2.5 text-ink-600">{{ $summary }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>
            </div>

            <aside class="space-y-5">
                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <h3 class="text-[15px] font-semibold">Review status</h3>
                    <ol class="mt-3 space-y-3 text-[12px]">
                        @foreach ([
                            ['Submitted for review', '28 Oct 2026, 16:45', true],
                            ['Certificate verification', 'Waiting for admin', false],
                            ['Listing decision', 'After certificate', false],
                        ] as [$label, $time, $done])
                            <li class="flex gap-2">
                                <span @class(['mt-1 h-1.5 w-1.5 shrink-0 rounded-full', 'bg-brand-500' => $done, 'bg-ink-300' => ! $done])></span>
                                <div>
                                    <p class="font-medium text-ink-700">{{ $label }}</p>
                                    <p class="text-ink-500">{{ $time }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </section>

                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <h3 class="text-[15px] font-semibold">Delete listing</h3>
                    <p class="mt-1 text-[12px] leading-5 text-ink-500">
                        Deletion is only possible while no request is open. Phase 1 has no open requests.
                    </p>
                    <button type="button" class="mt-3 rounded-md border border-danger/30 px-3 py-1.5 text-[12px] font-medium text-danger hover:bg-danger-soft">
                        Delete listing
                    </button>
                </section>
            </aside>
        </div>
    </div>
</x-layouts.company>
