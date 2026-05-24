<x-layouts.dashboard title="Threat Intelligence">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Active Threats</p>
            <p class="text-2xl font-bold text-error">{{ $stats['active_threats'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Total IOCs</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['total_iocs'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">New This Week</p>
            <p class="text-2xl font-bold text-info">{{ $stats['new_this_week'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Active Feeds</p>
            <p class="text-2xl font-bold text-success">{{ $stats['feeds_active'] }}</p>
        </div>
    </div>

    <!-- Header & Filters -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-4">
            <form method="GET" class="flex items-center gap-2">
                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    placeholder="Search threats..."
                    value="{{ $filters['search'] }}"
                    class="w-64 px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                >

                <!-- Type Filter -->
                <select
                    name="type"
                    onchange="this.form.submit()"
                    class="px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-success/50"
                >
                    <option value="">All Types</option>
                    <option value="APT" {{ $filters['type'] === 'APT' ? 'selected' : '' }}>APT</option>
                    <option value="Malware" {{ $filters['type'] === 'Malware' ? 'selected' : '' }}>Malware</option>
                    <option value="Ransomware" {{ $filters['type'] === 'Ransomware' ? 'selected' : '' }}>Ransomware</option>
                    <option value="Phishing" {{ $filters['type'] === 'Phishing' ? 'selected' : '' }}>Phishing</option>
                    <option value="Botnet" {{ $filters['type'] === 'Botnet' ? 'selected' : '' }}>Botnet</option>
                    <option value="Zero-Day" {{ $filters['type'] === 'Zero-Day' ? 'selected' : '' }}>Zero-Day</option>
                </select>

                <!-- Severity Filter -->
                <select
                    name="severity"
                    onchange="this.form.submit()"
                    class="px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-success/50"
                >
                    <option value="">All Severities</option>
                    <option value="critical" {{ $filters['severity'] === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="high" {{ $filters['severity'] === 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ $filters['severity'] === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ $filters['severity'] === 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </form>
        </div>

        <button class="inline-flex items-center gap-2 px-4 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
            <span class="material-symbols-outlined">add</span>
            Add Threat
        </button>
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Threats List (3/4) -->
        <div class="lg:col-span-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($threats as $threat)
                    <div class="glass-card rounded-xl p-6 hover:shadow-glow transition-all duration-300 cursor-pointer border-l-4 {{ match($threat['severity']) {
                        'critical' => 'border-l-error',
                        'high' => 'border-l-warning',
                        'medium' => 'border-l-info',
                        'low' => 'border-l-primary',
                        default => 'border-l-outline-variant',
                    } }}">
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <p class="text-xs font-mono text-on-surface-variant mb-1">{{ $threat['id'] }}</p>
                                <h3 class="text-lg font-semibold text-on-surface">{{ $threat['name'] }}</h3>
                            </div>
                            <x-ui.badge :variant="$threat['severity']">{{ ucfirst($threat['severity']) }}</x-ui.badge>
                        </div>

                        <!-- Description -->
                        <p class="text-sm text-on-surface-variant mb-4 line-clamp-2">{{ $threat['description'] }}</p>

                        <!-- Type & Confidence -->
                        <div class="flex items-center gap-4 mb-4">
                            <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-sm">category</span>
                                {{ $threat['type'] }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-sm">verified</span>
                                {{ $threat['confidence'] }}% confidence
                            </span>
                        </div>

                        <!-- Tags -->
                        <div class="flex items-center flex-wrap gap-2 mb-4">
                            @foreach($threat['tags'] as $tag)
                                <span class="px-2 py-1 rounded-full bg-surface-container-high text-xs text-on-surface-variant">
                                    #{{ $tag }}
                                </span>
                            @endforeach
                        </div>

                        <!-- Footer -->
                        <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
                            <span class="text-xs text-on-surface-variant">
                                <span class="material-symbols-outlined text-sm align-middle">timer</span>
                                Last seen: {{ $threat['last_seen'] }}
                            </span>
                            <a href="{{ route('threats.show', $threat['id']) }}" class="text-primary hover:text-primary/80 transition-colors">
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/30">radar</span>
                        <p class="text-sm text-on-surface-variant mt-2">No threats found</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Feed Status (1/4) -->
        <div class="lg:col-span-1">
            <x-ui.card title="Feed Status" subtitle="Threat intelligence feeds">
                <div class="space-y-3">
                    @foreach($feedStatus as $feed)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-surface-container">
                            <div class="flex items-center gap-3">
                                <x-data.status-pip status="success" :pulse="true" />
                                <span class="text-sm text-on-surface">{{ $feed['name'] }}</span>
                            </div>
                            <span class="text-xs text-on-surface-variant">{{ $feed['last_update'] }}</span>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="space-y-2">
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-primary">refresh</span>
                        <span class="text-sm text-on-surface">Refresh All Feeds</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-warning">download</span>
                        <span class="text-sm text-on-surface">Import IOCs</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-info">upload</span>
                        <span class="text-sm text-on-surface">Export Indicators</span>
                    </button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
