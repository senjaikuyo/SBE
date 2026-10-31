@php
    $roleLabels = array_combine($roles, array_map(fn ($r) => ucfirst(str_replace('_', ' ', $r)), $roles));
@endphp

<x-layouts.company title="Company members">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Company members</h2>
            <p class="mt-1 text-[13px] text-ink-600">
                {{ $members->count() }} {{ \Illuminate\Support\Str::plural('member', $members->count()) }} · {{ $invitations->count() }} pending {{ \Illuminate\Support\Str::plural('invitation', $invitations->count()) }}.
                Roles decide what each person can do.
            </p>
        </div>
        @if ($canManage)
            <a href="{{ route('members.invite') }}" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
                Invite member
            </a>
        @endif
    </div>

    <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Company members and their roles</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Name</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Role</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Last active</th>
                    @if ($canManage)
                        <th scope="col" class="px-4 py-2.5 text-right font-medium">Manage</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($members as $member)
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $member->user->name }}</p>
                            <p class="text-[12px] text-ink-500">{{ $member->user->email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if ($canManage)
                                <form method="POST" action="{{ route('members.update', $member) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="sr-only" for="role-{{ $member->id }}">Role for {{ $member->user->name }}</label>
                                    <select id="role-{{ $member->id }}" name="role"
                                            class="rounded-sm border border-ink-300 bg-white px-2 py-1 text-[12px] focus:border-brand-500">
                                        @foreach ($roles as $role)
                                            <option value="{{ $role }}" @selected($member->role === $role)>{{ $roleLabels[$role] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-md border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                        Save
                                    </button>
                                </form>
                            @else
                                <span class="rounded-sm bg-ink-100 px-2 py-1 text-[12px] font-medium text-ink-700">{{ $roleLabels[$member->role] ?? $member->role }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ink-600">
                            {{ $member->user->last_activity_at?->diffForHumans() ?? 'Never' }}
                        </td>
                        @if ($canManage)
                            <td class="px-4 py-3 text-right">
                                <form method="POST" action="{{ route('members.destroy', $member) }}"
                                      onsubmit="return confirm('Remove this member from the company?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-danger hover:bg-danger-soft">
                                        Remove
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white">
        <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
            <h3 class="text-[15px] font-semibold">Pending invitations</h3>
            <span class="text-[12px] text-ink-500">Expire {{ config('greengrid.invitation_expiry_days') }} days after sending</span>
        </div>

        @if ($invitations->isEmpty())
            <p class="px-5 py-4 text-[13px] text-ink-500">No invitations are pending.</p>
        @else
            <ul class="divide-y divide-ink-200">
                @foreach ($invitations as $invitation)
                    <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-[13px]">
                        <div>
                            <p class="font-medium">{{ $invitation->email }}</p>
                            <p class="text-[12px] text-ink-500">
                                {{ $roleLabels[$invitation->role] ?? $invitation->role }} · expires {{ $invitation->expires_at->format('j M Y, H:i') }} UTC
                            </p>
                        </div>
                        <x-status-badge status="PENDING_REVIEW" label="Invited" />
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
        <h3 class="text-[15px] font-semibold">What each role can do</h3>
        <div class="mt-3 overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead>
                    <tr class="border-b border-ink-200 text-left text-[12px] text-ink-500">
                        <th scope="col" class="pb-2 font-medium">Action</th>
                        <th scope="col" class="pb-2 text-center font-medium">Admin</th>
                        <th scope="col" class="pb-2 text-center font-medium">Procurement</th>
                        <th scope="col" class="pb-2 text-center font-medium">Finance</th>
                        <th scope="col" class="pb-2 text-center font-medium">Legal</th>
                        <th scope="col" class="pb-2 text-center font-medium">Sustainability</th>
                        <th scope="col" class="pb-2 text-center font-medium">Viewer</th>
                    </tr>
                </thead>
                <tbody class="text-ink-700">
                    @foreach (['Manage members' => [1, 0, 0, 0, 0, 0], 'Create or edit listings' => [1, 1, 0, 0, 0, 0], 'Upload KYC documents' => [1, 0, 0, 1, 0, 0], 'View dashboard' => [1, 1, 1, 1, 1, 1]] as $action => $allowed)
                        <tr class="border-b border-ink-200 last:border-b-0">
                            <td class="py-2">{{ $action }}</td>
                            @foreach ($allowed as $yes)
                                <td class="py-2 text-center">
                                    @if ($yes)
                                        <span class="text-success" aria-label="Allowed">✓</span>
                                    @else
                                        <span class="text-ink-300" aria-label="Not allowed">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</x-layouts.company>
