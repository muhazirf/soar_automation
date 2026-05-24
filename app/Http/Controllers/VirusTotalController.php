<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VirusTotalController extends BaseController
{
    /**
     * Display VirusTotal analysis dashboard
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();
        $filters = [
            'type' => $request->get('type', 'all'),
            'result' => $request->get('result'),
            'search' => $request->get('search'),
        ];

        // Get scan results (in real app, query database)
        $scans = $this->getScans($filters, 50);

        // Get stats
        $stats = $this->getScanStats();

        return view('virustotal.index', compact('scans', 'stats', 'filters'));
    }

    /**
     * Display single scan result details
     */
    public function show(Request $request, string $id)
    {
        $scan = $this->getScan($id);

        if (!$scan) {
            return $this->redirectError('Scan not found');
        }

        // Get scan results from vendors
        $vendorResults = $this->getVendorResults($scan);

        // Get related scans
        $relatedScans = $this->getRelatedScans($scan, 5);

        return view('virustotal.show', compact('scan', 'vendorResults', 'relatedScans'));
    }

    /**
     * Submit new file/url for scanning
     */
    public function scan(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:file,url,ip,domain',
            'target' => 'required|string|max:500',
        ]);

        // In real app, integrate with VirusTotal API
        // $response = Http::withToken(config('services.virustotal.api_key'))
        //     ->post('https://www.virustotal.com/api/v3/...', [...]);

        return $this->redirectSuccess('Scan submitted successfully', route('virustotal.index'));
    }

    /**
     * Rescan existing item
     */
    public function rescan(Request $request, string $id)
    {
        // In real app, trigger rescan via VirusTotal API
        // $response = Http::withToken(config('services.virustotal.api_key'))
        //     ->post("https://www.virustotal.com/api/v3/analyses/{id}");

        return $this->redirectSuccess('Rescan submitted successfully');
    }

    /**
     * Get scan results with filters
     */
    protected function getScans(array $filters, int $limit): array
    {
        $types = ['file', 'url', 'ip', 'domain', 'hash'];
        $results = ['malicious', 'suspicious', 'clean', 'undetected'];
        $fileTypes = [
            'PE32 executable (GUI) Intel 80386',
            'ELF 64-bit LSB executable',
            'PDF document',
            'Rich Text Format',
            'JavaScript',
            'HTML document',
            'Zip archive data',
        ];

        $scans = [];
        $count = rand(20, 60);

        for ($i = 0; $i < $count; $i++) {
            $type = $types[array_rand($types)];
            $result = $results[array_rand($results)];

            // Apply filters
            if ($filters['type'] && $filters['type'] !== 'all' && $type !== $filters['type']) continue;
            if ($filters['result'] && $result !== $filters['result']) continue;

            $malicious = $result === 'malicious' ? rand(5, 45) : 0;
            $suspicious = $result === 'suspicious' ? rand(1, 10) : rand(0, 3);
            $harmless = rand(40, 60);
            $timeout = rand(0, 5);

            $scans[] = [
                'id' => 'VT-' . str_pad($i + 1, 8, '0', STR_PAD_LEFT),
                'type' => $type,
                'target' => $this->generateTarget($type),
                'result' => $result,
                'detection_ratio' => "{$malicious}/{$malicious + $harmless}",
                'malicious' => $malicious,
                'suspicious' => $suspicious,
                'harmless' => $harmless,
                'timeout' => $timeout,
                'scan_date' => now()->subMinutes(rand(5, 2880))->format('M d, H:i'),
                'file_type' => $fileTypes[array_rand($fileTypes)],
                'size' => $this->generateSize(),
            ];
        }

        // Apply search filter
        if ($filters['search']) {
            $search = strtolower($filters['search']);
            $scans = array_filter($scans, function ($scan) use ($search) {
                return str_contains(strtolower($scan['target']), $search) ||
                       str_contains(strtolower($scan['id']), $search);
            });
        }

        return array_slice($scans, 0, $limit);
    }

    /**
     * Get single scan result
     */
    protected function getScan(string $id): ?array
    {
        return [
            'id' => $id,
            'type' => 'file',
            'target' => 'malware_sample.exe',
            'md5' => '44d88612fea8a8f36de82e1278abb02f',
            'sha1' => '3395856ce81f2b7382dee72602f798b642f14140',
            'sha256' => '275a021bbfb6489e54d471899f7db9d1663fc695ec2fe2a2c4538aabf651fd0f',
            'result' => 'malicious',
            'detection_ratio' => '45/60',
            'malicious' => 45,
            'suspicious' => 2,
            'harmless' => 11,
            'timeout' => 2,
            'scan_date' => now()->subMinutes(30)->format('M d, Y H:i:s'),
            'file_type' => 'PE32 executable (GUI) Intel 80386, for MS Windows',
            'size' => '45.2 KB',
            'first_seen' => now()->subDays(15)->format('M d, Y'),
            'last_seen' => now()->subMinutes(30)->format('M d, Y'),
            'submission_count' => rand(1, 50),
            'tags' => ['trojan', 'ransomware', 'windows'],
        ];
    }

    /**
     * Get vendor scan results
     */
    protected function getVendorResults(array $scan): array
    {
        $vendors = [
            ['name' => 'Kaspersky', 'result' => 'malicious', 'category' => 'malicious'],
            ['name' => 'McAfee', 'result' => 'Trojan.Generic', 'category' => 'malicious'],
            ['name' => 'Symantec', 'result' => 'Trojan.Zlob', 'category' => 'malicious'],
            ['name' => 'TrendMicro', 'result' => 'Worm_Generic', 'category' => 'malicious'],
            ['name' => 'BitDefender', 'result' => 'Gen:Trojan.Heur', 'category' => 'malicious'],
            ['name' => 'ESET-NOD32', 'result' => 'a variant of Win32/Trojan', 'category' => 'malicious'],
            ['name' => 'Avast', 'result' => 'Win32:Malware-gen', 'category' => 'malicious'],
            ['name' => 'AVG', 'result' => 'Win32/Malware', 'category' => 'malicious'],
            ['name' => 'Microsoft', 'result' => 'Trojan:Win32/Wacatac', 'category' => 'malicious'],
            ['name' => 'Sophos', 'result' => 'Troj/Agent-HKE', 'category' => 'malicious'],
            ['name' => 'Avira', 'result' => 'TR/Agent.406608', 'category' => 'malicious'],
            ['name' => 'Fortinet', 'result' => 'W32/Agent.UKY', 'category' => 'malicious'],
            ['name' => 'F-Secure', 'result' => 'Trojan.TR/Agent', 'category' => 'malicious'],
            ['name' => 'DrWeb', 'result' => 'Trojan.DownLoader', 'category' => 'malicious'],
            ['name' => 'ClamAV', 'result' => 'Win.Trojan.Agent', 'category' => 'malicious'],
            ['name' => ' CrowdStrike', 'result' => 'clean', 'category' => 'clean'],
            ['name' => 'Palo Alto', 'result' => 'clean', 'category' => 'clean'],
            ['name' => 'Cisco', 'result' => 'clean', 'category' => 'clean'],
            ['name' => 'FireEye', 'result' => 'suspicious', 'category' => 'suspicious'],
            ['name' => 'Carbon Black', 'result' => 'clean', 'category' => 'clean'],
        ];

        return $vendors;
    }

    /**
     * Get related scans
     */
    protected function getRelatedScans(array $scan, int $limit): array
    {
        $related = [];
        for ($i = 0; $i < $limit; $i++) {
            $malicious = rand(0, 30);
            $related[] = [
                'id' => 'VT-' . str_pad(rand(1, 99999), 8, '0', STR_PAD_LEFT),
                'target' => 'related_file_' . $i . '.exe',
                'result' => $malicious > 0 ? 'malicious' : 'clean',
                'detection_ratio' => "{$malicious}/60",
                'scan_date' => now()->subMinutes(rand(30, 1440))->format('M d, H:i'),
            ];
        }
        return $related;
    }

    /**
     * Get scan statistics
     */
    protected function getScanStats(): array
    {
        return [
            'total' => 1250,
            'malicious' => 185,
            'suspicious' => 95,
            'clean' => 820,
            'undetected' => 150,
            'today_scans' => 45,
        ];
    }

    /**
     * Generate target based on type
     */
    protected function generateTarget(string $type): string
    {
        return match($type) {
            'file' => 'file_' . rand(1000, 9999) . '.exe',
            'url' => 'http://' . $this->generateRandomString(10) . '.com/' . $this->generateRandomString(8),
            'ip' => rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255),
            'domain' => $this->generateRandomString(12) . '.com',
            'hash' => $this->generateRandomString(32),
            default => 'unknown',
        };
    }

    /**
     * Generate file size
     */
    protected function generateSize(): string
    {
        $sizes = ['12.5 KB', '45.2 KB', '128.7 KB', '1.2 MB', '3.5 MB', '890 KB'];
        return $sizes[array_rand($sizes)];
    }

    /**
     * Generate random string
     */
    protected function generateRandomString(int $length): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle(str_repeat($chars, ceil($length / strlen($chars)))), 1, $length);
    }
}
