<x-layouts.dashboard title="Playbooks">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Total Playbooks</p>
            <p class="text-2xl font-bold text-on-surface">{{ count($playbooks) }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Active</p>
            <p class="text-2xl font-bold text-success">{{ collect($playbooks)->where('status', 'active')->count() }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Draft</p>
            <p class="text-2xl font-bold text-warning">{{ collect($playbooks)->where('status', 'draft')->count() }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Avg Success Rate</p>
            <p class="text-2xl font-bold text-info">{{ collect($playbooks)->avg('success_rate') }}%</p>
        </div>
    </div>

    <!-- Header & Filters -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <!-- Category Filter -->
            <form method="GET" class="flex items-center gap-2">
                <select
                    name="category"
                    onchange="this.form.submit()"
                    class="px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-success/50"
                >
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ $filters['category'] === $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>

                <select
                    name="status"
                    onchange="this.form.submit()"
                    class="px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-success/50"
                >
                    <option value="">All Status</option>
                    <option value="active" {{ $filters['status'] === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="draft" {{ $filters['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </form>
        </div>

        <button class="inline-flex items-center gap-2 px-4 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
            <span class="material-symbols-outlined">add</span>
            New Playbook
        </button>
    </div>

    <!-- Playbooks Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($playbooks as $playbook)
            <div class="glass-card rounded-xl p-6 hover:shadow-glow transition-all duration-300 cursor-pointer border-l-4 {{ match($playbook['category']) {
                'Response' => 'border-l-error',
                'Investigation' => 'border-l-warning',
                'Maintenance' => 'border-l-info',
                'Compliance' => 'border-l-primary',
                default => 'border-l-outline-variant',
            } }}">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-mono text-on-surface-variant mb-1">{{ $playbook['id'] }}</p>
                        <h3 class="text-lg font-semibold text-on-surface">{{ $playbook['name'] }}</h3>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium {{ $playbook['status'] === 'active' ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                        <x-data.status-pip :status="$playbook['status'] === 'active' ? 'success' : 'warning'" :pulse="false" />
                        {{ ucfirst($playbook['status']) }}
                    </span>
                </div>

                <!-- Details -->
                <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $playbook['description'] }}</p>

                <!-- Category Badge -->
                <div class="mb-4">
                    <x-ui.badge :variant="match($playbook['category']) {
                        'Response' => 'error',
                        'Investigation' => 'warning',
                        'Maintenance' => 'info',
                        'Compliance' => 'primary',
                        default => 'default',
                    }">{{ $playbook['category'] }}</x-ui.badge>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-4 mb-4 py-4 border-y border-outline-variant/10">
                    <div class="text-center">
                        <p class="text-lg font-semibold text-on-surface">{{ $playbook['steps'] }}</p>
                        <p class="text-xs text-on-surface-variant">Steps</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-semibold text-on-surface">{{ $playbook['avg_duration'] }}</p>
                        <p class="text-xs text-on-surface-variant">Avg Time</p>
                    </div>
                    <div class="text-center">
                        <p class="text-lg font-semibold text-{{ $playbook['success_rate'] >= 90 ? 'success' : ($playbook['success_rate'] >= 80 ? 'warning' : 'error') }}">
                            {{ $playbook['success_rate'] }}%
                        </p>
                        <p class="text-xs text-on-surface-variant">Success</p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-between">
                    <span class="text-xs text-on-surface-variant">
                        Last used: {{ $playbook['last_used'] ?? 'Never' }}
                    </span>

                    <div class="flex items-center gap-2">
                        <button class="p-2 rounded-lg hover:bg-surface-container-high transition-colors group">
                            <span class="material-symbols-outlined text-lg text-on-surface-variant group-hover:text-primary">play_arrow</span>
                        </button>
                        <a href="{{ route('playbooks.show', $playbook['id']) }}" class="p-2 rounded-lg hover:bg-surface-container-high transition-colors group">
                            <span class="material-symbols-outlined text-lg text-on-surface-variant group-hover:text-primary">arrow_forward</span>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">auto_fix</span>
                <p class="text-sm text-on-surface-variant mt-2">No playbooks found</p>
            </div>
        @endforelse
    </div>
</x-layouts.dashboard>
