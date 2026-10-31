<x-mail::message>
# Company verification received

We received your verification documents for **{{ $companyName }}** ({{ $country }}).

Your requested marketplace role is **{{ str_replace('_', ' ', $role) }}**.

Our compliance team usually responds within {{ $decisionEstimateHours }} hours. You will be notified as soon as a decision is made.

<x-mail::button :url="route('login')">
View verification status
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
