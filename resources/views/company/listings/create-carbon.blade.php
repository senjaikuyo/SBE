<x-layouts.company title="Create listing" kyc="APPROVED">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/listings" class="hover:text-ink-700">My listings</a>
            <span aria-hidden="true"> / </span>
            <a href="/company/listings/create" class="hover:text-ink-700">New listing</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Carbon credits</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-[20px] font-semibold">New carbon credit listing</h2>
                <p class="mt-1 text-[13px] text-ink-600">Priced per tonne of CO2 equivalent, USD. Serial numbers are checked against the registry.</p>
            </div>
            <x-status-badge status="DRAFT" />
        </div>

        <form class="mt-6 space-y-6" novalidate>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Project</h3>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="title" class="block text-[13px] font-medium">Listing title</label>
                        <input id="title" type="text" value="Riau Biomass Carbon 2025"
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
                            <label for="expires-at" class="block text-[13px] font-medium">Listing expires on</label>
                            <input id="expires-at" type="date" value="2027-06-30"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Credits</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="quantity" class="block text-[13px] font-medium">Quantity (tCO2e)</label>
                        <input id="quantity" type="text" inputmode="decimal" value="18000"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                    </div>
                    <div>
                        <label for="vintage" class="block text-[13px] font-medium">Vintage year</label>
                        <input id="vintage" type="number" min="1990" max="2026" value="2025"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                    </div>
                </div>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="standard" class="block text-[13px] font-medium">Registry standard</label>
                        <select id="standard" class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                            <option>VERRA</option>
                            <option>GOLD_STANDARD</option>
                        </select>
                    </div>
                    <div>
                        <label for="price" class="block text-[13px] font-medium">Price per tonne (USD)</label>
                        <div class="mt-1.5 flex rounded-sm border border-ink-300 bg-white focus-within:border-brand-500">
                            <span class="border-r border-ink-200 px-3 py-2 text-[13px] text-ink-500">USD</span>
                            <input id="price" type="text" inputmode="decimal" value="6.400000"
                                   class="w-full bg-transparent px-3 py-2 text-sm tabular focus:outline-none">
                        </div>
                        <p class="mt-1 text-[12px] text-ink-500">Up to 6 decimal places. Single currency: USD.</p>
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Certificate</h3>
                <p class="mt-1 text-[12px] text-ink-500">Serial numbers are normalised (spaces removed, upper case) before they are checked.</p>

                <div class="mt-4">
                    <label for="serial" class="block text-[13px] font-medium">Serial number</label>
                    <input id="serial" type="text" placeholder="VCS-1520-2025-001234"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 font-mono text-[13px] placeholder:font-sans placeholder:text-ink-400 focus:border-brand-500"
                           aria-describedby="serial-hint">
                    <p id="serial-hint" class="mt-1 text-[12px] text-ink-500">The same serial cannot appear on two listings.</p>
                </div>

                <div class="mt-4">
                    <label class="block text-[13px] font-medium">Registry certificate</label>
                    <div class="mt-1.5 flex flex-wrap items-center justify-between gap-3 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-3">
                        <div>
                            <p class="text-[12px] text-ink-600">PDF or JPG · max 10 MB</p>
                            <p class="text-[12px] text-ink-400">No file selected</p>
                        </div>
                        <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                            Choose file
                        </button>
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
