@props([
    'user' => null,
])

@php
    $navItems = [
        ['icon' => 'emergency_home', 'label' => 'Dashboard', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
        ['icon' => 'notifications', 'label' => 'Alerts', 'route' => 'alerts.index', 'active' => request()->routeIs('alerts.*')],
        ['icon' => 'security', 'label' => 'VirusTotal', 'route' => 'virustotal.index', 'active' => request()->routeIs('virustotal.*')],
        ['icon' => 'auto_fix', 'label' => 'Playbooks', 'route' => 'playbooks.index', 'active' => request()->routeIs('playbooks.*')],
        ['icon' => 'radar', 'label' => 'Threat Intel', 'route' => 'threats.index', 'active' => request()->routeIs('threats.*')],
        ['icon' => 'report', 'label' => 'Incidents', 'route' => 'incidents.index', 'active' => request()->routeIs('incidents.*')],
        ['icon' => 'people', 'label' => 'Users', 'route' => 'users.index', 'active' => request()->routeIs('users.*')],
        ['icon' => 'settings', 'label' => 'Settings', 'route' => 'settings', 'active' => request()->routeIs('settings')],
    ];
@endphp

<aside class="w-64 h-screen fixed left-0 top-0 flex flex-col bg-surface-container-lowest/90 backdrop-blur-lg border-r border-outline-variant/10 shadow-2xl z-40">
    <!-- Logo Section -->
    <div class="px-6 py-6 border-b border-outline-variant/10">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-primary/20 flex items-center justify-center rounded-lg border border-primary/30">
                <span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">
                    security
                </span>
            </div>
            <div>
                <h1 class="text-headline-md text-primary leading-tight">Command Center</h1>
                <p class="text-label-sm text-on-surface-variant">Level {{ auth()->user()->clearance_level ?? 4 }} Clearance</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 py-4 space-y-1 overflow-y-auto">
        @foreach($navItems as $item)
            <a
                href="{{ $item['route'] == '#' ? '#' : route($item['route']) }}"
                class="flex items-center px-6 py-3 transition-all duration-150 group {{
                    $item['active']
                        ? 'text-primary bg-primary/10 border-l-2 border-primary'
                        : 'text-on-surface-variant hover:bg-white/5 border-l-2 border-transparent'
                }}"
            >
                <span class="material-symbols-outlined mr-3 text-xl">
                    {{ $item['icon'] }}
                </span>
                <span class="text-label-sm">{{ $item['label'] }}</span>

                @if($item['label'] === 'Alerts' && $item['active'])
                    <span class="ml-auto w-2 h-2 rounded-full bg-success pip-pulse"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- User Section -->
    @auth
        <div class="p-4 border-t border-outline-variant/10">
            <div class="flex items-center gap-3 px-3 py-2 rounded-lg bg-white/5">
                <div class="w-8 h-8 rounded-full bg-primary/30 flex items-center justify-center">
                    <span class="text-sm font-semibold text-primary">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-on-surface truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-on-surface-variant truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-on-surface-variant hover:text-error cursor-pointer bg-transparent border-none p-0">
                        <span class="material-symbols-outlined text-lg">logout</span>
                    </button>
                </form>
            </div>
        </div>
    @endauth
</aside>

<!-- Sidebar Spacer -->
<div class="w-64 flex-shrink-0"></div>
