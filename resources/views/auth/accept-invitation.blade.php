<x-layouts.public title="Accept invitation">
    <div class="mx-auto max-w-[520px] px-6 py-14">

        <div class="rounded-lg border border-ink-200 bg-white p-6">
            <p class="text-[12px] font-medium uppercase tracking-wider text-brand-700">Invitation</p>
            <h1 class="mt-2 text-[24px] font-semibold leading-8">Join {{ $invitation->company->name }}</h1>
            <p class="mt-1 text-[13px] leading-5 text-ink-600">
                {{ $invitation->inviter->name }} invited <span class="font-medium text-ink-800">{{ $invitation->email }}</span> to join as
                <span class="font-medium text-ink-800">{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</span>.
            </p>

            <dl class="mt-4 space-y-2 rounded-md bg-ink-50 p-3 text-[12px]">
                <div class="flex justify-between">
                    <dt class="text-ink-500">Company</dt>
                    <dd class="font-medium">{{ $invitation->company->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">Role</dt>
                    <dd class="font-medium">{{ ucfirst(str_replace('_', ' ', $invitation->role)) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-ink-500">Expires</dt>
                    <dd class="font-medium">{{ $invitation->expires_at->format('j M Y, H:i') }} UTC</dd>
                </div>
            </dl>

            @if ($errors->any())
                <div class="mt-4 rounded-md border border-danger/30 bg-danger-soft px-4 py-3 text-[13px] text-ink-800" role="alert">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @auth
                @if (strtolower(auth()->user()->email) !== $invitation->email)
                    <div class="mt-5 rounded-md border border-warning/30 bg-warning-soft px-4 py-3 text-[13px] text-ink-800">
                        You are signed in as {{ auth()->user()->email }}, but this invitation was sent to {{ $invitation->email }}.
                    </div>
                    <form method="POST" action="/logout" class="mt-4">
                        @csrf
                        <button type="submit" class="w-full rounded-md border border-ink-300 px-5 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50">
                            Sign out
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('invitations.store', $token) }}" class="mt-6">
                        @csrf
                        <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            Accept invitation
                        </button>
                    </form>
                @endif
            @else
                @if ($existingUser)
                    <p class="mt-5 rounded-md border border-ink-200 bg-ink-50 px-4 py-3 text-[13px] leading-5 text-ink-700">
                        An account already exists for this email. Sign in to accept the invitation.
                    </p>
                    <a href="/login" class="mt-4 block rounded-md bg-brand-500 px-5 py-2.5 text-center text-sm font-medium text-white hover:bg-brand-600">
                        Sign in to accept
                    </a>
                @else
                    <form method="POST" action="{{ route('invitations.store', $token) }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-[13px] font-medium">Full name</label>
                            <input id="name" name="name" type="text" required placeholder="Your full name"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
                        </div>

                        <div>
                            <label for="password" class="block text-[13px] font-medium">Create a password</label>
                            <input id="password" name="password" type="password" required autocomplete="new-password"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500"
                                   aria-describedby="password-hint">
                            <p id="password-hint" class="mt-1 text-[12px] text-ink-500">
                                At least {{ config('greengrid.password_min_length') }} characters. You will set up two-factor authentication next.
                            </p>
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-[13px] font-medium">Confirm password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                                   class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                        </div>

                        <button type="submit" class="w-full rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            Accept invitation
                        </button>
                    </form>
                @endif
            @endauth
        </div>

        <p class="mt-4 text-center text-[12px] text-ink-500">
            Invitations expire {{ config('greengrid.invitation_expiry_days') }} days after they are sent.
        </p>
    </div>
</x-layouts.public>
