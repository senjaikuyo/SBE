@php
    $statusOptions = ['DRAFT' => 'Save as draft', 'PENDING_REVIEW' => 'Submit for review'];
@endphp

<x-layouts.company title="Create listing" kyc="APPROVED">

    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/listings" class="hover:text-ink-700">My listings</a>
            <span aria-hidden="true"> / </span>
            <a href="/company/listings/create" class="hover:text-ink-700">New listing</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Solar energy</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-[20px] font-semibold">New solar energy listing</h2>
                <p class="mt-1 text-[13px] text-ink-600">Fixed tariff, USD. Listing goes live after review and certificate verification.</p>
            </div>
            <x-status-badge status="DRAFT" />
        </div>

        <form class="mt-6 space-y-6" novalidate>

            {{-- Project --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Project</h3>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="title" class="block text-[13px] font-medium">Listing title</label>
                        <input id="title" type="text" value="Sumba 20 MW Solar Farm — Phase 1"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="country" class="block text-[13px] font-medium">Country</label>
                            <select id="country" class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                                <option>Indonesia</option>
                                <option>Malaysia</option>
                                <option>Vietnam</option>
                                <option>Laos</option>
                            </select>
                        </div>
                        <div>
                            <label for="location" class="block text-[13px] font-medium">Location</label>
                            <input id="location" type="text" value="East Sumba, East Nusa Tenggara"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="start-date" class="block text-[13px] font-medium">Commercial operation date</label>
                            <input id="start-date" type="date" value="2027-03-01"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        </div>
                        <div>
                            <label for="expires-at" class="block text-[13px] font-medium">Listing expires on</label>
                            <input id="expires-at" type="date" value="2027-09-30"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                                   aria-describedby="expires-hint">
                            <p id="expires-hint" class="mt-1 text-[12px] text-ink-500">Listing moves to Expired on this date.</p>
                        </div>
                    </div>

                    <div>
                        <label for="permit" class="block text-[13px] font-medium">Project permit number</label>
                        <input id="permit" type="text" placeholder="e.g. 541/PLTS/2026"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
                    </div>
                </div>
            </section>

            {{-- Capacity and price --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Capacity and price</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="capacity" class="block text-[13px] font-medium">Installed capacity (MW)</label>
                        <input id="capacity" type="text" inputmode="decimal" value="20.000"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                    </div>
                    <div>
                        <label for="volume" class="block text-[13px] font-medium">Volume available (MW)</label>
                        <input id="volume" type="text" inputmode="decimal" value="8.500"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="price" class="block text-[13px] font-medium">Price per kWh (USD)</label>
                        <div class="mt-1.5 flex rounded-sm border border-ink-300 bg-white focus-within:border-brand-500">
                            <span class="border-r border-ink-200 px-3 py-2 text-[13px] text-ink-500">USD</span>
                            <input id="price" type="text" inputmode="decimal" value="0.072500"
                                   class="w-full bg-transparent px-3 py-2 text-sm tabular focus:outline-none">
                        </div>
                        <p class="mt-1 text-[12px] text-ink-500">Up to 6 decimal places. Annual escalation is fixed at 0%.</p>
                    </div>
                    <div>
                        <label for="duration" class="block text-[13px] font-medium">Contract duration (years)</label>
                        <input id="duration" type="number" min="1" value="20"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                    </div>
                </div>
            </section>

            {{-- Operating status --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Operating status</h3>

                <fieldset class="mt-3">
                    <legend class="sr-only">Operating status</legend>
                    <div class="space-y-2">
                        <label class="flex items-start gap-3 rounded-md border border-ink-300 p-3 hover:bg-ink-50">
                            <input type="radio" name="operating" value="operating" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500">
                            <span>
                                <span class="block text-[13px] font-medium">Operating</span>
                                <span class="mt-0.5 block text-[12px] leading-5 text-ink-600">Meter readings or production data must be uploaded.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 rounded-md border border-ink-300 p-3 hover:bg-ink-50">
                            <input type="radio" name="operating" value="pre_operational" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500" checked>
                            <span>
                                <span class="block text-[13px] font-medium">Pre-operational <span class="ml-1 rounded-sm bg-warning-soft px-1.5 py-0.5 text-[11px] font-medium text-warning">Flagged</span></span>
                                <span class="mt-0.5 block text-[12px] leading-5 text-ink-600">No meter evidence yet. The listing will carry a “pre-operational” label.</span>
                            </span>
                        </label>
                    </div>
                </fieldset>
            </section>

            {{-- Documents --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Documents and photos</h3>
                <p class="mt-1 text-[12px] text-ink-500">PDF, JPG or PNG · max 10 MB each · stored privately.</p>

                <div class="mt-4 space-y-3">
                    @foreach ([
                        ['Project permit', 'Required for review'],
                        ['Generation licence', 'Required for review'],
                        ['Production data or meter evidence', 'Required only for operating projects'],
                    ] as [$doc, $hint])
                        <div class="flex flex-wrap items-center justify-between gap-3 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-2.5">
                            <div>
                                <p class="text-[13px] font-medium">{{ $doc }}</p>
                                <p class="text-[12px] text-ink-500">{{ $hint }}</p>
                            </div>
                            <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                Choose file
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block text-[13px] font-medium">Project photos</label>
                    <div class="mt-1.5 grid grid-cols-3 gap-2 sm:grid-cols-4">
                        @for ($i = 0; $i < 3; $i++)
                            <div class="grid aspect-[4/3] place-items-center rounded-sm border border-ink-200 bg-ink-50 text-[11px] text-ink-400">
                                Photo {{ $i + 1 }}
                            </div>
                        @endfor
                        <button type="button" class="grid aspect-[4/3] place-items-center rounded-sm border border-dashed border-ink-300 text-[12px] font-medium text-ink-600 hover:bg-ink-50">
                            Add photo
                        </button>
                    </div>
                </div>
            </section>

            {{-- Certificate --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Certificate</h3>
                <p class="mt-1 text-[12px] leading-5 text-ink-500">
                    A listing cannot become active until its certificate serial is verified by our team.
                </p>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="registry" class="block text-[13px] font-medium">Registry</label>
                        <select id="registry" class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                            <option>I-REC</option>
                            <option>VERRA</option>
                            <option>GOLD_STANDARD</option>
                        </select>
                    </div>
                    <div>
                        <label for="serial" class="block text-[13px] font-medium">Serial number</label>
                        <input id="serial" type="text" placeholder="IREC-ID-2027-000123"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 font-mono text-[13px] placeholder:font-sans placeholder:text-ink-400 focus:border-brand-500">
                    </div>
                </div>
            </section>

            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <a href="/company/listings" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
                <div class="flex gap-2">
                    <button type="button" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                        Save as draft
                    </button>
                    <button type="button" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Submit for review
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.company>
