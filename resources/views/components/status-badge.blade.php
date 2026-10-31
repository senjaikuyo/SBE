@props(['status' => '', 'label' => null])

@php
    $map = [
        'NOT_SUBMITTED' => ['muted', 'Not submitted'],
        'PENDING_VERIFICATION' => ['warning', 'Under review'],
        'PENDING_REVIEW' => ['warning', 'Pending review'],
        'APPROVED' => ['success', 'Approved'],
        'ACTIVE' => ['success', 'Active'],
        'VALID' => ['success', 'Verified'],
        'REJECTED' => ['danger', 'Rejected'],
        'DRAFT' => ['muted', 'Draft'],
        'INACTIVE' => ['muted', 'Inactive'],
        'EXPIRED' => ['muted', 'Expired'],
        'SOLD_OUT' => ['info', 'Sold out'],
        'DUE_SOON' => ['warning', 'Due soon'],
        'OVERDUE' => ['danger', 'Overdue'],
    ];

    [$tone, $default] = $map[$status] ?? ['muted', str_replace('_', ' ', strtolower($status))];

    $toneClass = match ($tone) {
        'success' => 'bg-success-soft text-success',
        'warning' => 'bg-warning-soft text-warning',
        'danger' => 'bg-danger-soft text-danger',
        'info' => 'bg-info-soft text-info',
        default => 'bg-muted-soft text-ink-600',
    };
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-sm px-2 py-1 text-[12px] font-medium '.$toneClass]) }}>
    <svg class="h-3 w-3" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true">
        <circle cx="6" cy="6" r="3"/>
    </svg>
    {{ $label ?? $default }}
</span>
