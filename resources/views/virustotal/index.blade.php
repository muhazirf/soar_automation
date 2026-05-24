<x-layouts.dashboard title="VirusTotal Analysis">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Total</p>
            <p class="text-2xl font-bold text-on-surface">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-error">
            <p class="text-xs text-on-surface-variant">Malicious</p>
            <p class="text-2xl font-bold text-error">{{ $stats['malicious'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-warning">
            <p class="text-xs text-on-surface-variant">Suspicious</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['suspicious'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-success">
            <p class="text-xs text-on-surface-variant">Clean</p>
            <p class="text-2xl font-bold text-success">{{ $stats['clean'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-outline">
            <p class="text-xs text-on-surface-variant">Undetected</p>
            <p class="text-2xl font-bold text-outline">{{ $stats['undetected'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-primary">
            <p class="text-xs text-on-surface-variant">Today</p>
            <p class="text-2xl font-bold text-primary">{{ $stats['today_scans'] }}</p>
        </div>
    </div>

    <!-- Scan Form & Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by target, ID, or hash..."
                    value="{{ $filters['search'] }}"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                >
            </div>

            <!-- Type -->
            <x-ui.select
                name="type"
                label="Type"
                :options="[
                    ['value' => 'all', 'label' => 'All Types'],
                    ['value' => 'file', 'label' => 'File'],
                    ['value' => 'url', 'label' => 'URL'],
                    ['value' => 'ip', 'label' => 'IP Address'],
                    ['value' => 'domain', 'label' => 'Domain'],
                    ['value' => 'hash', 'label' => 'Hash'],
                ]"
                :value="$filters['type']"
            />

            <!-- Result -->
            <x-ui.select
                name="result"
                label="Result"
                :options="[
                    ['value' => '', 'label' => 'All Results'],
                    ['value' => 'malicious', 'label' => 'Malicious'],
                    ['value' => 'suspicious', 'label' => 'Suspicious'],
                    ['value' => 'clean', 'label' => 'Clean'],
                    ['value' => 'undetected', 'label' => 'Undetected'],
                ]"
                :value="$filters['result']"
            />

            <!-- Submit -->
            <button type="submit" class="px-6 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
                Apply Filters
            </button>

            @if($filters['type'] !== 'all' || $filters['result'] || $filters['search'])
                <a href="{{ route('virustotal.index') }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                    Clear
                </a>
            @endif

            <!-- New Scan Button -->
            <button type="button" x-data="{ open: false }" @click="open = true" class="ml-auto px-6 py-2 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">add</span>
                New Scan
            </button>
        </form>
    </x-ui.card>

    <!-- Scans Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/10">
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">ID</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Type</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Target</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Detection</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">File Type</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Size</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Scan Date</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scans as $scan)
                        <tr class="border-b border-outline-variant/5 hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-primary">{{ $scan['id'] }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$scan['type'] === 'file' ? 'info' : 'primary'" :dot="true">
                                    {{ ucfirst($scan['type']) }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('virustotal.show', $scan['id']) }}" class="text-on-surface hover:text-primary transition-colors">
                                    <span class="font-mono text-xs">{{ Str::limit($scan['target'], 40) }}</span>
                                </a>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="flex items-center gap-1">
                                        @if($scan['malicious'] > 0)
                                            <span class="text-xs text-error">{{ $scan['malicious'] }}</span>
                                        @endif
                                        @if($scan['suspicious'] > 0)
                                            <span class="text-xs text-warning">{{ $scan['suspicious'] }}</span>
                                        @endif
                                        @if($scan['harmless'] > 0)
                                            <span class="text-xs text-success">{{ $scan['harmless'] }}</span>
                                        @endif
                                        @if($scan['timeout'] > 0)
                                            <span class="text-xs text-outline">{{ $scan['timeout'] }}</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-on-surface-variant">/ {{ $scan['malicious'] + $scan['harmless'] + $scan['timeout'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant max-w-xs truncate">{{ $scan['file_type'] }}</td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant">{{ $scan['size'] }}</td>
                            <td class="px-4 py-3 text-on-surface-variant text-xs">{{ $scan['scan_date'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <button class="p-1.5 rounded hover:bg-white/10 transition-colors" title="View Details">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </button>
                                    <button class="p-1.5 rounded hover:bg-white/10 transition-colors" title="Rescan">
                                        <span class="material-symbols-outlined text-lg">refresh</span>
                                    </button>
                                    <button class="p-1.5 rounded hover:bg-white/10 transition-colors" title="Download Report">
                                        <span class="material-symbols-outlined text-lg">download</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2 block opacity-50">search_off</span>
                                <p>No scan results found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>

    <!-- New Scan Modal -->
    <x-ui.modal id="new-scan-modal" title="Submit New Scan">
        <form method="POST" action="{{ route('virustotal.scan') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1">Type</label>
                <select name="type" required class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50">
                    <option value="file">File</option>
                    <option value="url">URL</option>
                    <option value="ip">IP Address</option>
                    <option value="domain">Domain</option>
                    <option value="hash">File Hash</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1">Target</label>
                <input
                    type="text"
                    name="target"
                    required
                    placeholder="Enter file path, URL, IP, domain, or hash..."
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
            </div>
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="document.getElementById('new-scan-modal').closest('[x-data]').remove()" class="px-4 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                    Submit Scan
                </button>
            </div>
        </form>
    </x-ui.modal>
</x-layouts.dashboard>
