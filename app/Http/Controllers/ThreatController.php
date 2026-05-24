<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ThreatController extends BaseController
{
    /**
     * Display threat intelligence dashboard
     */
    public function index(Request $request)
    {
        $filters = [
            'type' => $request->get('type'),
            'severity' => $request->get('severity'),
            'search' => $request->get('search'),
        ];

        $threats = $this->getThreats($filters);
        $stats = $this->getThreatStats();
        $feedStatus = $this->getFeedStatus();

        return view('threats.index', compact('threats', 'stats', 'feedStatus', 'filters'));
    }

    /**
     * Display threat details
     */
    public function show(string $id)
    {
        $threat = $this->getThreat($id);
        $relatedThreats = $this->getRelatedThreats($threat);

        return view('threats.show', compact('threat', 'relatedThreats'));
    }

    /**
     * Get threats with filters
     */
    protected function getThreats(array $filters): array
    {
        $threatTypes = ['APT', 'Malware', 'Ransomware', 'Phishing', 'Botnet', 'Zero-Day'];
        $severities = ['critical', 'high', 'medium', 'low'];

        $threats = [
            [
                'id' => 'THR-001',
                'name' => 'APT-29',
                'type' => 'APT',
                'description' => 'State-sponsored threat group targeting research organizations.',
                'severity' => 'critical',
                'confidence' => 95,
                'iocs' => 42,
                'first_seen' => now()->subMonths(6)->format('M Y'),
                'last_seen' => now()->subDays(2)->format('M d'),
                'tags' => ['apt', 'espionage', 'state-sponsored'],
            ],
            [
                'id' => 'THR-002',
                'name' => 'BlackCat Ransomware',
                'type' => 'Ransomware',
                'description' => 'Ransomware-as-service operation targeting multiple sectors.',
                'severity' => 'high',
                'confidence' => 88,
                'iocs' => 28,
                'first_seen' => now()->subMonths(3)->format('M Y'),
                'last_seen' => now()->subHours(12)->format('H:i'),
                'tags' => ['ransomware', 'raaS', 'financial'],
            ],
            [
                'id' => 'THR-003',
                'name' => 'Emotet Botnet',
                'type' => 'Botnet',
                'description' => 'Modular botnet used for initial access and malware delivery.',
                'severity' => 'high',
                'confidence' => 92,
                'iocs' => 156,
                'first_seen' => now()->subYears(1)->format('M Y'),
                'last_seen' => now()->subDays(5)->format('M d'),
                'tags' => ['botnet', 'loader', 'banking'],
            ],
            [
                'id' => 'THR-004',
                'name' => 'CVE-2024-XXXX',
                'type' => 'Zero-Day',
                'description' => 'Unpatched vulnerability in widely-used enterprise software.',
                'severity' => 'critical',
                'confidence' => 78,
                'iocs' => 12,
                'first_seen' => now()->subWeeks(2)->format('M d'),
                'last_seen' => now()->subHours(6)->format('H:i'),
                'tags' => ['zero-day', 'cve', 'exploit'],
            ],
            [
                'id' => 'THR-005',
                'name' => 'Business Email Compromise',
                'type' => 'Phishing',
                'description' => 'Sophisticated BEC campaign targeting finance departments.',
                'severity' => 'medium',
                'confidence' => 72,
                'iocs' => 18,
                'first_seen' => now()->subWeeks(1)->format('M d'),
                'last_seen' => now()->subDays(1)->format('M d'),
                'tags' => ['phishing', 'bec', 'financial'],
            ],
            [
                'id' => 'THR-006',
                'name' => 'LockBit 3.0',
                'type' => 'Ransomware',
                'description' => 'Latest version of LockBit ransomware with improved evasion.',
                'severity' => 'critical',
                'confidence' => 91,
                'iocs' => 67,
                'first_seen' => now()->subMonths(2)->format('M Y'),
                'last_seen' => now()->subHours(24)->format('H:i'),
                'tags' => ['ransomware', 'encryption', 'data-exfil'],
            ],
        ];

        return $threats;
    }

    /**
     * Get single threat
     */
    protected function getThreat(string $id): array
    {
        return [
            'id' => $id,
            'name' => 'APT-29',
            'type' => 'APT',
            'description' => 'State-sponsored threat group targeting research organizations, academic institutions, and think tanks. Known for sophisticated phishing campaigns and custom malware.',
            'severity' => 'critical',
            'confidence' => 95,
            'aliases' => ['Cozy Bear', 'CozyDuke'],
            'first_seen' => now()->subMonths(6)->format('M Y'),
            'last_seen' => now()->subDays(2)->format('M d'),
            'tags' => ['apt', 'espionage', 'state-sponsored'],
            'iocs' => [
                ['type' => 'Domain', 'value' => 'malicious-domain[.]com', 'description' => 'C2 server'],
                ['type' => 'IP', 'value' => '192.168.1.100', 'description' => 'Known C2 IP'],
                ['type' => 'File Hash', 'value' => 'a1b2c3d4...', 'description' => 'Custom malware sample'],
                ['type' => 'Email', 'value' => 'recruiting@[.]company', 'description' => 'Phishing sender'],
            ],
            'ttps' => ['T1566.001', 'T1566.002', 'T1059.001', 'T1071'],
            'targets' => ['North America', 'Europe', 'Asia'],
            'industries' => ['Research', 'Education', 'Government'],
        ];
    }

    /**
     * Get related threats
     */
    protected function getRelatedThreats(array $threat): array
    {
        return [
            ['id' => 'THR-007', 'name' => 'APT-28', 'similarity' => 85],
            ['id' => 'THR-008', 'name' => 'Sofacy Group', 'similarity' => 72],
        ];
    }

    /**
     * Get threat statistics
     */
    protected function getThreatStats(): array
    {
        return [
            'total_iocs' => 1250,
            'active_threats' => 48,
            'new_this_week' => 12,
            'feeds_active' => 8,
        ];
    }

    /**
     * Get feed status
     */
    protected function getFeedStatus(): array
    {
        return [
            ['name' => 'AlienVault OTX', 'status' => 'active', 'last_update' => now()->subMinutes(15)->format('H:i')],
            ['name' => 'VirusTotal', 'status' => 'active', 'last_update' => now()->subMinutes(5)->format('H:i')],
            ['name' => 'MISP', 'status' => 'active', 'last_update' => now()->subHours(1)->format('H:i')],
            ['name' => 'Abuse.ch', 'status' => 'active', 'last_update' => now()->subMinutes(30)->format('H:i')],
        ];
    }
}
