@props([
    'stats' => [],
])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach($stats as $stat)
        <x-dashboard.stat-card
            :title="$stat['title']"
            :value="$stat['value']"
            :change="$stat['change']"
            :trend="$stat['trend']"
            :icon="$stat['icon']"
        />
    @endforeach
</div>
