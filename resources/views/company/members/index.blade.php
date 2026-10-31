@php
    $members = [
        ['Aditya Wirawan', 'aditya@nusantarasolar.co.id', 'company_admin', 'Active', 'Now'],
        ['Sari Dewi', 'sari@nusantarasolar.co.id', 'procurement', 'Active', '2 h ago'],
        ['Rudi Hartono', 'rudi@nusantarasolar.co.id', 'finance', 'Active', 'Yesterday'],
        ['Maya Putri', 'maya@nusantarasolar.co.id', 'legal', 'Active', '3 d ago'],
        ['Teguh Prasetyo', 'teguh@nusantarasolar.co.id', 'viewer', 'Invited', '—'],
    ];

    $invites = [
        ['legal@nusantarasolar.co.id', 'legal', '4 Nov 2026, 23:59 (GMT+7)'],
    ];
@endphp

<x-layouts.company title="Company members" kyc="APPROVED">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h2 class="text-[20px] font-semibold">Company members</h2>
            <p class="mt-1 text-[13px] text-ink-600">5 members · 1 pending invitation. Roles decide what each person can do.</p>
        </div>
        <a href="/company/members/invite" class="rounded-md bg-brand-500 px-4 py-2 text-sm font-medium text-white hover:bg-brand-600">
            Invite member
        </a>
    </div>

    <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
        <table class="w-full text-[13px]">
            <caption class="sr-only">Company members and their roles</caption>
            <thead>
                <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                    <th scope="col" class="px-4 py-2.5 font-medium">Name</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Role</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Status</th>
                    <th scope="col" class="px-4 py-2.5 font-medium">Last active</th>
                    <th scope="col" class="px-4 py-2.5 text-right font-medium">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($members as [$name, $email, $role, $status, $last])
                    <tr class="border-b border-ink-200 last:border-b-0 hover:bg-ink-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $name }}</p>
                            <p class="text-[12px] text-ink-500">{{ $email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="rounded-sm bg-ink-100 px-2 py-1 text-[12px] font-medium text-ink-700">{{ str_replace('_', ' ', $role) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($status === 'Active')
                                <x-status-badge status="ACTIVE" label="Active" />
                            @else
                                <x-status-badge status="PENDING_REVIEW" label="Invited" />
                            @endif
                        </td>
                        <td class="px-4 py-3 text-ink-600">{{ $last }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ($role === 'company_admin')
                                <span class="text-[12px] text-ink-400">—</span>
                            @else
                                <button type="button" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                    Manage
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white">
        <div class="flex items-center justify-between border-b border-ink-200 px-5 py-3">
            <h3 class="text-[15px] font-semibold">Pending invitations</h3>
            <span class="text-[12px] text-ink-500">Expire 7 days after sending</span>
        </div>
        <ul class="divide-y divide-ink-200">
            @foreach ($invites as [$email, $role, $expires])
                <li class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 text-[13px]">
                    <div>
                        <p class="font-medium">{{ $email }}</p>
                        <p class="text-[12px] text-ink-500">{{ ucfirst($role) }} · expires {{ $expires }}</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">Resend</button>
                        <button type="button" class="rounded-md border border-ink-300 bg-white px-3 py-1.5 text-[12px] font-medium text-danger hover:bg-danger-soft">Revoke</button>
                    </div>
                </li>
            @endforeach
        </ul>
    </section>

    <section class="mt-6 rounded-lg border border-ink-200 bg-white p-5">
        <h3 class="text-[15px] font-semibold">What each role can do</h3>
        <table class="mt-3 w-full text-[13px]">
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
                @foreach ([
                    ['Manage members', [1, 0, 0, 0, 0, 0]],
                    ['Create or edit listings', [1, 1, 0, 0, 0, 0]],
                    ['Upload KYC documents', [1, 0, 0, 1, 0, 0]],
                    ['View dashboard', [1, 1, 1, 1, 1, 1]],
                ] as [$action, $allowed])
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
    </section>
</x-layouts.company>
