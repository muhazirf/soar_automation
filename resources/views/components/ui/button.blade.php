@props([
    'variant' => 'primary', // primary, secondary, ghost, danger
    'size' => 'md',         // sm, md, lg
    'type' => 'button',
    'disabled' => false,
])

@php
    $variantClasses = [
        'primary' => 'bg-success text-surface hover:bg-success/90 focus:ring-success/50',
        'secondary' => 'bg-surface-container border border-outline-variant/20 text-on-surface hover:bg-surface-container-high focus:ring-outline-variant/50',
        'ghost' => 'bg-transparent text-on-surface hover:bg-white/5 focus:ring-on-surface-variant/20',
        'danger' => 'bg-error text-on-error hover:bg-error/90 focus:ring-error/50',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
    ];

    $baseClasses = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-surface disabled:opacity-50 disabled:cursor-not-allowed';
    $classes = implode(' ', [$baseClasses, $variantClasses[$variant], $sizeClasses[$size]]);
@endphp

<button
    {{ $attributes->merge([
        'type' => $type,
        'disabled' => $disabled,
        'class' => $classes,
    ]) }}
>
    {{ $slot }}
</button>
