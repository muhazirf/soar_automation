@props([
    'paginator' => null,
])

@if($paginator && $paginator->hasPages())
    <nav class="flex items-center justify-between mt-6">
        <!-- Previous Button -->
        <div>
            @if($paginator->onFirstPage())
                <span class="inline-flex items-center gap-2 px-4 py-2 text-sm text-on-surface-variant cursor-not-allowed opacity-50">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                    Previous
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-on-surface hover:text-primary hover:bg-white/5 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">chevron_left</span>
                    Previous
                </a>
            @endif
        </div>

        <!-- Page Numbers -->
        <div class="flex items-center gap-2">
            @foreach($elements = $paginator->getElements() as $element)
                @if(is_string($element))
                    <span class="px-3 py-2 text-sm text-on-surface-variant">{{ $element }}</span>
                @elseif($element === $paginator->currentPage())
                    <span class="px-4 py-2 text-sm font-medium rounded-lg bg-primary text-on-primary">
                        {{ $element }}
                    </span>
                @else
                    <a href="{{ $paginator->url($element) }}"
                       class="px-4 py-2 text-sm text-on-surface hover:text-primary hover:bg-white/5 rounded-lg transition-colors">
                        {{ $element }}
                    </a>
                @endif
            @endforeach
        </div>

        <!-- Next Button -->
        <div>
            @if($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-sm text-on-surface hover:text-primary hover:bg-white/5 rounded-lg transition-colors">
                    Next
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </a>
            @else
                <span class="inline-flex items-center gap-2 px-4 py-2 text-sm text-on-surface-variant cursor-not-allowed opacity-50">
                    Next
                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                </span>
            @endif
        </div>
    </nav>

    <!-- Info -->
    <p class="mt-3 text-sm text-on-surface-variant text-center">
        Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }}
        of {{ $paginator->total() }} results
    </p>
@endif
