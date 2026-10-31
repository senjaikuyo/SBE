@php
    $rows = ($submissions ?? collect())->map(function ($sub) {
        $submittedAt = $sub->submitted_at ?? $sub->created_at ?? now();
        $deadline = $submittedAt->copy()->addHours(72);
        $now = now();
        if ($now->greaterThan($deadline)) {
            $hoursOverdue = (int) $deadline->diffInHours($now);
            $sla = $hoursOverdue . 'h overdue';
            $flag = 'OVERDUE';
        } else {
            $hoursLeft = (int) $now->diffInHours($deadline, false);
            $sla = $hoursLeft . 'h left';
            $flag = $hoursLeft <= 24 ? 'DUE_SOON' : null;
        }

        return [
            $sub->company->name ?? 'Company',
            $sub->company->country ?? 'ID',
            $submittedAt->diffForHumans(),
            $sla,
            $flag,
            '—',
            $sub->id,
        ];
    });
@endphp

<x-layouts.admin title="KYC queue">

    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">KYC queue</h2>
            <p class="mt-1 text-[13px] text-ink-600">Target: median 48 hours, 90% within 72 hours. Overdue rows are flagged.</p>
        </div>
        <div class="flex gap-2 text-[13px]">
            <span class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 font-medium text-white">
                Pending <span class="tabular">{{ $rows->count() }}</span>
            </span>
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
                @forelse ($rows as [$company, $country, $submitted, $sla, $flag, $reviewer, $subId])
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
                            <a href="/admin/kyc/{{ $subId }}" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                Review
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-ink-500">
                            No companies awaiting KYC review.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</x-layouts.admin>
