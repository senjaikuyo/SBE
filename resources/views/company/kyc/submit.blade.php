<x-layouts.company title="KYC verification">
    <div class="max-w-[880px]">

        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-[20px] font-semibold">Company verification</h2>
                <p class="mt-1 text-[13px] text-ink-600">
                    {{ $company->name }} · {{ config('greengrid.countries')[$company->country] ?? $company->country }}
                </p>
            </div>
            <x-status-badge :status="$company->kyc_status" />
        </div>

        @if (session('status'))
            <div class="mt-4 rounded-md border border-success/30 bg-success-soft px-4 py-3 text-[13px] text-ink-800" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('kyc.submit') }}" enctype="multipart/form-data" class="mt-5 space-y-6">
            @csrf

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Requested marketplace role</h3>

                <div class="mt-3 space-y-2">
                    <label class="flex items-start gap-2.5 rounded-sm border border-ink-300 px-3 py-2.5 text-[13px]">
                        <input type="radio" name="requested_role" value="buyer" @checked(old('requested_role') === 'buyer') class="mt-0.5 h-4 w-4 text-brand-500">
                        <span>
                            <span class="block font-medium">Buyer</span>
                            <span class="block text-[12px] text-ink-500">I want to buy solar energy or carbon credits.</span>
                        </span>
                    </label>

                    <label class="flex items-start gap-2.5 rounded-sm border border-ink-300 px-3 py-2.5 text-[13px]">
                        <input type="radio" name="requested_role" value="seller" @checked(old('requested_role') === 'seller') class="mt-0.5 h-4 w-4 text-brand-500">
                        <span>
                            <span class="block font-medium">Seller</span>
                            <span class="block text-[12px] text-ink-500">I want to list solar projects or carbon credits.</span>
                        </span>
                    </label>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Company documents</h3>

                <ul class="mt-4 divide-y divide-ink-200">
                    @foreach ($countryDocs as $doc)
                        <li class="py-4 first:pt-0 last:pb-0">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-medium">{{ $doc['types'] }}</p>
                                    <p class="mt-0.5 text-[12px] text-ink-500">{{ $doc['name'] }}</p>
                                </div>
                                <span class="rounded-sm bg-muted-soft px-2 py-1 text-[11px] font-medium text-ink-600">Required</span>
                            </div>

                            <div class="mt-3 grid gap-3 sm:grid-cols-[1fr_170px]">
                                <label class="flex items-center gap-2.5 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-2.5 text-[12px] text-ink-600">
                                    <input type="checkbox" name="documents[{{ $doc['name'] }}]" value="1" @checked(old('documents.'.$doc['name']) == '1') class="h-4 w-4 rounded-sm border-ink-300 text-brand-500">
                                    Document provided
                                </label>

                                @if ($doc['needs_expiry'])
                                    <div>
                                        <label for="expiry-{{ $doc['name'] }}" class="block text-[12px] font-medium text-ink-700">Expiry date</label>
                                        <input id="expiry-{{ $doc['name'] }}" name="document_expiries.{{ $doc['name'] }}" type="date" value="{{ old('document_expiries.'.$doc['name']) }}" class="mt-1 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-[13px] text-ink-600">
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                <p class="mt-4 text-[12px] font-medium uppercase tracking-wide text-ink-500">Shared documents</p>

                <ul class="mt-2 divide-y divide-ink-200">
                    @foreach ($sharedDocs as $doc)
                        <li class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-3">
                            <div>
                                <p class="text-[13px] font-medium">{{ $doc['note'] }}</p>
                                <p class="mt-0.5 text-[12px] text-ink-500">{{ $doc['name'] }}</p>
                            </div>
                            <label class="flex items-center gap-2.5 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-2 text-[12px] text-ink-600">
                                <input type="checkbox" name="documents[{{ $doc['name'] }}]" value="1" @checked(old('documents.'.$doc['name']) == '1') class="h-4 w-4 rounded-sm border-ink-300 text-brand-500">
                                Document provided
                            </label>
                        </li>
                    @endforeach
                </ul>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-[15px] font-semibold">Beneficial owners</h3>
                        <p class="mt-1 text-[12px] leading-5 text-ink-500">
                            Every individual who ultimately owns or controls 25% or more of the company.
                        </p>
                    </div>
                    <button type="button" id="add-owner" class="rounded-md border border-ink-300 px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                        Add owner
                    </button>
                </div>

                <ul id="owner-rows" class="mt-4 space-y-3"></ul>
            </section>

            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <a href="{{ route('kyc.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
                <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Submit for review
                </button>
            </div>
        </form>
    </div>

    <script>
        (() => {
            const form = document.querySelector('form');
            const rows = document.getElementById('owner-rows');
            const list = [];
            let counter = 0;

            function addOwner() {
                const index = counter++;
                const item = document.createElement('li');
                item.className = 'grid gap-2 rounded-sm border border-ink-200 p-3 sm:grid-cols-4';
                item.innerHTML = [
                    '<input type="text" name="beneficial_owners[' + index + '][name]" placeholder="Full name" class="owner-name rounded-sm border border-ink-300 px-3 py-2 text-sm">',
                    '<input type="text" name="beneficial_owners[' + index + '][nationality]" placeholder="Nationality" class="owner-nationality rounded-sm border border-ink-300 px-3 py-2 text-sm">',
                    '<input type="number" name="beneficial_owners[' + index + '][ownership]" placeholder="Ownership (%)" class="owner-ownership rounded-sm border border-ink-300 px-3 py-2 text-sm">',
                    '<input type="text" name="beneficial_owners[' + index + '][document]" placeholder="Document" class="owner-document rounded-sm border border-ink-300 px-3 py-2 text-sm">',
                ].join('');
                rows.appendChild(item);
            }

            document.getElementById('add-owner').addEventListener('click', addOwner);

            form.addEventListener('submit', () => {
                const entries = [];
                rows.querySelectorAll('li').forEach((li) => {
                    entries.push({
                        name: li.querySelector('.owner-name').value,
                        nationality: li.querySelector('.owner-nationality').value,
                        ownership: li.querySelector('.owner-ownership').value,
                        document: li.querySelector('.owner-document').value,
                    });
                });
            });
        })();
    </script>
</x-layouts.company>
