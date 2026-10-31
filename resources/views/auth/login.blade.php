<x-layouts.public title="Sign in">
    <div class="mx-auto max-w-[420px] px-6 py-16">

        <h1 class="text-[24px] font-semibold leading-8">Sign in</h1>
        <p class="mt-1 text-[13px] text-ink-600">Use your work email. Marketplace access needs two-factor authentication.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="/login" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       autocomplete="email" placeholder="you@company.com"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
            </div>

            <div>
                <div class="flex items-baseline justify-between">
                    <label for="password" class="block text-[13px] font-medium">Password</label>
                    <a href="/forgot-password" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">Forgot password?</a>
                </div>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
            </div>

            <label class="flex items-center gap-2.5 text-[13px] text-ink-700">
                <input type="checkbox" name="remember" value="1"
                       class="h-4 w-4 rounded-sm border-ink-300 text-brand-500 focus:ring-2 focus:ring-brand-500">
                Keep me signed in on this device
            </label>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Sign in
            </button>
        </form>

        <p class="mt-4 text-[12px] text-ink-500">
            Sessions end after 30 minutes of inactivity. Drafts are saved automatically.
        </p>

        <p class="mt-8 border-t border-ink-200 pt-5 text-center text-[13px] text-ink-600">
            New to GreenGrid?
            <a href="/register" class="font-medium text-brand-700 underline underline-offset-2">Create an account</a>
        </p>
    </div>
</x-layouts.public>
