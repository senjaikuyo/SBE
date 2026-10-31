@props(['title' => null, 'company' => null, 'kyc' => null])

@php
    $user = auth()->user();
    $activeCompany = $company ?? $user?->company()?->name;
    $kycStatus = $kyc ?? $user?->company()?->kyc_status ?? 'NOT_SUBMITTED';
    $role = $user?->company()
        ? $user->membershipFor($user->company())?->role
        : null;

    $items = [
        ['label' => 'Dashboard', 'href' => '/dashboard', 'match' => 'dashboard'],
        ['label' => 'Company members', 'href' => '/company/members', 'match' => 'company/members*'],
        ['label' => 'KYC verification', 'href' => '/company/kyc', 'match' => 'company/kyc*'],
        ['label' => 'My listings', 'href' => '/company/listings', 'match' => 'company/listings*'],
        ['label' => 'Browse marketplace', 'href' => '/listings', 'match' => 'listings*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' · GreenGrid APAC' : 'GreenGrid APAC' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full">

<div class="flex min-h-screen">
    <aside class="hidden w-[240px] shrink-0 flex-col border-r border-ink-200 bg-white lg:flex" aria-label="Sidebar">
        <a href="/dashboard" class="flex h-16 items-center gap-2.5 border-b border-ink-200 px-5">
            <span class="grid h-8 w-8 place-items-center rounded-md bg-brand-500 text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="text-[15px] font-semibold">GreenGrid</span>
        </a>

        <nav class="flex-1 space-y-1 p-3" aria-label="Company">
            @foreach ($items as $item)
                <a href="{{ $item['href'] }}"
                   @class([
                       'flex items-center justify-between rounded-md px-3 py-2 text-sm',
                       'bg-brand-50 font-medium text-brand-700' => request()->is($item['match']),
                       'text-ink-700 hover:bg-ink-100' => ! request()->is($item['match']),
                   ])
                   @if (request()->is($item['match'])) aria-current="page" @endif>
                    {{ $item['label'] }}
                    @if ($item['href'] === '/listings' && $kycStatus !== 'APPROVED')
                        <span class="text-[11px] text-ink-400">locked</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div class="border-t border-ink-200 p-3">
            <div class="rounded-md bg-ink-50 p-3">
                <p class="truncate text-[13px] font-medium">{{ $activeCompany ?? 'No company yet' }}</p>
                <p class="mt-0.5 truncate text-[12px] text-ink-500">{{ $role ? ucfirst(str_replace('_', ' ', $role)) : 'Onboarding' }}</p>
            </div>

            @if ($user)
                <form method="POST" action="/logout" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full rounded-md px-3 py-2 text-left text-[13px] text-ink-600 hover:bg-ink-100">
                        Sign out
                    </button>
                </form>
            @endif
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex h-16 items-center justify-between border-b border-ink-200 bg-white px-5 lg:px-8">
            <div class="flex items-center gap-3">
                <span class="text-sm font-medium text-ink-500 lg:hidden">GreenGrid</span>
                <h1 class="text-[15px] font-semibold">{{ $title }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <x-status-badge :status="$kycStatus" />
                <span class="hidden text-[13px] text-ink-500 sm:inline">{{ $user?->email }}</span>
            </div>
        </header>

        <main class="mx-auto w-full max-w-[1120px] flex-1 px-5 py-6 lg:px-8 lg:py-8">
            @if (session('status'))
                <div class="mb-5 rounded-md border border-success/30 bg-success-soft px-4 py-3 text-[13px] text-ink-800" role="status">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-5 rounded-md border border-danger/30 bg-danger-soft px-4 py-3" role="alert">
                    <p class="text-[13px] font-medium text-danger">Please fix the following:</p>
                    <ul class="mt-1 list-inside list-disc text-[12px] text-ink-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
