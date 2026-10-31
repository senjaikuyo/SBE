<?php

namespace App\Http\Controllers;

use App\Models\KycSubmission;
use App\Models\User;
use App\Support\AuditLogger;
use App\Mail\KycApprovedMail;
use App\Mail\KycRejectedMail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AdminKycController extends Controller
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function queue(): View
    {
        return view('admin.kyc.index');
    }

    public function approve(Request $request, KycSubmission $kyc): RedirectResponse
    {
        $this->authorize('manage', User::class);

        if (! $kyc->isPending()) {
            return redirect()->route('admin.kyc.queue')->with('status', 'This submission is no longer pending.');
        }

        $company = $kyc->company;

        $company->update([
            'kyc_status' => 'APPROVED',
            'company_role' => $kyc->requested_role,
        ]);

        $kyc->update([
            'status' => 'APPROVED',
            'decision' => 'APPROVED',
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
        ]);

        $this->audit->record(
            action: 'kyc.approved',
            entity: 'kyc_submission',
            entityId: (string) $kyc->id,
            actor: $request->user(),
        );

        Mail::to($kyc->submittedBy)->queue(new KycApprovedMail($kyc));

        return redirect()->route('admin.kyc.queue')->with('status', 'Company approved. Marketplace access enabled.');
    }

    public function reject(Request $request, KycSubmission $kyc): RedirectResponse
    {
        $this->authorize('manage', User::class);

        if (! $kyc->isPending()) {
            return redirect()->route('admin.kyc.queue')->with('status', 'This submission is no longer pending.');
        }

        $validated = $request->validate([
            'decision_reason' => ['required', 'string', 'max:1000'],
        ]);

        $company = $kyc->company;

        $company->update(['kyc_status' => 'REJECTED']);

        $kyc->update([
            'status' => 'REJECTED',
            'decision' => 'REJECTED',
            'decision_reason' => $validated['decision_reason'],
            'decided_by' => $request->user()->id,
            'decided_at' => now(),
        ]);

        $this->audit->record(
            action: 'kyc.rejected',
            entity: 'kyc_submission',
            entityId: (string) $kyc->id,
            reason: $validated['decision_reason'],
            actor: $request->user(),
        );

        Mail::to($kyc->submittedBy)->queue(new KycRejectedMail($kyc, $validated['decision_reason']));

        return redirect()->route('admin.kyc.queue')->with('status', 'Company rejected. Resubmission opens a new submission.');
    }
}
