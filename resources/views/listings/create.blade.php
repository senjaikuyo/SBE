<x-layouts.company title="Create listing">

    <div class="max-w-[880px]">
        <h2 class="text-[20px] font-semibold">Create listing</h2>
        <p class="mt-1 text-[13px] text-ink-600">Seller creates an energy or carbon credit listing.</p>

        @if (session('status'))
            <div class="mt-4 rounded-md border border-success/30 bg-success-soft px-4 py-3 text-[13px] text-ink-800" role="status">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('listings.store') }}" class="mt-5 space-y-6">
            @csrf

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Listing type</h3>
                <div class="mt-3 flex gap-4">
                    <label class="flex items-center gap-2 rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        <input type="radio" name="type" value="energy" @checked(old('type') === 'energy') class="h-4 w-4">
                        Energy
                    </label>
                    <label class="flex items-center gap-2 rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        <input type="radio" name="type" value="carbon_credit" @checked(old('type') === 'carbon_credit') class="h-4 w-4">
                        Carbon credit
                    </label>
                </div>
            </section>

            @if (old('type') === 'energy')
                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <h3 class="text-[15px] font-semibold">Energy listing</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Capacity (MW)</label>
                            <input name="capacity_mw" type="number" step="0.01" value="{{ old('capacity_mw') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Available volume (MW)</label>
                            <input name="volume_available_mw" type="number" step="0.01" value="{{ old('volume_available_mw') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Price per kWh (USD)</label>
                            <input name="price_per_kwh" type="number" step="0.0001" value="{{ old('price_per_kwh') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Duration (years)</label>
                            <input name="duration_years" type="number" value="{{ old('duration_years') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Start date</label>
                            <input name="start_date" type="date" value="{{ old('start_date') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Location</label>
                            <input name="location" value="{{ old('location') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Permit reference</label>
                            <input name="permit_reference" value="{{ old('permit_reference') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Photo</label>
                            <input name="photo" type="file" class="mt-1 block text-[13px]">
                        </div>
                    </div>
                </section>
            @endif

            @if (old('type') === 'carbon_credit')
                <section class="rounded-lg border border-ink-200 bg-white p-5">
                    <h3 class="text-[15px] font-semibold">Carbon credit listing</h3>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Quantity (tCO2e)</label>
                            <input name="quantity_tco2e" type="number" value="{{ old('quantity_tco2e') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Vintage</label>
                            <input name="vintage" type="number" value="{{ old('vintage') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Registry standard</label>
                            <select name="registry_standard" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                                <option value="">Select</option>
                                <option value="VERRA">VERRA</option>
                                <option value="GOLD_STANDARD">GOLD_STANDARD</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Serial number</label>
                            <input name="serial_number" value="{{ old('serial_number') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                        <div>
                            <label class="block text-[12px] font-medium text-ink-700">Price per ton (USD)</label>
                            <input name="price_per_ton" type="number" step="0.0001" value="{{ old('price_per_ton') }}" class="mt-1 w-full rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                        </div>
                    </div>
                </section>
            @endif

            <div class="flex justify-end">
                <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Save draft
                </button>
            </div>
        </form>
    </div>
</x-layouts.company>
