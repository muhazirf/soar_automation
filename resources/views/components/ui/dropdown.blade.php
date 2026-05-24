@props([
    'trigger' => null,
    'position' => 'bottom-right', // bottom-left, bottom-right, top-left, top-right
])

@php
    $positionClasses = [
        'bottom-left' => 'left-0 top-full mt-1',
        'bottom-right' => 'right-0 top-full mt-1',
        'top-left' => 'left-0 bottom-full mb-1',
        'top-right' => 'right-0 bottom-full mb-1',
    ];
@endphp

<div x-data="{ open: false }" class="relative">
    <!-- Trigger -->
    <div @click="open = !open" @click.away="open = false">
        {{ $trigger }}
    </div>

    <!-- Dropdown Menu -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $positionClasses[$position] }} z-50 min-w-[200px] rounded-lg glass-card shadow-glow py-1"
        @click.away="open = false"
    >
        {{ $slot }}
    </div>
</div>
