@props(['title' => null, 'admin' => 'Rani Kusuma'])

@php
    $items = [
        ['label' => 'Overview', 'href' => '/admin', 'match' => '/admin'],
        ['label' => 'KYC queue', 'href' => '/admin/kyc', 'match' => '/admin/kyc*', 'count' => 12],
        ['label' => 'Listing moderation', 'href' => '/admin/listings', 'match' => '/admin/listings*', 'count' => 4],
        ['label' => 'Certificates', 'href' => '/admin/certificates', 'match' => '/admin/certificates*', 'count' => 7],
        ['label' => 'Approvals', 'href' => '/admin/approvals', 'match' => '/admin/approvals*'],
        ['label' => 'Settings', 'href' => '/admin/settings', 'match' => '/admin/settings*'],
    ];
@endphp

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · Admin · GreenGrid APAC' : 'Admin · GreenGrid APAC' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-ink-50">

<div class="flex min-h-screen">
    <aside class="hidden w-[240px] shrink-0 flex-col border-r border-ink-200 bg-ink-900 lg:flex" aria-label="Admin sidebar">
        <div class="flex h-16 items-center gap-2.5 border-b border-white/10 px-5">
            <span class="grid h-8 w-8 place-items-center rounded-md bg-brand-500 text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" stroke-linecap="round"/>
                </svg>
            </span>
            <div class="leading-tight">
                <p class="text-[14px] font-semibold text-white">GreenGrid</p>
                <p class="text-[11px] text-white/50">Platform admin</p>
            </div>
        </div>

        <nav class="flex-1 space-y-0.5 p-3" aria-label="Admin">
            @foreach ($items as $item)
                <a href="{{ $item['href'] }}"
                   @class([
                       'flex items-center justify-between rounded-md px-3 py-2 text-sm',
                       'bg-white/10 font-medium text-white' => request()->is(ltrim($item['match'], '/')),
                       'text-white/70 hover:bg-white/5 hover:text-white' => ! request()->is(ltrim($item['match'], '/')),
                   ])
                   @if (request()->is(ltrim($item['match'], '/'))) aria-current="page" @endif>
                    {{ $item['label'] }}
                    @isset($item['count'])
                        <span class="rounded-full bg-white/10 px-2 py-0.5 text-[11px] tabular">{{ $item['count'] }}</span>
                    @endisset
                </a>
            @endforeach
        </nav>

        <div class="border-t border-white/10 p-3 text-[12px] text-white/50">
            Signed in as {{ $admin }}
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="flex h-16 items-center justify-between border-b border-ink-200 bg-white px-5 lg:px-8">
            <h1 class="text-[15px] font-semibold">{{ $title }}</h1>
            <span class="text-[13px] text-ink-500">{{ $admin }}</span>
        </header>

        <main class="mx-auto w-full max-w-[1280px] flex-1 px-5 py-6 lg:px-8 lg:py-8">
            {{ $slot }}
        </main>
    </div>
</div>

</body>
</html>
