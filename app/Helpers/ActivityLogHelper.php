<?php

/**
 * Helper functions untuk Activity Logging
 */

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

if (!function_exists('logUserActivity')) {
    /**
     * Log aktivitas user
     *
     * @param string $action
     * @param string|null $modelType
     * @param int|null $modelId
     * @param string|null $description
     * @param array|null $changes
     * @return ActivityLog|null
     */
    function logUserActivity(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $changes = null
    ): ?ActivityLog {
        $user = Auth::user();
        
        if (!$user) {
            return null;
        }
        
        return ActivityLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }
}

if (!function_exists('log_created')) {
    function log_created(?string $modelType = null, ?int $modelId = null, ?string $description = null): ?ActivityLog
    {
        return logUserActivity('create', $modelType, $modelId, $description);
    }
}

if (!function_exists('log_updated')) {
    function log_updated(?string $modelType = null, ?int $modelId = null, ?string $description = null, ?array $changes = null): ?ActivityLog
    {
        return logUserActivity('update', $modelType, $modelId, $description, $changes);
    }
}

if (!function_exists('log_deleted')) {
    function log_deleted(?string $modelType = null, ?int $modelId = null, ?string $description = null): ?ActivityLog
    {
        return logUserActivity('delete', $modelType, $modelId, $description);
    }
}

if (!function_exists('log_download')) {
    function log_download(?string $modelType = null, ?int $modelId = null, ?string $description = null): ?ActivityLog
    {
        return logUserActivity('download', $modelType, $modelId, $description);
    }
}

if (!function_exists('log_view')) {
    function log_view(?string $modelType = null, ?int $modelId = null, ?string $description = null): ?ActivityLog
    {
        return logUserActivity('view', $modelType, $modelId, $description);
    }
}
