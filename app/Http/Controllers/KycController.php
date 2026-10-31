<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\KycDocument;
use App\Models\KycSubmission;
use App\Models\User;
use App\Support\AuditLogger;
use App\Support\SanctionsScreener;
use App\Support\VirusScanner;
use App\Mail\KycSubmittedMail;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class KycController extends Controller
{
    public function __construct(private AuditLogger $audit, private SanctionsScreener $screener)
    {
    }

    public function index(Request $request): View
    {
        $company = $request->user()->company();
        $this->authorize('viewKyc', $company);

        $submission = KycSubmission::query()
            ->where('company_id', $company->id)
            ->latest('id')
            ->first();

        return view('company.kyc.status', [
            'company' => $company,
            'submission' => $submission,
            'status' => $company->kyc_status,
        ]);
    }

    public function form(Request $request): View
    {
        $company = $request->user()->company();
        $this->authorize('viewKyc', $company);

        return view('company.kyc.submit', [
            'company' => $company,
            'countryDocs' => config('greengrid.kyc_documents')[$company->country] ?? [],
            'sharedDocs' => config('greengrid.kyc_shared_documents') ?? [],
        ]);
    }

    public function submit(Request $request): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('submitKyc', $company);

        $this->assertSubmittable($company);

        $validated = $request->validate([
            'requested_role' => ['required', Rule::in(['buyer', 'seller'])],
            'documents' => ['required', 'array', 'min:1'],
            'beneficial_owners' => ['nullable'],
        ]);

        $countryDocs = config('greengrid.kyc_documents')[$company->country] ?? [];
        $required = collect($countryDocs)->pluck('name')->all();
        foreach (config('greengrid.kyc_shared_documents') as $shared) {
            $required[] = $shared['name'];
        }

        foreach ($required as $type) {
            if (empty($request->input('documents.'.$type))) {
                throw ValidationException::withMessages([
                    'documents' => 'Missing required document: '.$type.'.',
                ]);
            }
        }

        $today = now()->toDateString();
        $expiredReasons = [];

        foreach ($countryDocs as $doc) {
            if (! ($doc['needs_expiry'] ?? false)) {
                continue;
            }

            if (! array_key_exists('types', $doc) || ! array_key_exists('needs_expiry', $doc) || ! array_key_exists('name', $doc)) {
                throw ValidationException::withMessages([
                    'documents' => 'Invalid document configuration for '.$company->country.'.',
                ]);
            }

            $expiry = $request->input('document_expiries.'.$doc['name']);

            if (! $expiry) {
                throw ValidationException::withMessages([
                    'document_expiries.'.$doc['name'] => 'Document '.$doc['types'].' requires an expiry date.',
                ]);
            }

            if (Carbon::parse($expiry)->lt(today())) {
                $expiredReasons[] = 'Document '.$doc['types'].' expired on '.$expiry.'.';
            }
        }

        if ($expiredReasons !== []) {
            $submission = KycSubmission::create([
                'company_id' => $company->id,
                'country' => $company->country,
                'requested_role' => $validated['requested_role'],
                'documents' => $request->input('documents'),
                'beneficial_owners' => $this->beneficialOwners($request),
                'status' => 'REJECTED',
                'decision' => 'REJECTED',
                'decision_reason' => implode(' ', $expiredReasons),
                'submitted_by' => $request->user()->id,
                'submitted_at' => now(),
                'decided_at' => now(),
            ]);

            $company->update(['kyc_status' => 'REJECTED']);

            $this->audit->record(
                action: 'kyc.rejected',
                entity: 'kyc_submission',
                entityId: (string) $submission->id,
                reason: implode(' ', $expiredReasons),
                actor: $request->user(),
            );

            return redirect()->route('kyc.index')->withErrors(['documents' => implode(' ', $expiredReasons)]);
        }

        $submission = KycSubmission::create([
            'company_id' => $company->id,
            'country' => $company->country,
            'requested_role' => $validated['requested_role'],
            'documents' => $request->input('documents'),
            'beneficial_owners' => $this->beneficialOwners($request),
            'status' => 'PENDING_VERIFICATION',
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        $company->update(['kyc_status' => 'PENDING_VERIFICATION']);

        $submission->forceFill([
            'screened_at' => now(),
            'screening' => $this->screener->screen($company, $company->country),
        ])->save();

        Mail::to($request->user())->queue(new KycSubmittedMail($submission));

        $this->audit->record(
            action: 'kyc.submitted',
            entity: 'kyc_submission',
            entityId: (string) $submission->id,
            actor: $request->user(),
        );

        return redirect()->route('kyc.index')->with('status', 'Verification submitted. Decision usually takes 48 hours.');
    }

    public function resubmit(Request $request): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('submitKyc', $company);

        $previous = KycSubmission::query()
            ->where('company_id', $company->id)
            ->latest('id')
            ->first();

        if (! $previous || $previous->status !== 'REJECTED') {
            return redirect()->route('kyc.index')->withErrors([
                'kyc' => 'Resubmission is only allowed after a rejection. Current status: '.($previous?->status ?? 'none').'.',
            ]);
        }

        $this->assertSubmittable($company);

        $validated = $request->validate([
            'requested_role' => ['required', Rule::in(['buyer', 'seller'])],
            'documents' => ['required', 'array', 'min:1'],
            'beneficial_owners' => ['nullable'],
        ]);

        $submission = KycSubmission::create([
            'company_id' => $company->id,
            'country' => $company->country,
            'requested_role' => $validated['requested_role'],
            'documents' => $request->input('documents'),
            'beneficial_owners' => $this->beneficialOwners($request),
            'status' => 'PENDING_VERIFICATION',
            'submitted_by' => $request->user()->id,
            'submitted_at' => now(),
        ]);

        $company->update(['kyc_status' => 'PENDING_VERIFICATION']);

        $this->audit->record(
            action: 'kyc.submitted',
            entity: 'kyc_submission',
            entityId: (string) $submission->id,
            actor: $request->user(),
        );

        return redirect()->route('kyc.index')->with('status', 'Verification resubmitted. Decision usually takes 48 hours.');
    }

    public function storeDocument(Request $request): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('submitKyc', $company);

        $validated = $request->validate([
            'type' => ['required', 'string', 'max:255'],
            'expiry' => ['nullable', 'date'],
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $file = $request->file('document');
        $allowed = ['application/pdf', 'image/jpeg', 'image/png'];

        if (! in_array($file->getMimeType(), $allowed, true)) {
            return back()->withErrors(['document' => 'Unsupported file type. Use PDF, JPG or PNG.']);
        }

        if ($file->getSize() > 10 * 1024 * 1024) {
            return back()->withErrors(['document' => 'File exceeds the 10 MB limit.']);
        }

        $virus = app(VirusScanner::class)->scan($file->getRealPath() ?: '');
        $clean = (bool) ($virus['clean'] ?? false);

        $submission = KycSubmission::query()
            ->where('company_id', $company->id)
            ->latest('id')
            ->first();

        if ($submission === null) {
            return back()->withErrors(['document' => 'No KYC submission is open for this company.']);
        }

        $kycDocument = KycDocument::create([
            'company_id' => $company->id,
            'submission_id' => $submission->id,
            'type' => $validated['type'],
            'file_path' => Storage::disk(config('greengrid.files.disk', 'local'))->putFileAs('kyc', $file, $file->getClientOriginalName()),
            'expires_at' => $validated['expiry'] ? now()->parse($validated['expiry']) : null,
            'status' => $clean ? 'UPLOADED' : 'QUARANTINED',
        ]);

        if (! $clean) {
            return back()->withErrors(['document' => 'The file failed content-based virus scanning.']);
        }

        return redirect()->route('kyc.index')->with('status', 'Document uploaded.');
    }

    public function download(Request $request, KycDocument $document): Response
    {
        $this->authorize('downloadKycDocument', $document);

        $kycDocument = $document->fresh();

        if ($kycDocument->expires_at !== null && $kycDocument->expires_at->isPast()) {
            abort(410, 'This document has expired.');
        }

        return Storage::disk(config('greengrid.files.disk', 'local'))->download($kycDocument->file_path, $kycDocument->type);
    }

    private function beneficialOwners(Request $request): array
    {
        $value = $request->input('beneficial_owners');

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? $decoded : [];
        }

        return is_array($value) ? $value : [];
    }

    private function assertSubmittable(Company $company): void
    {
        $status = $company->kyc_status;

        if ($status === 'PENDING_VERIFICATION' || $status === 'APPROVED') {
            throw ValidationException::withMessages([
                'kyc' => 'Submission is not accepted in status '.$status.'.',
            ]);
        }
    }
}
