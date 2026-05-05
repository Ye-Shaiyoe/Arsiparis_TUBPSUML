<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        // Filter bulan dan tahun
        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // Query surat dengan data pengolah
        $query = Surat::whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun)
                      ->with([
                          'user',
                          'tahapans' => function ($query) {
                              $query->where('status', 'selesai')
                                    ->whereNotNull('diproses_oleh')
                                    ->with('diprosesByUser')
                                    ->orderBy('tahap');
                          }
                      ])
                      ->orderByDesc('created_at');

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter jenis surat
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Search judul
        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $riwayat = $query->paginate(20)->withQueryString();

        // Statistik ringkas
        $totalSurat = Surat::whereMonth('created_at', $bulan)
                           ->whereYear('created_at', $tahun)
                           ->count();

        $totalSelesai = Surat::whereMonth('created_at', $bulan)
                             ->whereYear('created_at', $tahun)
                             ->where('status', 'selesai')
                             ->count();

        $totalProses = Surat::whereMonth('created_at', $bulan)
                            ->whereYear('created_at', $tahun)
                            ->where('status', 'proses')
                            ->count();

        $totalDitolak = Surat::whereMonth('created_at', $bulan)
                             ->whereYear('created_at', $tahun)
                             ->where('status', 'ditolak')
                             ->count();

        // List semua jenis surat untuk filter
        $jenisSurat = Surat::JENIS_LABEL;

        return view('admin.riwayat.index', compact(
            'riwayat',
            'bulan',
            'tahun',
            'totalSurat',
            'totalSelesai',
            'totalProses',
            'totalDitolak',
            'jenisSurat'
        ));
    }
}
