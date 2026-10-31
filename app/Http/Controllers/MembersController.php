<?php

namespace App\Http\Controllers;

use App\Mail\MemberInvitationMail;
use App\Models\Invitation;
use App\Models\Membership;
use App\Models\User;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MembersController extends Controller
{
    public function index(Request $request): View
    {
        $company = $request->user()->company();
        $this->authorize('view', $company);

        return view('company.members.index', [
            'company' => $company,
            'members' => $company->memberships()->with('user')->orderBy('id')->get(),
            'invitations' => $company->invitations()->pending()->orderBy('expires_at')->get(),
            'canManage' => $request->user()->can('manageMembers', $company),
            'roles' => config('greengrid.membership_roles'),
        ]);
    }

    public function create(Request $request): View
    {
        $company = $request->user()->company();
        $this->authorize('manageMembers', $company);

        return view('company.members.invite', [
            'company' => $company,
            'roles' => config('greengrid.membership_roles'),
        ]);
    }

    public function store(Request $request, AuditLogger $audit): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('manageMembers', $company);

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', Rule::in(config('greengrid.membership_roles'))],
        ]);

        $email = Str::lower($validated['email']);

        $existingMember = User::query()
            ->where('email', $email)
            ->whereHas('memberships', fn ($query) => $query->where('company_id', $company->id))
            ->exists();

        if ($existingMember) {
            return back()->withErrors(['email' => 'This person is already a member of your company.'])->withInput();
        }

        $pending = $company->invitations()->pending()->where('email', $email)->exists();

        if ($pending) {
            return back()->withErrors(['email' => 'An invitation is already pending for this email.'])->withInput();
        }

        $token = Str::random(64);

        $invitation = Invitation::create([
            'company_id' => $company->id,
            'invited_by' => $request->user()->id,
            'email' => $email,
            'role' => $validated['role'],
            'token' => hash('sha256', $token),
            'expires_at' => now()->addDays((int) config('greengrid.invitation_expiry_days')),
        ]);

        Mail::to($email)->queue(new MemberInvitationMail($invitation, $token));

        $audit->record(
            action: 'member.invited',
            entity: 'invitation',
            entityId: (string) $invitation->id,
            actor: $request->user(),
        );

        return redirect()->route('members.index')->with('status', 'Invitation sent.');
    }

    public function update(Request $request, Membership $membership, AuditLogger $audit): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('manageMembers', $company);

        abort_unless($membership->company_id === $company->id, 404);

        $validated = $request->validate([
            'role' => ['required', Rule::in(config('greengrid.membership_roles'))],
        ]);

        $previous = $membership->role;
        $membership->update(['role' => $validated['role']]);

        $audit->record(
            action: 'member.role_changed',
            entity: 'membership',
            entityId: (string) $membership->id,
            reason: $previous.' -> '.$validated['role'],
            actor: $request->user(),
        );

        return back()->with('status', 'Role updated.');
    }

    public function destroy(Request $request, Membership $membership, AuditLogger $audit): RedirectResponse
    {
        $company = $request->user()->company();
        $this->authorize('manageMembers', $company);

        abort_unless($membership->company_id === $company->id, 404);

        $membership->delete();

        $audit->record(
            action: 'member.removed',
            entity: 'membership',
            entityId: (string) $membership->id,
            actor: $request->user(),
        );

        return back()->with('status', 'Member removed.');
    }
}
