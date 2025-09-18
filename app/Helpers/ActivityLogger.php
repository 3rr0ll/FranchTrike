<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $category, string $action, ?string $description = null, array $data = [])
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'category' => $category,
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ]);
    }
}
