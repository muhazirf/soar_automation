<x-layouts.dashboard title="Alert {{ $alert['id'] }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('alerts.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Alerts
        </a>
    </div>

    <!-- Alert Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <x-ui.badge :variant="$alert['severity']" :dot="true" size="lg">
                        {{ ucfirst($alert['severity']) }}
                    </x-ui.badge>
                    <span class="text-xs font-mono text-on-surface-variant">{{ $alert['id'] }}</span>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2">{{ $alert['title'] }}</h1>
                <p class="text-on-surface-variant">{{ $alert['description'] }}</p>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary" size="md">Investigate</x-ui.button>
                <x-ui.button variant="primary" size="md">
                    <span class="material-symbols-outlined">escalate</span>
                    Escalate
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Alert Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Details (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <x-ui.card title="Alert Details">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Severity</dt>
                        <dd><x-ui.badge :variant="$alert['severity']">{{ ucfirst($alert['severity']) }}</x-ui.badge></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Status</dt>
                        <dd>
                            <span class="inline-flex items-center gap-1.5">
                                <x-data.status-pip status="danger" :pulse="true" />
                                {{ ucfirst($alert['status']) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Source</dt>
                        <dd>{{ $alert['source'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Confidence</dt>
                        <dd>{{ $alert['confidence'] }}%</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Created</dt>
                        <dd>{{ $alert['created_at'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Updated</dt>
                        <dd>{{ $alert['updated_at'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Assigned To</dt>
                        <dd>{{ $alert['assigned_to'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Affected Host</dt>
                        <dd class="font-mono text-sm">{{ $alert['affected_host'] }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            <!-- IOCs -->
            @if(isset($alert['ioc']) && count($alert['ioc']) > 0)
                <x-ui.card title="Indicators of Compromise">
                    <div class="space-y-3">
                        @foreach($alert['ioc'] as $ioc)
                            <div class="flex items-center justify-between p-3 bg-surface-container rounded-lg">
                                <div>
                                    <p class="text-xs text-on-surface-variant mb-1">{{ $ioc['type'] }}</p>
                                    <p class="font-mono text-sm text-on-surface">{{ $ioc['value'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-on-surface-variant">{{ $ioc['description'] }}</p>
                                    <button class="text-xs text-primary hover:text-primary/80 mt-1">Lookup</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

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
                                <p class="text-xs text-on-surface-variant mt-1">{{ $item['time'] }} by {{ $item['user'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="space-y-3">
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-primary">person_add</span>
                        <span class="text-sm">Assign to analyst</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-warning">playlist_add_check</span>
                        <span class="text-sm">Add to playbook</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-info">link</span>
                        <span class="text-sm">Link to incident</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-error">dismiss</span>
                        <span class="text-sm">Dismiss alert</span>
                    </button>
                </div>
            </x-ui.card>

            <!-- Related Alerts -->
            @if(count($relatedAlerts) > 0)
                <x-ui.card title="Related Alerts">
                    <div class="space-y-3">
                        @foreach($relatedAlerts as $related)
                            <a href="{{ route('alerts.show', $related['id']) }}" class="block p-3 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                                <p class="text-sm text-on-surface mb-1">{{ $related['title'] }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $related['id'] }} · {{ $related['created_at'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Tags -->
            @if(isset($alert['tags']) && count($alert['tags']) > 0)
                <x-ui.card title="Tags">
                    <div class="flex flex-wrap gap-2">
                        @foreach($alert['tags'] as $tag)
                            <span class="px-3 py-1 bg-surface-container rounded-full text-xs text-on-surface-variant">
                                #{{ $tag }}
                            </span>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>
    </div>
</x-layouts.dashboard>
