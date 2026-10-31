<x-layouts.public title="Two-factor authentication">
    <div class="mx-auto max-w-[420px] px-6 py-16">

        <h1 class="text-[24px] font-semibold leading-8">Two-factor authentication</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Enter the 6-digit code from your authenticator app, or use one of your recovery codes.
        </p>

        <form class="mt-6 space-y-5" novalidate>
            <div>
                <label for="code" class="block text-[13px] font-medium">Authentication code</label>
                <input id="code" inputmode="numeric" maxlength="6" autocomplete="one-time-code" placeholder="000000"
                       class="mt-1.5 w-[180px] rounded-sm border border-ink-300 bg-white px-3 py-2 font-mono text-sm tracking-[0.3em] placeholder:text-ink-300 focus:border-brand-500">
            </div>

            <label class="flex items-center gap-2.5 text-[13px] text-ink-700">
                <input type="checkbox" class="h-4 w-4 rounded-sm border-ink-300 text-brand-500 focus:ring-2 focus:ring-brand-500">
                Trust this device for 30 days
            </label>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Verify
            </button>
        </form>

        <p class="mt-6 text-[13px] text-ink-600">
            Lost your device?
            <a href="#" class="font-medium text-brand-700 underline underline-offset-2">Use a recovery code</a>
        </p>
        <p class="mt-1 text-[13px] text-ink-600">
            <a href="/login" class="text-ink-500 underline underline-offset-2">Sign in as a different user</a>
        </p>
    </div>
</x-layouts.public>
