@php
    $steps = [
        ['Account', 'Current'],
        ['Company', 'Next'],
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
                        'border-2 border-brand-500 text-brand-700' => $state === 'Current',
                        'border border-ink-300 text-ink-400' => $state === 'Next',
                    ])
                          @if ($state === 'Current') aria-current="step" @endif>{{ $i + 1 }}</span>
                    <span @class([
                        'text-[13px]',
                        'font-medium text-ink-900' => $state === 'Current',
                        'text-ink-500' => $state === 'Next',
                    ])>{{ $label }}</span>
                    @if (! $loop->last)
                        <span class="ml-1 hidden h-px flex-1 bg-ink-200 sm:block"></span>
                    @endif
                </li>
            @endforeach
        </ol>

        <h1 class="mt-8 text-[24px] font-semibold leading-8">Create your account</h1>
        <p class="mt-1 text-[13px] text-ink-600">Step 1 of 3. You will add your company details next.</p>

        <form class="mt-6 space-y-5" novalidate>

            <div>
                <label for="name" class="block text-[13px] font-medium">Full name</label>
                <input id="name" name="name" type="text" autocomplete="name" placeholder="Aditya Wirawan"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" name="email" type="email" autocomplete="email" placeholder="you@company.com"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500"
                       aria-describedby="email-hint">
                <p id="email-hint" class="mt-1 text-[12px] text-ink-500">One account per email address. Team members join by invitation.</p>
            </div>

            <div>
                <label for="password" class="block text-[13px] font-medium">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                       aria-describedby="password-hint">
                <p id="password-hint" class="mt-1 text-[12px] text-ink-500">At least 12 characters.</p>
            </div>

            <div class="flex items-start gap-2.5">
                <input id="terms" name="terms" type="checkbox"
                       class="mt-0.5 h-4 w-4 rounded-sm border-ink-300 text-brand-500 focus:ring-2 focus:ring-brand-500">
                <label for="terms" class="text-[13px] leading-5 text-ink-700">
                    I agree to the <a href="/terms" class="font-medium text-brand-700 underline underline-offset-2">Terms of Service</a>
                    and <a href="/privacy" class="font-medium text-brand-700 underline underline-offset-2">Privacy Policy</a>.
                </label>
            </div>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Continue
            </button>
        </form>

        <p class="mt-6 text-center text-[13px] text-ink-600">
            Already have an account?
            <a href="/login" class="font-medium text-brand-700 underline underline-offset-2">Sign in</a>
        </p>
    </div>
</x-layouts.public>
