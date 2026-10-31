<x-mail::message>
# Listing Rejected

Your listing for **{{ $listing->location }}** has been rejected.

**Reason:**
{{ $reason }}

Please review the requirements and submit again when ready.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
