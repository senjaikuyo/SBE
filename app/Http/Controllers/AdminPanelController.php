<?php

namespace App\Http\Controllers;

use App\Models\ApprovalRequest;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\KycSubmission;
use App\Models\Listing;
use App\Models\Setting;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminPanelController extends Controller
{
    public function __construct(private AuditLogger $audit)
    {
    }

    public function overview(): View
    {
        $this->authorize('manage', User::class);

        $kycSubmissions = KycSubmission::query()
            ->with('company')
            ->where('status', 'PENDING_VERIFICATION')
            ->latest('submitted_at')
            ->get();

        return view('admin.overview', [
            'kycPendingCount' => $kycSubmissions->count(),
            'listingsWaitingCount' => Listing::query()->where('status', 'PENDING_REVIEW')->count(),
            'certificatesWaitingCount' => Certificate::query()->where('status', 'PENDING_REVIEW')->count(),
            'approvalsWaitingCount' => ApprovalRequest::query()->where('status', 'PENDING_REVIEW')->count(),
            'submissions' => $kycSubmissions->take(4),
        ]);
    }

    public function kycQueue(): View
    {
        $this->authorize('manage', User::class);

        $submissions = KycSubmission::query()
            ->with('company')
            ->where('status', 'PENDING_VERIFICATION')
            ->latest('submitted_at')
            ->get();

        return view('admin.kyc.index', [
            'submissions' => $submissions,
        ]);
    }

    public function listingsQueue(): View
    {
        $this->authorize('manage', User::class);

        $listings = Listing::query()
            ->with(['company', 'certificates'])
            ->latest()
            ->get();

        return view('admin.listings.index', [
            'listings' => $listings,
        ]);
    }

    public function settings(): View
    {
        $this->authorize('manage', User::class);

        return view('admin.settings', [
            'feePercentage' => Setting::get('marketplace_fee_percentage'),
        ]);
    }

    public function approvals(): View
    {
        $this->authorize('manage', User::class);

        $requests = ApprovalRequest::query()
            ->with(['proposedBy'])
            ->latest()
            ->get();

        return view('admin.approvals.index', [
            'requests' => $requests,
        ]);
    }

    public function propose(Request $request): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $validated = $request->validate([
            'action' => ['required', 'string'],
            'reason' => ['required', 'string', 'min:3'],
            'payload' => ['nullable', 'array'],
            'entity_type' => ['nullable', 'string'],
            'entity_id' => ['nullable', 'integer'],
        ]);

        $approval = ApprovalRequest::create([
            'action' => $validated['action'],
            'reason' => $validated['reason'],
            'payload' => $validated['payload'] ?? null,
            'entity_type' => $validated['entity_type'] ?? null,
            'entity_id' => $validated['entity_id'] ?? null,
            'status' => 'PENDING_REVIEW',
            'proposed_by_id' => $request->user()->id,
        ]);

        $this->audit->record(
            action: 'admin.approval_proposed',
            entity: 'approval_request',
            entityId: (string) $approval->id,
            actor: $request->user(),
            reason: $validated['reason'],
        );

        return redirect()->route('admin.approvals.index')->with('status', 'Approval request proposed.');
    }

    public function approve(Request $request, ApprovalRequest $approval): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:3'],
        ]);

        // Four-eyes rule: Cannot approve own proposal
        if ($approval->proposed_by_id === $request->user()->id) {
            abort(403, 'A different admin must approve this request.');
        }

        // Conflict of interest rule: Admin cannot process company where they have an interest
        if ($this->hasConflictOfInterest($request->user(), $approval)) {
            $this->audit->record(
                action: 'admin.conflict_of_interest',
                entity: 'approval_request',
                entityId: (string) $approval->id,
                actor: $request->user(),
                reason: 'conflict of interest',
            );

            abort(403, 'Conflict of interest: you are a member of this company.');
        }

        // Apply action
        if ($approval->action === 'set_fee_percentage') {
            Setting::set('marketplace_fee_percentage', $approval->payload['fee_percentage'] ?? null);
        } elseif ($approval->action === 'reapprove_company' && $approval->entity_id) {
            Company::where('id', $approval->entity_id)->update(['kyc_status' => 'APPROVED']);
        }

        $approval->update([
            'status' => 'APPROVED',
            'reviewed_by_id' => $request->user()->id,
            'reviewed_at' => now(),
            'review_reason' => $validated['reason'],
        ]);

        $this->audit->record(
            action: 'admin.approval_approved',
            entity: 'approval_request',
            entityId: (string) $approval->id,
            actor: $request->user(),
            reason: $validated['reason'],
        );

        return redirect()->route('admin.approvals.index')->with('status', 'Approval request approved.');
    }

    public function reject(Request $request, ApprovalRequest $approval): RedirectResponse
    {
        $this->authorize('manage', User::class);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'min:3'],
        ]);

        if ($approval->proposed_by_id === $request->user()->id) {
            abort(403, 'A different admin must reject this request.');
        }

        if ($this->hasConflictOfInterest($request->user(), $approval)) {
            $this->audit->record(
                action: 'admin.conflict_of_interest',
                entity: 'approval_request',
                entityId: (string) $approval->id,
                actor: $request->user(),
                reason: 'conflict of interest',
            );

            abort(403, 'Conflict of interest: you are a member of this company.');
        }

        $approval->update([
            'status' => 'REJECTED',
            'reviewed_by_id' => $request->user()->id,
            'reviewed_at' => now(),
            'review_reason' => $validated['reason'],
        ]);

        $this->audit->record(
            action: 'admin.approval_rejected',
            entity: 'approval_request',
            entityId: (string) $approval->id,
            actor: $request->user(),
            reason: $validated['reason'],
        );

        return redirect()->route('admin.approvals.index')->with('status', 'Approval request rejected.');
    }

    private function hasConflictOfInterest(User $user, ApprovalRequest $approval): bool
    {
        if ($approval->entity_id && ($approval->entity_type === 'company' || $approval->action === 'reapprove_company')) {
            return $user->memberships()->where('company_id', $approval->entity_id)->exists();
        }

        return false;
    }
}
