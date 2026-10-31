@php
    $owners = [
        ['Budi Santoso', '60%', 'passport-budi.pdf', 'Checked'],
        ['Siti Rahayu', '40%', 'passport-siti.pdf', 'Checked'],
    ];
@endphp

<x-layouts.company title="Beneficial owners">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/kyc/submit" class="hover:text-ink-700">KYC submission</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Beneficial owners</span>
        </nav>

        <h2 class="mt-2 text-[20px] font-semibold">Beneficial owners</h2>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            List every individual who ultimately owns or controls 25% or more of the company.
            Ownership must add up to 100%.
        </p>

        <div class="mt-5 overflow-hidden rounded-lg border border-ink-200 bg-white">
            <table class="w-full text-[13px]">
                <caption class="sr-only">Beneficial owners submitted with the KYC application</caption>
                <thead>
                    <tr class="border-b border-ink-200 bg-ink-100 text-left text-[12px] text-ink-600">
                        <th scope="col" class="px-4 py-2.5 font-medium">Name</th>
                        <th scope="col" class="px-4 py-2.5 font-medium">Ownership</th>
                        <th scope="col" class="px-4 py-2.5 font-medium">Identity document</th>
                        <th scope="col" class="px-4 py-2.5 text-right font-medium">Checked</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($owners as [$name, $share, $file, $state])
                        <tr class="border-b border-ink-200 last:border-b-0">
                            <td class="px-4 py-3 font-medium">{{ $name }}</td>
                            <td class="px-4 py-3 tabular">{{ $share }}</td>
                            <td class="px-4 py-3">
                                <span class="font-mono text-[12px] text-ink-600">{{ $file }}</span>
                            </td>
                            <td class="px-4 py-3 text-right"><x-status-badge status="VALID" :label="$state" /></td>
                        </tr>
                    @endforeach
                    <tr class="bg-ink-50">
                        <td class="px-4 py-2.5 text-[12px] font-medium text-ink-600" colspan="1">Total</td>
                        <td class="px-4 py-2.5 text-[12px] font-medium tabular">100%</td>
                        <td colspan="2"></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <section class="mt-5 rounded-lg border border-ink-200 bg-white p-5">
            <h3 class="text-[15px] font-semibold">Add an owner</h3>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="owner-name" class="block text-[13px] font-medium">Full name</label>
                    <input id="owner-name" type="text" placeholder="As written on their identity document"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm placeholder:text-ink-400 focus:border-brand-500">
                </div>
                <div>
                    <label for="owner-share" class="block text-[13px] font-medium">Ownership (%)</label>
                    <input id="owner-share" type="text" inputmode="decimal" placeholder="0"
                           class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm tabular placeholder:text-ink-400 focus:border-brand-500">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-[13px] font-medium">Identity document</label>
                <div class="mt-1.5 flex flex-wrap items-center justify-between gap-3 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-3">
                    <div>
                        <p class="text-[12px] text-ink-600">Passport or national ID · PDF, JPG or PNG · max 10 MB</p>
                        <p class="text-[12px] text-ink-400">No file selected</p>
                    </div>
                    <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                        Choose file
                    </button>
                </div>
            </div>

            <button type="button" class="mt-4 rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                Add owner
            </button>
        </section>

        <div class="mt-5 flex items-center justify-between pb-4">
            <a href="/company/kyc/submit" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Back to documents</a>
            <a href="/company/kyc/submitted" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                Continue
            </a>
        </div>
    </div>
</x-layouts.company>
