<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChartController extends BaseController
{
    /**
     * Get alert trends data for charts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function alertTrends(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'time_range' => 'nullable|in:24h,7d,30d,90d',
            'interval' => 'nullable|in:hour,day,week',
        ]);

        $timeRange = $validated['time_range'] ?? '24h';
        $interval = $validated['interval'] ?? $this->getIntervalForTimeRange($timeRange);

        $data = $this->generateAlertTrendsData($timeRange, $interval);

        return $this->jsonSuccess($data);
    }

    /**
     * Get threat statistics for charts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function threatStats(Request $request): JsonResponse
    {
        $data = $this->generateThreatStatsData();

        return $this->jsonSuccess($data);
    }

    /**
     * Get incident resolution trends
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function incidentTrends(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'time_range' => 'nullable|in:7d,30d,90d',
        ]);

        $timeRange = $validated['time_range'] ?? '30d';

        $data = $this->generateIncidentTrendsData($timeRange);

        return $this->jsonSuccess($data);
    }

    /**
     * Get system metrics for dashboard charts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function systemMetrics(Request $request): JsonResponse
    {
        $data = $this->generateSystemMetricsData();

        return $this->jsonSuccess($data);
    }

    /**
     * Get clearance level distribution
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clearanceDistribution(Request $request): JsonResponse
    {
        $data = $this->generateClearanceDistributionData();

        return $this->jsonSuccess($data);
    }

    /**
     * Get real-time chart data (for live updates)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function realtime(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|in:alerts,incidents,threats,cpu,memory',
        ]);

        $data = match($validated['type']) {
            'alerts' => $this->getRealtimeAlerts(),
            'incidents' => $this->getRealtimeIncidents(),
            'threats' => $this->getRealtimeThreats(),
            'cpu' => $this->getRealtimeCpu(),
            'memory' => $this->getRealtimeMemory(),
            default => [],
        };

        return $this->jsonSuccess($data);
    }

    /**
     * Generate alert trends data
     */
    protected function generateAlertTrendsData(string $timeRange, string $interval): array
    {
        $points = $this->getDataPointCount($timeRange);
        $labels = $this->generateTimeLabels($timeRange, $interval, $points);

        // Generate realistic trend data
        $alertsData = [];
        $blockedData = [];
        $baseAlertValue = $this->getBaseValue('alerts', $timeRange);
        $baseBlockedValue = $this->getBaseValue('blocked', $timeRange);

        for ($i = 0; $i < $points; $i++) {
            // Add some randomness with trend
            $alertVariation = rand(-15, 20);
            $blockedVariation = rand(-10, 15);

            $alertsData[] = max(10, $baseAlertValue + $alertVariation + ($i % 3) * 5);
            $blockedData[] = max(30, $baseBlockedValue + $blockedVariation + ($i % 4) * 8);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Alerts',
                    'data' => $alertsData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
                [
                    'label' => 'Blocked',
                    'data' => $blockedData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'metadata' => [
                'time_range' => $timeRange,
                'interval' => $interval,
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    /**
     * Generate threat statistics data
     */
    protected function generateThreatStatsData(): array
    {
        return [
            'by_type' => [
                'labels' => ['APT', 'Malware', 'Ransomware', 'Phishing', 'Botnet', 'Zero-Day'],
                'data' => [15, 28, 12, 35, 8, 5],
                'colors' => ['#ef4444', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4', '#84cc16'],
            ],
            'by_severity' => [
                'labels' => ['Critical', 'High', 'Medium', 'Low'],
                'data' => [8, 15, 22, 12],
                'colors' => ['#ef4444', '#f59e0b', '#3b82f6', '#10b981'],
            ],
            'trend' => [
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                'data' => [45, 52, 48, 61, 55, 67],
            ],
        ];
    }

    /**
     * Generate incident trends data
     */
    protected function generateIncidentTrendsData(string $timeRange): array
    {
        $points = $timeRange === '7d' ? 7 : 30;
        $labels = $this->generateDateLabels($timeRange, $points);

        $openedData = [];
        $resolvedData = [];

        for ($i = 0; $i < $points; $i++) {
            $openedData[] = rand(2, 12);
            $resolvedData[] = rand(1, 10);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Opened',
                    'data' => $openedData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.5)',
                ],
                [
                    'label' => 'Resolved',
                    'data' => $resolvedData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                ],
            ],
        ];
    }

    /**
     * Generate system metrics data
     */
    protected function generateSystemMetricsData(): array
    {
        return [
            'cpu' => [
                'current' => rand(30, 70),
                'history' => array_fill(0, 20, 0)->map(fn() => rand(25, 75))->toArray(),
                'threshold' => 80,
            ],
            'memory' => [
                'current' => rand(50, 75),
                'history' => array_fill(0, 20, 0)->map(fn() => rand(45, 80))->toArray(),
                'threshold' => 85,
            ],
            'disk' => [
                'current' => rand(40, 60),
                'threshold' => 90,
            ],
            'network' => [
                'inbound' => rand(100, 500) . ' Mbps',
                'outbound' => rand(50, 300) . ' Mbps',
            ],
        ];
    }

    /**
     * Generate clearance distribution data
     */
    protected function generateClearanceDistributionData(): array
    {
        return [
            'labels' => ['Level 1', 'Level 2', 'Level 3', 'Level 4', 'Level 5'],
            'data' => [25, 35, 20, 12, 8],
            'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#ef4444', '#ec4899'],
        ];
    }

    /**
     * Get real-time alerts data
     */
    protected function getRealtimeAlerts(): array
    {
        return [
            'count' => rand(40, 60),
            'critical' => rand(2, 8),
            'high' => rand(5, 15),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get real-time incidents data
     */
    protected function getRealtimeIncidents(): array
    {
        return [
            'active' => rand(8, 15),
            'new_today' => rand(1, 5),
            'resolved_today' => rand(2, 8),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get real-time threats data
     */
    protected function getRealtimeThreats(): array
    {
        return [
            'active_threats' => rand(40, 55),
            'new_iocs' => rand(5, 20),
            'feeds_updated' => rand(1, 5),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get real-time CPU data
     */
    protected function getRealtimeCpu(): array
    {
        return [
            'value' => rand(25, 75),
            'cores' => array_fill(0, 4, 0)->map(fn() => rand(20, 80))->toArray(),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get real-time memory data
     */
    protected function getRealtimeMemory(): array
    {
        return [
            'used' => rand(45, 75),
            'total' => 100,
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Get interval for time range
     */
    protected function getIntervalForTimeRange(string $timeRange): string
    {
        return match($timeRange) {
            '24h' => 'hour',
            '7d', '30d' => 'day',
            '90d' => 'week',
            default => 'day',
        };
    }

    /**
     * Get data point count for time range
     */
    protected function getDataPointCount(string $timeRange): int
    {
        return match($timeRange) {
            '24h' => 24,
            '7d' => 7,
            '30d' => 30,
            '90d' => 12,
            default => 24,
        };
    }

    /**
     * Get base value for metric
     */
    protected function getBaseValue(string $metric, string $timeRange): int
    {
        $baseValues = [
            'alerts' => ['24h' => 35, '7d' => 45, '30d' => 50, '90d' => 55],
            'blocked' => ['24h' => 70, '7d' => 85, '30d' => 90, '90d' => 95],
        ];

        return $baseValues[$metric][$timeRange] ?? 50;
    }

    /**
     * Generate time labels
     */
    protected function generateTimeLabels(string $timeRange, string $interval, int $points): array
    {
        $labels = [];

        for ($i = 0; $i < $points; $i++) {
            if ($timeRange === '24h') {
                $labels[] = now()->subHours($points - $i)->format('H:00');
            } elseif ($timeRange === '7d') {
                $labels[] = now()->subDays($points - $i)->format('D M');
            } else {
                $labels[] = now()->subDays($points - $i)->format('M/d');
            }
        }

        return $labels;
    }

    /**
     * Generate date labels
     */
    protected function generateDateLabels(string $timeRange, int $points): array
    {
        $labels = [];

        for ($i = 0; $i < $points; $i++) {
            if ($timeRange === '7d') {
                $labels[] = now()->subDays($points - $i)->format('D M');
            } else {
                $labels[] = now()->subDays($points - $i)->format('M/d');
            }
        }

        return $labels;
    }
}
