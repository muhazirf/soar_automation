<x-layouts.dashboard title="Edit Incident {{ $incident['id'] }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('incidents.show', $incident['id']) }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Incident
        </a>
    </div>

    <!-- Form -->
    <div class="max-w-3xl">
        <x-ui.card>
            <form method="POST" action="{{ route('incidents.update', $incident['id']) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <x-ui.input
                    name="title"
                    type="text"
                    label="Incident Title"
                    :value="$incident['title']"
                    :error="$errors->first('title')"
                />

                <!-- Type & Severity Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.select
                        name="type"
                        label="Incident Type"
                        :options="[
                            ['value' => 'Malware', 'label' => 'Malware'],
                            ['value' => 'Phishing', 'label' => 'Phishing'],
                            ['value' => 'DDoS', 'label' => 'DDoS'],
                            ['value' => 'Insider Threat', 'label' => 'Insider Threat'],
                            ['value' => 'Data Breach', 'label' => 'Data Breach'],
                            ['value' => 'Ransomware', 'label' => 'Ransomware'],
                            ['value' => 'Other', 'label' => 'Other'],
                        ]"
                        :value="$incident['type']"
                    />

                    <x-ui.select
                        name="severity"
                        label="Severity Level"
                        :options="[
                            ['value' => 'low', 'label' => 'Low'],
                            ['value' => 'medium', 'label' => 'Medium'],
                            ['value' => 'high', 'label' => 'High'],
                            ['value' => 'critical', 'label' => 'Critical'],
                        ]"
                        :value="$incident['severity']"
                    />
                </div>

                <!-- Status -->
                <x-ui.select
                    name="status"
                    label="Status"
                    :options="[
                        ['value' => 'open', 'label' => 'Open'],
                        ['value' => 'investigating', 'label' => 'Investigating'],
                        ['value' => 'contained', 'label' => 'Contained'],
                        ['value' => 'eradicated', 'label' => 'Eradicated'],
                        ['value' => 'resolved', 'label' => 'Resolved'],
                        ['value' => 'closed', 'label' => 'Closed'],
                    ]"
                    :value="$incident['status']"
                />

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-on-surface mb-1">
                        Description
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        class="w-full px-4 py-3 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50 resize-none"
                    >{{ old('description', $incident['description']) }}</textarea>
                </div>

                <!-- Assigned To -->
                <x-ui.select
                    name="assigned_to"
                    label="Assign To"
                    :options="[
                        ['value' => '', 'label' => 'Unassigned'],
                        ['value' => '1', 'label' => 'Agent Smith'],
                        ['value' => '2', 'label' => 'Agent Johnson'],
                        ['value' => '3', 'label' => 'Agent Williams'],
                    ]"
                />

                <!-- Resolution (if resolved or closed) -->
                @if(in_array($incident['status'], ['resolved', 'closed']))
                    <div>
                        <label for="resolution" class="block text-sm font-medium text-on-surface mb-1">
                            Resolution Notes
                        </label>
                        <textarea
                            id="resolution"
                            name="resolution"
                            rows="4"
                            placeholder="Describe how the incident was resolved..."
                            class="w-full px-4 py-3 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50 resize-none"
                        >{{ old('resolution', $incident['resolution']) }}</textarea>
                    </div>
                @endif

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-outline-variant/10">
                    <button type="submit" name="delete" value="1" formaction="{{ route('incidents.destroy', $incident['id']) }}" formmethod="POST" class="px-4 py-2 text-sm text-error hover:text-error/80 transition-colors" onclick="return confirm('Are you sure you want to delete this incident?')">
                        Delete Incident
                    </button>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('incidents.show', $incident['id']) }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                            Cancel
                        </a>
                        <x-ui.button type="submit" variant="primary" size="md">
                            <span class="material-symbols-outlined">save</span>
                            Save Changes
                        </x-ui.button>
                    </div>
                </div>
            </form>
        </x-ui.card>

        <!-- Linked Alerts -->
        @if(isset($linkedAlerts) && count($linkedAlerts) > 0)
            <x-ui.card title="Linked Alerts" class="mt-6">
                <div class="space-y-3">
                    @foreach($linkedAlerts as $alert)
                        <a href="{{ route('alerts.show', $alert['id']) }}" class="flex items-center justify-between p-3 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                            <div>
                                <p class="text-sm text-on-surface">{{ $alert['title'] }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $alert['id'] }}</p>
                            </div>
                            <x-ui.badge :variant="$alert['severity']" size="sm">{{ ucfirst($alert['severity']) }}</x-ui.badge>
                        </a>
                    @endforeach
                </div>
            </x-ui.card>
        @endif
    </div>
</x-layouts.dashboard>
