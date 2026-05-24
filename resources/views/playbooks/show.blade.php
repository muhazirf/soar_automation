<x-layouts.dashboard title="Playbook Details">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-on-surface-variant mb-6">
        <a href="{{ route('playbooks.index') }}" class="hover:text-primary transition-colors">Playbooks</a>
        <span class="material-symbols-outlined text-lg">chevron_right</span>
        <span class="text-on-surface">{{ $playbook['id'] }}</span>
    </div>

    <!-- Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <p class="text-xs font-mono text-on-surface-variant">{{ $playbook['id'] }}</p>
                    <x-ui.badge :variant="match($playbook['category']) {
                        'Response' => 'error',
                        'Investigation' => 'warning',
                        'Maintenance' => 'info',
                        'Compliance' => 'primary',
                        default => 'default',
                    }">{{ $playbook['category'] }}</x-ui.badge>
                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-medium {{ $playbook['status'] === 'active' ? 'bg-success/20 text-success' : 'bg-warning/20 text-warning' }}">
                        <x-data.status-pip :status="$playbook['status'] === 'active' ? 'success' : 'warning'" :pulse="false" />
                        {{ ucfirst($playbook['status']) }}
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2">{{ $playbook['name'] }}</h1>
                <p class="text-on-surface-variant">{{ $playbook['description'] }}</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined">edit</span>
                    Edit
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
                    <span class="material-symbols-outlined">play_arrow</span>
                    Execute
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Playbook Steps -->
            <x-ui.card title="Playbook Steps" subtitle="Automated and manual steps">
                <div class="space-y-3">
                    @foreach($playbook['steps'] as $index => $step)
                        <div class="flex items-start gap-4 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full bg-primary/20 text-primary text-sm font-semibold">
                                {{ $step['id'] }}
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <h4 class="text-sm font-medium text-on-surface">{{ $step['name'] }}</h4>
                                    <x-ui.badge :variant="$step['type'] === 'automated' ? 'success' : 'warning'" size="sm">
                                        {{ ucfirst($step['type']) }}
                                    </x-ui.badge>
                                </div>
                                <p class="text-xs text-on-surface-variant">Estimated: {{ $step['duration'] }}</p>
                            </div>
                            <span class="material-symbols-outlined text-on-surface-variant">drag_indicator</span>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Execution History -->
            <x-ui.card title="Execution History" subtitle="Recent playbook runs">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-xs text-on-surface-variant border-b border-outline-variant/10">
                                <th class="pb-3 font-medium">Execution ID</th>
                                <th class="pb-3 font-medium">Target</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium">Duration</th>
                                <th class="pb-3 font-medium">Executed At</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @foreach($executionHistory as $execution)
                                <tr class="border-b border-outline-variant/5">
                                    <td class="py-3 font-mono text-primary">{{ $execution['execution_id'] }}</td>
                                    <td class="py-3">{{ $execution['target'] }}</td>
                                    <td class="py-3">
                                        <span class="inline-flex items-center gap-1">
                                            <x-data.status-pip :status="$execution['status'] === 'success' ? 'success' : 'error'" :pulse="false" />
                                            <span class="text-{{ $execution['status'] === 'success' ? 'success' : 'error' }}">
                                                {{ ucfirst($execution['status']) }}
                                            </span>
                                        </span>
                                    </td>
                                    <td class="py-3 text-on-surface-variant">{{ $execution['duration'] }}</td>
                                    <td class="py-3 text-on-surface-variant">{{ $execution['executed_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Info Card -->
            <x-ui.card title="Playbook Info">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Version</span>
                        <span class="text-sm font-mono text-on-surface">{{ $playbook['version'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Total Steps</span>
                        <span class="text-sm text-on-surface">{{ count($playbook['steps']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Created</span>
                        <span class="text-sm text-on-surface">{{ $playbook['created_at'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Last Updated</span>
                        <span class="text-sm text-on-surface">{{ $playbook['updated_at'] }}</span>
                    </div>
                </div>
            </x-ui.card>

            <!-- Parameters -->
            <x-ui.card title="Parameters" subtitle="Configurable parameters">
                <div class="space-y-4">
                    @foreach($playbook['parameters'] as $param)
                        <div>
                            <label class="block text-sm text-on-surface-variant mb-1">{{ ucfirst(str_replace('_', ' ', $param['name'])) }}</label>
                            @if($param['type'] === 'select')
                                <select class="w-full px-3 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-success/50">
                                    @foreach($param['options'] as $option)
                                        <option value="{{ $option }}" {{ $param['default'] === $option ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                    @endforeach
                                </select>
                            @elseif($param['type'] === 'boolean')
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" {{ $param['default'] ? 'checked' : '' }} class="w-4 h-4 rounded border-outline-variant text-primary focus:ring-success">
                                    <span class="text-sm text-on-surface">Enabled</span>
                                </label>
                            @endif
                        </div>
                    @endforeach
                </div>
            </x-ui.card>

            <!-- Quick Stats -->
            <x-ui.card title="Performance">
                <div class="space-y-4">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-on-surface-variant">Success Rate</span>
                            <span class="text-sm font-medium text-success">95%</span>
                        </div>
                        <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                            <div class="h-full bg-success rounded-full" style="width: 95%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-on-surface-variant">Avg Duration</span>
                            <span class="text-sm font-medium text-on-surface">15 min</span>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-on-surface-variant">Total Executions</span>
                            <span class="text-sm font-medium text-on-surface">24</span>
                        </div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
