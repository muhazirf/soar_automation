<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends BaseController
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();
        $timeRange = $request->get('time_range', '24h'); // 24h, 7d, 30d

        // Get stats based on time range
        $stats = $this->getStats($user, $timeRange);

        // Get recent alerts
        $recentAlerts = $this->getRecentAlerts($user, 10);

        // Get activity feed
        $activities = $this->getActivityFeed($user, 10);

        // Get chart data
        $chartData = $this->getChartData($user, $timeRange);

        return view('dashboard.index', compact(
            'stats',
            'recentAlerts',
            'activities',
            'chartData',
            'timeRange',
        ));
    }

    /**
     * Get dashboard statistics
     */
    protected function getStats($user, $timeRange): array
    {
        // In a real app, this would query the database
        // For now, return sample data
        return [
            [
                'title' => 'Total Alerts',
                'value' => $this->getRandomStat(100, 500),
                'change' => '+12%',
                'trend' => 'up',
                'icon' => 'notifications',
            ],
            [
                'title' => 'Active Incidents',
                'value' => $this->getRandomStat(5, 25),
                'change' => '-3%',
                'trend' => 'down',
                'icon' => 'report',
            ],
            [
                'title' => 'Threats Blocked',
                'value' => $this->getRandomStat(1000, 5000),
                'change' => '+28%',
                'trend' => 'up',
                'icon' => 'shield',
            ],
            [
                'title' => 'System Health',
                'value' => '98%',
                'change' => '+2%',
                'trend' => 'up',
                'icon' => 'health_and_safety',
            ],
        ];
    }

    /**
     * Get recent alerts
     */
    protected function getRecentAlerts($user, int $limit): array
    {
        // Sample alerts data
        $severities = ['critical', 'high', 'medium', 'low'];
        $alertTypes = [
            'Malware detected on workstation',
            'Suspicious login attempt',
            'Port scan detected',
            'DDoS attack mitigated',
            'Phishing email blocked',
            'Unauthorized access attempt',
            'SQL injection attempt',
            'Brute force attack detected',
        ];

        $alerts = [];
        for ($i = 0; $i < $limit; $i++) {
            $severity = $severities[array_rand($severities)];
            $alerts[] = [
                'id' => $i + 1,
                'title' => $alertTypes[array_rand($alertTypes)],
                'description' => $this->generateAlertDescription($severity),
                'severity' => $severity,
                'timestamp' => now()->subMinutes(rand(5, 240))->format('H:i'),
                'source' => 'System',
                'status' => rand(0, 1) ? 'active' : 'resolved',
            ];
        }

        return $alerts;
    }

    /**
     * Get activity feed
     */
    protected function getActivityFeed($user, int $limit): array
    {
        $activities = [
            [
                'icon' => 'person_add',
                'icon_color' => 'text-success',
                'title' => 'New user registered',
                'description' => 'Agent Smith joined the team',
                'time' => '5 minutes ago',
            ],
            [
                'icon' => 'shield',
                'icon_color' => 'text-primary',
                'title' => 'Threat neutralized',
                'description' => 'Malware threat blocked and quarantined',
                'time' => '15 minutes ago',
            ],
            [
                'icon' => 'warning',
                'icon_color' => 'text-warning',
                'title' => 'System update available',
                'description' => 'Security patch v2.5.1 ready to install',
                'time' => '1 hour ago',
            ],
            [
                'icon' => 'playbook',
                'icon_color' => 'text-info',
                'title' => 'Playbook executed',
                'description' => 'Malware Response playbook completed successfully',
                'time' => '2 hours ago',
            ],
            [
                'icon' => 'report',
                'icon_color' => 'text-error',
                'title' => 'Incident escalated',
                'description' => 'INC-2024-042 escalated to Level 3',
                'time' => '3 hours ago',
            ],
            [
                'icon' => 'radar',
                'icon_color' => 'text-primary',
                'title' => 'Threat intelligence updated',
                'description' => '35 new IOCs added to database',
                'time' => '4 hours ago',
            ],
        ];

        return array_slice($activities, 0, $limit);
    }

    /**
     * Get chart data
     */
    protected function getChartData($user, $timeRange): array
    {
        // Generate sample chart data
        $labels = [];
        $alertsData = [];
        $blockedData = [];

        $points = match($timeRange) {
            '24h' => 24,
            '7d' => 7,
            '30d' => 30,
            default => 24,
        };

        for ($i = 0; $i < $points; $i++) {
            if ($timeRange === '24h') {
                $labels[] = now()->subHours($points - $i)->format('H:00');
            } else {
                $labels[] = now()->subDays($points - $i)->format('M/d');
            }
            $alertsData[] = rand(10, 50);
            $blockedData[] = rand(40, 100);
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Alerts',
                    'data' => $alertsData,
                    'color' => '#ffb4ab',
                ],
                [
                    'label' => 'Blocked',
                    'data' => $blockedData,
                    'color' => '#64ffda',
                ],
            ],
        ];
    }

    /**
     * Generate random stat for demo
     */
    protected function getRandomStat(int $min, int $max): int
    {
        return rand($min, $max);
    }

    /**
     * Generate alert description
     */
    protected function generateAlertDescription(string $severity): string
    {
        $descriptions = [
            'critical' => 'Immediate action required. System compromised.',
            'high' => 'Elevated threat detected. Investigate immediately.',
            'medium' => 'Potential security issue. Review recommended.',
            'low' => 'Informational alert. Log for reference.',
        ];

        return $descriptions[$severity] ?? $descriptions['low'];
    }
}
