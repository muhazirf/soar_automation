<x-layouts.dashboard title="User {{ $user->name }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Users
        </a>
    </div>

    <!-- User Header -->
    <div class="glass-card rounded-xl p-6 mb-6">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-primary/30 flex items-center justify-center">
                    <span class="text-2xl font-bold text-primary">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-on-surface mb-1">{{ $user->name }}</h1>
                    <p class="text-on-surface-variant">{{ $user->email }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <x-ui.badge :variant="$user->clearance_level >= 4 ? 'warning' : ($user->clearance_level >= 3 ? 'info' : 'primary')" size="lg">
                    {{ $user->clearance_name }}
                </x-ui.badge>

                @if(auth()->user()->hasClearance(4) && $user->id !== auth()->id() && $user->clearance_level < auth()->user()->clearance_level)
                    <x-ui.button variant="secondary" size="md">
                        <span class="material-symbols-outlined">edit</span>
                        Edit
                    </x-ui.button>
                @endif
            </div>
        </div>
    </div>

    <!-- User Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Main Details (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Details Card -->
            <x-ui.card title="User Details">
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Name</dt>
                        <dd>{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Clearance Level</dt>
                        <dd><x-ui.badge :variant="$user->clearance_level >= 4 ? 'warning' : 'info'">{{ $user->clearance_name }}</x-ui.badge></dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Timezone</dt>
                        <dd>{{ $user->timezone ?? 'UTC' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Email Verified</dt>
                        <dd>
                            @if($user->email_verified_at)
                                <span class="text-success">{{ $user->email_verified_at->format('M d, Y') }}</span>
                            @else
                                <span class="text-outline">Not verified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-on-surface-variant mb-1">Member Since</dt>
                        <dd>{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </x-ui.card>

            <!-- Activity Log -->
            <x-ui.card title="Recent Activity">
                <div class="space-y-4">
                    @foreach($activity as $i => $item)
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                @if($i < count($activity) - 1)
                                    <div class="w-0.5 flex-1 bg-outline-variant/20 mt-1"></div>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm text-on-surface">{{ $item['action'] }}</p>
                                <p class="text-xs text-on-surface-variant mt-1">{{ $item['time'] }} · {{ $item['ip'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-ui.card>
        </div>

        <!-- Sidebar (1/3) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            @if(auth()->user()->hasClearance(4) && $user->id !== auth()->id())
                <x-ui.card title="Quick Actions">
                    <div class="space-y-3">
                        @if($user->clearance_level < auth()->user()->clearance_level)
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                                <span class="material-symbols-outlined text-info">edit</span>
                                <span class="text-sm">Edit User</span>
                            </button>
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                                <span class="material-symbols-outlined text-warning">vpn_key</span>
                                <span class="text-sm">Reset Password</span>
                            </button>
                            @if($user->clearance_level < 5)
                                <button class="w-full flex items-center gap-3 px-4 py-3 bg-surface-container hover:bg-surface-container-high rounded-lg text-left transition-colors">
                                    <span class="material-symbols-outlined text-success">trending_up</span>
                                    <span class="text-sm">Elevate Clearance</span>
                                </button>
                            @endif
                        @endif
                        @if($user->clearance_level < auth()->user()->clearance_level)
                            <button class="w-full flex items-center gap-3 px-4 py-3 bg-error/10 hover:bg-error/20 rounded-lg text-left transition-colors text-error">
                                <span class="material-symbols-outlined">delete</span>
                                <span class="text-sm">Delete User</span>
                            </button>
                        @endif
                    </div>
                </x-ui.card>
            @endif

            <!-- Assigned Incidents -->
            @if(count($incidents) > 0)
                <x-ui.card title="Assigned Incidents">
                    <div class="space-y-3">
                        @foreach($incidents as $incident)
                            <a href="{{ route('incidents.show', $incident['id']) }}" class="block p-3 bg-surface-container hover:bg-surface-container-high rounded-lg transition-colors">
                                <p class="text-sm text-on-surface mb-1">{{ $incident['title'] }}</p>
                                <p class="text-xs text-on-surface-variant">{{ $incident['id'] }} · {{ $incident['status'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </x-ui.card>
            @endif

            <!-- Security Info -->
            <x-ui.card title="Security">
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">2FA Enabled</span>
                        <span class="{{ $user->hasTwoFactorAuth() ? 'text-success' : 'text-outline' }}">
                            {{ $user->hasTwoFactorAuth() ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Admin Access</span>
                        <span class="{{ $user->isAdmin() ? 'text-error' : 'text-outline' }}">
                            {{ $user->isAdmin() ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-on-surface-variant">Can Create Users</span>
                        <span class="{{ $user->hasClearance(4) ? 'text-warning' : 'text-outline' }}">
                            {{ $user->hasClearance(4) ? 'Yes' : 'No' }}
                        </span>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>
