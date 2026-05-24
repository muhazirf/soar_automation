@props([
    'name' => null,
    'id' => null,
    'type' => 'text',
    'label' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
    'hint' => null,
])

@php
    $inputId = $id ?? $name ?? ('input-' . uniqid());
    $hasError = $error !== null;
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-on-surface">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <input
        {{ $attributes->merge([
            'id' => $inputId,
            'name' => $name,
            'type' => $type,
            'placeholder' => $placeholder,
            'value' => old($name, $value),
            'required' => $required,
            'disabled' => $disabled,
            'class' => implode(' ', [
                'w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm text-on-surface',
                'placeholder:text-on-surface-variant',
                'focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                'transition-colors duration-200',
                $hasError ? 'border-error focus:ring-error/50 focus:border-error/50' : 'border-outline-variant/20',
            ])
        ]) }}
    >

    @if($hasError)
        <p class="text-xs text-error">{{ $error }}</p>
    @elseif($hint)
        <p class="text-xs text-on-surface-variant">{{ $hint }}</p>
    @endif
</div>
