<x-layouts.dashboard title="Users">
    <!-- Stats Overview -->
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        <div class="glass-card rounded-lg p-4">
            <p class="text-xs text-on-surface-variant">Total</p>
            <p class="text-2xl font-bold text-on-surface">{{ $stats['total'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-success">
            <p class="text-xs text-on-surface-variant">Active</p>
            <p class="text-2xl font-bold text-success">{{ $stats['active'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-outline">
            <p class="text-xs text-on-surface-variant">Inactive</p>
            <p class="text-2xl font-bold text-outline">{{ $stats['inactive'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-error">
            <p class="text-xs text-on-surface-variant">Level 5</p>
            <p class="text-2xl font-bold text-error">{{ $stats['level5'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-warning">
            <p class="text-xs text-on-surface-variant">Level 4</p>
            <p class="text-2xl font-bold text-warning">{{ $stats['level4'] }}</p>
        </div>
        <div class="glass-card rounded-lg p-4 border-l-2 border-info">
            <p class="text-xs text-on-surface-variant">Level 3</p>
            <p class="text-2xl font-bold text-info">{{ $stats['level3'] }}</p>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <!-- Search -->
            <div class="flex-1 min-w-[200px]">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name or email..."
                    value="{{ $filters['search'] }}"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-success/50"
                >
            </div>

            <!-- Clearance Level -->
            <x-ui.select
                name="clearance"
                label="Clearance"
                :options="[
                    ['value' => '', 'label' => 'All Levels'],
                    ['value' => '1', 'label' => 'Level 1 - Basic'],
                    ['value' => '2', 'label' => 'Level 2 - Operator'],
                    ['value' => '3', 'label' => 'Level 3 - Analyst'],
                    ['value' => '4', 'label' => 'Level 4 - Senior'],
                    ['value' => '5', 'label' => 'Level 5 - Lead'],
                ]"
                :value="$filters['clearance']"
            />

            <!-- Status -->
            <x-ui.select
                name="status"
                label="Status"
                :options="[
                    ['value' => 'active', 'label' => 'Active'],
                    ['value' => 'inactive', 'label' => 'Inactive'],
                ]"
                :value="$filters['status']"
            />

            <!-- Submit -->
            <button type="submit" class="px-6 py-2 bg-success text-surface rounded-lg text-sm font-medium hover:bg-success/90 transition-colors">
                Apply Filters
            </button>

            @if($filters['search'] || $filters['clearance'] || $filters['status'] !== 'active')
                <a href="{{ route('users.index') }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                    Clear
                </a>
            @endif

            <!-- Add User Button (Level 4+) -->
            @if(auth()->user()->hasClearance(4))
                <a href="{{ route('users.create') }}" class="ml-auto px-6 py-2 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">add</span>
                    Add User
                </a>
            @endif
        </form>
    </x-ui.card>

    <!-- Users Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b border-outline-variant/10">
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">ID</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Name</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Email</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Clearance</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Timezone</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Status</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Last Seen</th>
                        <th class="px-4 py-3 font-medium text-on-surface-variant text-label-sm">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-outline-variant/5 hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3 font-mono text-xs text-primary">#{{ $user['id'] }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('users.show', $user['id']) }}" class="text-on-surface hover:text-primary transition-colors">
                                    {{ $user['name'] }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-on-surface-variant">{{ $user['email'] }}</td>
                            <td class="px-4 py-3">
                                <x-ui.badge :variant="$user['clearance_level'] >= 4 ? 'warning' : ($user['clearance_level'] >= 3 ? 'info' : 'primary')" :dot="true">
                                    {{ $user['clearance_name'] }}
                                </x-ui.badge>
                            </td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant">{{ $user['timezone'] ?? 'UTC' }}</td>
                            <td class="px-4 py-3">
                                @if($user['email_verified_at'])
                                    <span class="inline-flex items-center gap-1.5 text-success">
                                        <span class="material-symbols-outlined text-sm">check_circle</span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-outline">
                                        <span class="material-symbols-outlined text-sm">cancel</span>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs text-on-surface-variant">{{ $user['last_seen'] }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('users.show', $user['id']) }}" class="p-1.5 rounded hover:bg-white/10 transition-colors" title="View">
                                        <span class="material-symbols-outlined text-lg">visibility</span>
                                    </a>
                                    @if(auth()->user()->hasClearance(4) && $user['id'] !== auth()->id() && $user['clearance_level'] < auth()->user()->clearance_level)
                                        <a href="{{ route('users.edit', $user['id']) }}" class="p-1.5 rounded hover:bg-white/10 transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-4xl mb-2 block opacity-50">people_outline</span>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-ui.card>
</x-layouts.dashboard>
