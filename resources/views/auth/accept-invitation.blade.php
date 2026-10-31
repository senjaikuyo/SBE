<x-layouts.public title="Join Nusantara Solar Prima">
    <div class="mx-auto max-w-[520px] px-6 py-14">

        <div class="rounded-lg border border-ink-200 bg-white p-6">
            <p class="text-[12px] font-medium uppercase tracking-wider text-brand-700">Invitation</p>
            <h1 class="mt-2 text-[24px] font-semibold leading-8">Join Nusantara Solar Prima</h1>
            <p class="mt-1 text-[13px] leading-5 text-ink-600">
                Aditya Wirawan invited <span class="font-medium text-ink-800">you@company.com</span> to join as
                <span class="font-medium text-ink-800">Procurement</span>.
            </p>

            <dl class="mt-4 space-y-2 rounded-md bg-ink-50 p-3 text-[12px]">
                <div class="flex justify-between">
                    <dt class="text-ink-500">Company</dt>
                    <dd class="font-medium">Nusantara Solar Prima</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">Role</dt>
                    <dd class="font-medium">Procurement</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">Expires</dt>
                    <dd class="font-medium">7 Nov 2026, 23:59 (GMT+7)</dd>
                </div>
            </dl>

            <form class="mt-6 space-y-4" novalidate>
                <div>
                    <label for="name" class="block text-[13px] font-medium">Full name</label>
                    <input id="name" type="text" placeholder="Your full name"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
                </div>

                <div>
                    <label for="password" class="block text-[13px] font-medium">Create a password</label>
                    <input id="password" type="password"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                           aria-describedby="password-hint">
                    <p id="password-hint" class="mt-1 text-[12px] text-ink-500">At least 12 characters. You will set up two-factor authentication next.</p>
                </div>

                <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Accept invitation
                </button>
            </form>

            <p class="mt-4 text-center text-[12px] text-ink-500">
                Already have an account?
                <a href="/login" class="font-medium text-brand-700 underline underline-offset-2">Sign in to accept</a>
            </p>
        </div>

        <p class="mt-4 text-center text-[12px] text-ink-500">Invitations expire 7 days after they are sent.</p>
    </div>
</x-layouts.public>
