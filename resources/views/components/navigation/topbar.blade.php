@props([
    'title' => null,
    'subtitle' => null,
])

<header class="sticky top-0 z-30 bg-surface-container-lowest/80 backdrop-blur-lg border-b border-outline-variant/10">
    <div class="flex items-center justify-between px-6 py-4">
        <!-- Breadcrumb / Title -->
        <div class="flex items-center gap-4">
            @if($title)
                <div>
                    <h1 class="text-headline-md text-on-surface">{{ $title }}</h1>
                    @if($subtitle)
                        <p class="text-sm text-on-surface-variant">{{ $subtitle }}</p>
                    @endif
                </div>
            @else
                <x-navigation.breadcrumb />
            @endif
        </div>

        <!-- Actions -->
        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="relative hidden md:block">
                <input
                    type="text"
                    placeholder="Search..."
                    class="w-64 px-4 py-2 pl-10 bg-surface-container border border-outline-variant/20 rounded-lg text-sm text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary/50 focus:ring-1 focus:ring-primary/50"
                >
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">
                    search
                </span>
            </div>

            <!-- Notifications -->
            <button class="relative p-2 rounded-lg hover:bg-white/5 transition-colors">
                <span class="material-symbols-outlined text-on-surface-variant">notifications</span>
                <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-error"></span>
            </button>

            <!-- Theme Toggle -->
            <button
                onclick="toggleTheme()"
                class="p-2 rounded-lg hover:bg-white/5 transition-colors"
                title="Toggle theme"
            >
                <span class="material-symbols-outlined text-on-surface-variant dark-icon">dark_mode</span>
                <span class="material-symbols-outlined text-on-surface-variant light-icon hidden">light_mode</span>
            </button>

            <!-- Quick Actions -->
            <div class="relative">
                <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/5 transition-colors">
                    <span class="material-symbols-outlined text-on-surface-variant">more_vert</span>
                </button>
            </div>
        </div>
    </div>
</header>

@push('scripts')
<script>
    function toggleTheme() {
        document.documentElement.classList.toggle('dark');
        document.documentElement.classList.toggle('light');
        localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');

        // Toggle icons
        document.querySelectorAll('.dark-icon').forEach(el => el.classList.toggle('hidden'));
        document.querySelectorAll('.light-icon').forEach(el => el.classList.toggle('hidden'));
    }

    // Initialize theme
    if (localStorage.getItem('theme') === 'light') {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        document.querySelectorAll('.dark-icon').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.light-icon').forEach(el => el.classList.remove('hidden'));
    }
</script>
@endpush
