<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        if ($user) {
            ActivityLog::create([
                'user_id' => $user->id,
                'activity_type' => 'login',
                'description' => 'User logged in to the system',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'properties' => json_encode([
                    'browser' => 'Chrome',
                    'platform' => 'Windows',
                    'device' => 'Desktop'
                ])
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'activity_type' => 'create',
                'description' => 'Created a new record',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'properties' => json_encode([
                    'table' => 'users',
                    'action' => 'create',
                    'data' => [
                        'name' => 'Test User',
                        'email' => 'test@example.com'
                    ]
                ])
            ]);
        }

        // System activity log
        ActivityLog::create([
            'activity_type' => 'system',
            'description' => 'System maintenance performed',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'System/1.0',
            'properties' => json_encode([
                'type' => 'maintenance',
                'details' => 'Regular system maintenance and cleanup'
            ])
        ]);
    }
}
