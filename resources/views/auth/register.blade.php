<x-layouts.public title="Create account">
    <div class="mx-auto max-w-[460px] px-6 py-14">

        <p class="text-[12px] font-medium uppercase tracking-wider text-brand-700">Step 1 of 3</p>
        <h1 class="mt-2 text-[24px] font-semibold leading-8">Create your account</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            One account per person. You will add your company next, then set up two-factor authentication.
        </p>

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/register" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-[13px] font-medium">Full name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                       autocomplete="name" placeholder="Your full name"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       autocomplete="email" placeholder="you@company.com"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <div>
                <label for="password" class="block text-[13px] font-medium">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                       aria-describedby="password-hint">
                <p id="password-hint" class="mt-1 text-[12px] text-ink-500">
                    At least {{ config('greengrid.password_min_length') }} characters.
                </p>
            </div>

            <div>
                <label for="password_confirmation" class="block text-[13px] font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
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
