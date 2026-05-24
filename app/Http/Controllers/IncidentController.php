<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IncidentController extends BaseController
{
    /**
     * Display incidents list
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();
        $filters = [
            'severity' => $request->get('severity'),
            'status' => $request->get('status'),
            'search' => $request->get('search'),
        ];

        $incidents = $this->getIncidents($filters, 50);
        $stats = $this->getIncidentStats();

        return view('incidents.index', compact('incidents', 'stats', 'filters'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $this->authorizeCreate();

        return view('incidents.create');
    }

    /**
     * Store new incident
     */
    public function store(Request $request)
    {
        $this->authorizeCreate();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'severity' => 'required|in:low,medium,high,critical',
            'type' => 'required|string|max:100',
            'affected_assets' => 'nullable|array',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        // In real app, create incident
        // Incident::create([...])

        return $this->redirectSuccess('Incident created successfully', route('incidents.index'));
    }

    /**
     * Show incident details
     */
    public function show(string $id)
    {
        $incident = $this->getIncident($id);

        if (!$incident) {
            return $this->redirectError('Incident not found');
        }

        $timeline = $this->getIncidentTimeline($incident);
        $linkedAlerts = $this->getLinkedAlerts($incident);

        return view('incidents.show', compact('incident', 'timeline', 'linkedAlerts'));
    }

    /**
     * Show edit form
     */
    public function edit(string $id)
    {
        $incident = $this->getIncident($id);

        if (!$incident) {
            return $this->redirectError('Incident not found');
        }

        $this->authorizeEdit($incident);

        return view('incidents.edit', compact('incident'));
    }

    /**
     * Update incident
     */
    public function update(Request $request, string $id)
    {
        $incident = $this->getIncident($id);

        if (!$incident) {
            return $this->redirectError('Incident not found');
        }

        $this->authorizeEdit($incident);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'severity' => 'sometimes|in:low,medium,high,critical',
            'status' => 'sometimes|in:open,investigating,contained,eradicated,resolved,closed',
            'assigned_to' => 'nullable|exists:users,id',
            'resolution' => 'nullable|string',
        ]);

        // In real app, update incident
        // Incident::where('id', $id)->update($validated)

        return $this->redirectSuccess('Incident updated successfully', route('incidents.show', $id));
    }

    /**
     * Delete incident
     */
    public function destroy(string $id)
    {
        $incident = $this->getIncident($id);

        if (!$incident) {
            return $this->redirectError('Incident not found');
        }

        $this->authorizeDelete($incident);

        // In real app, delete incident
        // Incident::where('id', $id)->delete()

        return $this->redirectSuccess('Incident deleted successfully', route('incidents.index'));
    }

    /**
     * Get incidents with filters
     */
    protected function getIncidents(array $filters, int $limit): array
    {
        $severities = ['critical', 'high', 'medium', 'low'];
        $statuses = ['open', 'investigating', 'contained', 'eradicated', 'resolved', 'closed'];
        $types = ['Malware', 'Phishing', 'DDoS', 'Insider Threat', 'Data Breach', 'Ransomware'];

        $incidents = [];
        $count = rand(15, 40);

        for ($i = 0; $i < $count; $i++) {
            $severity = $severities[array_rand($severities)];
            $status = $statuses[array_rand($statuses)];

            if ($filters['severity'] && $severity !== $filters['severity']) continue;
            if ($filters['status'] && $status !== $filters['status']) continue;

            $type = $types[array_rand($types)];
            $id = 'INC-' . date('Y') . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            $incidents[] = [
                'id' => $id,
                'title' => "{$type} Incident - " . chr(65 + rand(0, 25)) . rand(100, 999),
                'description' => "Investigation ongoing for {$type} activity detected on corporate network.",
                'severity' => $severity,
                'status' => $status,
                'type' => $type,
                'created_at' => now()->subDays(rand(1, 30))->format('M d, Y'),
                'updated_at' => now()->subHours(rand(1, 48))->format('H:i'),
                'assigned_to' => 'Agent ' . ['Smith', 'Johnson', 'Williams', 'Brown'][array_rand([0, 1, 2, 3])],
                'resolution' => $status === 'resolved' || $status === 'closed' ? 'Incident has been resolved and closed.' : null,
            ];
        }

        return array_slice($incidents, 0, $limit);
    }

    /**
     * Get single incident
     */
    protected function getIncident(string $id): ?array
    {
        // In real app, query database
        return [
            'id' => $id,
            'title' => 'Malware Incident - A487',
            'description' => 'Suspicious malware activity detected on multiple workstations. Initial analysis indicates APT-style behavior with potential data exfiltration.',
            'severity' => 'high',
            'status' => 'investigating',
            'type' => 'Malware',
            'created_at' => now()->subDays(2)->format('M d, Y'),
            'updated_at' => now()->subHours(3)->format('H:i'),
            'assigned_to' => 'Agent Smith',
            'affected_assets' => ['ws-015.domain.local', 'ws-023.domain.local', 'srv-003.domain.local'],
            'tags' => ['malware', 'apt', 'windows'],
            'resolution' => null,
        ];
    }

    /**
     * Get incident timeline
     */
    protected function getIncidentTimeline(array $incident): array
    {
        return [
            ['event' => 'Incident created', 'time' => $incident['created_at'] . ' 09:00', 'user' => 'Agent Smith'],
            ['event' => 'Initial assessment completed', 'time' => $incident['created_at'] . ' 10:30', 'user' => 'Agent Smith'],
            ['event' => 'Containment initiated', 'time' => $incident['created_at'] . ' 14:00', 'user' => 'Incident Response Team'],
            ['event' => 'Status changed to investigating', 'time' => now()->subHours(3)->format('Y-m-d H:i'), 'user' => 'Agent Smith'],
        ];
    }

    /**
     * Get linked alerts
     */
    protected function getLinkedAlerts(array $incident): array
    {
        return [
            ['id' => 'ALT-000145', 'title' => 'Malware process execution', 'severity' => 'high', 'linked_at' => now()->subHours(24)->format('H:i')],
            ['id' => 'ALT-000146', 'title' => 'Suspicious network connection', 'severity' => 'medium', 'linked_at' => now()->subHours(22)->format('H:i')],
            ['id' => 'ALT-000147', 'title' => 'File modification detected', 'severity' => 'medium', 'linked_at' => now()->subHours(20)->format('H:i')],
        ];
    }

    /**
     * Get incident statistics
     */
    protected function getIncidentStats(): array
    {
        return [
            'open' => 8,
            'investigating' => 15,
            'resolved' => 45,
            'critical' => 4,
            'high' => 12,
        ];
    }

    /**
     * Authorize create action
     */
    protected function authorizeCreate(): void
    {
        if (!$this->hasClearance(2)) {
            abort(403, 'Insufficient clearance level');
        }
    }

    /**
     * Authorize edit action
     */
    protected function authorizeEdit(array $incident): void
    {
        if (!$this->hasClearance(2)) {
            abort(403, 'Insufficient clearance level');
        }
    }

    /**
     * Authorize delete action
     */
    protected function authorizeDelete(array $incident): void
    {
        if (!$this->hasClearance(4)) {
            abort(403, 'Insufficient clearance level');
        }
    }
}
