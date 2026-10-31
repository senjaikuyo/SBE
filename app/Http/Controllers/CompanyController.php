<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\FormDraft;
use App\Models\Membership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->company() !== null) {
            return redirect()->route('dashboard');
        }

        $draft = FormDraft::query()
            ->where('user_id', $request->user()->id)
            ->where('form_key', 'company.create')
            ->first();

        return view('company.create', [
            'draft' => $draft?->payload ?? [],
            'countries' => config('greengrid.countries'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->company() !== null) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'in:'.implode(',', array_keys(config('greengrid.countries')))],
            'registration_number' => ['nullable', 'string', 'max:100'],
        ]);

        DB::transaction(function () use ($request, $validated): void {
            $company = Company::create([
                'name' => $validated['name'],
                'country' => $validated['country'],
                'registration_number' => $validated['registration_number'] ?? null,
            ]);

            // The first user of a company is its company_admin (FR-01.3).
            Membership::create([
                'user_id' => $request->user()->id,
                'company_id' => $company->id,
                'role' => 'company_admin',
            ]);

            FormDraft::query()
                ->where('user_id', $request->user()->id)
                ->where('form_key', 'company.create')
                ->delete();
        });

        return redirect()->route('two-factor.setup');
    }
}
