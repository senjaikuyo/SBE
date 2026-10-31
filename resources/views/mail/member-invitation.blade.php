<x-mail::message>
# Join {{ $companyName }}

{{ $inviterName }} invited you to join **{{ $companyName }}** on GreenGrid APAC as **{{ str_replace('_', ' ', $role) }}**.

This invitation expires on {{ $expiresAt->format('j M Y, H:i') }} UTC.

<x-mail::button :url="$acceptUrl">
Accept invitation
</x-mail::button>

If you were not expecting this invitation, you can ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
