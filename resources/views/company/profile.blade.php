<x-layouts.company title="Profile" kyc="APPROVED">
    <div class="max-w-[760px]">

        <h2 class="text-[20px] font-semibold">Profile</h2>
        <p class="mt-1 text-[13px] text-ink-600">Personal details, time zone and account security.</p>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Personal details</h3>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="name" class="block text-[13px] font-medium">Full name</label>
                    <input id="name" type="text" value="Aditya Wirawan"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                </div>
                <div>
                    <label for="email" class="block text-[13px] font-medium">Work email</label>
                    <input id="email" type="email" value="aditya@nusantarasolar.co.id" readonly
                           class="mt-1.5 w-full rounded-sm border border-ink-200 bg-ink-50 px-3 py-2 text-sm text-ink-600">
                </div>
            </div>

            <div class="mt-4 sm:max-w-[280px]">
                <label for="timezone" class="block text-[13px] font-medium">Time zone</label>
                <select id="timezone" class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    <option>Asia/Jakarta (GMT+7)</option>
                    <option>Asia/Singapore (GMT+8)</option>
                    <option>Asia/Kuala_Lumpur (GMT+8)</option>
                    <option>Asia/Ho_Chi_Minh (GMT+7)</option>
                    <option>Asia/Vientiane (GMT+7)</option>
                </select>
                <p class="mt-1 text-[12px] text-ink-500">Deadlines and timestamps are shown in this zone.</p>
            </div>

            <button type="button" class="mt-5 rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Save changes
            </button>
        </section>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Password</h3>
            <p class="mt-1 text-[12px] text-ink-500">At least 12 characters. You will be asked to sign in again after changing it.</p>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="current-password" class="block text-[13px] font-medium">Current password</label>
                    <input id="current-password" type="password"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                </div>
                <div>
                    <label for="new-password" class="block text-[13px] font-medium">New password</label>
                    <input id="new-password" type="password"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                </div>
            </div>

            <button type="button" class="mt-5 rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                Update password
            </button>
        </section>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="text-[15px] font-semibold">Two-factor authentication</h3>
                    <p class="mt-1 text-[12px] text-ink-500">Required for every account. Enabled since 12 Oct 2026.</p>
                </div>
                <x-status-badge status="ACTIVE" label="Enabled" />
            </div>

            <div class="mt-4 flex flex-wrap gap-2">
                <a href="/two-factor-setup" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    View recovery codes
                </a>
                <button type="button" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    Replace authenticator
                </button>
            </div>
        </section>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Sessions</h3>
            <ul class="mt-3 divide-y divide-ink-200 text-[13px]">
                <li class="flex items-center justify-between py-3 first:pt-0">
                    <div>
                        <p class="font-medium">Chrome on macOS · Jakarta</p>
                        <p class="text-[12px] text-ink-500">Current session · last active now</p>
                    </div>
                    <span class="text-[12px] text-ink-500">Active</span>
                </li>
                <li class="flex items-center justify-between py-3 last:pb-0">
                    <div>
                        <p class="font-medium">Safari on iPhone · Singapore</p>
                        <p class="text-[12px] text-ink-500">Last active 2 days ago</p>
                    </div>
                    <button type="button" class="text-[12px] font-medium text-danger underline underline-offset-2">Sign out</button>
                </li>
            </ul>
        </section>
    </div>
</x-layouts.company>
