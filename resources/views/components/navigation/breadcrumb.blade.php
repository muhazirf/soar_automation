@props([
    'items' => null,
])

@php
    if (!$items) {
        $segments = request()->segments();
        $items = [];
        $url = '';

        foreach ($segments as $segment) {
            $url .= '/' . $segment;
            $items[] = [
                'label' => ucfirst(str_replace(['-', '_'], ' ', $segment)),
                'url' => $url,
            ];
        }
    }
@endphp

@if(count($items) > 0)
    <nav class="flex items-center gap-2 text-sm">
        <a href="/" class="text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-lg">home</span>
        </a>

        <span class="text-on-surface-variant">/</span>

        @foreach($items as $index => $item)
            @if($index === count($items) - 1)
                <span class="text-primary font-medium">{{ $item['label'] }}</span>
            @else
                <a href="{{ $item['url'] }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    {{ $item['label'] }}
                </a>
                <span class="text-on-surface-variant">/</span>
            @endif
        @endforeach
    </nav>
@endif
