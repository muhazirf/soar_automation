<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlertController extends BaseController
{
    /**
     * Display alerts list
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();
        $filters = [
            'severity' => $request->get('severity'),
            'status' => $request->get('status', 'active'),
            'source' => $request->get('source'),
            'search' => $request->get('search'),
        ];

        // Get alerts (in real app, query database)
        $alerts = $this->getAlerts($filters, 50);

        // Get stats
        $stats = $this->getAlertStats();

        return view('alerts.index', compact('alerts', 'stats', 'filters'));
    }

    /**
     * Display single alert details
     */
    public function show(Request $request, string $id)
    {
        $alert = $this->getAlert($id);

        if (!$alert) {
            return $this->redirectError('Alert not found');
        }

        // Get related alerts
        $relatedAlerts = $this->getRelatedAlerts($alert, 5);

        // Get alert timeline
        $timeline = $this->getAlertTimeline($alert);

        return view('alerts.show', compact('alert', 'relatedAlerts', 'timeline'));
    }

    /**
     * Update alert status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:active,investigating,resolved,dismissed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // In real app, update database
        // Alert::where('id', $id)->update($validated);

        return $this->redirectSuccess('Alert status updated');
    }

    /**
     * Escalate alert
     */
    public function escalate(Request $request, string $id)
    {
        $validated = $request->validate([
            'to_level' => 'required|integer|min:2|max:5',
            'reason' => 'required|string|max:500',
        ]);

        // In real app, create escalation record
        // AlertEscalation::create([...])

        return $this->redirectSuccess('Alert escalated successfully');
    }

    /**
     * Get alerts with filters
     */
    protected function getAlerts(array $filters, int $limit): array
    {
        $severities = ['critical', 'high', 'medium', 'low'];
        $statuses = ['active', 'investigating', 'resolved', 'dismissed'];
        $sources = ['System', 'Network', 'Endpoint', 'Cloud', 'User'];
        $alertTypes = [
            'Malware detected',
            'Suspicious login',
            'Port scan',
            'DDoS attack',
            'Phishing email',
            'Unauthorized access',
            'SQL injection',
            'Brute force',
            'XSS attempt',
            'Data exfiltration',
        ];

        $alerts = [];
        $count = rand(30, 80);

        for ($i = 0; $i < $count; $i++) {
            $severity = $severities[array_rand($severities)];
            $status = $statuses[array_rand($statuses)];

            // Apply filters
            if ($filters['severity'] && $severity !== $filters['severity']) continue;
            if ($filters['status'] && $status !== $filters['status']) continue;

            $alerts[] = [
                'id' => 'ALT-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'title' => $alertTypes[array_rand($alertTypes)],
                'description' => $this->generateDescription($severity),
                'severity' => $severity,
                'status' => $status,
                'source' => $sources[array_rand($sources)],
                'created_at' => now()->subMinutes(rand(5, 1440))->format('M d, H:i'),
                'affected_host' => 'host-' . rand(1, 50) . '.domain.local',
                'confidence' => rand(60, 100),
            ];
        }

        // Apply search filter
        if ($filters['search']) {
            $search = strtolower($filters['search']);
            $alerts = array_filter($alerts, function ($alert) use ($search) {
                return str_contains(strtolower($alert['title']), $search) ||
                       str_contains(strtolower($alert['description']), $search) ||
                       str_contains(strtolower($alert['id']), $search);
            });
        }

        return array_slice($alerts, 0, $limit);
    }

    /**
     * Get single alert
     */
    protected function getAlert(string $id): ?array
    {
        // In real app, query database
        $alertTypes = [
            'Malware detected on workstation',
            'Suspicious login attempt',
            'Port scan detected',
        ];

        return [
            'id' => $id,
            'title' => $alertTypes[array_rand($alertTypes)],
            'description' => 'Advanced persistent threat detected. Multiple indicators of compromise found including unusual network traffic, suspicious process execution, and file modifications.',
            'severity' => ['critical', 'high', 'medium', 'low'][array_rand(['critical', 'high', 'medium', 'low'])],
            'status' => 'active',
            'source' => 'Network Monitor',
            'created_at' => now()->subMinutes(45)->format('M d, H:i'),
            'updated_at' => now()->subMinutes(10)->format('M d, H:i'),
            'affected_host' => 'ws-015.domain.local',
            'confidence' => 92,
            'ioc' => [
                ['type' => 'IP Address', 'value' => '192.168.1.100', 'description' => 'Suspicious external connection'],
                ['type' => 'Domain', 'value' => 'malicious-domain.xyz', 'description' => 'Known C2 server'],
                ['type' => 'File Hash', 'value' => 'a1b2c3d4e5f6...', 'description' => 'Malware signature'],
            ],
            'assigned_to' => 'Agent Smith',
            'tags' => ['malware', 'apt', 'network'],
            'mitre_techniques' => ['T1059.001', 'T1071'],
        ];
    }

    /**
     * Get related alerts
     */
    protected function getRelatedAlerts(array $alert, int $limit): array
    {
        $related = [];
        for ($i = 0; $i < $limit; $i++) {
            $related[] = [
                'id' => 'ALT-' . str_pad(rand(1, 9999), 6, '0', STR_PAD_LEFT),
                'title' => 'Related activity detected',
                'severity' => $alert['severity'],
                'created_at' => now()->subMinutes(rand(30, 120))->format('H:i'),
            ];
        }
        return $related;
    }

    /**
     * Get alert timeline
     */
    protected function getAlertTimeline(array $alert): array
    {
        return [
            ['event' => 'Alert created', 'time' => $alert['created_at'], 'user' => 'System'],
            ['event' => 'Assigned to analyst', 'time' => now()->subMinutes(40)->format('H:i'), 'user' => 'Auto'],
            ['event' => 'Status changed to investigating', 'time' => now()->subMinutes(30)->format('H:i'), 'user' => 'Agent Smith'],
            ['event' => 'IOC enrichment completed', 'time' => now()->subMinutes(20)->format('H:i'), 'user' => 'System'],
            ['event' => 'Related incidents linked', 'time' => now()->subMinutes(10)->format('H:i'), 'user' => 'System'],
        ];
    }

    /**
     * Get alert statistics
     */
    protected function getAlertStats(): array
    {
        return [
            'total' => rand(100, 500),
            'critical' => rand(5, 20),
            'high' => rand(20, 50),
            'medium' => rand(40, 80),
            'low' => rand(50, 100),
            'active' => rand(20, 60),
            'resolved' => rand(50, 150),
        ];
    }

    /**
     * Generate alert description
     */
    protected function generateDescription(string $severity): string
    {
        $descriptions = [
            'critical' => 'Immediate action required. System compromise detected.',
            'high' => 'Elevated threat level. Investigation recommended.',
            'medium' => 'Suspicious activity detected. Monitor closely.',
            'low' => 'Informational alert. Log for reference.',
        ];

        return $descriptions[$severity] ?? $descriptions['low'];
    }
}
