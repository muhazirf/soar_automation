@props([
    'status' => 'info',
    'pulse' => false,
    'size' => 'md', // sm, md, lg
])

@php
    $statusColors = [
        'info' => 'bg-info',
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'danger' => 'bg-error',
        'error' => 'bg-error',
        'active' => 'bg-success',
        'inactive' => 'bg-on-surface-variant',
        'primary' => 'bg-primary',
    ];

    // Map unknown statuses to default
    $mappedStatus = $statusColors[$status] ?? $statusColors['info'];

    $sizeClasses = [
        'sm' => 'w-1.5 h-1.5',
        'md' => 'w-2 h-2',
        'lg' => 'w-2.5 h-2.5',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => implode(' ', [
            'rounded-full inline-block',
            $mappedStatus,
            $sizeClasses[$size],
            $pulse ? 'pip-pulse' : '',
        ])
    ]) }}
></span>
