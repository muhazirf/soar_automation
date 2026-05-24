<x-layouts.dashboard title="Add User">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to Users
        </a>
    </div>

    <!-- Create User Form -->
    <x-ui.card title="Create New User">
        <form method="POST" action="{{ route('users.store') }}" class="max-w-2xl">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-on-surface mb-1">Full Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                    autofocus
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                    placeholder="John Doe"
                >
                @error('name')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-on-surface mb-1">Email Address</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                    placeholder="john.doe@example.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-on-surface mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                    placeholder="••••••••"
                >
                @error('password')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-on-surface mb-1">Confirm Password</label>
                <input
                    type="password"
                    name="password_confirmation"
                    id="password_confirmation"
                    required
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                    placeholder="••••••••"
                >
            </div>

            <!-- Clearance Level -->
            <div class="mb-4">
                <label for="clearance_level" class="block text-sm font-medium text-on-surface mb-1">Clearance Level</label>
                <select
                    name="clearance_level"
                    id="clearance_level"
                    required
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
                    <option value="1">Level 1 - Basic</option>
                    <option value="2">Level 2 - Operator</option>
                    <option value="3">Level 3 - Analyst</option>
                    <option value="4">Level 4 - Senior</option>
                </select>
                <p class="mt-1 text-xs text-on-surface-variant">Cannot assign Level 5 (Lead) clearance</p>
                @error('clearance_level')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Timezone -->
            <div class="mb-6">
                <label for="timezone" class="block text-sm font-medium text-on-surface mb-1">Timezone</label>
                <select
                    name="timezone"
                    id="timezone"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
                    <option value="UTC">UTC</option>
                    <option value="America/New_York">America/New_York (EST)</option>
                    <option value="America/Chicago">America/Chicago (CST)</option>
                    <option value="America/Denver">America/Denver (MST)</option>
                    <option value="America/Los_Angeles">America/Los_Angeles (PST)</option>
                    <option value="Europe/London">Europe/London (GMT)</option>
                    <option value="Europe/Paris">Europe/Paris (CET)</option>
                    <option value="Europe/Berlin">Europe/Berlin (CET)</option>
                    <option value="Asia/Dubai">Asia/Dubai (GST)</option>
                    <option value="Asia/Kolkata">Asia/Kolkata (IST)</option>
                    <option value="Asia/Bangkok">Asia/Bangkok (ICT)</option>
                    <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
                    <option value="Asia/Tokyo">Asia/Tokyo (JST)</option>
                    <option value="Australia/Sydney">Australia/Sydney (AEDT)</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant/10">
                <a href="{{ route('users.index') }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                    Create User
                </button>
            </div>
        </form>
    </x-ui.card>
</x-layouts.dashboard>
