<x-layouts.dashboard title="Edit User {{ $user->name }}">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <a href="{{ route('users.show', $user->id) }}" class="inline-flex items-center gap-2 text-sm text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Back to User
        </a>
    </div>

    <!-- Edit User Form -->
    <x-ui.card title="Edit User">
        <form method="POST" action="{{ route('users.update', $user->id) }}" class="max-w-2xl">
            @method('PUT')
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-on-surface mb-1">Full Name</label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
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
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
                @error('email')
                    <p class="mt-1 text-sm text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Clearance Level -->
            @if(auth()->user()->hasClearance(4) && $user->id !== auth()->id() && $user->clearance_level < auth()->user()->clearance_level)
                <div class="mb-4">
                    <label for="clearance_level" class="block text-sm font-medium text-on-surface mb-1">Clearance Level</label>
                    <select
                        name="clearance_level"
                        id="clearance_level"
                        required
                        class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50"
                    >
                        <option value="1" {{ $user->clearance_level === 1 ? 'selected' : '' }}>Level 1 - Basic</option>
                        <option value="2" {{ $user->clearance_level === 2 ? 'selected' : '' }}>Level 2 - Operator</option>
                        <option value="3" {{ $user->clearance_level === 3 ? 'selected' : '' }}>Level 3 - Analyst</option>
                        <option value="4" {{ $user->clearance_level === 4 ? 'selected' : '' }}>Level 4 - Senior</option>
                    </select>
                    <p class="mt-1 text-xs text-on-surface-variant">Cannot assign clearance level equal to or higher than your own (Level {{ auth()->user()->clearance_level }})</p>
                    @error('clearance_level')
                        <p class="mt-1 text-sm text-error">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <input type="hidden" name="clearance_level" value="{{ $user->clearance_level }}">
            @endif

            <!-- Timezone -->
            <div class="mb-6">
                <label for="timezone" class="block text-sm font-medium text-on-surface mb-1">Timezone</label>
                <select
                    name="timezone"
                    id="timezone"
                    class="w-full px-4 py-2 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50"
                >
                    <option value="UTC" {{ $user->timezone === 'UTC' ? 'selected' : '' }}>UTC</option>
                    <option value="America/New_York" {{ $user->timezone === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                    <option value="America/Chicago" {{ $user->timezone === 'America/Chicago' ? 'selected' : '' }}>America/Chicago (CST)</option>
                    <option value="America/Denver" {{ $user->timezone === 'America/Denver' ? 'selected' : '' }}>America/Denver (MST)</option>
                    <option value="America/Los_Angeles" {{ $user->timezone === 'America/Los_Angeles' ? 'selected' : '' }}>America/Los_Angeles (PST)</option>
                    <option value="Europe/London" {{ $user->timezone === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                    <option value="Europe/Paris" {{ $user->timezone === 'Europe/Paris' ? 'selected' : '' }}>Europe/Paris (CET)</option>
                    <option value="Europe/Berlin" {{ $user->timezone === 'Europe/Berlin' ? 'selected' : '' }}>Europe/Berlin (CET)</option>
                    <option value="Asia/Dubai" {{ $user->timezone === 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                    <option value="Asia/Kolkata" {{ $user->timezone === 'Asia/Kolkata' ? 'selected' : '' }}>Asia/Kolkata (IST)</option>
                    <option value="Asia/Bangkok" {{ $user->timezone === 'Asia/Bangkok' ? 'selected' : '' }}>Asia/Bangkok (ICT)</option>
                    <option value="Asia/Jakarta" {{ $user->timezone === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB)</option>
                    <option value="Asia/Tokyo" {{ $user->timezone === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                    <option value="Australia/Sydney" {{ $user->timezone === 'Australia/Sydney' ? 'selected' : '' }}>Australia/Sydney (AEDT)</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t border-outline-variant/10">
                @if(auth()->user()->hasClearance(4) && $user->id !== auth()->id() && $user->clearance_level < auth()->user()->clearance_level)
                    <button type="button" class="px-4 py-2 text-sm text-error hover:text-error/80 transition-colors">
                        Delete User
                    </button>
                @else
                    <div></div>
                @endif

                <div class="flex gap-3">
                    <a href="{{ route('users.show', $user->id) }}" class="px-6 py-2 text-sm text-on-surface-variant hover:text-on-surface transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-primary text-surface rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </x-ui.card>
</x-layouts.dashboard>
