<x-layouts.dashboard title="Create Incident">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('incidents.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Incidents
        </a>
    </div>

    <!-- Form -->
    <div class="max-w-3xl">
        <x-ui.card>
            <form method="POST" action="{{ route('incidents.store') }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <x-ui.input
                    name="title"
                    type="text"
                    label="Incident Title"
                    placeholder="Brief description of the incident"
                    required="true"
                    :error="$errors->first('title')"
                />

                <!-- Type & Severity Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.select
                        name="type"
                        label="Incident Type"
                        :options="[
                            ['value' => '', 'label' => 'Select type'],
                            ['value' => 'Malware', 'label' => 'Malware'],
                            ['value' => 'Phishing', 'label' => 'Phishing'],
                            ['value' => 'DDoS', 'label' => 'DDoS'],
                            ['value' => 'Insider Threat', 'label' => 'Insider Threat'],
                            ['value' => 'Data Breach', 'label' => 'Data Breach'],
                            ['value' => 'Ransomware', 'label' => 'Ransomware'],
                            ['value' => 'Other', 'label' => 'Other'],
                        ]"
                        required="true"
                        :error="$errors->first('type')"
                    />

                    <x-ui.select
                        name="severity"
                        label="Severity Level"
                        :options="[
                            ['value' => '', 'label' => 'Select severity'],
                            ['value' => 'low', 'label' => 'Low'],
                            ['value' => 'medium', 'label' => 'Medium'],
                            ['value' => 'high', 'label' => 'High'],
                            ['value' => 'critical', 'label' => 'Critical'],
                        ]"
                        required="true"
                        :error="$errors->first('severity')"
                    />
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-on-surface mb-1">
                        Description <span class="text-error">*</span>
                    </label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Detailed description of the incident..."
                        required
                        class="w-full px-4 py-3 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50 resize-none"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-error mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Affected Assets -->
                <div>
                    <label for="affected_assets" class="block text-sm font-medium text-on-surface mb-1">
                        Affected Assets
                    </label>
                    <input
                        type="text"
                        id="affected_assets"
                        name="affected_assets[]"
                        placeholder="Enter hostname or IP (press Enter to add multiple)"
                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                    >
                    <p class="text-xs text-on-surface-variant mt-1">Press Enter or comma to add multiple assets</p>
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

                <!-- Submit -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant/10">
                    <a href="{{ route('incidents.index') }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                        Cancel
                    </a>
                    <x-ui.button type="submit" variant="primary" size="md">
                        <span class="material-symbols-outlined">add</span>
                        Create Incident
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</x-layouts.dashboard>
