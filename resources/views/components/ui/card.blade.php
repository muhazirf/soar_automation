@props([
    'title' => null,
    'subtitle' => null,
    'padding' => 'md', // none, sm, md, lg
])

@php
    $paddingClasses = [
        'none' => '',
        'sm' => 'p-4',
        'md' => 'p-6',
        'lg' => 'p-8',
    ];
@endphp

<div
    {{ $attributes->merge([
        'class' => implode(' ', [
            'glass-card rounded-xl',
            $paddingClasses[$padding],
        ])
    ]) }}
>
    @if($title || $subtitle)
        <div class="mb-4 pb-4 border-b border-outline-variant/10 @if($padding === 'none') px-6 pt-6 @endif">
            @if($title)
                <h3 class="text-headline-md text-on-surface">{{ $title }}</h3>
            @endif
            @if($subtitle)
                <p class="text-sm text-on-surface-variant mt-1">{{ $subtitle }}</p>
            @endif
        </div>
    @endif

    {{ $slot }}
</div>
