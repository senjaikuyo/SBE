@php
    $kycRows = [
        ['Johor Green Power Sdn Bhd', 'Malaysia', '9h overdue', 'OVERDUE'],
        ['Laos Sunshine Energy', 'Laos', '1 d 8h overdue', 'OVERDUE'],
        ['Andalan Solar Nusantara', 'Indonesia', '46h left', 'DUE_SOON'],
        ['Mekong Renewables Co., Ltd', 'Vietnam', '3h left', 'DUE_SOON'],
    ];
@endphp

<x-layouts.admin title="Overview">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Operations overview</h2>
            <p class="mt-1 text-[13px] text-ink-600">31 Oct 2026 · times in Asia/Jakarta (GMT+7)</p>
        </div>
        <p class="text-[12px] text-ink-500">Target: KYC median 48h, 90% within 72h</p>
    </div>

    {{-- Overdue alert --}}
    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 rounded-md border border-danger/30 bg-danger-soft px-4 py-3" role="alert">
        <div>
            <p class="text-[13px] font-medium text-danger">2 KYC reviews are overdue</p>
            <p class="text-[12px] text-ink-700">Oldest submitted 4 days ago. Decision SLA is 72 hours.</p>
        </div>
        <a href="/admin/kyc" class="rounded-md bg-white px-3 py-1.5 text-[12px] font-medium text-danger hover:bg-danger-soft">Open KYC queue</a>
    </div>

    {{-- Stats --}}
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
            ['KYC pending', '12', '2 overdue', 'danger'],
            ['Listings waiting', '4', '1 needs certificate check', 'warning'],
            ['Certificates waiting', '7', 'Serials not yet verified', 'warning'],
            ['Approvals waiting', '1', 'Proposed by Bima P.', 'info'],
        ] as [$label, $count, $note, $tone])
            <div class="rounded-lg border border-ink-200 bg-white p-4">
                <p class="text-[12px] font-medium text-ink-500">{{ $label }}</p>
                <p class="mt-1 text-[24px] font-semibold tabular">{{ $count }}</p>
                <p @class([
                    'mt-1 text-[12px]',
                    'text-danger' => $tone === 'danger',
                    'text-warning' => $tone === 'warning',
                    'text-info' => $tone === 'info',
                ])>{{ $note }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[1fr_360px]">

        {{-- KYC preview --}}
        <section class="overflow-hidden rounded-lg border border-ink-200 bg-white">
            <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
                <h3 class="text-[15px] font-semibold">Oldest KYC submissions</h3>
                <a href="/admin/kyc" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">View all</a>
            </div>
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                        <th scope="col" class="px-5 py-2 font-medium">Company</th>
                        <th scope="col" class="px-5 py-2 font-medium">Country</th>
                        <th scope="col" class="px-5 py-2 font-medium">SLA</th>
                        <th scope="col" class="px-5 py-2 text-right font-medium">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kycRows as [$company, $country, $sla, $flag])
                        <tr class="border-b border-ink-200 last:border-b-0">
                            <td class="px-5 py-2.5 font-medium">{{ $company }}</td>
                            <td class="px-5 py-2.5 text-ink-600">{{ $country }}</td>
                            <td class="px-5 py-2.5"><x-status-badge :status="$flag" :label="$sla" /></td>
                            <td class="px-5 py-2.5 text-right">
                                <a href="/admin/kyc/1" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">Review</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>

        <aside class="space-y-5">
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Needs attention</h3>
                <ul class="mt-3 space-y-3 text-[12px]">
                    <li class="flex gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-danger"></span>
                        <div>
                            <p class="font-medium text-ink-800">Fee setting is empty</p>
                            <p class="text-ink-500">Decision D-08 is still open. Fee is not used in Phase 1.</p>
                        </div>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-warning"></span>
                        <div>
                            <p class="font-medium text-ink-800">Listing without valid certificate</p>
                            <p class="text-ink-500">Sumba Phase 2 cannot go active until verification.</p>
                        </div>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-info"></span>
                        <div>
                            <p class="font-medium text-ink-800">1 approval waiting</p>
                            <p class="text-ink-500">Second admin required. You proposed it, so you cannot approve.</p>
                        </div>
                    </li>
                </ul>
            </section>

            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">This week</h3>
                <dl class="mt-3 space-y-2 text-[13px]">
                    @foreach ([['KYC approved', '9'], ['KYC rejected', '2'], ['Listings approved', '6'], ['Certificates verified', '11']] as [$label, $value])
                        <div class="flex justify-between border-b border-ink-100 pb-2 last:border-b-0">
                            <dt class="text-ink-500">{{ $label }}</dt>
                            <dd class="font-medium tabular">{{ $value }}</dd>
                        </div>
                    @endforeach
                </dl>
            </section>
        </aside>
    </div>
</x-layouts.admin>
