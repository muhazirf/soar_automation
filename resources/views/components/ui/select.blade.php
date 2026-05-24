@props([
    'name' => null,
    'id' => null,
    'label' => null,
    'options' => [],
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'disabled' => false,
    'error' => null,
])

@php
    $selectId = $id ?? $name ?? ('select-' . uniqid());
    $hasError = $error !== null;
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-on-surface">
            {{ $label }}
            @if($required)
                <span class="text-error">*</span>
            @endif
        </label>
    @endif

    <select
        {{ $attributes->merge([
            'id' => $selectId,
            'name' => $name,
            'required' => $required,
            'disabled' => $disabled,
            'class' => implode(' ', [
                'w-full px-4 py-2.5 bg-surface-container border rounded-lg text-sm text-on-surface',
                'focus:outline-none focus:ring-2 focus:ring-success/50 focus:border-success/50',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                'transition-colors duration-200',
                $hasError ? 'border-error focus:ring-error/50 focus:border-error/50' : 'border-outline-variant/20',
            ])
        ]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $key => $option)
            @if(is_array($option))
                <option value="{{ $option['value'] }}" @if(old($name, $value) == $option['value']) selected @endif>
                    {{ $option['label'] }}
                </option>
            @else
                <option value="{{ $key }}" @if(old($name, $value) == $key) selected @endif>
                    {{ $option }}
                </option>
            @endif
        @endforeach
    </select>

    @if($hasError)
        <p class="text-xs text-error">{{ $error }}</p>
    @endif
</div>
