<?php
// =============================================
// app/Http/Controllers/User/DashboardController.php
// =============================================

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if ($userId === null) {
            abort(403);
        }

        $totalSurat   = Surat::where('user_id', $userId)->count();
        $suratSelesai = Surat::where('user_id', $userId)->where('status', 'selesai')->count();
        $suratProses  = Surat::where('user_id', $userId)->whereIn('status', ['proses', 'revisi'])->count();
        $suratDitolak = Surat::where('user_id', $userId)->where('status', 'ditolak')->count();

        // Surat terbaru + tahapan untuk tracking
        $suratTerbaru = Surat::where('user_id', $userId)
                             ->with(['tahapans.diprosesByUser'])
                             ->latest()
                             ->limit(5)
                             ->get();

        // Surat aktif untuk SLA bar
        $suratAktif = Surat::where('user_id', $userId)
                           ->whereIn('status', ['proses', 'revisi'])
                           ->latest()
                           ->limit(3)
                           ->get();

        // Data untuk chart: distribusi jenis surat
        $jenisSurat = Surat::where('user_id', $userId)
                          ->select('jenis', DB::raw('count(*) as total'))
                          ->groupBy('jenis')
                          ->pluck('total', 'jenis')
                          ->toArray();

        // Data untuk chart: tren surat per bulan (6 bulan terakhir)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $trenBulanan = Surat::where('user_id', $userId)
                           ->where('created_at', '>=', $sixMonthsAgo)
                           ->select(
                               DB::raw("DATE_FORMAT(created_at, '%M %Y') as month"),
                               DB::raw('count(*) as total')
                           )
                           ->groupBy('month')
                           ->orderBy('month', 'asc')
                           ->pluck('total', 'month')
                           ->toArray();

        // Template surat dari storage
        /** @var FilesystemAdapter $publicDisk */
        $publicDisk = Storage::disk('public');
        $templates = collect($publicDisk->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url'  => $publicDisk->url($path),
            ])
            ->values();

        return view('dashboard', compact(
            'totalSurat', 'suratSelesai', 'suratProses', 'suratDitolak',
            'suratTerbaru', 'suratAktif', 'templates',
            'jenisSurat', 'trenBulanan'
        ));
    }
}