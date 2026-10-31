<x-layouts.public title="Solar energy and carbon credits across APAC">

    {{-- Hero --}}
    <section class="border-b border-ink-200 bg-white">
        <div class="mx-auto max-w-[1120px] px-6 py-16 lg:py-24">
            <div class="max-w-2xl">
                <p class="text-[13px] font-medium uppercase tracking-wider text-brand-700">Verified B2B marketplace</p>
                <h1 class="mt-4 text-[36px] font-semibold leading-[44px] tracking-tight text-ink-900 lg:text-[48px] lg:leading-[56px]">
                    Buy and sell solar energy and verified carbon credits across APAC.
                </h1>
                <p class="mt-5 max-w-xl text-[15px] leading-6 text-ink-600">
                    GreenGrid connects corporate and data centre buyers in Singapore and Malaysia with solar developers in
                    Indonesia, Malaysia, Vietnam and Laos. Every company is verified before it can trade.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="/register" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                        Create account
                    </a>
                    <a href="/login" class="rounded-md border border-ink-300 bg-white px-5 py-2.5 text-sm font-medium text-ink-700 hover:bg-ink-50">
                        Sign in
                    </a>
                </div>

                <p class="mt-4 text-[12px] text-ink-500">
                    Marketplace access requires company verification (KYC) and two-factor authentication.
                </p>
            </div>
        </div>
    </section>

    {{-- How it works --}}
    <section class="mx-auto max-w-[1120px] px-6 py-14">
        <h2 class="text-[20px] font-semibold">How it works</h2>

        <ol class="mt-6 grid gap-4 md:grid-cols-3">
            @foreach ([
                ['1', 'Create an account', 'Register your company and invite your team. Two-factor authentication is required for everyone.'],
                ['2', 'Verify your company', 'Submit company documents, directors and beneficial owners. Our compliance team reviews within 48 hours.'],
                ['3', 'List or browse', 'Verified sellers publish energy or carbon listings. Verified buyers browse active listings.'],
            ] as [$number, $heading, $body])
                <li class="rounded-lg border border-ink-200 bg-white p-5">
                    <span class="grid h-8 w-8 place-items-center rounded-full bg-brand-50 text-[13px] font-semibold text-brand-700">{{ $number }}</span>
                    <h3 class="mt-4 text-[15px] font-semibold">{{ $heading }}</h3>
                    <p class="mt-1.5 text-[13px] leading-5 text-ink-600">{{ $body }}</p>
                </li>
            @endforeach
        </ol>
    </section>

    {{-- Footprint --}}
    <section class="border-t border-ink-200 bg-white">
        <div class="mx-auto grid max-w-[1120px] gap-6 px-6 py-10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ([
                ['4', 'Seller markets', 'Indonesia, Malaysia, Vietnam, Laos'],
                ['2', 'Buyer markets', 'Singapore, Malaysia'],
                ['2', 'Asset types', 'Solar energy, carbon credits'],
                ['USD', 'Settlement currency', 'Single currency in Phase 1'],
            ] as [$stat, $label, $note])
                <div>
                    <p class="text-[24px] font-semibold tabular">{{ $stat }}</p>
                    <p class="mt-1 text-[13px] font-medium text-ink-700">{{ $label }}</p>
                    <p class="text-[12px] text-ink-500">{{ $note }}</p>
                </div>
            @endforeach
        </div>
    </section>

</x-layouts.public>
