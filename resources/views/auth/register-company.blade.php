@php
    $steps = [
        ['Account', 'Complete'],
        ['Company', 'Current'],
        ['Security', 'Next'],
    ];
@endphp

<x-layouts.public title="Create account">
    <div class="mx-auto max-w-[560px] px-6 py-12">

        <ol class="flex items-center gap-2" aria-label="Registration progress">
            @foreach ($steps as $i => [$label, $state])
                <li class="flex flex-1 items-center gap-2">
                    <span @class([
                        'grid h-7 w-7 shrink-0 place-items-center rounded-full text-[12px] font-semibold',
                        'bg-brand-500 text-white' => $state === 'Complete',
                        'border-2 border-brand-500 text-brand-700' => $state === 'Current',
                        'border border-ink-300 text-ink-400' => $state === 'Next',
                    ])
                          @if ($state === 'Current') aria-current="step" @endif>
                        @if ($state === 'Complete')
                            <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path d="M16.7 5.3a1 1 0 0 1 0 1.4l-7.5 7.5a1 1 0 0 1-1.4 0L3.3 9.7a1 1 0 1 1 1.4-1.4l3.8 3.8 6.8-6.8a1 1 0 0 1 1.4 0Z"/></svg>
                        @else
                            {{ $i + 1 }}
                        @endif
                    </span>
                    <span @class([
                        'text-[13px]',
                        'font-medium text-ink-900' => $state !== 'Next',
                        'text-ink-500' => $state === 'Next',
                    ])>{{ $label }}</span>
                    @if (! $loop->last)
                        <span class="ml-1 hidden h-px flex-1 bg-ink-200 sm:block"></span>
                    @endif
                </li>
            @endforeach
        </ol>

        <h1 class="mt-8 text-[24px] font-semibold leading-8">Company details</h1>
        <p class="mt-1 text-[13px] text-ink-600">
            Step 2 of 3. The first user of a company becomes its company administrator.
        </p>

        <form class="mt-6 space-y-5" novalidate>

            <div>
                <label for="company-name" class="block text-[13px] font-medium">Registered company name</label>
                <input id="company-name" name="company_name" type="text" value="Nusantara Solar Prima"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500 focus:ring-0"
                       aria-describedby="company-name-hint">
                <p id="company-name-hint" class="mt-1 text-[12px] text-ink-500">As written on your company registration document.</p>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <div>
                    <label for="country" class="block text-[13px] font-medium">Country of registration</label>
                    <select id="country" name="country"
                            class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        <option>Indonesia</option>
                        <option>Singapore</option>
                        <option>Malaysia</option>
                        <option>Vietnam</option>
                        <option>Laos</option>
                    </select>
                </div>

                <div>
                    <label for="registration-number" class="block text-[13px] font-medium">Registration number</label>
                    <input id="registration-number" name="registration_number" type="text" value="9120001234567"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                </div>
            </div>

            <div>
                <label for="website" class="block text-[13px] font-medium">Company website <span class="font-normal text-ink-500">(optional)</span></label>
                <input id="website" name="website" type="url" placeholder="https://"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <div class="rounded-md border border-ink-200 bg-ink-50 p-4">
                <p class="text-[13px] font-medium">Documents come next</p>
                <p class="mt-1 text-[12px] leading-5 text-ink-600">
                    After your account is secured with two-factor authentication, you can submit KYC documents at your own pace.
                    Required documents depend on your country of registration.
                </p>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="/register" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Back</a>
                <a href="/two-factor-setup" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Continue to security
                </a>
            </div>
        </form>
    </div>
</x-layouts.public>
