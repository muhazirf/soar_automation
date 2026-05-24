<x-layouts.dashboard title="Dashboard">
    <!-- Time Range Selector -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 text-sm rounded-lg {{ $timeRange === '24h' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} transition-colors" onclick="changeTimeRange('24h')">
                24H
            </button>
            <button class="px-4 py-2 text-sm rounded-lg {{ $timeRange === '7d' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} transition-colors" onclick="changeTimeRange('7d')">
                7D
            </button>
            <button class="px-4 py-2 text-sm rounded-lg {{ $timeRange === '30d' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high' }} transition-colors" onclick="changeTimeRange('30d')">
                30D
            </button>
        </div>

        <button class="flex items-center gap-2 px-4 py-2 text-sm bg-surface-container border border-outline-variant/20 rounded-lg text-on-surface hover:bg-surface-container-high transition-colors">
            <span class="material-symbols-outlined text-lg">refresh</span>
            Refresh
        </button>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chart Section (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Alert Trends Chart -->
            <x-dashboard.chart-card
                title="Alert Trends"
                subtitle="Security alerts over time"
            >
                <canvas id="alertsChart" height="300"></canvas>
            </x-dashboard.chart-card>

            <!-- Recent Alerts -->
            <x-ui.card title="Recent Alerts" subtitle="Latest security alerts">
                <div class="space-y-3">
                    @foreach($recentAlerts as $alert)
                        <x-dashboard.alert-card :alert="$alert" />
                    @endforeach
                </div>

                <div class="mt-4 pt-4 border-t border-outline-variant/10 text-center">
                    <a href="{{ route('alerts.index') }}" class="inline-flex items-center gap-2 text-sm text-primary hover:text-primary/80 transition-colors">
                        View all alerts
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            </x-ui.card>
        </div>

        <!-- Activity Feed (1/3 width) -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <x-ui.card title="Quick Actions">
                <div class="grid grid-cols-2 gap-3">
                    <button class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-primary group-hover:scale-110 transition-transform">add_alert</span>
                        <span class="text-xs text-on-surface-variant">New Alert</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-warning group-hover:scale-110 transition-transform">report</span>
                        <span class="text-xs text-on-surface-variant">Report</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-info group-hover:scale-110 transition-transform">scan</span>
                        <span class="text-xs text-on-surface-variant">Scan</span>
                    </button>
                    <button class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-success group-hover:scale-110 transition-transform">playbook</span>
                        <span class="text-xs text-on-surface-variant">Playbook</span>
                    </button>
                </div>
            </x-ui.card>

            <!-- Activity Feed -->
            <x-ui.card title="Activity Feed" subtitle="Recent system activity">
                <x-dashboard.activity-feed :activities="$activities" />
            </x-ui.card>

            <!-- System Status -->
            <x-ui.card>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">CPU Usage</span>
                        <span class="text-sm font-mono text-on-surface">42%</span>
                    </div>
                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-success rounded-full" style="width: 42%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Memory</span>
                        <span class="text-sm font-mono text-on-surface">68%</span>
                    </div>
                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-warning rounded-full" style="width: 68%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Storage</span>
                        <span class="text-sm font-mono text-on-surface">55%</span>
                    </div>
                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                        <div class="h-full bg-info rounded-full" style="width: 55%"></div>
                    </div>
                </div>
            </x-ui.card>
        </div>
    </div>
</x-layouts.dashboard>

@push('scripts')
<script>
    // Time range change
    function changeTimeRange(range) {
        const url = new URL(window.location);
        url.searchParams.set('time_range', range);
        window.location = url.toString();
    }

    // Chart data
    const chartData = @json($chartData);

    // Simple canvas chart (without external library)
    document.addEventListener('DOMContentLoaded', function() {
        const canvas = document.getElementById('alertsChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const width = canvas.offsetWidth;
        const height = 300;

        canvas.width = width;
        canvas.height = height;

        // Clear canvas
        ctx.clearRect(0, 0, width, height);

        const datasets = chartData.datasets;
        const labels = chartData.labels;
        const padding = 40;
        const chartWidth = width - padding * 2;
        const chartHeight = height - padding * 2;

        // Find max value for scaling
        const maxValue = Math.max(
            ...datasets.flatMap(d => d.data)
        );

        // Draw grid lines
        ctx.strokeStyle = 'rgba(143, 144, 151, 0.1)';
        ctx.lineWidth = 1;
        for (let i = 0; i <= 4; i++) {
            const y = padding + (chartHeight / 4) * i;
            ctx.beginPath();
            ctx.moveTo(padding, y);
            ctx.lineTo(width - padding, y);
            ctx.stroke();
        }

        // Draw datasets
        datasets.forEach((dataset, datasetIndex) => {
            const color = dataset.color;
            const data = dataset.data;
            const stepX = chartWidth / (data.length - 1);

            // Draw fill area
            ctx.beginPath();
            ctx.moveTo(padding, height - padding);
            data.forEach((value, i) => {
                const x = padding + stepX * i;
                const y = height - padding - (value / maxValue) * chartHeight;
                ctx.lineTo(x, y);
            });
            ctx.lineTo(width - padding, height - padding);
            ctx.closePath();
            ctx.fillStyle = color + '20';
            ctx.fill();

            // Draw line
            ctx.beginPath();
            data.forEach((value, i) => {
                const x = padding + stepX * i;
                const y = height - padding - (value / maxValue) * chartHeight;
                if (i === 0) ctx.moveTo(x, y);
                else ctx.lineTo(x, y);
            });
            ctx.strokeStyle = color;
            ctx.lineWidth = 2;
            ctx.stroke();

            // Draw points
            data.forEach((value, i) => {
                const x = padding + stepX * i;
                const y = height - padding - (value / maxValue) * chartHeight;
                ctx.beginPath();
                ctx.arc(x, y, 4, 0, Math.PI * 2);
                ctx.fillStyle = color;
                ctx.fill();
            });
        });

        // Draw labels
        ctx.fillStyle = '#c5c6cd';
        ctx.font = '11px JetBrains Mono';
        ctx.textAlign = 'center';
        labels.forEach((label, i) => {
            const stepX = chartWidth / (labels.length - 1);
            const x = padding + stepX * i;
            ctx.fillText(label, x, height - 10);
        });
    });
</script>
@endpush
