@props([
    'data' => [],
    'color' => '#64ffda',
    'width' => 100,
    'height' => 30,
])

@php
    $min = min($data);
    $max = max($data);
    $range = $max - $min ?: 1;

    $points = [];
    foreach ($data as $i => $value) {
        $x = ($i / (count($data) - 1)) * $width;
        $y = $height - (($value - $min) / $range) * $height;
        $points[] = "{$x},{$y}";
    }
    $pointsString = implode(' ', $points);
@endphp

<div class="inline-flex items-center" style="width: {{ $width }}px; height: {{ $height }}px;">
    <svg width="{{ $width }}" height="{{ $height }}" viewBox="0 0 {{ $width }} {{ $height }}" class="overflow-visible">
        <!-- Fill Area -->
        <polygon
            points="0,{{ $height }} {{ $pointsString }} {{ $width }},{{ $height }}"
            fill="{{ $color }}"
            fill-opacity="0.2"
        />

        <!-- Line -->
        <polyline
            points="{{ $pointsString }}"
            fill="none"
            stroke="{{ $color }}"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
        />

        <!-- Dots -->
        @foreach($data as $i => $value)
            <circle
                cx="{{ ($i / (count($data) - 1)) * $width }}"
                cy="{{ $height - (($value - $min) / $range) * $height }}"
                r="2"
                fill="{{ $color }}"
            />
        @endforeach
    </svg>
</div>
