<x-layouts.public title="Two-factor authentication">
    <div class="mx-auto max-w-[520px] px-6 py-14">

        <p class="text-[12px] font-medium uppercase tracking-wider text-brand-700">Step 3 of 3</p>
        <h1 class="mt-2 text-[24px] font-semibold leading-8">Set up two-factor authentication</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Required for every account. Scan the code with an authenticator app, then enter the 6-digit code.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                <div class="w-[192px] shrink-0 rounded-md border border-ink-200 bg-white p-2 [&>svg]:h-full [&>svg]:w-full">
                    {!! $qrCodeSvg !!}
                </div>

                <div class="min-w-0">
                    <p class="text-[13px] font-medium">Cannot scan?</p>
                    <p class="mt-1 text-[12px] text-ink-500">Enter this key manually in your app:</p>
                    <p class="mt-2 break-all rounded-sm bg-ink-50 px-2.5 py-2 font-mono text-[12px] tracking-wide">{{ $secret }}</p>
                    <p class="mt-3 text-[12px] leading-5 text-ink-500">
                        Keep this key private. If you lose your device you will need your recovery codes.
                    </p>
                </div>
            </div>

            <form method="POST" action="/two-factor-setup" class="mt-6 border-t border-ink-200 pt-5">
                @csrf

                <label for="code" class="block text-[13px] font-medium">Authentication code</label>
                <input id="code" name="code" inputmode="numeric" maxlength="6" required autocomplete="one-time-code" placeholder="000000"
                       class="mt-1.5 w-[180px] rounded-sm border border-ink-300 bg-white px-3 py-2 font-mono text-sm tracking-[0.3em] placeholder:text-ink-300 focus:border-brand-500"
                       aria-describedby="code-hint">
                <p id="code-hint" class="mt-1 text-[12px] text-ink-500">Codes rotate every 30 seconds.</p>

                <button type="submit" class="mt-4 w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Turn on two-factor authentication
                </button>
            </form>
        </div>

        <p class="mt-4 text-[12px] leading-5 text-ink-500">
            Until this is enabled, only your profile is available. Marketplace, listings and company features stay locked.
        </p>
    </div>
</x-layouts.public>
