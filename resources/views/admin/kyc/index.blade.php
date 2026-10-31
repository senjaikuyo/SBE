@php
    $rows = [
        ['Andalan Solar Nusantara', 'Indonesia', '2 h ago', '46h left', 'DUE_SOON', '—'],
        ['Mekong Renewables Co., Ltd', 'Vietnam', '2 d ago', '3h left', 'DUE_SOON', 'Rani K.'],
        ['Johor Green Power Sdn Bhd', 'Malaysia', '3 d ago', '9h overdue', 'OVERDUE', 'Rani K.'],
        ['Laos Sunshine Energy', 'Laos', '4 d ago', '1 d 8h overdue', 'OVERDUE', 'Bima P.'],
        ['Solaris Vietnam JSC', 'Vietnam', '5 h ago', '67h left', null, '—'],
        ['Nusantara Solar Prima', 'Indonesia', '6 h ago', '66h left', null, '—'],
    ];
@endphp

<x-layouts.admin title="KYC queue">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">KYC queue</h2>
            <p class="mt-1 text-[13px] text-ink-600">Target: median 48 hours, 90% within 72 hours. Overdue rows are flagged.</p>
        </div>
        <div class="flex gap-2 text-[13px]">
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">All <span class="tabular">12</span></a>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">Due soon <span class="tabular">3</span></a>
            <a href="#" class="inline-flex items-center gap-1.5 rounded-md border border-ink-300 bg-white px-3 py-1.5 text-ink-700 hover:bg-ink-50">Overdue <span class="tabular">2</span></a>
        </div>
    </div>

    <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Companies awaiting KYC review</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Company</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Country</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Submitted</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Decision SLA</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Reviewer</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as [$company, $country, $submitted, $sla, $flag, $reviewer])
                    <tr @class([
                        'border-b border-ink-200 last:border-b-0',
                        'border-l-[3px] border-l-danger' => $flag === 'OVERDUE',
                        'border-l-[3px] border-l-warning' => $flag === 'DUE_SOON',
                        'border-l-[3px] border-l-transparent' => $flag === null,
                        'hover:bg-ink-50' => true,
                    ])>
                        <td class="px-4 py-3 font-medium">{{ $company }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $country }}</td>
                        <td class="px-4 py-3 text-ink-600">{{ $submitted }}</td>
                        <td class="px-4 py-3">
                            @if ($flag)
                                <x-status-badge :status="$flag" :label="$sla" />
                            @else
                                <span class="text-ink-500 tabular">{{ $sla }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span @class(['text-ink-500' => $reviewer === '—'])>{{ $reviewer }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="/admin/kyc/1" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                Review
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="flex items-center justify-between border-t border-ink-200 px-4 py-3 text-[12px] text-ink-500">
            <span>Showing 6 of 12</span>
            <div class="flex gap-1">
                <button type="button" class="rounded-sm border border-ink-300 px-2.5 py-1 text-ink-400" disabled>Previous</button>
                <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-ink-700 hover:bg-ink-50">Next</button>
            </div>
        </div>
    </div>

</x-layouts.admin>
