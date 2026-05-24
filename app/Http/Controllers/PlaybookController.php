<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaybookController extends BaseController
{
    /**
     * Display playbooks list
     */
    public function index(Request $request)
    {
        $filters = [
            'category' => $request->get('category'),
            'status' => $request->get('status'),
        ];

        $playbooks = $this->getPlaybooks($filters);
        $categories = $this->getCategories();

        return view('playbooks.index', compact('playbooks', 'categories', 'filters'));
    }

    /**
     * Display single playbook
     */
    public function show(string $id)
    {
        $playbook = $this->getPlaybook($id);
        $executionHistory = $this->getExecutionHistory($id);

        return view('playbooks.show', compact('playbook', 'executionHistory'));
    }

    /**
     * Execute playbook
     */
    public function execute(Request $request, string $id)
    {
        $validated = $request->validate([
            'target' => 'required|string',
            'parameters' => 'nullable|array',
        ]);

        // In real app, execute playbook
        return $this->jsonSuccess(['execution_id' => uniqid()], 'Playbook execution started');
    }

    /**
     * Get playbooks
     */
    protected function getPlaybooks(array $filters): array
    {
        $playbooks = [
            [
                'id' => 'PB-001',
                'name' => 'Malware Response',
                'description' => 'Automated response to malware detection including isolation, scanning, and remediation.',
                'category' => 'Response',
                'status' => 'active',
                'steps' => 8,
                'avg_duration' => '15 min',
                'success_rate' => 95,
                'last_used' => now()->subHours(2)->format('H:i'),
            ],
            [
                'id' => 'PB-002',
                'name' => 'Phishing Investigation',
                'description' => 'Investigate and respond to phishing email reports including header analysis and URL checking.',
                'category' => 'Investigation',
                'status' => 'active',
                'steps' => 6,
                'avg_duration' => '10 min',
                'success_rate' => 89,
                'last_used' => now()->subDays(1)->format('M d'),
            ],
            [
                'id' => 'PB-003',
                'name' => 'DDoS Mitigation',
                'description' => 'Automated DDoS mitigation including rate limiting and IP blocking.',
                'category' => 'Response',
                'status' => 'active',
                'steps' => 5,
                'avg_duration' => '5 min',
                'success_rate' => 98,
                'last_used' => now()->subHours(6)->format('H:i'),
            ],
            [
                'id' => 'PB-004',
                'name' => 'Data Breach Containment',
                'description' => 'Rapid containment procedures for suspected data breaches.',
                'category' => 'Response',
                'status' => 'active',
                'steps' => 12,
                'avg_duration' => '30 min',
                'success_rate' => 92,
                'last_used' => now()->subDays(3)->format('M d'),
            ],
            [
                'id' => 'PB-005',
                'name' => 'Insider Threat Analysis',
                'description' => 'Analyze user behavior patterns for potential insider threats.',
                'category' => 'Investigation',
                'status' => 'draft',
                'steps' => 10,
                'avg_duration' => '45 min',
                'success_rate' => 78,
                'last_used' => null,
            ],
            [
                'id' => 'PB-006',
                'name' => 'Vulnerability Patching',
                'description' => 'Automated vulnerability scanning and patch deployment.',
                'category' => 'Maintenance',
                'status' => 'active',
                'steps' => 7,
                'avg_duration' => '20 min',
                'success_rate' => 96,
                'last_used' => now()->subHours(12)->format('H:i'),
            ],
        ];

        return $playbooks;
    }

    /**
     * Get single playbook
     */
    protected function getPlaybook(string $id): array
    {
        return [
            'id' => $id,
            'name' => 'Malware Response',
            'description' => 'Automated response to malware detection including isolation, scanning, and remediation.',
            'category' => 'Response',
            'status' => 'active',
            'version' => '2.1',
            'created_at' => now()->subMonths(2)->format('M d, Y'),
            'updated_at' => now()->subDays(7)->format('M d, Y'),
            'steps' => [
                ['id' => 1, 'name' => 'Isolate affected host', 'type' => 'automated', 'duration' => '1 min'],
                ['id' => 2, 'name' => 'Collect system snapshot', 'type' => 'automated', 'duration' => '2 min'],
                ['id' => 3, 'name' => 'Run full system scan', 'type' => 'automated', 'duration' => '5 min'],
                ['id' => 4, 'name' => 'Analyze scan results', 'type' => 'manual', 'duration' => '3 min'],
                ['id' => 5, 'name' => 'Quarantine malicious files', 'type' => 'automated', 'duration' => '1 min'],
                ['id' => 6, 'name' => 'Update antivirus definitions', 'type' => 'automated', 'duration' => '2 min'],
                ['id' => 7, 'name' => 'Restore from backup if needed', 'type' => 'manual', 'duration' => '10 min'],
                ['id' => 8, 'name' => 'Generate incident report', 'type' => 'automated', 'duration' => '1 min'],
            ],
            'parameters' => [
                ['name' => 'scan_type', 'type' => 'select', 'options' => ['quick', 'full', 'custom'], 'default' => 'full'],
                ['name' => 'quarantine', 'type' => 'boolean', 'default' => true],
                ['name' => 'snapshot', 'type' => 'boolean', 'default' => true],
            ],
        ];
    }

    /**
     * Get execution history
     */
    protected function getExecutionHistory(string $id): array
    {
        return [
            ['execution_id' => 'EX-001', 'target' => 'ws-015', 'status' => 'success', 'executed_at' => now()->subHours(2)->format('H:i'), 'duration' => '14 min'],
            ['execution_id' => 'EX-002', 'target' => 'ws-023', 'status' => 'success', 'executed_at' => now()->subDays(1)->format('M d H:i'), 'duration' => '16 min'],
            ['execution_id' => 'EX-003', 'target' => 'srv-001', 'status' => 'failed', 'executed_at' => now()->subDays(2)->format('M d H:i'), 'duration' => '8 min'],
            ['execution_id' => 'EX-004', 'target' => 'ws-042', 'status' => 'success', 'executed_at' => now()->subDays(3)->format('M d H:i'), 'duration' => '15 min'],
        ];
    }

    /**
     * Get categories
     */
    protected function getCategories(): array
    {
        return ['Response', 'Investigation', 'Maintenance', 'Compliance'];
    }
}
