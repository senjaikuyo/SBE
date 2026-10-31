<x-layouts.admin title="Approvals">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Approval requests</h2>
            <p class="mt-1 text-[13px] text-ink-600">One admin proposes, a different admin approves. Reasons are mandatory.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-md bg-ink-900 px-3 py-1.5 text-[13px] font-medium text-white">
            Pending <span class="tabular">{{ $requests->count() }}</span>
        </span>
    </div>

    @if (session('status'))
        <div class="mt-4 rounded-md bg-brand-50 p-3 text-sm text-brand-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mt-5 space-y-4">
        @forelse ($requests as $approval)
            @php
                $isSelf = $approval->proposed_by_id === auth()->id();
            @endphp
            <article class="rounded-lg border border-ink-200 bg-white">
                <div class="flex flex-wrap items-start justify-between gap-3 border-b border-ink-200 px-5 py-3">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-[15px] font-semibold">{{ ucwords(str_replace('_', ' ', $approval->action)) }}</h3>
                            <x-status-badge :status="$approval->status" label="Awaiting second admin" />
                        </div>
                        <p class="mt-0.5 text-[12px] text-ink-500">
                            {{ $approval->entity_type ?? 'Platform' }} · proposed by {{ $approval->proposedBy->name ?? 'Admin' }} · {{ $approval->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <span class="font-mono text-[11px] text-ink-400">#{{ $approval->id }}</span>
                </div>

                <div class="grid gap-4 px-5 py-4 lg:grid-cols-[1fr_280px]">
                    <div>
                        <p class="text-[12px] font-medium uppercase tracking-wide text-ink-500">Proposal Reason</p>
                        <p class="mt-1 text-[13px] leading-5 text-ink-800">{{ $approval->reason }}</p>

                        @if ($approval->payload)
                            <div class="mt-3 rounded bg-ink-50 p-2.5 text-[12px] text-ink-700">
                                <span class="font-medium">Payload:</span> {{ json_encode($approval->payload) }}
                            </div>
                        @endif
                    </div>

                    <div class="rounded-md bg-ink-50 p-3">
                        @if ($isSelf)
                            <p class="text-[12px] font-medium text-warning">You proposed this request</p>
                            <p class="mt-1 text-[12px] leading-5 text-ink-600">
                                A different admin must review it. The approve button is disabled for you.
                            </p>
                            <button type="button" disabled
                                    class="mt-3 w-full cursor-not-allowed rounded-md bg-ink-200 px-3 py-2 text-[13px] font-medium text-ink-500">
                                Approve (Disabled)
                            </button>
                        @else
                            <p class="text-[12px] font-medium text-ink-700">Your decision</p>
                            <p class="mt-1 text-[12px] leading-5 text-ink-600">Approving applies the change immediately and writes an audit entry.</p>

                            <form method="POST" action="{{ route('admin.approvals.approve', $approval) }}" class="mt-3 space-y-2">
                                @csrf
                                <div>
                                    <label for="approve-reason-{{ $approval->id }}" class="sr-only">Decision reason</label>
                                    <input id="approve-reason-{{ $approval->id }}" name="reason" type="text" placeholder="Reason for approval (required)" required
                                           class="w-full rounded-sm border border-ink-300 bg-white px-2.5 py-1.5 text-[12px] placeholder:text-ink-400 focus:border-brand-500">
                                </div>
                                <button type="submit" class="w-full rounded-md bg-brand-500 px-3 py-2 text-[13px] font-medium text-white hover:bg-brand-600">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.approvals.reject', $approval) }}" class="mt-2 space-y-2">
                                @csrf
                                <div>
                                    <label for="reject-reason-{{ $approval->id }}" class="sr-only">Rejection reason</label>
                                    <input id="reject-reason-{{ $approval->id }}" name="reason" type="text" placeholder="Reason for rejection (required)" required
                                           class="w-full rounded-sm border border-ink-300 bg-white px-2.5 py-1.5 text-[12px] placeholder:text-ink-400 focus:border-brand-500">
                                </div>
                                <button type="submit" class="w-full rounded-md border border-danger/30 bg-white px-3 py-2 text-[13px] font-medium text-danger hover:bg-danger-soft">
                                    Reject
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-lg border border-ink-200 bg-white p-8 text-center text-ink-600">
                No pending approval requests.
            </div>
        @endforelse
    </div>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
        <h3 class="text-[15px] font-semibold">Conflict of interest</h3>
        <p class="mt-2 text-[13px] leading-5 text-ink-700">
            Requests involving a company where you are a member are blocked before the approve action is shown.
            Blocked decisions are recorded in the audit log with the reason “conflict of interest”.
        </p>
    </section>
</x-layouts.admin>
