@props([
    'activities' => [],
    'title' => 'Activity Feed',
    'subtitle' => 'Recent system activity',
])

<div class="space-y-4">
    @forelse($activities as $activity)
        <div class="flex gap-4">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg {{ $activity['icon_color'] ?? 'text-primary' }}">
                        {{ $activity['icon'] ?? 'info' }}
                    </span>
                </div>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pb-4 border-l border-outline-variant/10 pl-4">
                <p class="text-sm text-on-surface">
                    {{ $activity['title'] ?? 'Activity' }}
                </p>
                <p class="text-xs text-on-surface-variant mt-1">
                    {{ $activity['description'] ?? '' }}
                </p>
                <p class="text-xs text-on-surface-variant/60 mt-2">
                    {{ $activity['time'] ?? now()->diffForHumans() }}
                </p>
            </div>
        </div>
    @empty
        <div class="text-center py-8">
            <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">history</span>
            <p class="text-sm text-on-surface-variant mt-2">No recent activity</p>
        </div>
    @endforelse
</div>
