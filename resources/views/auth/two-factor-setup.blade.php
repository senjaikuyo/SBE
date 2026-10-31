@php
    $codes = ['8F2K-QW7L', 'M4RT-9XZB', 'LC6P-2HVN', 'ZQ1D-5KRS', 'BN8T-4WYG', 'YH3M-7PXC', 'D5LV-6QFA', 'U9JK-0RTE'];
@endphp

<x-layouts.public title="Set up two-factor authentication">
    <div class="mx-auto max-w-[640px] px-6 py-12">

        <div class="rounded-md border border-warning/30 bg-warning-soft px-4 py-3">
            <p class="text-[13px] font-medium text-warning">Security step required</p>
            <p class="mt-0.5 text-[12px] leading-5 text-ink-700">
                Two-factor authentication is required for every account before you can access the marketplace.
            </p>
        </div>

        <h1 class="mt-6 text-[24px] font-semibold leading-8">Set up two-factor authentication</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Scan the QR code with an authenticator app such as Google Authenticator, 1Password or Authy.
        </p>

        <div class="mt-6 grid gap-6 sm:grid-cols-[200px_1fr]">

            <div class="grid h-[200px] w-[200px] place-items-center rounded-lg border border-ink-200 bg-white">
                <div class="grid grid-cols-8 gap-0.5" aria-hidden="true">
                    @for ($i = 0; $i < 64; $i++)
                        <span @class(['h-4 w-4', 'bg-ink-900' => ($i * 7) % 3 !== 0, 'bg-white' => ($i * 7) % 3 === 0])></span>
                    @endfor
                </div>
                <span class="sr-only">QR code placeholder for authenticator app</span>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-[13px] font-medium">Or enter this key manually</p>
                    <p class="mt-1.5 rounded-sm border border-ink-200 bg-ink-50 px-3 py-2 font-mono text-[13px] tracking-wider">
                        GRDG 4K2M 9XQ7 LBN3
                    </p>
                </div>

                <div>
                    <label for="code" class="block text-[13px] font-medium">6-digit code from your app</label>
                    <input id="code" name="code" inputmode="numeric" maxlength="6" placeholder="000000"
                           class="mt-1.5 w-[160px] rounded-sm border border-ink-300 bg-white px-3 py-2 font-mono text-sm tracking-[0.3em] placeholder:text-ink-300 focus:border-brand-500">
                </div>

                <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Confirm and continue
                </button>
            </div>
        </div>

        <section class="mt-8 rounded-lg border border-ink-200 bg-white p-5">
            <h2 class="text-[15px] font-semibold">Recovery codes</h2>
            <p class="mt-1 text-[12px] leading-5 text-ink-600">
                Store these somewhere safe. Each code can be used once if you lose access to your authenticator app.
            </p>
            <ul class="mt-3 grid grid-cols-2 gap-1.5 font-mono text-[12px] text-ink-700 sm:grid-cols-4">
                @foreach ($codes as $code)
                    <li class="rounded-sm bg-ink-50 px-2 py-1.5 text-center">{{ $code }}</li>
                @endforeach
            </ul>
        </section>

        <p class="mt-6 text-[12px] text-ink-500">
            You can change this later under Profile → Two-factor authentication.
        </p>
    </div>
</x-layouts.public>
