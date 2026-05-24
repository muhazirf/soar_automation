@props([
    'variant' => 'default',
    'size' => 'md',         // sm, md, lg
    'dot' => false,
])

@php
    // Map various severity/result types to standard variants
    $variantMap = [
        'critical' => 'danger',
        'high' => 'danger',
        'medium' => 'warning',
        'low' => 'info',
        'malicious' => 'danger',
        'suspicious' => 'warning',
        'clean' => 'success',
        'undetected' => 'default',
        'error' => 'danger',
        'info' => 'info',
        'success' => 'success',
        'warning' => 'warning',
        'danger' => 'danger',
        'primary' => 'primary',
        'default' => 'default',
    ];

    // Map variant to standard variant
    $mappedVariant = $variantMap[$variant] ?? 'default';

    $variantClasses = [
        'default' => 'bg-surface-container border border-outline-variant/20 text-on-surface-variant',
        'success' => 'bg-success/20 border border-success/30 text-success',
        'warning' => 'bg-warning/20 border border-warning/30 text-warning',
        'danger' => 'bg-error/20 border border-error/30 text-error',
        'info' => 'bg-info/20 border border-info/30 text-info',
        'primary' => 'bg-primary/20 border border-primary/30 text-primary',
    ];

    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-2.5 py-1 text-xs',
        'lg' => 'px-3 py-1.5 text-sm',
    ];

    $dotColors = [
        'default' => 'bg-on-surface-variant',
        'success' => 'bg-success',
        'warning' => 'bg-warning',
        'danger' => 'bg-error',
        'info' => 'bg-info',
        'primary' => 'bg-primary',
    ];
@endphp

<span
    {{ $attributes->merge([
        'class' => implode(' ', [
            'inline-flex items-center gap-1.5 rounded-md font-medium border transition-colors',
            $variantClasses[$mappedVariant],
            $sizeClasses[$size],
        ])
    ]) }}
>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$mappedVariant] }} {{ $mappedVariant === 'success' || $mappedVariant === 'danger' ? 'pip-pulse' : '' }}"></span>
    @endif

    {{ $slot }}
</span>
