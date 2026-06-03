<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->search ?? '');
        
        if (strlen($search) >= 2) {
            $query = User::whereNotNull('uuid');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
            $users = $query->latest()->paginate(12)->withQueryString();
        } else {
            $users = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);
            $users->withPath($request->url());
            $users->appends($request->query());
        }

        return view('user.pegawai.index', [
            'title' => 'Direktori Pegawai',
            'users' => $users
        ]);
    }

    public function show(Request $request, User $user)
    {
        $tahun = $request->input('tahun', date('Y'));

        // 1. Get statistics for status cards (filtered by year)
        $baseQuery = $user->surats()->whereYear('created_at', $tahun);
        
        $totalSurat = (clone $baseQuery)->count();
        $totalDisetujui = (clone $baseQuery)->where('status', 'selesai')->count();
        $totalDitolak = (clone $baseQuery)->where('status', 'ditolak')->count();
        $totalProses = (clone $baseQuery)->whereIn('status', ['proses', 'revisi'])->count();

        // 2. Data for Chart: Group by Month (All months in the selected year)
        $monthlyStats = [];
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($m = 1; $m <= 12; $m++) {
            $count = $user->surats()
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->count();
            $monthlyStats[] = $count;
        }

        // 3. Detailed Monthly Data for Table with Status Breakdown and Sparkline
        $monthlyDetails = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyQuery = $user->surats()
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m);
            
            $mTotal = (clone $monthlyQuery)->count();
            $mDisetujui = (clone $monthlyQuery)->where('status', 'selesai')->count();
            $mProses = (clone $monthlyQuery)->whereIn('status', ['proses', 'revisi'])->count();
            $mDitolak = (clone $monthlyQuery)->where('status', 'ditolak')->count();

            // Daily activity in that month for inline sparkline
            $dailyData = $user->surats()
                ->whereYear('created_at', $tahun)
                ->whereMonth('created_at', $m)
                ->select(DB::raw('DAY(created_at) as day'), DB::raw('count(*) as count'))
                ->groupBy('day')
                ->pluck('count', 'day')
                ->toArray();

            $daysInMonth = \Carbon\Carbon::create($tahun, $m, 1)->daysInMonth;
            $sparklineData = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $sparklineData[] = $dailyData[$d] ?? 0;
            }

            $monthlyDetails[] = [
                'name' => $labels[$m - 1],
                'total' => $mTotal,
                'disetujui' => $mDisetujui,
                'proses' => $mProses,
                'ditolak' => $mDitolak,
                'sparkline' => $sparklineData,
            ];
        }

        // 4. Data for Chart: Group by Status
        $statusLabels = ['Disetujui', 'Ditolak', 'Diproses'];
        $statusStats = [$totalDisetujui, $totalDitolak, $totalProses];

        // 5. Data for Chart: Distribusi Jenis Surat & Detailed Jenis Table
        $jenisSurat = (clone $baseQuery)
                          ->select('jenis', DB::raw('count(*) as total'))
                          ->groupBy('jenis')
                          ->pluck('total', 'jenis')
                          ->toArray();

        $jenisDetails = [];
        foreach (Surat::JENIS_LABEL as $key => $label) {
            $jenisQuery = $user->surats()
                ->whereYear('created_at', $tahun)
                ->where('jenis', $key);

            $jTotal = (clone $jenisQuery)->count();
            $jDisetujui = (clone $jenisQuery)->where('status', 'selesai')->count();
            $jProses = (clone $jenisQuery)->whereIn('status', ['proses', 'revisi'])->count();
            $jDitolak = (clone $jenisQuery)->where('status', 'ditolak')->count();

            // Monthly trend for this specific jenis
            $jenisMonthlyTrend = [];
            for ($m = 1; $m <= 12; $m++) {
                $jenisMonthlyTrend[] = (clone $jenisQuery)->whereMonth('created_at', $m)->count();
            }

            if ($jTotal > 0) {
                $jenisDetails[] = [
                    'label' => $label,
                    'total' => $jTotal,
                    'disetujui' => $jDisetujui,
                    'proses' => $jProses,
                    'ditolak' => $jDitolak,
                    'trend' => $jenisMonthlyTrend,
                ];
            }
        }

        // Sort jenisDetails descending by total
        usort($jenisDetails, function($a, $b) {
            return $b['total'] <=> $a['total'];
        });

        // 6. Data Heatmap Kontribusi (GitHub Stats)
        $heatmapYear = (int) $request->input('heatmap_year', date('Y'));
        $heatmapData = $user->getActivityHeatmapData($heatmapYear);

        // 7. Data SLA
        $chartSlaRate = [];
        $recentMonths = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $recentMonths[] = $date->translatedFormat('M');
            
            $totalSelesai = $user->surats()
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('status', 'selesai')
                ->count();

            $onTime = $user->surats()
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('status', 'selesai')
                ->where(function($q) {
                    $q->whereNull('deadline_sla')->orWhereColumn('updated_at', '<=', 'deadline_sla');
                })
                ->count();

            $chartSlaRate[] = $totalSelesai > 0 ? round(($onTime / $totalSelesai) * 100) : 100;
        }
        $slaAvg = count($chartSlaRate) > 0 ? round(array_sum($chartSlaRate) / count($chartSlaRate)) : 100;

        // Weekly Activity
        $weeklyData = $user->getWeeklyActivityData();

        return view('user.pegawai.show', [
            'title' => 'Profil Pegawai: ' . $user->name,
            'user' => $user,
            'tahun' => $tahun,
            'totalSurat' => $totalSurat,
            'totalDisetujui' => $totalDisetujui,
            'totalDitolak' => $totalDitolak,
            'totalProses' => $totalProses,
            'chartLabels' => $labels,
            'chartData' => $monthlyStats,
            'statusLabels' => $statusLabels,
            'statusData' => $statusStats,
            'jenisSurat' => $jenisSurat,
            'heatmapData' => $heatmapData,
            'heatmapYear' => $heatmapYear,
            'monthlyDetails' => $monthlyDetails,
            'jenisDetails' => $jenisDetails,
            'stats' => [
                'total' => $totalSurat,
                'selesai' => $totalDisetujui,
                'proses' => $totalProses,
                'sla_avg' => $slaAvg
            ],
            'chartDataSla' => [
                'months' => $recentMonths,
                'sla_rate' => $chartSlaRate,
                'weekly' => $weeklyData
            ]
        ]);
    }
}
