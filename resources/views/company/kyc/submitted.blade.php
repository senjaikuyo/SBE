<x-layouts.company title="KYC submitted" kyc="PENDING_VERIFICATION">
    <div class="mx-auto max-w-[560px] py-8 text-center">
        <span class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-success-soft text-success">
            <svg class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z"/>
            </svg>
        </span>

        <h2 class="mt-4 text-[24px] font-semibold leading-8">Documents received</h2>
        <p class="mt-2 text-[13px] leading-5 text-ink-600">
            We sent a confirmation to <span class="font-medium text-ink-800">aditya@nusantarasolar.co.id</span>.
            Our compliance team reviews within 48 hours, and 90% of decisions are made within 72 hours.
        </p>

        <div class="mt-6 rounded-lg border border-ink-200 bg-white p-5 text-left">
            <h3 class="text-[15px] font-semibold">What happens next</h3>
            <ol class="mt-3 space-y-3">
                @foreach ([
                    ['Sanctions screening', 'Runs automatically and repeats every 90 days while your company stays active.'],
                    ['Compliance review', 'A reviewer checks each document against the national registry.'],
                    ['Decision by email', 'You are notified whether the company is approved or needs to resubmit documents.'],
                ] as [$title, $body])
                    <li class="flex gap-2.5">
                        <span class="mt-1 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                        <div>
                            <p class="text-[13px] font-medium">{{ $title }}</p>
                            <p class="text-[12px] leading-5 text-ink-600">{{ $body }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="mt-6 rounded-md bg-ink-50 px-4 py-3 text-left text-[12px] leading-5 text-ink-600">
            While you wait you can invite team members and complete your profile. Marketplace access unlocks after approval.
        </div>

        <div class="mt-6 flex flex-col gap-2 sm:flex-row sm:justify-center">
            <a href="/company/kyc" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                View submission status
            </a>
            <a href="/dashboard" class="rounded-md border border-ink-300 bg-white px-5 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50">
                Back to dashboard
            </a>
        </div>
    </div>
</x-layouts.company>
