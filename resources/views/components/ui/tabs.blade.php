@props([
    'tabs' => [],
    'active' => null,
])

@php
    $activeTab = $active ?? (count($tabs) > 0 ? array_key_first($tabs) : null);
@endphp

<div x-data="{ activeTab: '{{ $activeTab }}' }">
    <!-- Tab Headers -->
    <div class="flex items-center gap-1 border-b border-outline-variant/10">
        @foreach($tabs as $key => $label)
            <button
                @click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}'
                    ? 'text-primary border-b-2 border-primary'
                    : 'text-on-surface-variant hover:text-on-surface hover:bg-white/5'"
                class="px-4 py-3 text-sm font-medium transition-all duration-200 border-b-2 border-transparent"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Tab Content -->
    <div class="mt-4">
        @foreach($tabs as $key => $label)
            <div x-show="activeTab === '{{ $key }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                {{ isset(${"content_{$key}"}) ? ${"content_{$key}"} : '' }}
            </div>
        @endforeach
    </div>

    <!-- Slot-based content -->
    {{ $slots ?? '' }}
</div>
