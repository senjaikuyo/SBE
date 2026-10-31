<x-layouts.public title="Set a new password">
    <div class="mx-auto max-w-[420px] px-6 py-16">

        <h1 class="text-[24px] font-semibold leading-8">Set a new password</h1>
        <p class="mt-1 text-[13px] text-ink-600">Choose a password of at least {{ config('greengrid.password_min_length') }} characters.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                <ul class="list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="/reset-password" class="mt-6 space-y-5">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required readonly
                       class="mt-1.5 w-full rounded-sm border border-ink-200 bg-ink-50 px-3 py-2 text-sm text-ink-600">
            </div>

            <div>
                <label for="password" class="block text-[13px] font-medium">New password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
            </div>

            <div>
                <label for="password_confirmation" class="block text-[13px] font-medium">Confirm new password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
            </div>

            <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Update password
            </button>
        </form>
    </div>
</x-layouts.public>
