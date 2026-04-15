<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Surat;
use App\Models\User;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class KomentarController extends Controller
{
    /**
     * Store komentar baru
     */
    public function store(Request $request, Surat $surat)
    {
        $request->validate([
            'isi' => 'required|string|max:2000',
        ]);

        $komentar = Komentar::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'parent_id' => null,
            'isi' => $request->isi,
        ]);

        // Kirim notifikasi ke pemilik surat (jika bukan yang komen)
        if ($surat->user_id !== Auth::id()) {
            $surat->user->notify(new NewCommentNotification($komentar));
        }

        // Kirim notifikasi ke admin lain yang pernah komentar
        $adminIds = Komentar::where('surat_id', $surat->id)
            ->where('user_id', '!=', Auth::id())
            ->whereHas('user', function ($q) {
                $q->where('role', '!=', 'user');
            })
            ->pluck('user_id')
            ->unique();

        User::whereIn('id', $adminIds)->each(function ($user) use ($komentar) {
            $user->notify(new NewCommentNotification($komentar));
        });

        return response()->json([
            'success' => true,
            'komentar' => $komentar->load('user'),
        ]);
    }

    /**
     * Reply komentar
     */
    public function reply(Request $request, Surat $surat, Komentar $komentar)
    {
        $request->validate([
            'isi' => 'required|string|max:2000',
        ]);

        if ($komentar->surat_id !== $surat->id) {
            return response()->json(['success' => false, 'message' => 'Invalid reply'], 400);
        }

        $reply = Komentar::create([
            'surat_id' => $surat->id,
            'user_id' => Auth::id(),
            'parent_id' => $komentar->id,
            'isi' => $request->isi,
        ]);

        // Kirim notifikasi ke owner komentar
        if ($komentar->user_id !== Auth::id()) {
            $komentar->user->notify(new NewCommentNotification($reply));
        }

        return response()->json([
            'success' => true,
            'reply' => $reply->load('user'),
        ]);
    }

    /**
     * Delete komentar (soft delete)
     */
    public function destroy(Surat $surat, Komentar $komentar)
    {
        // Cek authorization: owner atau admin
        if ($komentar->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($komentar->surat_id !== $surat->id) {
            return response()->json(['success' => false, 'message' => 'Invalid'], 400);
        }

        $komentar->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Get komentar untuk surat
     */
    public function index(Surat $surat)
    {
        $komentars = $surat->komentars;

        return response()->json([
            'success' => true,
            'komentars' => $komentars,
        ]);
    }
}
