<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    /**
     * Display users list
     */
    public function index(Request $request)
    {
        $user = $this->authenticatedUser();

        // Check clearance level
        if (!$this->hasClearance(3)) {
            return $this->redirectError('Insufficient clearance level');
        }

        $filters = [
            'search' => $request->get('search'),
            'clearance' => $request->get('clearance'),
            'status' => $request->get('status', 'active'),
        ];

        // Get users with filters
        $users = $this->getUsers($filters, 50);

        // Get stats
        $stats = $this->getUserStats();

        return view('users.index', compact('users', 'stats', 'filters'));
    }

    /**
     * Display single user details
     */
    public function show(Request $request, string $id)
    {
        $currentUser = $this->authenticatedUser();

        // Check clearance level
        if (!$this->hasClearance(3)) {
            return $this->redirectError('Insufficient clearance level');
        }

        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->redirectError('User not found');
        }

        // Get user activity
        $activity = $this->getUserActivity($user);

        // Get user incidents
        $incidents = $this->getUserIncidents($user, 5);

        return view('users.show', compact('user', 'activity', 'incidents'));
    }

    /**
     * Show user creation form
     */
    public function create(Request $request)
    {
        $currentUser = $this->authenticatedUser();

        // Only level 4+ can create users
        if (!$this->hasClearance(4)) {
            return $this->redirectError('Insufficient clearance level');
        }

        return view('users.create');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $currentUser = $this->authenticatedUser();

        // Only level 4+ can create users
        if (!$this->hasClearance(4)) {
            return $this->redirectError('Insufficient clearance level');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'clearance_level' => 'required|integer|min:1|max:5',
            'timezone' => 'nullable|string|max:50',
        ]);

        // Check if trying to assign higher clearance than own
        if ($validated['clearance_level'] >= $currentUser->clearance_level) {
            return $this->redirectError('Cannot assign clearance level equal to or higher than your own');
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'clearance_level' => $validated['clearance_level'],
            'timezone' => $validated['timezone'] ?? 'UTC',
            'email_verified_at' => now(),
        ]);

        return $this->redirectSuccess('User created successfully', route('users.show', $user->id));
    }

    /**
     * Show user edit form
     */
    public function edit(Request $request, string $id)
    {
        $currentUser = $this->authenticatedUser();
        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->redirectError('User not found');
        }

        // Can only edit users with lower clearance, or own profile
        if ($user->clearance_level >= $currentUser->clearance_level && $user->id !== $currentUser->id) {
            return $this->redirectError('Cannot edit users with equal or higher clearance level');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update user
     */
    public function update(Request $request, string $id)
    {
        $currentUser = $this->authenticatedUser();
        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->redirectError('User not found');
        }

        // Can only update users with lower clearance, or own profile
        if ($user->clearance_level >= $currentUser->clearance_level && $user->id !== $currentUser->id) {
            return $this->redirectError('Cannot modify users with equal or higher clearance level');
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'timezone' => 'nullable|string|max:50',
            'clearance_level' => 'sometimes|required|integer|min:1|max:5',
        ]);

        // Check clearance level modification permission
        if (isset($validated['clearance_level'])) {
            if ($validated['clearance_level'] >= $currentUser->clearance_level) {
                return $this->redirectError('Cannot assign clearance level equal to or higher than your own');
            }
        }

        $user->update($validated);

        return $this->redirectSuccess('User updated successfully', route('users.show', $user->id));
    }

    /**
     * Delete user
     */
    public function destroy(Request $request, string $id)
    {
        $currentUser = $this->authenticatedUser();
        $user = User::where('id', $id)->first();

        if (!$user) {
            return $this->redirectError('User not found');
        }

        // Cannot delete self
        if ($user->id === $currentUser->id) {
            return $this->redirectError('Cannot delete your own account');
        }

        // Can only delete users with lower clearance
        if ($user->clearance_level >= $currentUser->clearance_level) {
            return $this->redirectError('Cannot delete users with equal or higher clearance level');
        }

        $user->delete();

        return $this->redirectSuccess('User deleted successfully', route('users.index'));
    }

    /**
     * Get users with filters
     */
    protected function getUsers(array $filters, int $limit): array
    {
        $query = User::query();

        // Apply search filter
        if ($filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        // Apply clearance filter
        if ($filters['clearance']) {
            $query->where('clearance_level', $filters['clearance']);
        }

        // Apply status filter (active = verified email, inactive = not verified)
        if ($filters['status'] === 'active') {
            $query->whereNotNull('email_verified_at');
        } elseif ($filters['status'] === 'inactive') {
            $query->whereNull('email_verified_at');
        }

        return $query->orderBy('created_at', 'desc')->take($limit)->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'clearance_level' => $user->clearance_level,
                'clearance_name' => $user->clearance_name,
                'timezone' => $user->timezone,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at?->format('M d, Y'),
                'last_seen' => $this->getLastSeen($user),
            ];
        })->toArray();
    }

    /**
     * Get user statistics
     */
    protected function getUserStats(): array
    {
        return [
            'total' => User::count(),
            'active' => User::whereNotNull('email_verified_at')->count(),
            'inactive' => User::whereNull('email_verified_at')->count(),
            'level5' => User::where('clearance_level', 5)->count(),
            'level4' => User::where('clearance_level', 4)->count(),
            'level3' => User::where('clearance_level', 3)->count(),
            'level2' => User::where('clearance_level', 2)->count(),
            'level1' => User::where('clearance_level', 1)->count(),
        ];
    }

    /**
     * Get user activity log
     */
    protected function getUserActivity(User $user): array
    {
        // In real app, query activity logs table
        return [
            ['action' => 'Login successful', 'ip' => '192.168.1.100', 'time' => now()->subMinutes(5)->format('M d, H:i')],
            ['action' => 'Viewed dashboard', 'ip' => '192.168.1.100', 'time' => now()->subMinutes(10)->format('M d, H:i')],
            ['action' => 'Updated incident #1234', 'ip' => '192.168.1.100', 'time' => now()->subHours(2)->format('M d, H:i')],
            ['action' => 'Password changed', 'ip' => '192.168.1.100', 'time' => now()->subDays(1)->format('M d, H:i')],
            ['action' => 'Login successful', 'ip' => '192.168.1.100', 'time' => now()->subDays(1)->format('M d, H:i')],
        ];
    }

    /**
     * Get user incidents
     */
    protected function getUserIncidents(User $user, int $limit): array
    {
        // In real app, query incidents assigned to user
        $incidents = [];
        for ($i = 0; $i < $limit; $i++) {
            $incidents[] = [
                'id' => 'INC-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'title' => 'Security incident ' . ($i + 1),
                'status' => ['open', 'investigating', 'resolved'][array_rand(['open', 'investigating', 'resolved'])],
                'created_at' => now()->subDays(rand(1, 30))->format('M d, Y'),
            ];
        }
        return $incidents;
    }

    /**
     * Get last seen time
     */
    protected function getLastSeen(User $user): string
    {
        // In real app, query sessions or activity logs
        return now()->subMinutes(rand(5, 1440))->format('M d, H:i');
    }
}
