<x-layouts.admin title="Verify certificate">
    <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
        <a href="/admin/certificates" class="hover:text-ink-700">Certificates</a>
        <span aria-hidden="true"> / </span>
        <span class="text-ink-700">{{ $certificate->serial_normalized ?? 'IRECID-2027000457' }}</span>
    </nav>

    <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Verify certificate</h2>
            <p class="mt-1 text-[13px] text-ink-600">Check the serial in the external registry, then record the decision.</p>
        </div>
        <x-status-badge :status="$certificate->status ?? 'PENDING_REVIEW'" :label="isset($certificate) ? ($certificate->status === 'VALID' ? 'Verified' : ($certificate->status === 'REJECTED' ? 'Rejected' : 'Not verified')) : 'Not verified'" />
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">

        <div class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Serial</h3>

                <dl class="mt-3 grid gap-3 text-[13px] sm:grid-cols-2">
                    <div>
                        <dt class="text-[12px] text-ink-500">As submitted</dt>
                        <dd class="mt-0.5 rounded-sm bg-ink-50 px-2 py-1 font-mono text-[12px]">{{ $certificate->serial_number ?? 'irec id-2027 000457' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[12px] text-ink-500">Normalised</dt>
                        <dd class="mt-0.5 rounded-sm bg-ink-50 px-2 py-1 font-mono text-[12px] font-medium">{{ $certificate->serial_normalized ?? 'IRECID-2027000457' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[12px] text-ink-500">Registry</dt>
                        <dd class="mt-0.5 font-medium">{{ $certificate->registry ?? 'I-REC' }}</dd>
                    </div>
                    <div>
                        <dt class="text-[12px] text-ink-500">Vintage / period</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $certificate->listing?->vintage ?? 2027 }}</dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-md border border-success/30 bg-success-soft px-4 py-3">
                    <p class="text-[13px] font-medium text-success">No duplicate on the platform</p>
                    <p class="mt-0.5 text-[12px] text-ink-700">This serial is not used by any other listing.</p>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Listing</h3>
                <dl class="mt-3 space-y-2 text-[13px]">
                    @foreach ([
                        ['Listing', isset($certificate) ? ($certificate->listing?->type === 'energy' ? 'Energy Listing #' . $certificate->listing_id : 'Carbon Credit #' . $certificate->listing_id) : 'Sumba 20 MW Solar Farm — Phase 2'],
                        ['Seller', $certificate->listing?->company?->name ?? 'Nusantara Solar Prima'],
                        ['Type', isset($certificate) ? ucfirst(str_replace('_', ' ', $certificate->listing?->type ?? '')) : 'Solar energy'],
                        ['Status', ucfirst(strtolower($certificate->listing?->status ?? 'Pending review'))],
                    ] as [$label, $value])
                        <div class="flex justify-between border-b border-ink-100 pb-2 last:border-b-0">
                            <dt class="text-ink-500">{{ $label }}</dt>
                            <dd class="font-medium">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
                <a href="/admin/listings/{{ $certificate->listing_id ?? 1 }}" class="mt-3 inline-block text-[12px] font-medium text-brand-700 underline underline-offset-2">Open listing</a>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Uploaded certificate</h3>
                <div class="mt-3 flex items-center justify-between rounded-sm border border-ink-200 bg-ink-50 px-3 py-2.5">
                    <div>
                        <p class="font-mono text-[12px] text-ink-700">irec-certificate.pdf</p>
                        <p class="text-[11px] text-ink-500">2.1 MB · uploaded 30 Oct 2026</p>
                    </div>
                    <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                        Open
                    </button>
                </div>
            </section>
        </div>

        <aside class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Decision</h3>
                <p class="mt-1 text-[12px] leading-5 text-ink-500">
                    Verify only after finding the serial in the registry. A note is required for rejection.
                </p>

                <form method="POST" action="{{ isset($certificate) ? route('certificates.decision', $certificate) : '#' }}">
                    @csrf
                    <fieldset class="mt-4 space-y-2">
                        <legend class="sr-only">Certificate decision</legend>
                        <label class="flex items-start gap-2.5 rounded-md border border-ink-300 p-3 hover:bg-ink-50">
                            <input type="radio" name="decision" value="valid" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500" checked>
                            <span>
                                <span class="block text-[13px] font-medium">Verified</span>
                                <span class="block text-[12px] text-ink-500">Serial found in registry and unused.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-2.5 rounded-md border border-ink-300 p-3 hover:bg-ink-50">
                            <input type="radio" name="decision" value="rejected" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500">
                            <span>
                                <span class="block text-[13px] font-medium">Rejected</span>
                                <span class="block text-[12px] text-ink-500">Serial not found, retired or already used.</span>
                            </span>
                        </label>
                    </fieldset>

                    <div class="mt-4">
                        <label for="note" class="block text-[13px] font-medium">Note</label>
                        <textarea id="note" name="note" rows="3" placeholder="Found in I-REC registry, issued 12 Jan 2027."
                                  class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500"></textarea>
                    </div>

                    <button type="submit" class="mt-4 w-full rounded-md bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Save decision
                    </button>
                </form>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">History</h3>
                <ol class="mt-3 space-y-3 text-[12px]">
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                        <div>
                            <p class="font-medium text-ink-700">Certificate submitted</p>
                            <p class="text-ink-500">{{ isset($certificate) ? $certificate->created_at?->format('d M Y, H:i (e)') : '30 Oct 2026, 14:10 (GMT+7)' }}</p>
                        </div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>
</x-layouts.admin>
