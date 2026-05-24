@props([
    'status' => 'info', // info, success, warning, danger, active, inactive
    'pulse' => false,
    'size' => 'md', // sm, md, lg
])

@php
    $statusColors = [
        'info' => 'bg-info',
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'danger' => 'bg-error',
        'active' => 'bg-success',
        'inactive' => 'bg-on-surface-variant',
    ];

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
            $statusColors[$status],
            $sizeClasses[$size],
            $pulse ? 'pip-pulse' : '',
        ])
    ]) }}
></span>
