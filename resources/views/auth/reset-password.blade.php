<x-layouts.public title="Set a new password">
    <div class="mx-auto max-w-[420px] px-6 py-16">

        <h1 class="text-[24px] font-semibold leading-8">Set a new password</h1>
        <p class="mt-1 text-[13px] text-ink-600">Choose a password of at least 12 characters.</p>

        <form class="mt-6 space-y-5" novalidate>
            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" type="email" value="aditya@nusantarasolar.co.id" readonly
                       class="mt-1.5 w-full rounded-sm border border-ink-200 bg-ink-50 px-3 py-2 text-sm text-ink-600">
            </div>

            <div>
                <label for="password" class="block text-[13px] font-medium">New password</label>
                <input id="password" type="password" autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                       aria-describedby="password-hint">
                <p id="password-hint" class="mt-1 text-[12px] text-ink-500">At least 12 characters.</p>
            </div>

            <div>
                <label for="password-confirmation" class="block text-[13px] font-medium">Confirm new password</label>
                <input id="password-confirmation" type="password" autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
            </div>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Update password
            </button>
        </form>
    </div>
</x-layouts.public>
