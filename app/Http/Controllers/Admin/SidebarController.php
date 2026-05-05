<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Support\Facades\Auth;

class SidebarController extends Controller
{
    public function counts()
    {
        $admin = Auth::user();
        if (!$admin) return response()->json(['error' => 'Unauthenticated'], 401);

        // 1. Notif Count
        $notifCount = $admin->unreadNotifications()->count();

        // 2. Antrian Count (Logic copied from Dashboard)
        $antrianQuery = Surat::where(function($q) use ($admin) {
            $q->where('status', 'proses')
              ->orWhere('status', 'revisi');
            if ($admin->role === 'admin_aspirasi') {
                $q->orWhere(function($sub) {
                    $sub->where('status', 'revisi_admin')->where('tahap_sekarang', 2);
                });
            }
        });

        if ($admin->role === 'admin_aspirasi') {
            $antrianQuery->where(function($q) {
                $q->where('tahap_sekarang', 2)->orWhere('tahap_sekarang', '>=', 5);
            });
        } elseif ($admin->role === 'admin_kasubbag_tu') {
            $antrianQuery->where('tahap_sekarang', 3);
        } elseif ($admin->role === 'admin_kepala_balai') {
            $antrianQuery->where('tahap_sekarang', 4);
        }

        $antrianCount = $antrianQuery->count();

        return response()->json([
            'notifCount' => $notifCount,
            'antrianCount' => $antrianCount,
        ]);
    }
}
