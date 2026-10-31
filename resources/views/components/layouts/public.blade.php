@props(['title' => null])

<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' · GreenGrid APAC' : 'GreenGrid APAC' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col">

<header class="border-b border-ink-200 bg-white">
    <div class="mx-auto flex h-16 max-w-[1120px] items-center justify-between px-6">
        <a href="/" class="flex items-center gap-2.5" aria-label="GreenGrid APAC home">
            <span class="grid h-8 w-8 place-items-center rounded-md bg-brand-500 text-white">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 4h6v6H4zM14 4h6v6h-6zM4 14h6v6H4zM14 14h6v6h-6z" stroke-linecap="round"/>
                </svg>
            </span>
            <span class="text-[15px] font-semibold tracking-tight">GreenGrid <span class="text-ink-500">APAC</span></span>
        </a>

        <nav class="flex items-center gap-2" aria-label="Main">
            <a href="/login" class="rounded-md px-3 py-2 text-sm font-medium text-ink-700 hover:bg-ink-100">Sign in</a>
            <a href="/register" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">Create account</a>
        </nav>
    </div>
</header>

<main class="flex-1">
    {{ $slot }}
</main>

<footer class="border-t border-ink-200 bg-white">
    <div class="mx-auto flex max-w-[1120px] flex-wrap items-center justify-between gap-3 px-6 py-6 text-[13px] text-ink-500">
        <p>GreenGrid APAC — solar energy and carbon credit marketplace. We do not transmit electricity or act as a party to contracts.</p>
        <nav class="flex gap-5" aria-label="Legal">
            <a href="/privacy" class="hover:text-ink-700">Privacy Policy</a>
            <a href="/terms" class="hover:text-ink-700">Terms of Service</a>
        </nav>
    </div>
</footer>

</body>
</html>
