<x-layouts.company title="New listing" kyc="APPROVED">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/listings" class="hover:text-ink-700">My listings</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">New listing</span>
        </nav>

        <h2 class="mt-2 text-[20px] font-semibold">What are you listing?</h2>
        <p class="mt-1 text-[13px] text-ink-600">Energy and carbon credits use different fields, so the forms are separate.</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2">

            <a href="/company/listings/create/energy" class="group rounded-lg border border-ink-200 bg-white p-5 hover:border-brand-500 hover:shadow-sm">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-50 text-brand-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2" stroke-linecap="round"/>
                    </svg>
                </span>
                <h3 class="mt-4 text-[15px] font-semibold">Solar energy</h3>
                <p class="mt-1 text-[13px] leading-5 text-ink-600">
                    Capacity in MW, fixed price per kWh in USD, contract duration and project documents.
                </p>
                <p class="mt-3 text-[12px] font-medium text-brand-700 group-hover:underline">Start energy listing →</p>
            </a>

            <a href="/company/listings/create/carbon" class="group rounded-lg border border-ink-200 bg-white p-5 hover:border-brand-500 hover:shadow-sm">
                <span class="grid h-10 w-10 place-items-center rounded-md bg-brand-50 text-brand-700">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                        <path d="M12 21c5-3 7-7 7-11a7 7 0 1 0-14 0c0 4 2 8 7 11Z" stroke-linejoin="round"/>
                        <path d="M12 21V9M12 13l3-3M12 16l-2.5-2.5" stroke-linecap="round"/>
                    </svg>
                </span>
                <h3 class="mt-4 text-[15px] font-semibold">Carbon credits</h3>
                <p class="mt-1 text-[13px] leading-5 text-ink-600">
                    Quantity in tCO2e, vintage, registry standard and serial number. No capacity fields.
                </p>
                <p class="mt-3 text-[12px] font-medium text-brand-700 group-hover:underline">Start carbon listing →</p>
            </a>
        </div>

        <div class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Before you start</h3>
            <ul class="mt-3 space-y-2 text-[13px] text-ink-700">
                <li class="flex gap-2"><span class="text-brand-600">•</span> Your company is verified as a seller.</li>
                <li class="flex gap-2"><span class="text-brand-600">•</span> Project permits and certificate serials are ready to upload.</li>
                <li class="flex gap-2"><span class="text-brand-600">•</span> Listings stay inactive until a reviewer verifies the certificate.</li>
            </ul>
        </div>
    </div>
</x-layouts.company>
