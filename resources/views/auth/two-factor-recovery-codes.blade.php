<x-layouts.public title="Recovery codes">
    <div class="mx-auto max-w-[520px] px-6 py-14">

        <h1 class="text-[24px] font-semibold leading-8">Recovery codes</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Each code works once. Store them somewhere safe, offline, and away from the device that runs your authenticator.
        </p>

        <ul class="mt-5 grid grid-cols-2 gap-2 rounded-lg border border-ink-200 bg-white p-5 font-mono text-[13px]">
            @foreach ($codes as $code)
                <li class="rounded-sm bg-ink-50 px-3 py-2 text-center tracking-wide">{{ $code }}</li>
            @endforeach
        </ul>

        <a href="/profile" class="mt-6 inline-block rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
            Back to profile
        </a>
    </div>
</x-layouts.public>
