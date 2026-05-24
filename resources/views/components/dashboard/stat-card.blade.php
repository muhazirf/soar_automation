@props([
    'title' => null,
    'value' => null,
    'change' => null,
    'icon' => null,
    'trend' => 'up', // up, down, neutral
])

@php
    $trendColors = [
        'up' => 'text-success',
        'down' => 'text-error',
        'neutral' => 'text-on-surface-variant',
    ];

    $trendIcons = [
        'up' => 'trending_up',
        'down' => 'trending_down',
        'neutral' => 'trending_flat',
    ];
@endphp

<div class="glass-card rounded-xl p-6 hover:shadow-glow transition-shadow duration-300">
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm text-on-surface-variant mb-1">{{ $title }}</p>
            <h3 class="text-3xl font-bold text-on-surface">{{ $value }}</h3>

            @if($change !== null)
                <p class="text-sm mt-2 {{ $trendColors[$trend] }} flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">{{ $trendIcons[$trend] }}</span>
                    {{ $change }}
                    <span class="text-on-surface-variant">from last period</span>
                </p>
            @endif
        </div>

        @if($icon)
            <div class="p-3 rounded-lg bg-primary/20">
                <span class="material-symbols-outlined text-2xl text-primary">{{ $icon }}</span>
            </div>
        @endif
    </div>
</div>
