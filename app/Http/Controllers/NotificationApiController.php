<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    // Polling: ambil notif baru sejak timestamp tertentu
    public function poll(Request $request)
    {
        $since = $request->get('since'); // ISO timestamp dari frontend

        $query = Auth::user()->notifications();

        if ($since) {
            try {
                // Parse ISO 8601 string dari frontend agar dicocokkan dengan timezone database yang benar
                $carbonSince = \Illuminate\Support\Carbon::parse($since);
                $query->where('created_at', '>', $carbonSince);
            } catch (\Exception $e) {
                $query->where('created_at', '>', $since);
            }
        } else {
            // Pertama kali load: ambil yang unread saja
            $query->whereNull('read_at');
        }

        $notifs = $query->latest()->limit(10)->get()->map(fn($n) => [
            'id'       => $n->id,
            'type'     => $n->data['type']    ?? 'info',
            'title'    => $n->data['title']   ?? 'Notifikasi',
            'message'  => $n->data['message'] ?? '',
            'url'      => $n->data['url']      ?? null,
            'read'     => !is_null($n->read_at),
            'time'     => $n->created_at->diffForHumans(),
            'created'  => $n->created_at->toISOString(),
        ]);

        return response()->json([
            'notifications' => $notifs,
            'unread_count'  => Auth::user()->unreadNotifications()->count(),
            'server_time'   => now()->toISOString(),
        ]);
    }

    // Tandai satu notif dibaca
    public function markRead(string $id)
    {
        $notif = Auth::user()->notifications()->find($id);
        if ($notif) $notif->markAsRead();

        return response()->json(['ok' => true]);
    }

    // Tandai semua dibaca
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['ok' => true]);
    }

    // Hapus satu notif
    public function destroy(string $id)
    {
        $notif = Auth::user()->notifications()->find($id);
        if ($notif) $notif->delete();

        return response()->json(['ok' => true]);
    }

    // Hapus semua notif
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();
        return response()->json(['ok' => true]);
    }
}
