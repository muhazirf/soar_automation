<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Test Admin',
                'email' => 'test@example.com',
                'password' => 'password',
                'clearance_level' => 5,
            ],
            [
                'name' => 'Security Analyst',
                'email' => 'analyst@example.com',
                'password' => 'password',
                'clearance_level' => 4,
            ],
            [
                'name' => 'Incident Responder',
                'email' => 'responder@example.com',
                'password' => 'password',
                'clearance_level' => 3,
            ],
            [
                'name' => 'Junior Analyst',
                'email' => 'junior@example.com',
                'password' => 'password',
                'clearance_level' => 2,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make($user['password']),
                    'clearance_level' => $user['clearance_level'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
