<x-layouts.company title="Listing detail">

    <div class="max-w-[880px]">
        <a href="{{ route('marketplace.index') }}" class="text-[12px] font-medium text-ink-500 hover:text-ink-900">&larr; Back to marketplace</a>

        <div class="mt-4 rounded-lg border border-ink-200 bg-white p-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-[20px] font-semibold">{{ $listing->location }}</h2>
                    <p class="mt-1 text-[13px] text-ink-600">
                        {{ $listing->type === 'energy' ? 'Solar energy' : 'Carbon credit' }}
                        · Status <span class="font-medium">{{ $listing->status }}</span>
                    </p>
                </div>
                <span class="rounded-md border border-ink-300 px-3 py-1.5 text-[12px] font-medium text-ink-700">View listing</span>
            </div>

            @if ($listing->type === 'energy')
                <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-ink-100 pt-4 text-[13px] sm:grid-cols-3">
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Capacity (MW)</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->capacity_mw }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Available volume (MW)</dt>
                        <dd class="mt-5 font-medium tabular">{{ $listing->volume_available_mw }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Price (USD / kWh)</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->price_per_kwh }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Duration (years)</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->duration_years }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Start date</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->start_date }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Permit reference</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->permit_reference }}</dd>
                    </div>
                </dl>
            @else
                <dl class="mt-6 grid grid-cols-2 gap-4 border-t border-ink-100 pt-4 text-[13px] sm:grid-cols-3">
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Quantity (tCO2e)</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->quantity_tco2e }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Vintage</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->vintage }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Registry standard</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->registry_standard }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Serial number</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->serial_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-wide text-ink-500">Price (USD / ton)</dt>
                        <dd class="mt-0.5 font-medium tabular">{{ $listing->price_per_ton }}</dd>
                    </div>
                </dl>
            @endif
        </div>
    </div>
</x-layouts.company>
