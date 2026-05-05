<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Surat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatistikController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $tahun = $request->input('tahun', date('Y'));

        // 1. Get statistics for status cards (filtered by year)
        $baseQuery = Surat::where('user_id', $userId)->whereYear('created_at', $tahun);
        
        $totalSurat = (clone $baseQuery)->count();
        $totalDisetujui = (clone $baseQuery)->where('status', 'selesai')->count();
        $totalDitolak = (clone $baseQuery)->where('status', 'ditolak')->count();
        $totalProses = (clone $baseQuery)->whereIn('status', ['proses', 'revisi'])->count();

        // 2. Data for Chart: Group by Month (All months in the selected year)
        $monthlyStats = [];
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($m = 1; $m <= 12; $m++) {
            $count = Surat::where('user_id', $userId)
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->count();
            $monthlyStats[] = $count;
        }

        // 3. Data for Chart: Group by Status
        $statusLabels = ['Disetujui', 'Ditolak', 'Diproses'];
        $statusStats = [$totalDisetujui, $totalDitolak, $totalProses];

        // 4. Data for Chart: Distribusi Jenis Surat
        $jenisSurat = (clone $baseQuery)
                          ->select('jenis', DB::raw('count(*) as total'))
                          ->groupBy('jenis')
                          ->pluck('total', 'jenis')
                          ->toArray();

        return view('user.statistik.index', [
            'title' => 'Statistik & Chart',
            'tahun' => $tahun,
            'totalSurat' => $totalSurat,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'totalProses' => $totalProses,
            'chartLabels' => $labels,
            'chartData' => $monthlyStats,
            'statusLabels' => $statusLabels,
            'statusData' => $statusStats,
            'jenisSurat' => $jenisSurat
        ]);
    }
}
