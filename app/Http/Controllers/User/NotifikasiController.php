<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    // Lihat semua notifikasi
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('user.notifikasi.index', compact('notifications'));
    }

    // Klik notifikasi (Redirect ke surat)
    public function read(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        $url = $notif->data['url'] ?? route('dashboard');

        // SMART REDIRECT: Jika URL mengandung ID angka (misal /surat/125)
        // Kita ubah jadi UUID (misal /surat/uuid-abc-123)
        if (preg_match('/\/surat\/(\d+)/', $url, $matches)) {
            $suratId = $matches[1];
            $surat = \App\Models\Surat::find($suratId);
            if ($surat) {
                $url = route('user.surat.show', $surat);
            }
        }

        return redirect($url);
    }

    // Tandai satu dibaca (AJAX)
    public function markAsRead(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->markAsRead();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back();
    }

    // Tandai semua dibaca (AJAX)
    public function readAll()
    {
        Auth::user()->unreadNotifications->markAsRead();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'unreadCount' => 0
            ]);
        }

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    // Hapus satu notif (AJAX)
    public function destroy(string $id)
    {
        $notif = Auth::user()->notifications()->findOrFail($id);
        $notif->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'ok' => true,
                'unreadCount' => Auth::user()->unreadNotifications()->count()
            ]);
        }

        return back()->with('success', 'Notifikasi dihapus.');
    }

    // Hapus semua notif (AJAX)
    public function destroyAll()
    {
        Auth::user()->notifications()->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'ok' => true,
                'unreadCount' => 0
            ]);
        }

        return back()->with('success', 'Semua notifikasi dihapus.');
    }
}