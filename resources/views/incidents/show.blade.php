<x-layouts.dashboard title="Incident {{ $incident['id'] }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('incidents.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Incidents
        </a>
    </div>

    <!-- Incident Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <x-ui.badge :variant="$incident['severity']" :dot="true" size="lg">
                        {{ ucfirst($incident['severity']) }}
                    </x-ui.badge>
                    <span class="text-xs font-mono text-on-surface-variant">{{ $incident['id'] }}</span>
                    <x-ui.badge variant="default">{{ $incident['type'] }}</x-ui.badge>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2">{{ $incident['title'] }}</h1>
                <p class="text-on-surface-variant">{{ $incident['description'] }}</p>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('incidents.edit', $incident['id']) }}" class="px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-lg align-middle">edit</span>
                    Edit
                </a>
                <x-ui.button variant="primary" size="md">Update Status</x-ui.button>
            </div>
        </div>
    </div>

    <!-- Incident Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <x-ui.card title="Incident Details">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Status</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1.5">
                                <x-data.status-pip :status="match($incident['status']) {
                                    'open', 'investigating' => 'danger',
                                    'contained' => 'warning',
                                    default => 'success',
                                }" :pulse="in_array($incident['status'], ['open', 'investigating'])" />
                                {{ ucfirst($incident['status']) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Type</dt>
                        <dd>{{ $incident['type'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Created</dt>
                        <dd>{{ $incident['created_at'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Updated</dt>
                        <dd>{{ $incident['updated_at'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Assigned To</dt>
                        <dd>{{ $incident['assigned_to'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Resolution</dt>
                        <dd>{{ $incident['resolution'] ?? 'N/A' }}</dd>
                    </div>
                </dl>

                @if(isset($incident['affected_assets']) && count($incident['affected_assets']) > 0)
                    <div class="mt-4 pt-4 border-t border-outline-variant/10">
                        <dt class="text-xs text-on-surface-variant mb-2">Affected Assets</dt>
                        <div class="flex flex-wrap gap-2">
                            @foreach($incident['affected_assets'] as $asset)
                                <span class="px-3 py-1 bg-surface-container rounded-lg text-xs font-mono text-on-surface">
                                    {{ $asset }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-ui.card>

            <!-- Timeline -->
            <x-ui.card title="Activity Timeline">
                <div class="space-y-4">
                    @foreach($timeline as $i => $item)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                @if($i < count($timeline) - 1)
                                    <div class="w-0.5 flex-1 bg-outline-variant/20 mt-1"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm text-on-surface">{{ $item['event'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $item['time'] }} · {{ $item['user'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Linked Alerts -->
            @if(isset($linkedAlerts) && count($linkedAlerts) > 0)
                <x-ui.card title="Linked Alerts">
                    <div class="space-y-3">
                        @foreach($linkedAlerts as $alert)
                            <a href="{{ route('alerts.show', $alert['id']) }}" class="block p-4 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-on-surface mb-1">{{ $alert['title'] }}</p>
                                        <p class="text-xs text-on-surface-variant">{{ $alert['id'] }}</p>
                                    </div>
                                    <x-ui.badge :variant="$alert['severity']" size="sm">{{ ucfirst($alert['severity']) }}</x-ui.badge>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="space-y-3">
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-primary">edit</span>
                        <span class="text-sm">Edit incident</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-warning">playlist_add_check</span>
                        <span class="text-sm">Run playbook</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-info">link</span>
                        <span class="text-sm">Link alerts</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-success">check_circle</span>
                        <span class="text-sm">Mark resolved</span>
                    </button>
                </div>
            </x-ui.card>

            <!-- Tags -->
            @if(isset($incident['tags']) && count($incident['tags']) > 0)
                <x-ui.card title="Tags">
                    <div class="flex flex-wrap gap-2">
                        @foreach($incident['tags'] as $tag)
                            <span class="px-3 py-1 bg-surface-container rounded-full text-xs text-on-surface-variant">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- MITRE ATT&CK -->
            @if(isset($incident['mitre_techniques']) && count($incident['mitre_techniques']) > 0)
                <x-ui.card title="MITRE ATT&CK Techniques">
                    <div class="flex flex-wrap gap-2">
                        @foreach($incident['mitre_techniques'] as $technique)
                            <a href="https://attack.mitre.org/techniques/{{ str_replace('.', '/', $technique) }}" target="_blank" class="px-3 py-1 bg-surface-container hover:bg-surface-container-high rounded-lg text-xs font-mono text-primary transition-colors">
                                {{ $technique }}
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
