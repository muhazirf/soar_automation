<x-layouts.dashboard title="Scan {{ $scan['id'] }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('virustotal.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to VirusTotal
        </a>
    </div>

    <!-- Scan Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <x-ui.badge :variant="$scan['result']" :dot="true" size="lg">
                        {{ ucfirst($scan['result']) }}
                    </x-ui.badge>
                    <span class="text-xs font-mono text-on-surface-variant">{{ $scan['id'] }}</span>
                </div>
                <h1 class="text-2xl font-bold text-on-surface mb-2 font-mono">{{ $scan['target'] }}</h1>
                <p class="text-on-surface-variant">{{ $scan['detection_ratio']}} detections</p>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.button variant="secondary" size="md">
                    <span class="material-symbols-outlined">refresh</span>
                    Rescan
                </x-ui.button>
                <x-ui.button variant="primary" size="md">
                    <span class="material-symbols-outlined">download</span>
                    Report
                </x-ui.button>
            </div>
        </div>
    </div>

    <!-- Detection Ratio Banner -->
    <div class="glass-card rounded-xl p-6 mb-6 @if($scan['malicious'] > 0) bg-error/10 border-error/30 @elseif($scan['suspicious'] > 0) bg-warning/10 border-warning/30 @else bg-success/10 border-success/30 @endif border">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-6">
                <div class="text-center">
                    <p class="text-3xl font-bold text-error">{{ $scan['malicious'] }}</p>
                    <p class="text-xs text-on-surface-variant">Malicious</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-warning">{{ $scan['suspicious'] }}</p>
                    <p class="text-xs text-on-surface-variant">Suspicious</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-success">{{ $scan['harmless'] }}</p>
                    <p class="text-xs text-on-surface-variant">Harmless</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl font-bold text-outline">{{ $scan['timeout'] }}</p>
                    <p class="text-xs text-on-surface-variant">Timeout</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-on-surface-variant">Total Scans</p>
                <p class="text-2xl font-bold text-on-surface">{{ $scan['malicious'] + $scan['harmless'] + $scan['timeout'] }}</p>
            </div>
        </div>
    </div>

    <!-- Scan Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Details (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- File Hashes -->
            <x-ui.card title="File Hashes">
                <dl class="space-y-3">
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">MD5</dt>
                        <dd class="font-mono text-sm bg-surface-container p-2 rounded break-all">{{ $scan['md5'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">SHA1</dt>
                        <dd class="font-mono text-sm bg-surface-container p-2 rounded break-all">{{ $scan['sha1'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">SHA256</dt>
                        <dd class="font-mono text-sm bg-surface-container p-2 rounded break-all">{{ $scan['sha256'] }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            <!-- File Information -->
            <x-ui.card title="File Information">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">File Type</dt>
                        <dd class="text-sm">{{ $scan['file_type'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">File Size</dt>
                        <dd class="text-sm">{{ $scan['size'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">First Seen</dt>
                        <dd class="text-sm">{{ $scan['first_seen'] }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Last Seen</dt>
                        <dd class="text-sm">{{ $scan['last_seen'] }}</dd>
                    </div>
                    <div class="col-span-2">
                        <dt class="text-xs text-on-surface-variant mb-1">Submission Count</dt>
                        <dd class="text-sm">{{ $scan['submission_count'] }} times</dd>
                    </div>
                </dl>
            </x-ui.card>

            <!-- Vendor Results -->
            <x-ui.card title="Vendor Detection Results">
                <div class="overflow-x-auto max-h-96 overflow-y-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="sticky top-0 bg-surface-container-lowest">
                            <tr class="border-b border-outline-variant/10">
                                <th class="px-3 py-2 font-medium text-on-surface-variant text-label-sm">Vendor</th>
                                <th class="px-3 py-2 font-medium text-on-surface-variant text-label-sm">Result</th>
                                <th class="px-3 py-2 font-medium text-on-surface-variant text-label-sm">Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendorResults as $vendor)
                                <tr class="border-b border-outline-variant/5 hover:bg-white/5 transition-colors">
                                    <td class="px-3 py-2 font-medium text-on-surface">{{ $vendor['name'] }}</td>
                                    <td class="px-3 py-2 font-mono text-xs @if($vendor['category'] === 'malicious') text-error @elseif($vendor['category'] === 'suspicious') text-warning @elseif($vendor['category'] === 'clean') text-success @endif">{{ $vendor['result'] }}</td>
                                    <td class="px-3 py-2">
                                        <x-ui.badge :variant="$vendor['category']" size="sm">
                                            {{ ucfirst($vendor['category']) }}
                                        </x-ui.badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="space-y-3">
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-primary">add_alert</span>
                        <span class="text-sm">Create Alert</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-info">link</span>
                        <span class="text-sm">Link to Incident</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-success">check_circle</span>
                        <span class="text-sm">Mark as Clean</span>
                    </button>
                    <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                        <span class="material-symbols-outlined text-error">block</span>
                        <span class="text-sm">Add to Blocklist</span>
                    </button>
                </div>
            </x-ui.card>

            <!-- Related Scans -->
            @if(count($relatedScans) > 0)
                <x-ui.card title="Related Scans">
                    <div class="space-y-3">
                        @foreach($relatedScans as $related)
                            <a href="{{ route('virustotal.show', $related['id']) }}" class="block p-3 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                                <p class="text-sm text-on-surface mb-1 font-mono truncate">{{ $related['target'] }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $related['detection_ratio'] }} · {{ $related['scan_date'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Tags -->
            @if(isset($scan['tags']) && count($scan['tags']) > 0)
                <x-ui.card title="Tags">
                    <div class="flex flex-wrap gap-2">
                        @foreach($scan['tags'] as $tag)
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
