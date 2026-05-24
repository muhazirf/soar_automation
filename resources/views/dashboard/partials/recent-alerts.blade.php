@props([
    'alerts' => [],
])

<div class="space-y-3">
    @forelse($alerts as $alert)
        <x-dashboard.alert-card :alert="$alert" />
    @empty
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">check_circle</span>
            <p class="text-sm text-on-surface-variant mt-2">No recent alerts</p>
        </div>
    @endforelse
</div>

@if(count($alerts) > 0)
    <div class="mt-4 pt-4 border-t border-outline-variant/10 text-center">
        <a href="{{ route('alerts.index') }}" class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary/80 transition-colors">
            View all alerts
            <span class="material-symbols-outlined text-lg">arrow_forward</span>
        </a>
    </div>
@endif
