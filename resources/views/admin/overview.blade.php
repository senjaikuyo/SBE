@php
    $kycRows = ($submissions ?? collect())->map(function ($sub) {
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
            $sla,
            $flag,
            $sub->id,
        ];
    });
@endphp

<x-layouts.admin title="Overview">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Operations overview</h2>
            <p class="mt-1 text-[13px] text-ink-600">{{ now()->format('d M Y') }} · times in Asia/Jakarta (GMT+7)</p>
        </div>
        <p class="text-[12px] text-ink-500">Target: KYC median 48h, 90% within 72h</p>
    </div>

    {{-- Stats --}}
    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">KYC pending</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $kycPendingCount ?? 0 }}</p>
            <p class="mt-1 text-[12px] text-ink-600">Decision SLA 72h</p>
        </div>
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Listings waiting</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $listingsWaitingCount ?? 0 }}</p>
            <p class="mt-1 text-[12px] text-warning">Needs certificate check</p>
        </div>
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Certificates waiting</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $certificatesWaitingCount ?? 0 }}</p>
            <p class="mt-1 text-[12px] text-warning">Serials to verify</p>
        </div>
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Approvals waiting</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $approvalsWaitingCount ?? 0 }}</p>
            <p class="mt-1 text-[12px] text-info">Four-eyes approval</p>
        </div>
    </div>

    <div class="mt-6 grid items-start gap-5 lg:grid-cols-[1fr_360px]">

        {{-- KYC preview --}}
        <section class="overflow-hidden rounded-lg border border-ink-200 bg-white">
            <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
                <h3 class="text-[15px] font-semibold">Pending KYC submissions</h3>
                <a href="{{ route('admin.kyc.index') }}" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">View all</a>
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
                    @forelse ($kycRows as [$company, $country, $sla, $flag, $subId])
                        <tr class="border-b border-ink-200 last:border-b-0">
                            <td class="px-5 py-2.5 font-medium">{{ $company }}</td>
                            <td class="px-5 py-2.5 text-ink-600">{{ $country }}</td>
                            <td class="px-5 py-2.5">
                                @if ($flag)
                                    <x-status-badge :status="$flag" :label="$sla" />
                                @else
                                    <span class="text-ink-500 tabular">{{ $sla }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-2.5 text-right">
                                <a href="/admin/kyc/{{ $subId }}" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">Review</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-ink-500">
                                No pending KYC reviews.
                            </td>
                        </tr>
                    @endforelse
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
                            <p class="text-ink-500">Listing cannot go active until verification.</p>
                        </div>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-info"></span>
                        <div>
                            <p class="font-medium text-ink-800">Four-eyes rule active</p>
                            <p class="text-ink-500">High-impact actions require two admins.</p>
                        </div>
                    </li>
                </ul>
            </section>
        </aside>
    </div>
</x-layouts.admin>
