@php
    $documents = [
        ['NIB — Business identification number', 'nib-2026.pdf', 'Valid until 12 Mar 2029', true],
        ['NPWP — Tax identification number', 'npwp.pdf', 'Valid until 31 Dec 2027', true],
        ['Articles of association', 'akta-pendirian.pdf', 'No expiry', true],
        ['Director identity', 'passport-budi.pdf', 'Valid until 04 Aug 2031', true],
        ['Letter of appointment', 'surat-penunjukan.pdf', 'No expiry', false],
        ['Proof of company bank account', 'bank-statement.pdf', 'Statement: Jan 2026', true],
    ];
@endphp

<x-layouts.admin title="Review KYC — Nusantara Solar Prima">

    <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
        <a href="/admin/kyc" class="hover:text-ink-700">KYC queue</a>
        <span aria-hidden="true"> / </span>
        <span class="text-ink-700">Nusantara Solar Prima</span>
    </nav>

    <div class="mt-2 flex flex-wrap items-start justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Nusantara Solar Prima</h2>
            <p class="mt-1 text-[13px] text-ink-600">Indonesia · Registration 9120001234567 · Submitted 6 h ago</p>
        </div>
        <div class="flex items-center gap-2">
            <x-status-badge status="PENDING_VERIFICATION" label="Under review" />
            <x-status-badge status="DUE_SOON" label="66h left" />
        </div>
    </div>

    <div class="mt-5 grid gap-5 lg:grid-cols-[1fr_340px]">

        <div class="space-y-5">

            {{-- Requested role --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Requested marketplace role</h3>
                <div class="mt-3 flex items-center gap-3">
                    <span class="rounded-sm bg-brand-50 px-2.5 py-1 text-[13px] font-medium text-brand-700">Seller</span>
                    <span class="text-[12px] text-ink-500">Requested by the applicant. You assign the role when you approve.</span>
                </div>
                <p class="mt-2 text-[12px] leading-5 text-ink-500">
                    Supporting evidence: 2 project permits and 1 generation licence uploaded with the listing draft.
                </p>
            </section>

            {{-- Documents --}}
            <section class="rounded-lg border border-ink-200 bg-white">
                <div class="border-b border-ink-200 px-5 py-3">
                    <h3 class="text-[15px] font-semibold">Documents</h3>
                </div>
                <table class="w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                            <th scope="col" class="px-5 py-2 font-medium">Document</th>
                            <th scope="col" class="px-5 py-2 font-medium">Status</th>
                            <th scope="col" class="px-5 py-2 text-right font-medium">File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as [$name, $file, $meta, $ok])
                            <tr class="border-b border-ink-200 last:border-b-0">
                                <td class="px-5 py-3">
                                    <p class="font-medium">{{ $name }}</p>
                                    <p class="text-[12px] text-ink-500">{{ $meta }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($ok)
                                        <x-status-badge status="VALID" label="Valid" />
                                    @else
                                        <x-status-badge status="PENDING_REVIEW" label="Missing" />
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <span class="font-mono text-[12px] text-ink-600">{{ $file }}</span>
                                    <button type="button" class="ml-2 rounded-sm border border-ink-300 px-2 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                        Open
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            {{-- Beneficial owners --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Beneficial owners</h3>
                <table class="mt-3 w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-ink-200 text-left text-[12px] text-ink-500">
                            <th scope="col" class="pb-2 font-medium">Name</th>
                            <th scope="col" class="pb-2 font-medium">Ownership</th>
                            <th scope="col" class="pb-2 font-medium">Check</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-ink-200">
                            <td class="py-2.5">Budi Santoso</td>
                            <td class="py-2.5 tabular">60%</td>
                            <td class="py-2.5"><x-status-badge status="VALID" label="Checked" /></td>
                        </tr>
                        <tr>
                            <td class="py-2.5">Siti Rahayu</td>
                            <td class="py-2.5 tabular">40%</td>
                            <td class="py-2.5"><x-status-badge status="VALID" label="Checked" /></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            {{-- Sanctions --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <div class="flex items-start justify-between gap-3">
                    <h3 class="text-[15px] font-semibold">Sanctions screening</h3>
                    <x-status-badge status="APPROVED" label="Clear" />
                </div>
                <p class="mt-2 text-[12px] text-ink-500">Screened 31 Oct 2026, 09:12 (GMT+7). Automatic re-screen in 90 days.</p>
            </section>
        </div>

        {{-- Decision panel --}}
        <aside class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Decision</h3>
                <p class="mt-1 text-[12px] leading-5 text-ink-500">A reason is required for both approval and rejection. The applicant is notified by email.</p>

                <div class="mt-4">
                    <label for="reason" class="block text-[13px] font-medium">Reason</label>
                    <textarea id="reason" rows="4" placeholder="Documents verified against the national registry."
                              class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500"></textarea>
                </div>

                <div class="mt-4">
                    <label class="block text-[13px] font-medium">Assign role</label>
                    <div class="mt-1.5 grid grid-cols-2 gap-2">
                        <label class="flex cursor-pointer items-center gap-2 rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                            <input type="radio" name="assign_role" value="seller" checked class="h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500">
                            Seller
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 rounded-sm border border-ink-300 px-3 py-2 text-[13px]">
                            <input type="radio" name="assign_role" value="buyer" class="h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500">
                            Buyer
                        </label>
                    </div>
                </div>

                <div class="mt-4 space-y-2">
                    <button type="button" class="w-full rounded-md bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Approve company
                    </button>
                    <button type="button" class="w-full rounded-md border border-danger/30 bg-white px-4 py-2.5 text-sm font-medium text-danger hover:bg-danger-soft">
                        Reject with reason
                    </button>
                </div>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Conflict check</h3>
                <p class="mt-2 text-[12px] leading-5 text-ink-500">
                    You are not a member of this company, so you can decide. If you were, the decision would be blocked.
                </p>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">History</h3>
                <ol class="mt-3 space-y-3 text-[12px]">
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                        <div>
                            <p class="font-medium text-ink-700">Submitted for review</p>
                            <p class="text-ink-500">31 Oct 2026, 09:04 (GMT+7) · Aditya Wirawan</p>
                        </div>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-ink-300"></span>
                        <div>
                            <p class="font-medium text-ink-700">Sanctions screening completed</p>
                            <p class="text-ink-500">31 Oct 2026, 09:12 (GMT+7) · system</p>
                        </div>
                    </li>
                </ol>
            </section>
        </aside>
    </div>

</x-layouts.admin>
