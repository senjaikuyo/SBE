<x-layouts.company title="Edit listing" kyc="APPROVED">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/listings" class="hover:text-ink-700">My listings</a>
            <span aria-hidden="true"> / </span>
            <a href="/company/listings/1" class="hover:text-ink-700">Sumba 20 MW — Phase 1</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Edit</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-[20px] font-semibold">Edit listing</h2>
                <p class="mt-1 text-[13px] text-ink-600">Editing version 3 creates version 4. The previous version stays in history.</p>
            </div>
            <span class="rounded-sm bg-ink-100 px-2 py-1 text-[12px] font-medium text-ink-600 tabular">v3</span>
        </div>

        {{-- Conflict notice (shown when someone else saved first) --}}
        <div class="mt-4 rounded-md border border-warning/30 bg-warning-soft px-4 py-3">
            <p class="text-[13px] font-medium text-warning">This listing changed since you opened it</p>
            <p class="mt-0.5 text-[12px] leading-5 text-ink-700">
                Someone saved version 4 at 12:06. Reload to see the latest values, or review the differences before saving.
            </p>
            <div class="mt-2 flex gap-2">
                <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">Reload</button>
                <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">Compare versions</button>
            </div>
        </div>

        <form class="mt-5 space-y-6" novalidate>
            <input type="hidden" name="version" value="3">

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Listing details</h3>

                <div class="mt-4 space-y-4">
                    <div>
                        <label for="title" class="block text-[13px] font-medium">Listing title</label>
                        <input id="title" type="text" value="Sumba 20 MW Solar Farm — Phase 1"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="volume" class="block text-[13px] font-medium">Volume available (MW)</label>
                            <input id="volume" type="text" value="8.500"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                        </div>
                        <div>
                            <label for="price" class="block text-[13px] font-medium">Price per kWh (USD)</label>
                            <div class="mt-1.5 flex rounded-sm border border-ink-300 bg-white focus-within:border-brand-500">
                                <span class="border-r border-ink-200 px-3 py-2 text-[13px] text-ink-500">USD</span>
                                <input id="price" type="text" value="0.072500"
                                       class="w-full bg-transparent px-3 py-2 text-sm tabular focus:outline-none">
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="duration" class="block text-[13px] font-medium">Contract duration (years)</label>
                            <input id="duration" type="number" value="20"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular focus:border-brand-500">
                        </div>
                        <div>
                            <label for="expires" class="block text-[13px] font-medium">Listing expires on</label>
                            <input id="expires" type="date" value="2027-09-30"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Read-only fields --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Locked after review</h3>
                <p class="mt-1 text-[12px] text-ink-500">Changing an active listing re-enters PENDING_REVIEW or requires a new listing.</p>

                <dl class="mt-3 space-y-2 text-[13px]">
                    @foreach ([['Type', 'Solar energy'], ['Country', 'Indonesia'], ['Certificate serial', 'IRECID-2027000123']] as [$label, $value])
                        <div class="flex justify-between border-b border-ink-100 pb-2">
                            <dt class="text-ink-500">{{ $label }}</dt>
                            <dd class="font-medium">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>

            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <a href="/company/listings/1" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
                <button type="button" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Save as version 4
                </button>
            </div>
        </form>
    </div>
</x-layouts.company>
