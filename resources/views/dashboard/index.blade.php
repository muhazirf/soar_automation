<x-layouts.dashboard title="Dashboard">
    @php
        // Pass initial data to JavaScript
        $initialTimeRange = $timeRange ?? '24h';
    @endphp

    <!-- Time Range Selector -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2" x-data="{ timeRange: '{{ $initialTimeRange }}' }">
            <button
                @click="timeRange = '24h'; refreshCharts('24h')"
                :class="timeRange === '24h' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 text-sm rounded-lg transition-colors"
            >
                24H
            </button>
            <button
                @click="timeRange = '7d'; refreshCharts('7d')"
                :class="timeRange === '7d' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 text-sm rounded-lg transition-colors"
            >
                7D
            </button>
            <button
                @click="timeRange = '30d'; refreshCharts('30d')"
                :class="timeRange === '30d' ? 'bg-primary text-on-primary' : 'bg-surface-container text-on-surface-variant hover:bg-surface-container-high'"
                class="px-4 py-2 text-sm rounded-lg transition-colors"
            >
                30D
            </button>
        </div>

        <button
            onclick="refreshAllCharts()"
            class="flex items-center gap-2 px-4 py-2 text-sm bg-surface-container border border-outline-variant/20 rounded-lg text-on-surface hover:bg-surface-container-high transition-colors"
        >
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
                <div class="h-[300px]">
                    <canvas id="alertsChart"></canvas>
                </div>
            </x-dashboard.chart-card>

            <!-- System Metrics Charts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- CPU Usage Chart -->
                <x-ui.card title="CPU Usage" subtitle="Real-time CPU utilization">
                    <div class="h-[200px]">
                        <canvas id="cpuChart"></canvas>
                    </div>
                </x-ui.card>

                <!-- Memory Usage Chart -->
                <x-ui.card title="Memory Usage" subtitle="Real-time memory utilization">
                    <div class="h-[200px]">
                        <canvas id="memoryChart"></canvas>
                    </div>
                </x-ui.card>
            </div>

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
                    <a href="{{ route('incidents.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-warning group-hover:scale-110 transition-transform">report</span>
                        <span class="text-xs text-on-surface-variant">Report</span>
                    </a>
                    <a href="{{ route('virustotal.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-info group-hover:scale-110 transition-transform">scan</span>
                        <span class="text-xs text-on-surface-variant">Scan</span>
                    </a>
                    <a href="{{ route('playbooks.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-lg bg-surface-container hover:bg-surface-container-high transition-colors group">
                        <span class="material-symbols-outlined text-2xl text-success group-hover:scale-110 transition-transform">playbook</span>
                        <span class="text-xs text-on-surface-variant">Playbook</span>
                    </a>
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
                        <span class="text-sm font-mono text-on-surface" id="cpuValue">--%</span>
                    </div>
                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                        <div id="cpuBar" class="h-full bg-success rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-on-surface-variant">Memory</span>
                        <span class="text-sm font-mono text-on-surface" id="memoryValue">--%</span>
                    </div>
                    <div class="w-full h-2 bg-surface-container rounded-full overflow-hidden">
                        <div id="memoryBar" class="h-full bg-warning rounded-full transition-all duration-500" style="width: 0%"></div>
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
    // Global variables for chart instances
    let alertsChart = null;
    let cpuChart = null;
    let memoryChart = null;
    let autoRefreshInterval = null;

    /**
     * Initialize all charts when DOM is ready
     */
    document.addEventListener('DOMContentLoaded', async function() {
        await initializeCharts();
        setupAutoRefresh();
    });

    /**
     * Initialize all charts
     */
    async function initializeCharts() {
        const timeRange = '{{ $initialTimeRange }}';

        try {
            // Create Alert Trends Chart
            alertsChart = await chartManager.createLineChartFromApi(
                'alertsChart',
                '/api/charts/alerts/trends',
                { time_range: timeRange }
            );

            // Create CPU Chart (real-time data)
            await updateSystemMetrics();

            // Create Memory Chart (real-time data)
            await updateSystemMetrics();

        } catch (error) {
            console.error('Failed to initialize charts:', error);
        }
    }

    /**
     * Update system metrics charts
     */
    async function updateSystemMetrics() {
        try {
            const response = await fetch('/api/charts/system/metrics');
            const result = await response.json();

            if (result.success && result.data) {
                const { cpu, memory } = result.data;

                // Update CPU value and bar
                document.getElementById('cpuValue').textContent = cpu.current + '%';
                document.getElementById('cpuBar').style.width = cpu.current + '%';
                document.getElementById('cpuBar').className = `h-full rounded-full transition-all duration-500 ${cpu.current > 80 ? 'bg-error' : cpu.current > 60 ? 'bg-warning' : 'bg-success'}`;

                // Update Memory value and bar
                document.getElementById('memoryValue').textContent = memory.current + '%';
                document.getElementById('memoryBar').style.width = memory.current + '%';
                document.getElementById('memoryBar').className = `h-full rounded-full transition-all duration-500 ${memory.current > 85 ? 'bg-error' : memory.current > 70 ? 'bg-warning' : 'bg-success'}`;

                // Create CPU history chart
                if (!cpuChart || !chartManager.hasChart('cpuChart')) {
                    cpuChart = chartManager.createChart('cpuChart', 'line', {
                        labels: Array(20).fill('').map((_, i) => i),
                        datasets: [{
                            label: 'CPU %',
                            data: cpu.history,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                        }]
                    }, {
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: {
                                min: 0,
                                max: 100,
                                grid: { color: 'rgba(143, 144, 151, 0.1)' },
                                ticks: { color: '#c5c6cd', font: { size: 10 } }
                            }
                        }
                    });
                }

                // Create Memory history chart
                if (!memoryChart || !chartManager.hasChart('memoryChart')) {
                    memoryChart = chartManager.createChart('memoryChart', 'line', {
                        labels: Array(20).fill('').map((_, i) => i),
                        datasets: [{
                            label: 'Memory %',
                            data: memory.history,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.1)',
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                        }]
                    }, {
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { display: false },
                            y: {
                                min: 0,
                                max: 100,
                                grid: { color: 'rgba(143, 144, 151, 0.1)' },
                                ticks: { color: '#c5c6cd', font: { size: 10 } }
                            }
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Failed to update system metrics:', error);
        }
    }

    /**
     * Refresh charts for specific time range
     */
    async function refreshCharts(timeRange) {
        try {
            await chartManager.updateChartFromApi(
                'alertsChart',
                '/api/charts/alerts/trends',
                { time_range: timeRange }
            );
        } catch (error) {
            console.error('Failed to refresh charts:', error);
        }
    }

    /**
     * Refresh all charts
     */
    async function refreshAllCharts() {
        const activeTimeRange = document.querySelector('[x-data]*[x-data]')?.timeRange || '24h';
        await refreshCharts(activeTimeRange);
        await updateSystemMetrics();
    }

    /**
     * Setup auto-refresh interval
     */
    function setupAutoRefresh() {
        // Refresh system metrics every 10 seconds
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }

        autoRefreshInterval = setInterval(() => {
            updateSystemMetrics();
        }, 10000);
    }

    /**
     * Cleanup on page unload
     */
    window.addEventListener('beforeunload', () => {
        if (autoRefreshInterval) {
            clearInterval(autoRefreshInterval);
        }
        chartManager.destroyAllCharts();
    });
</script>
@endpush
