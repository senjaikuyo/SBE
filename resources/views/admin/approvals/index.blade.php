@php
    $requests = [
        [
            'id' => 'ap_1042',
            'action' => 'Re-approve company after beneficial owner check failure',
            'entity' => 'Johor Green Power Sdn Bhd',
            'proposed_by' => 'Rani Kusuma',
            'when' => '31 Oct 2026, 11:20 (GMT+7)',
            'reason' => 'Ownership structure corrected with new ACRA filing. Beneficial owner verified as 32% shareholder.',
            'status' => 'PENDING_REVIEW',
            'self' => true,
        ],
        [
            'id' => 'ap_1041',
            'action' => 'Set marketplace fee percentage',
            'entity' => 'Platform settings',
            'proposed_by' => 'Bima Pratama',
            'when' => '30 Oct 2026, 16:05 (GMT+7)',
            'reason' => 'Preparing Phase 2 pricing. Value stays unused until D-08 is closed.',
            'status' => 'PENDING_REVIEW',
            'self' => false,
        ],
    ];
@endphp

<x-layouts.admin title="Approvals">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Approval requests</h2>
            <p class="mt-1 text-[13px] text-ink-600">One admin proposes, a different admin approves. Reasons are mandatory.</p>
        </div>
        <a href="#" class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 text-[13px] font-medium text-white">Pending <span class="tabular">2</span></a>
    </div>

    <div class="mt-5 space-y-4">
        @foreach ($requests as $request)
            <article class="rounded-lg border border-ink-200 bg-white">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-ink-200 px-5 py-3">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-[15px] font-semibold">{{ $request['action'] }}</h3>
                            <x-status-badge :status="$request['status']" label="Awaiting second admin" />
                        </div>
                        <p class="mt-0.5 text-[12px] text-ink-500">{{ $request['entity'] }} · proposed by {{ $request['proposed_by'] }} · {{ $request['when'] }}</p>
                    </div>
                    <span class="font-mono text-[11px] text-ink-400">{{ $request['id'] }}</span>
                </div>

                <div class="grid gap-4 px-5 py-4 lg:grid-cols-[1fr_280px]">
                    <div>
                        <p class="text-[12px] font-medium uppercase tracking-wide text-ink-500">Reason</p>
                        <p class="mt-1 text-[13px] leading-5 text-ink-800">{{ $request['reason'] }}</p>
                    </div>

                    <div class="rounded-md bg-ink-50 p-3">
                        @if ($request['self'])
                            <p class="text-[12px] font-medium text-warning">You proposed this request</p>
                            <p class="mt-1 text-[12px] leading-5 text-ink-600">
                                A different admin must review it. The approve button is disabled for you.
                            </p>
                            <button type="button" disabled
                                    class="mt-3 w-full cursor-not-allowed rounded-md bg-ink-200 px-3 py-2 text-[13px] font-medium text-ink-500">
                                Approve
                            </button>
                        @else
                            <p class="text-[12px] font-medium text-ink-700">Your decision</p>
                            <p class="mt-1 text-[12px] leading-5 text-ink-600">Approving applies the change immediately and writes an audit entry.</p>
                            <div class="mt-3 space-y-2">
                                <button type="button" class="w-full rounded-md bg-brand-500 px-3 py-2 text-[13px] font-medium text-white hover:bg-brand-600">
                                    Approve
                                </button>
                                <button type="button" class="w-full rounded-md border border-danger/30 bg-white px-3 py-2 text-[13px] font-medium text-danger hover:bg-danger-soft">
                                    Reject
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                @if (! $request['self'])
                    <div class="border-t border-ink-200 px-5 py-3">
                        <label for="reason-{{ $request['id'] }}" class="block text-[12px] font-medium">Decision reason <span class="font-normal text-ink-500">(required)</span></label>
                        <input id="reason-{{ $request['id'] }}" type="text" placeholder="Why you approve or reject"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-[13px] placeholder:text-ink-400 focus:border-brand-500">
                    </div>
                @endif
            </article>
        @endforeach
    </div>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
        <h3 class="text-[15px] font-semibold">Conflict of interest</h3>
        <p class="mt-2 text-[13px] leading-5 text-ink-700">
            Requests involving a company where you are a member are blocked before the approve action is shown.
            Blocked decisions are recorded in the audit log with the reason “conflict of interest”.
        </p>
    </section>
</x-layouts.admin>
