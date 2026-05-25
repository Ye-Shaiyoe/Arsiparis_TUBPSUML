<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsUserActivity
{
    /**
     * Log aktivitas user
     *
     * @param string $action
     * @param string|null $modelType
     * @param int|null $modelId
     * @param string|null $description
     * @param array|null $changes
     */
    public static function logActivity(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        ?string $description = null,
        ?array $changes = null
    ): ActivityLog {
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

    /**
     * Helper untuk log create action
     */
    public static function logCreated(?string $modelType = null, ?int $modelId = null, ?string $description = null): ActivityLog
    {
        return self::logActivity('create', $modelType, $modelId, $description);
    }

    /**
     * Helper untuk log update action
     */
    public static function logUpdated(?string $modelType = null, ?int $modelId = null, ?string $description = null, ?array $changes = null): ActivityLog
    {
        return self::logActivity('update', $modelType, $modelId, $description, $changes);
    }

    /**
     * Helper untuk log delete action
     */
    public static function logDeleted(?string $modelType = null, ?int $modelId = null, ?string $description = null): ActivityLog
    {
        return self::logActivity('delete', $modelType, $modelId, $description);
    }

    /**
     * Helper untuk log download action
     */
    public static function logDownload(?string $modelType = null, ?int $modelId = null, ?string $description = null): ActivityLog
    {
        return self::logActivity('download', $modelType, $modelId, $description);
    }

    /**
     * Helper untuk log view action
     */
    public static function logView(?string $modelType = null, ?int $modelId = null, ?string $description = null): ActivityLog
    {
        return self::logActivity('view', $modelType, $modelId, $description);
    }
}
