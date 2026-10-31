<x-layouts.company title="Profile">
    <div class="max-w-[760px]">

        <h2 class="text-[20px] font-semibold">Profile</h2>
        <p class="mt-1 text-[13px] text-ink-600">Personal details, time zone and account security.</p>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Personal details</h3>

            <form method="POST" action="{{ route('profile.update') }}" class="mt-4">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-[13px] font-medium">Full name</label>
                        <input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                    <div>
                        <label for="email" class="block text-[13px] font-medium">Work email</label>
                        <input id="email" type="email" value="{{ $user->email }}" readonly
                               class="mt-1.5 w-full rounded-sm border border-ink-200 bg-ink-50 px-3 py-2 text-sm text-ink-600">
                    </div>
                </div>

                <div class="mt-4 sm:max-w-[280px]">
                    <label for="timezone" class="block text-[13px] font-medium">Time zone</label>
                    <select id="timezone" name="timezone"
                            class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        @foreach (['Asia/Jakarta', 'Asia/Singapore', 'Asia/Kuala_Lumpur', 'Asia/Ho_Chi_Minh', 'Asia/Vientiane'] as $zone)
                            <option value="{{ $zone }}" @selected(old('timezone', $user->timezone) === $zone)>{{ $zone }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-[12px] text-ink-500">Deadlines and timestamps are shown in this zone.</p>
                </div>

                <button type="submit" class="mt-5 rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Save changes
                </button>
            </form>
        </section>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Password</h3>
            <p class="mt-1 text-[12px] text-ink-500">
                At least {{ config('greengrid.password_min_length') }} characters.
            </p>

            <form method="POST" action="{{ route('profile.password') }}" class="mt-4">
                @csrf
                @method('PATCH')

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="current_password" class="block text-[13px] font-medium">Current password</label>
                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                    <div>
                        <label for="new_password" class="block text-[13px] font-medium">New password</label>
                        <input id="new_password" name="password" type="password" required autocomplete="new-password"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-[13px] font-medium">Confirm new password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                </div>

                <button type="submit" class="mt-5 rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    Update password
                </button>
            </form>
        </section>

        <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h3 class="text-[15px] font-semibold">Two-factor authentication</h3>
                    <p class="mt-1 text-[12px] text-ink-500">Required for every account.</p>
                </div>
                <x-status-badge :status="$user->hasTwoFactorEnabled() ? 'ACTIVE' : 'PENDING_REVIEW'" :label="$user->hasTwoFactorEnabled() ? 'Enabled' : 'Not set up'" />
            </div>

            @if ($user->hasTwoFactorEnabled())
                <a href="{{ route('two-factor.recovery-codes.show') }}"
                   class="mt-4 inline-block rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    View recovery codes
                </a>
            @else
                <a href="{{ route('two-factor.setup') }}"
                   class="mt-4 inline-block rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                    Set up two-factor authentication
                </a>
            @endif
        </section>

        @if ($company)
            <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Company</h3>
                <dl class="mt-3 space-y-2 text-[13px]">
                    <div class="flex justify-between border-b border-ink-100 pb-2">
                        <dt class="text-ink-500">Name</dt>
                        <dd class="font-medium">{{ $company->name }}</dd>
                    </div>
                    <div class="flex justify-between border-b border-ink-100 pb-2">
                        <dt class="text-ink-500">Country</dt>
                        <dd class="font-medium">{{ config('greengrid.countries')[$company->country] ?? $company->country }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-ink-500">Verification</dt>
                        <dd><x-status-badge :status="$company->kyc_status" /></dd>
                    </div>
                </dl>
            </section>
        @endif
    </div>
</x-layouts.company>
