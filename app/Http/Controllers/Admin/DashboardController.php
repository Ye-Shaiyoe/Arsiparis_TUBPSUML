<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulanSelected = (int) $request->input('bulan', now()->month);
        $tahunSelected = (int) $request->input('tahun', now()->year);

        $data = $this->getDashboardData($bulanSelected, $tahunSelected);

        return view('admin.dashboard', array_merge($data, [
            'bulanSelected' => $bulanSelected,
            'tahunSelected' => $tahunSelected,
        ]));
    }

    public function liveData(Request $request)
    {
        $bulanSelected = (int) $request->input('bulan', now()->month);
        $tahunSelected = (int) $request->input('tahun', now()->year);

        $data = $this->getDashboardData($bulanSelected, $tahunSelected);

        return response()->json([
            'stats' => [
                'totalBulanIni' => $data['totalBulanIni'],
                'totalSelesai' => $data['totalSelesai'],
                'totalProses' => $data['totalProses'],
                'totalTerlambat' => $data['totalTerlambat'],
            ],
            'antrian' => [
                'items' => $data['antrian'],
                'count' => $data['antrianCount'],
            ]
        ]);
    }

    private function getDashboardData($bulan, $tahun)
    {
        $admin = Auth::user();

        // Base Query untuk statistik periode (Bulan Ini / Selesai Bulan Ini)
        $periodeQuery = Surat::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        // Role-based filter untuk beban kerja (Global)
        $roleFilter = function ($q) use ($admin) {
            if ($admin->role === 'admin_aspirasi') {
                $q->where(function ($sq) {
                    $sq->where('tahap_sekarang', 2)->orWhere('tahap_sekarang', '>=', 5);
                });
            } elseif ($admin->role === 'admin_kasubbag_tu') {
                $q->where('tahap_sekarang', 3);
            } elseif ($admin->role === 'admin_kepala_balai') {
                $q->where('tahap_sekarang', 4);
            }
        };

        // Statistik Periode (Terfilter)
        $totalBulanIni = (clone $periodeQuery)->count();
        $totalSelesai = (clone $periodeQuery)->where('status', 'selesai')->count();

        // Statistik Workload (Global - Tidak terfilter bulan/tahun agar admin tahu semua kerjaan)
        $workloadQuery = Surat::query()->where($roleFilter);

        $totalProses = (clone $workloadQuery)->whereIn('status', ['proses', 'revisi', 'revisi_admin'])->count();
        $totalTerlambat = (clone $workloadQuery)
            ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->whereNotNull('deadline_sla')
            ->where('deadline_sla', '<', now())
            ->count();

        // Antrian (Global - Tampilkan semua yang butuh aksi sekarang)
        $antrian = (clone $workloadQuery)
            ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->with('user')
            ->orderByRaw("CASE WHEN status = 'revisi' OR status = 'revisi_admin' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($s) {
                $s->status_label = match ($s->status) {
                    'revisi' => 'Perlu Revisi User',
                    'revisi_admin' => 'Revisi Internal',
                    'proses' => 'Proses',
                    default => $s->status
                };
                $s->sla_status = $s->deadline_sla && now()->gt($s->deadline_sla) ? 'terlambat' : 'ok';
                return $s;
            })->values();

        $antrianCount = $antrian->count();

        // Rekap Jenis (Terfilter periode)
        $rekapJenis = (clone $periodeQuery)->selectRaw('jenis, COUNT(*) as jumlah')
            ->groupBy('jenis')
            ->pluck('jumlah', 'jenis');

        $suratTerbaru = Surat::with('user')->latest()->limit(5)->get();

        $suratDenganPengolah = (clone $periodeQuery)
            ->with([
                'user',
                'tahapans' => function ($query) {
                    $query->where('status', 'selesai')
                        ->whereNotNull('diproses_oleh')
                        ->with('diprosesByUser')
                        ->orderBy('tahap');
                }
            ])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        // Chart Data (Last 6 Months - Always Global Stats per Month)
        $chartMonths = [];
        $chartMasuk = [];
        $chartSelesai = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonths[] = $date->translatedFormat('M Y');
            $chartMasuk[] = Surat::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
            $chartSelesai[] = Surat::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->where('status', 'selesai')->count();
        }

        return [
            'totalBulanIni' => $totalBulanIni,
            'totalSelesai' => $totalSelesai,
            'totalProses' => $totalProses,
            'totalTerlambat' => $totalTerlambat,
            'antrian' => $antrian,
            'antrianCount' => $antrianCount,
            'rekapJenis' => $rekapJenis,
            'suratTerbaru' => $suratTerbaru,
            'suratDenganPengolah' => $suratDenganPengolah,
            'chartMonths' => $chartMonths,
            'chartMasuk' => $chartMasuk,
            'chartSelesai' => $chartSelesai,
        ];
    }
}