<x-mail::message>
# Company verification decision

**{{ $companyName }}**.

Your company verification was **rejected**.

Reason: {{ $reason }}

You can submit new documents after a new verification request.

<x-mail::button :url="route('login')">
View verification status
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
