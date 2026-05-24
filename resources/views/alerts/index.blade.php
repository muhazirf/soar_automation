<x-layouts.dashboard title="Alerts">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Total</p>
            <p class="text-2xl font-bold text-on-surface">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-error">
            <p class="text-xs text-on-surface-variant">Critical</p>
            <p class="text-2xl font-bold text-error">{{ $stats['critical'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-warning">
            <p class="text-xs text-on-surface-variant">High</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['high'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-info">
            <p class="text-xs text-on-surface-variant">Medium</p>
            <p class="text-2xl font-bold text-info">{{ $stats['medium'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-primary">
            <p class="text-xs text-on-surface-variant">Low</p>
            <p class="text-2xl font-bold text-primary">{{ $stats['low'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-success">
            <p class="text-xs text-on-surface-variant">Active</p>
            <p class="text-2xl font-bold text-success">{{ $stats['active'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    placeholder="Search alerts..."
                    value="{{ $filters['search'] }}"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                >
            </div>

            <!-- Severity -->
            <x-ui.select
                name="severity"
                label="Severity"
                :options="[
                    ['value' => '', 'label' => 'All'],
                    ['value' => 'critical', 'label' => 'Critical'],
                    ['value' => 'high', 'label' => 'High'],
                    ['value' => 'medium', 'label' => 'Medium'],
                    ['value' => 'low', 'label' => 'Low'],
                ]"
                :value="$filters['severity']"
            />

            <!-- Status -->
            <x-ui.select
                name="status"
                label="Status"
                :options="[
                    ['value' => '', 'label' => 'All'],
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'investigating', 'label' => 'Investigating'],
                    ['value' => 'resolved', 'label' => 'Resolved'],
                    ['value' => 'dismissed', 'label' => 'Dismissed'],
                ]"
                :value="$filters['status']"
            />

            <!-- Submit -->
            <button type="submit" class="px-6 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
                Apply Filters
            </button>

            @if($filters['severity'] || $filters['status'] || $filters['search'])
                <a href="{{ route('alerts.index') }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </x-ui.card>

    <!-- Alerts Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/10">
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">ID</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Severity</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Title</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Source</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Host</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Time</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Status</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alerts as $alert)
                        <tr class="border-b border-outline-variant/5 hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-primary">{{ $alert['id'] }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$alert['severity']" :dot="true">
                                    {{ ucfirst($alert['severity']) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('alerts.show', $alert['id']) }}" class="text-on-surface hover:text-primary transition-colors">
                                    {{ $alert['title'] }}
                                </a>
                                <p class="text-xs text-on-surface-variant mt-1 truncate max-w-xs">{{ $alert['description'] }}</p>
                            </td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $alert['source'] }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $alert['affected_host'] }}</td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $alert['created_at'] }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center gap-1.5">
                                    <x-data.status-pip :status="$alert['status'] === 'active' ? 'danger' : 'success'" :pulse="$alert['status'] === 'active'" />
                                    {{ ucfirst($alert['status']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button class="p-1.5 rounded hover:bg-white/10 transition-colors" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-1.5 rounded hover:bg-white/10 transition-colors" title="Assign">
                                        <span class="material-symbols-outlined text-lg">person_add</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2 block opacity-50">search_off</span>
                                <p>No alerts found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</x-layouts.dashboard>
