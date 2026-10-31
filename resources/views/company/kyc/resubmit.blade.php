<x-layouts.company title="Resubmit KYC documents" kyc="REJECTED">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/kyc" class="hover:text-ink-700">KYC verification</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Resubmit</span>
        </nav>

        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-[20px] font-semibold">Resubmit documents</h2>
            <x-status-badge status="REJECTED" />
        </div>

        {{-- Rejection reason --}}
        <section class="mt-4 rounded-lg border border-danger/30 bg-danger-soft p-5">
            <h3 class="text-[15px] font-semibold text-danger">Decision: rejected</h3>
            <p class="mt-1 text-[12px] text-ink-600">Decided 2 Nov 2026, 14:20 (GMT+7) by Rani Kusuma · Compliance</p>
            <p class="mt-3 rounded-md bg-white px-4 py-3 text-[13px] leading-5 text-ink-800">
                “The tax identification document (NPWP) is expired. Please upload a valid NPWP issued within the last 12 months.
                The other four documents were accepted and do not need to be uploaded again.”
            </p>
        </section>

        {{-- What to fix --}}
        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Documents to replace</h3>
            <p class="mt-1 text-[12px] text-ink-500">Only documents marked “Replace” are required. Accepted documents stay valid.</p>

            <ul class="mt-4 divide-y divide-ink-200">
                @foreach ([
                    ['NPWP — Tax identification number', 'Rejected · expired', true],
                    ['NIB — Business identification number', 'Accepted · valid until 12 Mar 2029', false],
                    ['Articles of association and approval', 'Accepted · no expiry', false],
                    ['Director identity', 'Accepted · valid until 04 Aug 2031', false],
                    ['Proof of company bank account', 'Accepted · statement Jan 2026', false],
                ] as [$name, $meta, $replace])
                    <li class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-0 last:pb-0">
                        <div>
                            <p class="text-[13px] font-medium">{{ $name }}</p>
                            <p @class(['text-[12px]', 'text-danger' => $replace, 'text-ink-500' => ! $replace])>{{ $meta }}</p>
                        </div>
                        @if ($replace)
                            <button type="button" class="rounded-md bg-brand-500 px-3 py-1.5 text-[12px] font-medium text-white hover:bg-brand-600">
                                Upload replacement
                            </button>
                        @else
                            <x-status-badge status="VALID" label="Accepted" />
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>

        {{-- Notes --}}
        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <label for="note" class="block text-[13px] font-medium">Note for the reviewer <span class="font-normal text-ink-500">(optional)</span></label>
            <textarea id="note" rows="3" placeholder="Explain what changed, if useful."
                      class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500"></textarea>
        </section>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 pb-4">
            <a href="/company/kyc" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
            <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Resubmit for review
            </button>
        </div>
    </div>
</x-layouts.company>
