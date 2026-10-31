@php
    $kycStatus = $company?->kyc_status ?? 'NOT_SUBMITTED';
    $role = $membership?->role;
@endphp

<x-layouts.company title="Dashboard">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Welcome, {{ explode(' ', auth()->user()->name)[0] }}</h2>
            <p class="mt-1 text-[13px] text-ink-600">
                {{ $company?->name }} · {{ ucfirst(str_replace('_', ' ', $role ?? '')) }}
            </p>
        </div>
        <p class="text-[12px] text-ink-500">Local time: {{ now()->setTimezone(auth()->user()->timezone)->format('D j M Y, H:i') }}</p>
    </div>

    @if ($kycStatus !== 'APPROVED')
        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div class="max-w-xl">
                    <h3 class="text-[15px] font-semibold">Company verification</h3>
                    <p class="mt-1 text-[13px] leading-5 text-ink-600">
                        Marketplace access, listings and the buyer or seller role unlock after our compliance team approves the company.
                    </p>
                </div>
                <x-status-badge :status="$kycStatus" />
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="/company/kyc" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    {{ $kycStatus === 'NOT_SUBMITTED' ? 'Start verification' : 'View status' }}
                </a>
            </div>
        </section>
    @endif

    <div class="mt-5 grid gap-4 sm:grid-cols-3">
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Team members</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $memberCount }}</p>
            <a href="/company/members" class="mt-1 inline-block text-[12px] font-medium text-brand-700 underline underline-offset-2">Manage members</a>
        </div>
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Pending invitations</p>
            <p class="mt-1 text-[24px] font-semibold tabular">{{ $pendingInvitations }}</p>
            <p class="mt-1 text-[12px] text-ink-500">Expire after {{ config('greengrid.invitation_expiry_days') }} days</p>
        </div>
        <div class="rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] font-medium text-ink-500">Marketplace role</p>
            <p class="mt-1 text-[15px] font-semibold">{{ $company?->company_role ? ucfirst($company->company_role) : 'Assigned after KYC' }}</p>
            <p class="mt-1 text-[12px] text-ink-500">Set by GreenGrid, not by you</p>
        </div>
    </div>

    <div class="mt-6 grid gap-5 lg:grid-cols-[1fr_360px]">
        <section class="rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">What you can do now</h3>
            <ul class="mt-3 space-y-3 text-[13px]">
                <li class="flex items-start gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                    <div>
                        <p class="font-medium">Invite your team</p>
                        <p class="text-[12px] text-ink-500">Company admins choose each person's role. Invitations expire after 7 days.</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-brand-500"></span>
                    <div>
                        <p class="font-medium">Complete company verification</p>
                        <p class="text-[12px] text-ink-500">Upload the documents required for your country of registration.</p>
                    </div>
                </li>
                <li class="flex items-start gap-2">
                    <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-ink-300"></span>
                    <div>
                        <p class="font-medium text-ink-500">Create listings</p>
                        <p class="text-[12px] text-ink-500">Available after verification and certificate review.</p>
                    </div>
                </li>
            </ul>
        </section>

        <aside class="rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Account security</h3>
            <div class="mt-3 flex items-center justify-between text-[13px]">
                <span class="text-ink-600">Two-factor authentication</span>
                <x-status-badge status="ACTIVE" label="On" />
            </div>
            <p class="mt-2 text-[12px] leading-5 text-ink-500">
                Sessions end after {{ config('greengrid.session_idle_minutes') }} minutes of inactivity. Drafts are restored after sign-in.
            </p>
            <a href="/profile" class="mt-3 inline-block text-[13px] font-medium text-brand-700 underline underline-offset-2">Open profile</a>
        </aside>
    </div>
</x-layouts.company>
