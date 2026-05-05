<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi untuk admin
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all'); // all, unread, read

        $query = Auth::user()->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->latest('created_at')->paginate(20);

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('admin.notifikasi.index', compact('notifications', 'unreadCount', 'filter'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        // Redirect ke URL dari notifikasi jika ada
        $url = $notification->data['url'] ?? route('admin.dashboard');

        // SMART REDIRECT: Jika URL mengandung ID angka (misal /surat/125)
        if (preg_match('/\/surat\/(\d+)/', $url, $matches)) {
            $suratId = $matches[1];
            $surat = \App\Models\Surat::find($suratId);
            if ($surat) {
                $url = route('user.surat.show', $surat);
            }
        }
        
        return redirect($url);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => 0
            ]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = Auth::user()->notifications();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $query->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back()->with('success', 'Semua notifikasi berhasil dihapus.');
    }
}
