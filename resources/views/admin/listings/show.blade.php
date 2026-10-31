<x-layouts.admin title="Moderate listing">
    <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
        <a href="/admin/listings" class="hover:text-ink-700">Listing moderation</a>
        <span aria-hidden="true"> / </span>
        <span class="text-ink-700">Sumba 20 MW — Phase 2</span>
    </nav>

    <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Sumba 20 MW Solar Farm — Phase 2</h2>
            <p class="mt-1 text-[13px] text-ink-600">Nusantara Solar Prima · Indonesia · submitted 1 d ago</p>
        </div>
        <x-status-badge status="PENDING_REVIEW" />
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">

        <div class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Listing details</h3>
                <dl class="mt-3 grid gap-x-6 gap-y-3 text-[13px] sm:grid-cols-2">
                    @foreach ([
                        ['Type', 'Solar energy'],
                        ['Installed capacity', '20.000 MW'],
                        ['Volume available', '12.000 MW'],
                        ['Price', 'USD 0.074000 / kWh'],
                        ['Duration', '20 years'],
                        ['Commercial operation', '1 Sep 2027'],
                        ['Operating status', 'Pre-operational · flagged'],
                        ['Listing expires', '30 Sep 2027'],
                    ] as [$label, $value])
                        <div class="flex justify-between gap-4 border-b border-ink-100 pb-2">
                            <dt class="text-ink-500">{{ $label }}</dt>
                            <dd class="text-right font-medium tabular">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <h3 class="text-[15px] font-semibold">Certificate</h3>
                        <p class="mt-1 text-[12px] text-ink-500">Listing cannot become active until this is verified.</p>
                    </div>
                    <x-status-badge status="PENDING_REVIEW" label="Not verified" />
                </div>

                <dl class="mt-3 grid gap-3 text-[13px] sm:grid-cols-2">
                    <div>
                        <dt class="text-[12px] text-ink-500">Registry</dt>
                        <dd class="mt-0.5 font-medium">I-REC</dd>
                    </div>
                    <div>
                        <dt class="text-[12px] text-ink-500">Serial (normalised)</dt>
                        <dd class="mt-0.5 font-mono text-[12px]">IRECID-2027000457</dd>
                    </div>
                </dl>

                <a href="/admin/certificates/1" class="mt-4 inline-block rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Verify certificate first
                </a>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Submitted documents</h3>
                <ul class="mt-3 divide-y divide-ink-200 text-[13px]">
                    @foreach ([['project-permit.pdf', '840 KB'], ['generation-licence.pdf', '1.3 MB'], ['site-plan.pdf', '2.6 MB'], ['photo-1.jpg', '1.1 MB'], ['photo-2.jpg', '980 KB']] as [$file, $size])
                        <li class="flex items-center justify-between py-2 first:pt-0 last:pb-0">
                            <span class="font-mono text-[12px] text-ink-700">{{ $file }}</span>
                            <span class="flex items-center gap-2 text-[12px] text-ink-500">
                                {{ $size }}
                                <button type="button" class="rounded-sm border border-ink-300 px-2 py-0.5 text-[11px] font-medium text-ink-700 hover:bg-ink-50">Open</button>
                            </span>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <aside class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Decision</h3>
                <p class="mt-1 text-[12px] leading-5 text-ink-500">Approval activates the listing. Rejection requires a reason and returns it to the seller as a draft.</p>

                <div class="mt-4">
                    <label for="reason" class="block text-[13px] font-medium">Reason</label>
                    <textarea id="reason" rows="4" placeholder="Checked against registry and project permits."
                              class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500"></textarea>
                </div>

                <div class="mt-4 space-y-2">
                    <button type="button" disabled
                            class="w-full cursor-not-allowed rounded-md bg-ink-200 px-4 py-2.5 text-sm font-medium text-ink-500"
                            aria-describedby="blocked-note">
                        Approve listing
                    </button>
                    <p id="blocked-note" class="text-[11px] leading-4 text-ink-500">Blocked until the certificate is verified.</p>

                    <button type="button" class="w-full rounded-md border border-danger/30 bg-white px-4 py-2.5 text-sm font-medium text-danger hover:bg-danger-soft">
                        Reject with reason
                    </button>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">History</h3>
                <ol class="mt-3 space-y-3 text-[12px]">
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                        <div>
                            <p class="font-medium text-ink-700">Submitted for review</p>
                            <p class="text-ink-500">30 Oct 2026, 14:02 (GMT+7)</p>
                        </div>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-ink-300"></span>
                        <div>
                            <p class="font-medium text-ink-700">Certificate verification pending</p>
                            <p class="text-ink-500">Queued for registry check</p>
                        </div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>
</x-layouts.admin>
