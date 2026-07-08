<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class NotificationStreamController extends Controller
{
    /**
     * Server-Sent Events stream untuk real-time notifications
     * Endpoint: /notif/stream
     */
    public function stream()
    {
        if (!Auth::check()) {
            return response('Unauthorized', 401);
        }

        $userId = Auth::id();
        $lastEventId = request()->query('lastId', 0);

        return new StreamedResponse(function () use ($userId, $lastEventId) {
            // Disable time limit untuk SSE stream (bisa berjalan lama)
            set_time_limit(0);
            ignore_user_abort(true);
            
            // Set headers untuk SSE
            header('Content-Type: text/event-stream');
            header('Cache-Control: no-cache');
            header('X-Accel-Buffering: no');
            header('Connection: keep-alive');

            // Connection keep-alive
            $heartbeatInterval = 30; // detik
            $lastHeartbeat = time();
            $maxDuration = 20 * 60; // 20 menit max — browser auto-reconnect, PHP worker lebih sering bebas
            $startTime = time();

            // Flag untuk track notif yang sudah dikirim
            $sentIds = [];
            $lastQueryTime = time();
            $lastCheckTime = time();

            error_log("[SSE] Stream started for user {$userId}, lastId={$lastEventId}");

            while (true) {
                try {
                    // Check jika client disconnect DULUAN (prevent unnecessary queries)
                    if (connection_aborted()) {
                        error_log("[SSE] Client disconnected, user {$userId}");
                        break;
                    }

                    // Check timeout
                    if (time() - $startTime > $maxDuration) {
                        error_log("[SSE] Stream timeout for user {$userId}");
                        echo "event: timeout\n";
                        echo "data: Connection expired, please reconnect\n\n";
                        break;
                    }

                    $currentTime = time();
                    $timeSinceLastCheck = $currentTime - $lastCheckTime;
                    $checkInterval = 3; // Query setiap 3 detik — cukup responsif, 3x lebih hemat
                    
                    if ($timeSinceLastCheck >= $checkInterval) {
                        try {
                            $notifications = DB::table('notifications')
                                ->where('notifiable_id', $userId)
                                ->where('notifiable_type', 'App\\Models\\User')
                                ->where('id', '>', $lastEventId)
                                ->orderBy('id', 'asc')
                                ->limit(10)
                                ->get();

                            // Send notifications
                            if ($notifications->isNotEmpty()) {
                                error_log("[SSE] Found " . count($notifications) . " new notifications for user {$userId}");
                                
                                foreach ($notifications as $notif) {
                                    if (!in_array($notif->id, $sentIds)) {
                                        $data = json_decode($notif->data, true) ?? [];
                                        
                                        echo "event: notification\n";
                                        echo "id: {$notif->id}\n";
                                        echo "data: " . json_encode([
                                            'id' => (int)$notif->id,
                                            'title' => $data['title'] ?? 'Notifikasi',
                                            'message' => $data['message'] ?? '',
                                            'type' => $data['type'] ?? 'info',
                                            'url' => $data['url'] ?? null,
                                            'read_at' => $notif->read_at,
                                        ]) . "\n\n";

                                        $lastEventId = $notif->id;
                                        $sentIds[] = $notif->id;
                                        
                                        error_log("[SSE] Sent notification ID {$notif->id} to user {$userId}");
                                    }
                                }

                                $unreadCount = $notifications->filter(fn($n) => is_null($n->read_at))->count();

                                echo "event: unread_count\n";
                                echo "data: " . json_encode(['count' => (int)$unreadCount]) . "\n\n";
                            }
                            
                            $lastCheckTime = $currentTime;
                        } catch (Exception $dbError) {
                            error_log("[SSE] DB Error: " . $dbError->getMessage() . ", user {$userId}");
                            // Continue jangan break, retry nanti
                        }
                    }

                    // Heartbeat untuk keep connection alive (lebih jarang: 45 detik)
                    if (time() - $lastHeartbeat > 45) {
                        echo "event: heartbeat\n";
                        echo "data: {}\n\n";
                        $lastHeartbeat = time();
                        error_log("[SSE] Heartbeat sent to user {$userId}");
                    }

                    // Flush output
                    if (ob_get_level() > 0) {
                        ob_flush();
                    }
                    flush();

                    sleep(3);

                } catch (Exception $e) {
                    error_log("[SSE] Error: " . $e->getMessage() . ", user {$userId}");
                    echo "event: error\n";
                    echo "data: " . json_encode(['message' => 'Connection error']) . "\n\n";
                    break;
                }
            }
            
            error_log("[SSE] Stream ended for user {$userId}");
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ]);
    }

    /**
     * Get unread count (untuk initial load)
     */
    public function unreadCount()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $count = DB::table('notifications')
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', 'App\\Models\\User')
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }
}
