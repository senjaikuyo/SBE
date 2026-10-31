<x-layouts.company title="Dashboard">

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Welcome, Aditya</h2>
            <p class="mt-1 text-[13px] text-ink-600">Nusantara Solar Prima · Indonesia · company_admin</p>
        </div>
        <a href="/company/listings/create" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-300 pointer-events-none" aria-disabled="true">
            Create listing
        </a>
    </div>

    {{-- KYC call to action --}}
    <section class="mt-6 rounded-lg border border-ink-200 bg-white">
        <div class="flex flex-wrap items-start justify-between gap-4 p-5">
            <div class="max-w-xl">
                <div class="flex items-center gap-2">
                    <h3 class="text-[15px] font-semibold">Company verification</h3>
                    <x-status-badge status="NOT_SUBMITTED" />
                </div>
                <p class="mt-2 text-[13px] leading-5 text-ink-600">
                    Submit your company documents to unlock the marketplace. Reviews take up to 48 hours.
                    You can save your progress and finish later.
                </p>
                <p class="mt-2 text-[12px] text-ink-500">Required for Indonesia: NIB, NPWP, articles of association and its approval.</p>
            </div>
            <a href="/company/kyc/submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Complete KYC
            </a>
        </div>

        <ol class="grid gap-0 border-t border-ink-200 sm:grid-cols-3">
            @foreach ([
                ['Company documents', 'Not started', false],
                ['Directors and owners', 'Not started', false],
                ['Review by compliance', 'Waiting on you', false],
            ] as [$label, $state, $done])
                <li class="flex items-center gap-3 border-b border-ink-200 px-5 py-3 last:border-b-0 sm:border-b-0 sm:border-r sm:last:border-r-0">
                    <span class="grid h-6 w-6 place-items-center rounded-full border border-ink-300 text-[11px] text-ink-400">
                        <svg class="h-3 w-3" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                            <circle cx="6" cy="6" r="4"/>
                        </svg>
                    </span>
                    <div>
                        <p class="text-[13px] font-medium">{{ $label }}</p>
                        <p class="text-[12px] text-ink-500">{{ $state }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">

        {{-- Marketplace access --}}
        <section class="rounded-lg border border-ink-200 bg-white p-5 lg:col-span-2">
            <h3 class="text-[15px] font-semibold">Marketplace</h3>
            <p class="mt-1 text-[13px] text-ink-600">Browsing is available once your company is approved and you have a marketplace role.</p>

            <div class="mt-4 rounded-md border border-dashed border-ink-300 bg-ink-50 p-6 text-center">
                <svg class="mx-auto h-8 w-8 text-ink-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                    <rect x="4" y="10" width="16" height="10" rx="2"/>
                    <path d="M8 10V7a4 4 0 1 1 8 0v3" stroke-linecap="round"/>
                </svg>
                <p class="mt-3 text-[13px] font-medium">Marketplace locked</p>
                <p class="mt-1 text-[12px] text-ink-500">Available after KYC approval. Listing prices and capacity are not public.</p>
                <a href="/company/kyc/submit" class="mt-3 inline-block text-[13px] font-medium text-brand-700 underline underline-offset-2">Complete KYC to unlock</a>
            </div>
        </section>

        {{-- Company summary --}}
        <section class="rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Company</h3>
            <dl class="mt-3 space-y-3 text-[13px]">
                <div class="flex justify-between gap-3">
                    <dt class="text-ink-500">Country</dt>
                    <dd class="font-medium">Indonesia</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-ink-500">Registration no.</dt>
                    <dd class="font-mono text-[12px]">9120001234567</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-ink-500">Marketplace role</dt>
                    <dd class="text-ink-500">Assigned after KYC</dd>
                </div>
                <div class="flex justify-between gap-3">
                    <dt class="text-ink-500">Members</dt>
                    <dd class="font-medium tabular">1</dd>
                </div>
            </dl>
            <a href="/company/members" class="mt-4 block rounded-md border border-ink-300 px-3 py-2 text-center text-[13px] font-medium text-ink-700 hover:bg-ink-50">
                Invite team members
            </a>
        </section>
    </div>

    {{-- Activity --}}
    <section class="mt-6 rounded-lg border border-ink-200 bg-white">
        <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
            <h3 class="text-[15px] font-semibold">Recent activity</h3>
            <span class="text-[12px] text-ink-500">Times shown in Asia/Jakarta (GMT+7)</span>
        </div>
        <div class="px-5 py-10 text-center">
            <p class="text-[13px] font-medium">No activity yet</p>
            <p class="mt-1 text-[12px] text-ink-500">Account and verification events will appear here.</p>
        </div>
    </section>

</x-layouts.company>
