<x-layouts.public title="Invitation unavailable">
    <div class="mx-auto max-w-[520px] px-6 py-16">
        <div class="rounded-lg border border-ink-200 bg-white p-6 text-center">
            <span class="mx-auto grid h-10 w-10 place-items-center rounded-full bg-danger-soft text-danger">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/>
                    <path d="M12 8v5M12 16h.01" stroke-linecap="round"/>
                </svg>
            </span>
            <h1 class="mt-4 text-[20px] font-semibold leading-7">This invitation is no longer valid</h1>
            <p class="mt-2 text-[13px] leading-5 text-ink-600">
                Invitations are valid for {{ config('greengrid.invitation_expiry_days') }} days and can be used once. Ask your company administrator to send a new one.
            </p>
            <div class="mt-6 flex flex-col gap-2">
                <a href="/login" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Go to sign in
                </a>
                <a href="/" class="rounded-md border border-ink-300 px-5 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    Back to home
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-[12px] leading-5 text-ink-500">
            If you already accepted this invitation, sign in instead.
        </p>
    </div>
</x-layouts.public>
