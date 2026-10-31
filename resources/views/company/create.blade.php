<x-layouts.public title="Create company">
    <div class="mx-auto max-w-[520px] px-6 py-14">

        <p class="text-[12px] font-medium uppercase tracking-wider text-brand-700">Step 2 of 3</p>
        <h1 class="mt-2 text-[24px] font-semibold leading-8">Add your company</h1>
        <p class="mt-1 text-[13px] leading-5 text-ink-600">
            You become the company administrator. Verification documents are requested later, after two-factor setup.
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

        <form method="POST" action="/company/create" class="mt-6 space-y-5" id="company-form">
            @csrf

            <div>
                <label for="name" class="block text-[13px] font-medium">Registered company name</label>
                <input id="name" name="name" type="text" required
                       value="{{ old('name', $draft['name'] ?? '') }}"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
            </div>

            <div>
                <label for="country" class="block text-[13px] font-medium">Country of registration</label>
                <select id="country" name="country" required
                        class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                    <option value="">Select a country</option>
                    @foreach ($countries as $code => $label)
                        <option value="{{ $code }}" @selected(old('country', $draft['country'] ?? '') === $code)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="registration_number" class="block text-[13px] font-medium">
                    Registration number <span class="font-normal text-ink-500">(optional)</span>
                </label>
                <input id="registration_number" name="registration_number" type="text"
                       value="{{ old('registration_number', $draft['registration_number'] ?? '') }}"
                       class="mt-1.5 w-full rounded-sm border border-ink-300 bg-white px-3 py-2 text-sm focus:border-brand-500">
                <p class="mt-1 text-[12px] text-ink-500">You can add this during KYC verification if you do not have it yet.</p>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <button type="button" id="save-draft"
                        class="rounded-md border border-ink-300 bg-white px-4 py-2 text-sm font-medium text-ink-700 hover:bg-ink-50">
                    Save draft
                </button>
                <button type="submit" class="rounded-md bg-brand-500 px-5 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                    Continue to two-factor setup
                </button>
            </div>

            <p id="draft-status" class="text-[12px] text-ink-500" role="status" aria-live="polite"></p>
        </form>
    </div>

    <script>
        (() => {
            const form = document.getElementById('company-form');
            const status = document.getElementById('draft-status');
            let timer;

            async function saveDraft() {
                const payload = Object.fromEntries(new FormData(form).entries());
                delete payload._token;

                const response = await fetch('/drafts/company.create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ payload }),
                });

                status.textContent = response.ok
                    ? 'Draft saved. It is restored the next time you open this page.'
                    : 'Draft could not be saved. Try again.';
            }

            function scheduleSave() {
                clearTimeout(timer);
                timer = setTimeout(saveDraft, 800);
            }

            document.getElementById('save-draft')?.addEventListener('click', saveDraft);
            form.querySelectorAll('input, select').forEach((field) => {
                field.addEventListener('input', scheduleSave);
                field.addEventListener('change', scheduleSave);
            });
        })();
    </script>
</x-layouts.public>
