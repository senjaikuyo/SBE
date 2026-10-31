<x-layouts.company title="Browse marketplace" kyc="APPROVED">
    <div>
        <h2 class="text-[20px] font-semibold">Browse marketplace</h2>
        <p class="mt-1 text-[13px] text-ink-600">Active listings only. Prices and capacity are visible to verified companies.</p>

        {{-- Filters --}}
        <form method="GET" action="{{ route('marketplace.index') }}" id="filter-form"
              onsubmit="var btn=document.getElementById('btn-apply-filters'); if(btn){ btn.disabled=true; btn.classList.add('opacity-50','cursor-not-allowed'); } var l=document.getElementById('filter-loading'); if(l){ l.classList.remove('hidden'); }"
              class="mt-5 rounded-lg border border-ink-200 bg-white p-4" novalidate>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
                <div>
                    <label for="asset-type" class="block text-[12px] font-medium text-ink-600">Asset type</label>
                    <select id="asset-type" name="type" class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-2.5 py-2 text-[13px] focus:border-brand-500">
                        <option value="" {{ request('type') == '' ? 'selected' : '' }}>All types</option>
                        <option value="energy" {{ request('type') == 'energy' ? 'selected' : '' }}>Solar energy</option>
                        <option value="carbon_credit" {{ request('type') == 'carbon_credit' ? 'selected' : '' }}>Carbon credits</option>
                    </select>
                </div>
                <div>
                    <label for="country" class="block text-[12px] font-medium text-ink-600">Country</label>
                    <select id="country" name="country" class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-2.5 py-2 text-[13px] focus:border-brand-500">
                        <option value="" {{ request('country') == '' ? 'selected' : '' }}>All countries</option>
                        <option value="Indonesia" {{ request('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="Malaysia" {{ request('country') == 'Malaysia' ? 'selected' : '' }}>Malaysia</option>
                        <option value="Vietnam" {{ request('country') == 'Vietnam' ? 'selected' : '' }}>Vietnam</option>
                        <option value="Laos" {{ request('country') == 'Laos' ? 'selected' : '' }}>Laos</option>
                    </select>
                </div>
                <div>
                    <label for="capacity" class="block text-[12px] font-medium text-ink-600">Min capacity (MW)</label>
                    <input id="capacity" name="capacity" type="text" inputmode="decimal" placeholder="0" value="{{ request('capacity') }}"
                           class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-2.5 py-2 text-[13px] tabular placeholder:text-ink-400 focus:border-brand-500">
                </div>
                <div>
                    <label for="price" class="block text-[12px] font-medium text-ink-600">Max price (USD)</label>
                    <input id="price" name="price" type="text" inputmode="decimal" placeholder="Any" value="{{ request('price') }}"
                           class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-2.5 py-2 text-[13px] tabular placeholder:text-ink-400 focus:border-brand-500">
                </div>
                <div>
                    <label for="duration" class="block text-[12px] font-medium text-ink-600">Min duration (years)</label>
                    <input id="duration" name="duration" type="number" placeholder="Any" value="{{ request('duration') }}"
                           class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-2.5 py-2 text-[13px] tabular placeholder:text-ink-400 focus:border-brand-500">
                </div>
            </div>

            <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap gap-2 text-[12px]">
                    @if(request('type'))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 font-medium text-brand-700">
                            {{ request('type') === 'energy' ? 'Solar energy' : 'Carbon credits' }}
                        </span>
                    @endif
                    @if(request('country'))
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 font-medium text-brand-700">
                            {{ request('country') }}
                        </span>
                    @endif
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('marketplace.index') }}" class="rounded-md px-3 py-1.5 text-[12px] font-medium text-ink-600 hover:bg-ink-100">Clear</a>
                    <button type="submit" id="btn-apply-filters" class="rounded-md bg-brand-500 px-4 py-1.5 text-[12px] font-medium text-white hover:bg-brand-600">
                        Apply filters
                    </button>
                </div>
            </div>
        </form>

        {{-- Loading indicator --}}
        <div id="filter-loading" class="hidden mt-3 rounded-lg border border-ink-200 bg-white p-4 text-center text-[13px] text-ink-600" role="status">
            <span class="inline-block animate-pulse font-medium">Loading listings...</span>
        </div>

        @if ($results->isEmpty())
            {{-- Empty state --}}
            <div class="mt-6 rounded-lg border border-ink-200 bg-white px-6 py-14 text-center">
                <span class="mx-auto grid h-11 w-11 place-items-center rounded-full bg-ink-100 text-ink-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="11" cy="11" r="6"/>
                        <path d="M20 20l-3.5-3.5" stroke-linecap="round"/>
                    </svg>
                </span>

                <h3 class="mt-4 text-[15px] font-semibold">No listings match these filters</h3>
                <p class="mx-auto mt-1 max-w-md text-[13px] leading-5 text-ink-600">
                    Try widening the capacity range or removing the country filter. New listings appear here once they pass review.
                </p>

                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    <a href="{{ route('marketplace.index') }}" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        Clear filters
                    </a>
                    <button type="button" disabled
                            class="cursor-not-allowed rounded-md border border-ink-200 bg-ink-50 px-4 py-2 text-sm font-medium text-ink-400"
                            aria-describedby="save-search-note">
                        Save this search
                    </button>
                </div>
                <p id="save-search-note" class="mt-2 text-[12px] text-ink-500">
                    Saved searches and new-listing alerts are planned for Phase 2.
                </p>
            </div>
        @else
            <div class="mt-4 flex items-center justify-between text-[12px] text-ink-500">
                <p><span class="font-medium text-ink-700 tabular">{{ $results->count() }}</span> listings match your filters</p>
                <p>Sorted by: newest first</p>
            </div>

            {{-- Results --}}
            <ul class="mt-3 space-y-3">
                @foreach ($results as $listing)
                    <li>
                        <a href="{{ route('marketplace.show', $listing) }}" class="block rounded-lg border border-ink-200 bg-white p-4 hover:border-brand-300 hover:bg-brand-50/30">
                            <div class="flex flex-wrap items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="text-[15px] font-semibold">{{ $listing->location }}</h3>
                                        <span class="rounded-md border border-ink-300 px-3 py-1.5 text-[12px] font-medium text-ink-700">{{ $listing->type === 'energy' ? 'Solar energy' : 'Carbon credit' }}</span>
                                    </div>
                                    <p class="mt-0.5 text-[12px] text-ink-500">{{ $listing->company->name }}</p>
                                </div>
                                <span class="rounded-md border border-ink-300 px-3 py-1.5 text-[12px] font-medium text-ink-700">View listing</span>
                            </div>

                            <dl class="mt-4 grid grid-cols-2 gap-4 border-t border-ink-100 pt-3 text-[13px] sm:grid-cols-4">
                                @if ($listing->type === 'energy')
                                    <div>
                                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Available</dt>
                                        <dd class="font-medium tabular">{{ $listing->volume_available_mw }} MW</dd>
                                    </div>
                                    <div>
                                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Price</dt>
                                        <dd class="font-medium tabular">{{ $listing->price_per_kwh }} USD / kWh</dd>
                                    </div>
                                    <div>
                                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Duration</dt>
                                        <dd class="font-medium tabular">{{ $listing->duration_years }} years</dd>
                                    </div>
                                @else
                                    <div>
                                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Quantity</dt>
                                        <dd class="font-medium tabular">{{ $listing->quantity_tco2e }} tCO2e</dd>
                                    </div>
                                    <div>
                                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Price</dt>
                                        <dd class="font-medium tabular">{{ $listing->price_per_ton }} USD / ton</dd>
                                    </div>
                                @endif
                            </dl>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Phase 2 teaser --}}
        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-ink-200 bg-white px-4 py-3">
            <div>
                <p class="text-[13px] font-medium">Want to save this search?</p>
                <p class="text-[12px] text-ink-500">Saved searches and new-listing alerts arrive in Phase 2.</p>
            </div>
            <button type="button" disabled
                    class="cursor-not-allowed rounded-md border border-ink-200 bg-ink-50 px-3 py-1.5 text-[12px] font-medium text-ink-400"
                    aria-describedby="save-search-note">
                Save search
            </button>
            <span id="save-search-note" class="sr-only">Not available in Phase 1</span>
        </div>
    </div>
</x-layouts.company>
