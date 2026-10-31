<x-layouts.public title="Forgot password">
    <div class="mx-auto max-w-[420px] px-6 py-16">

        <h1 class="text-[24px] font-semibold leading-8">Reset your password</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Enter your work email. If an account exists, we will send a reset link that expires in 60 minutes.
        </p>

        <form class="mt-6 space-y-5" novalidate>
            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" type="email" autocomplete="email" placeholder="you@company.com"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Send reset link
            </button>
        </form>

        <p class="mt-6 text-[13px] text-ink-600">
            <a href="/login" class="font-medium text-brand-700 underline underline-offset-2">Back to sign in</a>
        </p>
    </div>
</x-layouts.public>
