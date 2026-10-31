@props(['code', 'title', 'description', 'hint' => null])

<x-layouts.public :title="$title">
    <div class="mx-auto max-w-[560px] px-6 py-20 text-center">
        <p class="font-mono text-[13px] font-medium text-ink-400">{{ $code }}</p>
        <h1 class="mt-2 text-[24px] font-semibold leading-8">{{ $title }}</h1>
        <p class="mt-2 text-[13px] leading-5 text-ink-600">{{ $description }}</p>

        @if ($hint)
            <p class="mt-4 rounded-md bg-ink-50 px-4 py-3 text-left text-[12px] leading-5 text-ink-600">{{ $hint }}</p>
        @endif

        <div class="mt-6 flex flex-col justify-center gap-2 sm:flex-row">
            <a href="/dashboard" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Go to dashboard
            </a>
            <a href="/" class="rounded-md border border-ink-300 bg-white px-5 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50">
                Back to home
            </a>
        </div>

        <p class="mt-6 text-[12px] text-ink-500">If this keeps happening, contact support@greengrid.example with code {{ $code }}.</p>
    </div>
</x-layouts.public>
