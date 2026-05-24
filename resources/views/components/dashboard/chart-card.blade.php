@props([
    'title' => null,
    'subtitle' => null,
    'height' => '300',
])

<div class="glass-card rounded-xl p-6">
    <div class="mb-4">
        @if($title)
            <h3 class="text-headline-md text-on-surface">{{ $title }}</h3>
        @endif
        @if($subtitle)
            <p class="text-sm text-on-surface-variant mt-1">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="relative" style="height: {{ $height }}px;">
        {{ $slot }}
    </div>
</div>
