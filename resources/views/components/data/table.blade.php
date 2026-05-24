@props([
    'columns' => [],
    'rows' => [],
    'sortable' => false,
    'sortColumn' => null,
    'sortDirection' => 'asc',
])

<div class="overflow-x-auto rounded-xl glass-card">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-outline-variant/10">
                @foreach($columns as $key => $column)
                    <th class="px-6 py-4 font-medium text-on-surface-variant text-label-sm">
                        @if($sortable && isset($column['sortable']) && $column['sortable'])
                            <button
                                onclick="sortTable('{{ $key }}')"
                                class="flex items-center gap-2 hover:text-primary transition-colors"
                            >
                                {{ $column['label'] }}
                                <span class="material-symbols-outlined text-base">
                                    {{ $sortColumn === $key
                                        ? ($sortDirection === 'asc' ? 'arrow_upward' : 'arrow_downward')
                                        : 'sort'
                                    }}
                                </span>
                            </button>
                        @else
                            {{ $column['label'] ?? $column }}
                        @endif
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr class="border-b border-outline-variant/5 hover:bg-white/5 transition-colors group">
                    @foreach($columns as $key => $column)
                        <td class="px-6 py-4 text-on-surface">
                            {{ is_array($row) ? ($row[$key] ?? '-') : ($row->$key ?? '-') }}
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-6 py-12 text-center text-on-surface-variant">
                        <span class="material-symbols-outlined text-4xl mb-2 block opacity-50">inbox</span>
                        <p>No data available</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    let currentSort = '{{ $sortColumn }}';
    let currentDirection = '{{ $sortDirection }}';

    function sortTable(column) {
        if (currentSort === column) {
            currentDirection = currentDirection === 'asc' ? 'desc' : 'asc';
        } else {
            currentSort = column;
            currentDirection = 'asc';
        }

        // Reload page with sort params
        const url = new URL(window.location);
        url.searchParams.set('sort', column);
        url.searchParams.set('direction', currentDirection);
        window.location = url.toString();
    }
</script>
@endpush
