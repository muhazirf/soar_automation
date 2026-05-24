@props([
    'id' => null,
    'title' => null,
    'show' => false,
    'size' => 'md', // sm, md, lg, xl
])

@php
    $modalId = $id ?? ('modal-' . uniqid());
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div
    x-data="{ open: {{ $show ? 'true' : 'false' }} }"
    x-show="open"
    id="{{ $modalId }}"
    class="relative z-50"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
>
    <!-- Backdrop -->
    <div
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm"
        aria-hidden="true"
        @click="open = false"
    ></div>

    <!-- Modal Panel -->
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div
                x-show="open"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative {{ $sizeClasses[$size] }} w-full transform overflow-hidden rounded-xl glass-card text-left shadow-glow"
            >
                <!-- Header -->
                @if($title)
                    <div class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/10">
                        <h3 class="text-headline-md text-on-surface" id="modal-title">
                            {{ $title }}
                        </h3>
                        <button
                            type="button"
                            @click="open = false"
                            class="text-on-surface-variant hover:text-on-surface transition-colors"
                        >
                            <span class="material-symbols-outlined">close</span>
                        </button>
                    </div>
                @endif

                <!-- Content -->
                <div class="px-6 py-4 @if(!$title) pt-6 @endif">
                    {{ $slot }}
                </div>

                <!-- Footer (if actions are provided) -->
                @if(isset($actions))
                    <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-outline-variant/10">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.__x__?.setData({ open: true });
        }
    }

    function closeModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.__x__?.setData({ open: false });
        }
    }
</script>
@endpush
