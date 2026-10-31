@php
    $roleLabels = array_combine($roles, array_map(fn ($r) => str_replace('_', ' ', $r), $roles));
@endphp

<x-layouts.company title="Invite member">
    <div class="max-w-[560px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="{{ route('members.index') }}" class="hover:text-ink-700">Company members</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Invite</span>
        </nav>

        <h2 class="mt-2 text-[20px] font-semibold">Invite a team member</h2>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            They receive an email invitation valid for {{ config('greengrid.invitation_expiry_days') }} days.
            New users set a password and two-factor authentication before entering.
        </p>

        <form method="POST" action="{{ route('members.store') }}" class="mt-6 space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-[13px] font-medium">Work email</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="name@company.com"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
                <p class="mt-1 text-[12px] text-ink-500">One person per email. Existing accounts can accept too.</p>
            </div>

            <div>
                <label for="role" class="block text-[13px] font-medium">Role</label>
                <select id="role" name="role" required
                        class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ $roleLabels[$role] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between pt-1">
                <a href="{{ route('members.index') }}" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
                <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Send invitation
                </button>
            </div>
        </form>

        <div class="mt-6 rounded-lg border border-ink-200 bg-white p-4">
            <p class="text-[12px] leading-5 text-ink-600">
                Inviting, changing a role and removing a member are all recorded in the audit log.
            </p>
        </div>
    </div>
</x-layouts.company>
