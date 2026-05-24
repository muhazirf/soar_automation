@props([
    'alert' => null,
])

@php
    if (!$alert) return;

    $severityColors = [
        'critical' => 'border-l-error bg-error/5',
        'high' => 'border-l-warning bg-warning/5',
        'medium' => 'border-l-info bg-info/5',
        'low' => 'border-l-primary bg-primary/5',
    ];

    $severityBadges = [
        'critical' => 'danger',
        'high' => 'warning',
        'medium' => 'info',
        'low' => 'default',
    ];
@endphp

<div class="glass-card rounded-lg border-l-4 {{ $severityColors[$alert['severity'] ?? 'medium'] }} p-4 hover:shadow-glow transition-all duration-300 cursor-pointer">
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <x-ui.badge :variant="$severityBadges[$alert['severity'] ?? 'default']" :dot="true">
                    {{ ucfirst($alert['severity'] ?? 'medium') }}
                </x-ui.badge>
                <span class="text-xs text-on-surface-variant">{{ $alert['timestamp'] ?? now()->format('H:i') }}</span>
            </div>
            <h4 class="text-sm font-medium text-on-surface truncate">{{ $alert['title'] ?? 'Unknown Alert' }}</h4>
            <p class="text-xs text-on-surface-variant mt-1 truncate">{{ $alert['description'] ?? '' }}</p>
        </div>

        <span class="material-symbols-outlined text-on-surface-variant">arrow_forward</span>
    </div>
</div>
