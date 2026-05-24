<x-layouts.dashboard title="Incidents">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Open</p>
            <p class="text-2xl font-bold text-on-surface">{{ $stats['open'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Investigating</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['investigating'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Resolved</p>
            <p class="text-2xl font-bold text-success">{{ $stats['resolved'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-error">
            <p class="text-xs text-on-surface-variant">Critical</p>
            <p class="text-2xl font-bold text-error">{{ $stats['critical'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-warning">
            <p class="text-xs text-on-surface-variant">High</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['high'] }}</p>
        </div>
    </div>

    <!-- Header & Actions -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <!-- Search -->
            <form method="GET" class="flex-1">
                <input
                    type="text"
                    name="search"
                    placeholder="Search incidents..."
                    value="{{ $filters['search'] }}"
                    class="w-80 px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                >
            </form>
        </div>

        <a href="{{ route('incidents.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
            <span class="material-symbols-outlined">add</span>
            New Incident
        </a>
    </div>

    <!-- Incidents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($incidents as $incident)
            <div class="glass-card rounded-xl p-6 hover:shadow-glow transition-all duration-300 cursor-pointer border-l-4 {{ match($incident['severity']) {
                'critical' => 'border-l-error',
                'high' => 'border-l-warning',
                'medium' => 'border-l-info',
                'low' => 'border-l-primary',
                default => 'border-l-outline-variant',
            } }}">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-mono text-on-surface-variant mb-1">{{ $incident['id'] }}</p>
                        <h3 class="text-lg font-semibold text-on-surface">{{ $incident['title'] }}</h3>
                    </div>
                    <x-ui.badge :variant="$incident['severity']">{{ ucfirst($incident['severity']) }}</x-ui.badge>
                </div>

                <!-- Details -->
                <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $incident['description'] }}</p>

                <!-- Meta -->
                <div class="flex items-center gap-4 text-xs text-on-surface-variant mb-4">
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">category</span>
                        {{ $incident['type'] }}
                    </span>
                    <span class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">person</span>
                        {{ $incident['assigned_to'] }}
                    </span>
                </div>

                <!-- Status & Actions -->
                <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
                    <span class="inline-flex items-center gap-1.5 text-sm">
                        <x-data.status-pip :status="match($incident['status']) {
                            'open', 'investigating' => 'danger',
                            'contained' => 'warning',
                            'resolved', 'closed' => 'success',
                            default => 'info',
                        }" :pulse="in_array($incident['status'], ['open', 'investigating'])" />
                        {{ ucfirst($incident['status']) }}
                    </span>

                    <a href="{{ route('incidents.show', $incident['id']) }}" class="text-primary hover:text-primary/80 transition-colors">
                        <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">folder_open</span>
                <p class="text-sm text-on-surface-variant mt-2">No incidents found</p>
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>
