@php
    $countryDocs = [
        ['NIB — Business identification number', 'PDF or JPG, max 10 MB', true],
        ['NPWP — Tax identification number', 'PDF or JPG, max 10 MB', true],
        ['Articles of association and approval', 'PDF, max 10 MB', false],
    ];

    $sharedDocs = [
        ['Director identity document', 'Passport or national ID'],
        ['Proof of company bank account', 'Statement header only — account numbers are not stored'],
    ];
@endphp

<x-layouts.company title="KYC verification">
    <div class="max-w-[760px]">

        <nav class="text-[12px] text-ink-500" aria-label="Breadcrumb">
            <a href="/company/kyc" class="hover:text-ink-700">KYC verification</a>
            <span aria-hidden="true"> / </span>
            <span class="text-ink-700">Submit documents</span>
        </nav>

        <h2 class="mt-2 text-[20px] font-semibold">Submit company documents</h2>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            Indonesia · documents listed below are required. Files are stored privately and reviewed by our compliance team.
        </p>

        <div class="mt-4 flex items-center justify-between rounded-md border border-ink-200 bg-white px-4 py-2.5">
            <p class="text-[12px] text-ink-500">Draft saved · 12:04 (Asia/Jakarta)</p>
            <button type="button" class="text-[12px] font-medium text-brand-700 underline underline-offset-2">Save draft</button>
        </div>

        <form class="mt-6 space-y-6" novalidate>

            {{-- Requested role --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Marketplace role</h3>
                <p class="mt-1 text-[12px] leading-5 text-ink-500">
                    Tell us how you intend to use GreenGrid. Your role is assigned by our team after review — you cannot change it yourself.
                </p>

                <fieldset class="mt-4 grid gap-3 sm:grid-cols-2">
                    <legend class="sr-only">Requested marketplace role</legend>

                    <label class="flex cursor-pointer items-start gap-3 rounded-md border border-ink-300 p-4 hover:bg-ink-50">
                        <input type="radio" name="requested_role" value="buyer" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500" checked>
                        <span>
                            <span class="block text-[13px] font-medium">Buyer</span>
                            <span class="mt-0.5 block text-[12px] leading-5 text-ink-600">I want to purchase energy or carbon credits.</span>
                        </span>
                    </label>

                    <label class="flex cursor-pointer items-start gap-3 rounded-md border border-ink-300 p-4 hover:bg-ink-50">
                        <input type="radio" name="requested_role" value="seller" class="mt-0.5 h-4 w-4 text-brand-500 focus:ring-2 focus:ring-brand-500">
                        <span>
                            <span class="block text-[13px] font-medium">Seller</span>
                            <span class="mt-0.5 block text-[12px] leading-5 text-ink-600">I want to list solar projects or carbon credits.</span>
                        </span>
                    </label>
                </fieldset>
            </section>

            {{-- Company documents --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Company documents</h3>

                <ul class="mt-4 divide-y divide-ink-200">
                    @foreach ($countryDocs as [$docName, $types, $needsExpiry])
                        <li class="py-4 first:pt-0 last:pb-0">
                            <div class="flex flex-wrap items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="text-[13px] font-medium">{{ $docName }}</p>
                                    <p class="mt-0.5 text-[12px] text-ink-500">{{ $types }} · {{ $needsExpiry ? 'Expiry date required' : 'No expiry' }}</p>
                                </div>
                                <span class="rounded-sm bg-muted-soft px-2 py-1 text-[11px] font-medium text-ink-600">Required</span>
                            </div>

                            <div @class(['mt-3 grid gap-3', 'sm:grid-cols-[1fr_170px]' => $needsExpiry])>
                                <div class="flex items-center justify-between gap-3 rounded-sm border border-dashed border-ink-300 bg-ink-50 px-3 py-2.5">
                                    <span class="text-[12px] text-ink-500">No file selected</span>
                                    <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                        Choose file
                                    </button>
                                </div>
                                @if ($needsExpiry)
                                    <div>
                                        <label for="expiry-{{ $loop->index }}" class="sr-only">Expiry date</label>
                                        <input id="expiry-{{ $loop->index }}" type="date"
                                               class="w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-[13px] text-ink-600 focus:border-brand-500">
                                    </div>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            </section>

            {{-- Directors and signatory --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <h3 class="text-[15px] font-semibold">Directors and authorised signatory</h3>

                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="director-name" class="block text-[13px] font-medium">Director full name</label>
                        <input id="director-name" type="text" value="Budi Santoso"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                    <div>
                        <label for="director-id" class="block text-[13px] font-medium">Identity number</label>
                        <input id="director-id" type="text" value="317409••••••••"
                               class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    </div>
                </div>

                <ul class="mt-4 divide-y divide-ink-200">
                    @foreach ($sharedDocs as [$docName, $note])
                        <li class="flex flex-wrap items-center justify-between gap-3 py-3 first:pt-3">
                            <div>
                                <p class="text-[13px] font-medium">{{ $docName }}</p>
                                <p class="mt-0.5 text-[12px] text-ink-500">{{ $note }}</p>
                            </div>
                            <button type="button" class="rounded-sm border border-ink-300 bg-white px-2.5 py-1 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                                Choose file
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    <label class="flex items-start gap-2.5 text-[13px] leading-5">
                        <input type="checkbox" class="mt-0.5 h-4 w-4 rounded-sm border-ink-300 text-brand-500 focus:ring-2 focus:ring-brand-500">
                        <span>
                            The signatory is authorised by the company to sign contracts on its behalf.
                            <span class="block text-[12px] text-ink-500">A letter of appointment must be uploaded.</span>
                        </span>
                    </label>
                </div>
            </section>

            {{-- UBO --}}
            <section class="rounded-lg border border-ink-200 bg-white p-5">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-[15px] font-semibold">Beneficial owners</h3>
                        <p class="mt-1 text-[12px] leading-5 text-ink-500">
                            Every individual who ultimately owns or controls 25% or more of the company.
                        </p>
                    </div>
                    <button type="button" class="rounded-md border border-ink-300 px-3 py-1.5 text-[12px] font-medium text-ink-700 hover:bg-ink-50">
                        Add owner
                    </button>
                </div>

                <table class="mt-4 w-full text-[13px]">
                    <thead>
                        <tr class="border-b border-ink-200 text-left text-[12px] text-ink-500">
                            <th scope="col" class="pb-2 font-medium">Name</th>
                            <th scope="col" class="pb-2 font-medium">Ownership</th>
                            <th scope="col" class="pb-2 font-medium">Document</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-ink-200">
                            <td class="py-2.5">Budi Santoso</td>
                            <td class="py-2.5 tabular">60%</td>
                            <td class="py-2.5 text-ink-500">Not uploaded</td>
                        </tr>
                        <tr>
                            <td class="py-2.5">Siti Rahayu</td>
                            <td class="py-2.5 tabular">40%</td>
                            <td class="py-2.5 text-ink-500">Not uploaded</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <a href="/company/kyc" class="rounded-md px-3 py-2 text-sm font-medium text-ink-600 hover:bg-ink-100">Cancel</a>
                <div class="flex gap-2">
                    <button type="button" class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                        Save draft
                    </button>
                    <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Submit for review
                    </button>
                </div>
            </div>
        </form>
    </div>
</x-layouts.company>
