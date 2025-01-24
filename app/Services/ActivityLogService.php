<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log($activityType, $description, $properties = [])
    {
        return ActivityLog::create([
            'user_id' => Auth::id(),
            'activity_type' => $activityType,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'properties' => $properties
        ]);
    }

    public static function logLogin(User $user, string $description)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'login',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function logLogout(User $user, string $description)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'logout',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function logCreate(User $user, string $description)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'create',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function logUpdate(User $user, string $description)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'update',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public static function logDelete(User $user, string $description)
    {
        ActivityLog::create([
            'user_id' => $user->id,
            'activity_type' => 'delete',
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
