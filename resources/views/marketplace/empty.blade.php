<x-layouts.company title="Browse marketplace" kyc="APPROVED">
    <div>
        <h2 class="text-[20px] font-semibold">Browse marketplace</h2>
        <p class="mt-1 text-[13px] text-ink-600">Active listings only. Prices and capacity are visible to verified companies.</p>

        {{-- Active filters --}}
        <div class="mt-5 flex flex-wrap gap-2 text-[12px]">
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 font-medium text-brand-700">
                Solar energy
                <button type="button" aria-label="Remove asset type filter" class="text-brand-700/70 hover:text-brand-700">×</button>
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 font-medium text-brand-700">
                Laos
                <button type="button" aria-label="Remove country filter" class="text-brand-700/70 hover:text-brand-700">×</button>
            </span>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-brand-50 px-2.5 py-1 font-medium text-brand-700">
                Min 50 MW
                <button type="button" aria-label="Remove capacity filter" class="text-brand-700/70 hover:text-brand-700">×</button>
            </span>
            <a href="/listings" class="rounded-full px-2 py-1 text-ink-600 underline underline-offset-2 hover:text-ink-900">Clear all</a>
        </div>

        {{-- Empty state --}}
        <div class="mt-4 rounded-lg border border-ink-200 bg-white px-6 py-14 text-center">
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
                <a href="/listings" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
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

        <div class="mt-4 rounded-md border border-ink-200 bg-white px-4 py-3 text-[12px] leading-5 text-ink-600">
            Sellers only see their own listings in Phase 1. Requests and offers are not available yet.
        </div>
    </div>
</x-layouts.company>
