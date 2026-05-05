<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Notifications\AspirasiDibalasNotification;
use Illuminate\Http\Request;

class AspirasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Aspirasi::with('user')->latest();

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $aspirasis = $query->paginate(15)->withQueryString();
        return view('admin.aspirasi.index', compact('aspirasis'), ['title' => 'Manajemen Aspirasi']);
    }

    public function update(Request $request, Aspirasi $aspirasi)
    {
        $request->validate([
            'balasan' => 'required|string',
        ]);

        $aspirasi->update([
            'balasan' => $request->balasan,
            'status' => 'dibalas',
            'dibalas_at' => now(),
        ]);

        // Kirim notif ke user
        $aspirasi->user->notify(new AspirasiDibalasNotification($aspirasi));

        return back()->with('success', 'Balasan berhasil dikirim!');
    }

    public function markAsRead(Aspirasi $aspirasi)
    {
        if ($aspirasi->status === 'pending') {
            $aspirasi->update(['status' => 'dibaca']);
        }
        return response()->json(['ok' => true]);
    }

    public function destroy(Aspirasi $aspirasi)
    {
        $aspirasi->delete();
        return back()->with('success', 'Aspirasi berhasil dihapus.');
    }
}
