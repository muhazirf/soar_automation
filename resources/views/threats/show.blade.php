<x-layouts.dashboard title="Threat Details">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-6">
        <a href="{{ route('threats.index') }}" class="hover:text-primary transition-colors">Threat Intel</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-on-surface">{{ $threat['id'] }}</span>
    </div>

    <!-- Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <p class="text-xs font-mono text-on-surface-variant">{{ $threat['id'] }}</p>
                    <x-ui.badge :variant="$threat['severity']">{{ ucfirst($threat['severity']) }}</x-ui.badge>
                    <x-ui.badge variant="primary">{{ $threat['type'] }}</x-ui.badge>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2">{{ $threat['name'] }}</h1>
                <p class="text-on-surface-variant max-w-2xl">{{ $threat['description'] }}</p>

                @if(!empty($threat['aliases']))
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-xs text-on-surface-variant">Also known as:</span>
                        @foreach($threat['aliases'] as $alias)
                            <span class="px-2 py-1 rounded bg-surface-container-high text-xs text-on-surface-variant">{{ $alias }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined">share</span>
                    Share
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-warning text-surface rounded-lg text-sm font-medium hover:bg-warning/90 transition-colors">
                    <span class="material-symbols-outlined">warning</span>
                    Create Alert
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- IOCs -->
            <x-ui.card title="Indicators of Compromise" subtitle="{{ count($threat['iocs']) }} indicators">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs text-on-surface-variant border-b border-outline-variant/10">
                                <th class="pb-3 font-medium">Type</th>
                                <th class="pb-3 font-medium">Value</th>
                                <th class="pb-3 font-medium">Description</th>
                                <th class="pb-3 font-medium"></th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($threat['iocs'] as $ioc)
                                <tr class="border-b border-outline-variant/5">
                                    <td class="py-3">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded bg-primary/10 text-primary text-xs font-medium">
                                            {{ $ioc['type'] }}
                                        </span>
                                    </td>
                                    <td class="py-3 font-mono text-on-surface">{{ $ioc['value'] }}</td>
                                    <td class="py-3 text-on-surface-variant">{{ $ioc['description'] }}</td>
                                    <td class="py-3">
                                        <button class="p-1 hover:bg-surface-container-high rounded transition-colors">
                                            <span class="material-symbols-outlined text-lg text-on-surface-variant">content_copy</span>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>

            <!-- MITRE ATT&CK -->
            @if(!empty($threat['ttps']))
                <x-ui.card title="MITRE ATT&CK Techniques" subtitle="Known tactics and techniques">
                    <div class="flex flex-wrap gap-2">
                        @foreach($threat['ttps'] as $ttp)
                            <a href="https://attack.mitre.org/techniques/{{ $ttp }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 px-3 py-2 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors">
                                <span class="text-sm font-mono text-primary">{{ $ttp }}</span>
                                <span class="material-symbols-outlined text-sm text-on-surface-variant">open_in_new</span>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Related Threats -->
            @if(!empty($relatedThreats))
                <x-ui.card title="Related Threats" subtitle="Similar threat actors">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($relatedThreats as $related)
                            <a href="{{ route('threats.show', $related['id']) }}" class="flex items-center justify-between p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors">
                                <div>
                                    <p class="text-xs font-mono text-on-surface-variant">{{ $related['id'] }}</p>
                                    <p class="text-sm font-medium text-on-surface">{{ $related['name'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-on-surface-variant">Similarity</p>
                                    <p class="text-sm font-semibold text-info">{{ $related['similarity'] }}%</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Threat Info -->
            <x-ui.card title="Threat Info">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Confidence</span>
                        <div class="flex items-center gap-2">
                            <div class="w-20 h-2 bg-surface-container rounded-full overflow-hidden">
                                <div class="h-full bg-success rounded-full" style="width: {{ $threat['confidence'] }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-on-surface">{{ $threat['confidence'] }}%</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">First Seen</span>
                        <span class="text-sm text-on-surface">{{ $threat['first_seen'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Last Seen</span>
                        <span class="text-sm text-on-surface">{{ $threat['last_seen'] }}</span>
                    </div>
                </div>
            </x-ui.card>

            <!-- Targets -->
            @if(!empty($threat['targets']))
                <x-ui.card title="Targeted Regions">
                    <div class="flex flex-wrap gap-2">
                        @foreach($threat['targets'] as $target)
                            <span class="px-3 py-1 rounded-full bg-surface-container-high text-sm text-on-surface-variant">
                                {{ $target }}
                            </span>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Industries -->
            @if(!empty($threat['industries']))
                <x-ui.card title="Targeted Industries">
                    <div class="flex flex-wrap gap-2">
                        @foreach($threat['industries'] as $industry)
                            <span class="px-3 py-1 rounded-full bg-warning/10 text-warning text-sm">
                                {{ $industry }}
                            </span>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Tags -->
            <x-ui.card title="Tags">
                <div class="flex flex-wrap gap-2">
                    @foreach($threat['tags'] as $tag)
                        <span class="px-3 py-1 rounded-full bg-surface-container-high text-sm text-on-surface-variant">
                            #{{ $tag }}
                        </span>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Actions -->
            <x-ui.card title="Actions">
                <div class="space-y-2">
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-primary">add_alert</span>
                        <span class="text-sm text-on-surface">Create Detection Rule</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-info">blocked</span>
                        <span class="text-sm text-on-surface">Block IOCs</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-warning">search</span>
                        <span class="text-sm text-on-surface">Search in Logs</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors text-left">
                        <span class="material-symbols-outlined text-success">playbook</span>
                        <span class="text-sm text-on-surface">Run Playbook</span>
                    </button>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
