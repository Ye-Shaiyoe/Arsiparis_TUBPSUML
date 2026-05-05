<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    public function index(Request $request)
    {
        $query = Aspirasi::where('user_id', Auth::id())->latest();

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        $aspirasis = $query->paginate(10)->withQueryString();
        return view('user.aspirasi.index', compact('aspirasis'), ['title' => 'Kotak Aspirasi']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'kategori' => 'required|in:saran,keluhan,pertanyaan',
        ]);

        $aspirasi = Aspirasi::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
        ]);

        // Kirim notif ke Admin
        $admins = \App\Models\User::whereIn('role', ['admin', 'admin_kasubbag_tu', 'admin_kepala_balai'])->get();
        foreach ($admins as $admin) {
            $admin->notify(new \App\Notifications\AspirasiBaruNotification($aspirasi));
        }

        return redirect()->route('user.aspirasi.index')->with('success', 'Aspirasi Anda berhasil dikirim!');
    }

    public function destroy(Aspirasi $aspirasi)
    {
        if ($aspirasi->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        if ($aspirasi->balasan) {
            return redirect()->back()->with('error', 'Aspirasi yang sudah dibalas tidak bisa dihapus.');
        }

        $aspirasi->delete();
        return redirect()->route('user.aspirasi.index')->with('success', 'Aspirasi berhasil dihapus.');
    }
}
