<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Listing;
use App\Models\User;
use App\Support\AuditLogger;
use App\Mail\ListingApprovedMail;
use App\Mail\ListingRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminCertificateController extends Controller
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function queue(Request $request): View
    {
        $this->authorize('manage', User::class);

        $certificates = Certificate::query()
            ->with(['listing.company', 'reviewer'])
            ->latest()
            ->get();

        return view('admin.certificates.index', [
            'certificates' => $certificates,
        ]);
    }

    public function show(Certificate $certificate): View
    {
        $this->authorize('manage', User::class);

        return view('admin.certificates.show', [
            'certificate' => $certificate->load(['listing.company', 'reviewer']),
        ]);
    }

    public function approve(Request $request, Certificate $certificate): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $certificate->update([
            'status' => 'VALID',
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'note' => $request->input('note'),
        ]);

        $certificate->listing->update([
            'status' => 'ACTIVE',
        ]);

        $this->audit->record(
            action: 'certificate.approved',
            entity: 'certificate',
            entityId: (string) $certificate->id,
            reason: $request->input('note'),
            actor: $request->user(),
        );

        $this->audit->record(
            action: 'listing.activated',
            entity: 'listing',
            entityId: (string) $certificate->listing_id,
            actor: $request->user(),
        );

        return redirect()->route('admin.certificates.queue')
            ->with('status', 'Certificate verified and listing activated.');
    }

    public function reject(Request $request, Certificate $certificate): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $validated = $request->validate([
            'reason' => ['required_without:rejection_reason', 'nullable', 'string', 'min:3'],
            'rejection_reason' => ['required_without:reason', 'nullable', 'string', 'min:3'],
        ]);

        $reason = $validated['reason'] ?? $validated['rejection_reason'];

        if (empty(trim((string) $reason))) {
            throw ValidationException::withMessages([
                'reason' => 'Alasan penolakan wajib diisi.',
            ]);
        }

        $certificate->update([
            'status' => 'REJECTED',
            'rejection_reason' => $reason,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
        ]);

        $certificate->listing->update([
            'status' => 'REJECTED',
        ]);

        $this->audit->record(
            action: 'certificate.rejected',
            entity: 'certificate',
            entityId: (string) $certificate->id,
            reason: $reason,
            actor: $request->user(),
        );

        $this->audit->record(
            action: 'listing.rejected',
            entity: 'listing',
            entityId: (string) $certificate->listing_id,
            reason: $reason,
            actor: $request->user(),
        );

        return redirect()->route('admin.certificates.queue')
            ->with('status', 'Certificate rejected and reason recorded.');
    }

    public function decision(Request $request, Certificate $certificate): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $decision = strtolower((string) $request->input('decision', ''));

        if ($decision === 'valid' || $decision === 'approve' || $decision === 'approved') {
            return $this->approve($request, $certificate);
        }

        if ($decision === 'rejected' || $decision === 'reject') {
            if (! $request->filled('reason') && ! $request->filled('rejection_reason') && $request->filled('note')) {
                $request->merge(['reason' => $request->input('note')]);
            }

            return $this->reject($request, $certificate);
        }

        $request->validate([
            'decision' => ['required', Rule::in(['valid', 'rejected', 'approve', 'reject'])],
        ]);

        return redirect()->route('admin.certificates.queue');
    }

    public function approveListing(Request $request, Listing $listing): RedirectResponse
    {
        $this->authorize('manage', User::class);

        if (! $listing->canBeActive()) {
            throw ValidationException::withMessages([
                'certificate' => 'Listing tidak dapat ACTIVE sebelum nomor seri sertifikat diverifikasi admin.',
            ]);
        }

        $listing->activate();

        $this->audit->record(
            action: 'listing.activated',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
        );

        try {
            foreach ($listing->company->users as $u) {
                Mail::to($u)->queue(new ListingApprovedMail($listing));
            }
        } catch (\Throwable $e) {
            // Email service failure does not fail main action
        }

        return back()->with('status', 'Listing activated.');
    }

    public function rejectListing(Request $request, Listing $listing): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:3'],
        ]);

        $listing->update(['status' => 'REJECTED']);

        $this->audit->record(
            action: 'listing.rejected',
            entity: 'listing',
            entityId: (string) $listing->id,
            actor: $request->user(),
            reason: $validated['reason'],
        );

        try {
            foreach ($listing->company->users as $u) {
                Mail::to($u)->queue(new ListingRejectedMail($listing, $validated['reason']));
            }
        } catch (\Throwable $e) {
            // Email service failure does not fail main action
        }

        return back()->with('status', 'Listing rejected.');
    }
}
