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
        // Validate query parameters
        $validated = $request->validate([
            'tahun' => 'nullable|integer|between:2020,' . date('Y'),
            'to' => 'nullable|in:admin,it_support', // Whitelist validation
        ]);

        $query = Aspirasi::where('user_id', Auth::id())->latest();

        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $validated['tahun']);
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
            'tujuan' => 'nullable|in:admin,it_support',
        ]);

        $aspirasi = Aspirasi::create([
            'user_id' => Auth::id(),
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tujuan' => $request->tujuan ?? 'admin',
        ]);

        // Kirim notif ke pihak yang dituju
        if ($aspirasi->tujuan === 'it_support') {
            $targets = \App\Models\User::where('role', 'it_support')->get();
        } else {
            $targets = \App\Models\User::whereIn('role', ['admin', 'admin_kasubbag_tu', 'admin_kepala_balai'])->get();
        }

        foreach ($targets as $target) {
            $target->notify(new \App\Notifications\AspirasiBaruNotification($aspirasi));
        }

        // Redirect berdasarkan tujuan
        if ($aspirasi->tujuan === 'it_support') {
            return redirect()->route('itsupport.dashboard')->with('success', 'Aspirasi Anda berhasil dikirim ke IT Support!');
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
