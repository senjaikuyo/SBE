@php
    $documents = [
        ['NIB — Business identification number', 'nib-2026.pdf', 'Valid until 12 Mar 2029', 'VALID'],
        ['NPWP — Tax identification number', 'npwp.pdf', 'Valid until 31 Dec 2027', 'VALID'],
        ['Articles of association and approval', 'akta-pendirian.pdf', 'No expiry', 'VALID'],
        ['Director identity', 'passport-budi.pdf', 'Valid until 04 Aug 2031', 'VALID'],
        ['Proof of company bank account', 'bank-statement.pdf', 'Statement: Jan 2026', 'VALID'],
    ];
@endphp

<x-layouts.company title="KYC verification" kyc="PENDING_VERIFICATION">
    <div class="max-w-[880px]">

        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-[20px] font-semibold">Company verification</h2>
                <p class="mt-1 text-[13px] text-ink-600">Submitted 31 Oct 2026, 09:04 (GMT+7) · requested role: Seller</p>
            </div>
            <x-status-badge status="PENDING_VERIFICATION" />
        </div>

        {{-- Status summary --}}
        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="max-w-xl">
                    <h3 class="text-[15px] font-semibold">Under review</h3>
                    <p class="mt-1 text-[13px] leading-5 text-ink-600">
                        Our compliance team aims to decide within 48 hours, and 90% within 72 hours.
                        You will receive an email as soon as a decision is made.
                    </p>
                </div>
                <div class="rounded-md bg-ink-50 px-4 py-3 text-center">
                    <p class="text-[11px] uppercase tracking-wide text-ink-500">Time remaining</p>
                    <p class="mt-0.5 text-[20px] font-semibold tabular">41h 12m</p>
                </div>
            </div>

            <ol class="mt-5 grid gap-3 border-t border-ink-200 pt-4 sm:grid-cols-3">
                @foreach ([
                    ['Documents submitted', '31 Oct, 09:04', true],
                    ['Sanctions screening', '31 Oct, 09:12', true],
                    ['Compliance decision', 'Expected by 2 Nov', false],
                ] as [$label, $time, $done])
                    <li class="flex items-start gap-2.5">
                        <span @class([
                            'mt-0.5 grid h-5 w-5 shrink-0 place-items-center rounded-full',
                            'bg-brand-500 text-white' => $done,
                            'border border-ink-300 text-ink-400' => ! $done,
                        ])>
                            @if ($done)
                                <svg class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z"/></svg>
                            @else
                                <span class="h-1.5 w-1.5 rounded-full bg-ink-300"></span>
                            @endif
                        </span>
                        <div>
                            <p class="text-[13px] font-medium">{{ $label }}</p>
                            <p class="text-[12px] text-ink-500">{{ $time }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </section>

        {{-- Requested role --}}
        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Requested marketplace role</h3>
            <div class="mt-2 flex items-center gap-2">
                <span class="rounded-sm bg-brand-50 px-2.5 py-1 text-[13px] font-medium text-brand-700">Seller</span>
                <span class="text-[12px] text-ink-500">Assigned by GreenGrid after approval.</span>
            </div>
        </section>

        {{-- Documents --}}
        <section class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
            <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
                <h3 class="text-[15px] font-semibold">Documents</h3>
                <span class="text-[12px] text-ink-500">Private · served through expiring links</span>
            </div>
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                        <th scope="col" class="px-5 py-2 font-medium">Document</th>
                        <th scope="col" class="px-5 py-2 font-medium">Validity</th>
                        <th scope="col" class="px-5 py-2 text-right font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as [$name, $file, $meta, $status])
                        <tr class="border-b border-ink-200 last:border-b-0">
                            <td class="px-5 py-3">
                                <p class="font-medium">{{ $name }}</p>
                                <p class="font-mono text-[12px] text-ink-500">{{ $file }}</p>
                            </td>
                            <td class="px-5 py-3 text-ink-600">{{ $meta }}</td>
                            <td class="px-5 py-3 text-right"><x-status-badge :status="$status" label="Uploaded" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-lg border border-ink-200 bg-white px-5 py-4">
            <p class="text-[12px] text-ink-500">Need to correct something? You can update documents after a decision.</p>
            <a href="/company/kyc/resubmit" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                View resubmission rules
            </a>
        </div>
    </div>
</x-layouts.company>
