<x-layouts.admin title="Settings">
    <div class="max-w-[820px]">
        <h2 class="text-[20px] font-semibold">Platform settings</h2>
        <p class="mt-1 text-[13px] text-ink-600">Changes here need a second admin. Every change is written to the audit log with a reason.</p>

        @if (session('status'))
            <div class="mt-4 rounded-md bg-brand-50 p-3 text-sm text-brand-700">
                {{ session('status') }}
            </div>
        @endif

        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="text-[15px] font-semibold">Marketplace fee</h3>
                    <p class="mt-1 text-[12px] leading-5 text-ink-500">Percentage charged on settled transactions. Not used in Phase 1.</p>
                </div>
                <span class="rounded-sm bg-warning-soft px-2 py-1 text-[11px] font-medium text-warning">D-08 open</span>
            </div>

            <form method="POST" action="{{ route('admin.approvals.propose') }}">
                @csrf
                <input type="hidden" name="action" value="set_fee_percentage">

                <div class="mt-4 sm:max-w-[220px]">
                    <label for="fee" class="block text-[13px] font-medium">Fee percentage</label>
                    <div class="mt-1.5 flex rounded-sm border border-ink-200 bg-white">
                        <input id="fee" name="payload[fee_percentage]" type="text" value="{{ $feePercentage ?? '' }}" placeholder="Not set"
                               class="w-full bg-transparent px-3 py-2 text-sm tabular">
                        <span class="border-l border-ink-200 px-3 py-2 text-[13px] text-ink-400">%</span>
                    </div>
                    <p class="mt-1 text-[12px] text-ink-500">Charged by, and initial incentive, are also undecided.</p>
                </div>

                <div class="mt-4">
                    <label for="fee-reason" class="block text-[13px] font-medium">Reason <span class="font-normal text-ink-500">(required when saving)</span></label>
                    <textarea id="fee-reason" name="reason" rows="2" placeholder="Explain why this fee percentage is proposed..." required
                              class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"></textarea>
                </div>

                <div class="mt-4 flex flex-wrap items-center gap-3">
                    <button type="submit"
                            class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                        Propose change
                    </button>
                    <p id="fee-note" class="text-[12px] text-ink-500">Waiting on decision D-08. Stored in settings table, not used in Phase 1.</p>
                </div>
            </form>
        </section>

        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="text-[15px] font-semibold">Document retention</h3>
                    <p class="mt-1 text-[12px] leading-5 text-ink-500">Retention period and hosting region for KYC documents.</p>
                </div>
                <span class="rounded-sm bg-warning-soft px-2 py-1 text-[11px] font-medium text-warning">D-07 open</span>
            </div>

            <dl class="mt-4 grid gap-3 text-[13px] sm:grid-cols-2">
                <div>
                    <dt class="text-[12px] text-ink-500">Retention period</dt>
                    <dd class="mt-0.5 rounded-sm border border-ink-200 bg-ink-50 px-2 py-1.5 text-ink-400">Not configured</dd>
                </div>
                <div>
                    <dt class="text-[12px] text-ink-500">Hosting region</dt>
                    <dd class="mt-0.5 rounded-sm border border-ink-200 bg-ink-50 px-2 py-1.5 text-ink-400">Not configured</dd>
                </div>
            </dl>
            <p class="mt-3 text-[12px] text-ink-500">Both values are read from configuration. No value is hard-coded.</p>
        </section>

        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Four-eyes rule</h3>
            <p class="mt-2 text-[13px] leading-5 text-ink-700">
                High-impact changes require two admins: one proposes, a different admin approves. You cannot approve your own proposal,
                and you cannot decide on a company where you are a member.
            </p>
            <a href="{{ route('admin.approvals.index') }}" class="mt-3 inline-block text-[13px] font-medium text-brand-700 underline underline-offset-2">View approval requests</a>
        </section>
    </div>
</x-layouts.admin>
