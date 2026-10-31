<x-mail::message>
# Company verification decision

**{{ $companyName }}**.

Your company verification was **approved**. Your marketplace role is **{{ str_replace('_', ' ', $role) }}**.

<x-mail::button :url="route('login')">
View verification status
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
