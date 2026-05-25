<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->check() && !$request->has('home')) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isITSupport()) {
                return redirect()->route('itsupport.dashboard');
            }
            return redirect()->route('dashboard');
        }

        $data = Cache::remember('welcome.page.stats', 300, fn () => $this->buildStats());

        return view('welcome', $data);
    }

    private function buildStats(): array
    {
        $totals = Surat::query()->selectRaw('
            COUNT(*) as total_masuk,
            SUM(CASE WHEN tahap_sekarang >= 5 THEN 1 ELSE 0 END) as total_keluar,
            SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as total_arsip
        ', ['selesai'])->first();

        $totalSuratMasuk = (int) ($totals->total_masuk ?? 0);
        $totalSuratKeluar = (int) ($totals->total_keluar ?? 0);
        $totalDokumenTerarsip = (int) ($totals->total_arsip ?? 0);
        $totalPengguna = User::count();
        $averageRating = (float) (Surat::whereNotNull('rating')->avg('rating') ?: 5.0);

        $from12 = now()->subMonths(11)->startOfMonth();

        $monthlyCreated = Surat::query()
            ->selectRaw('
                YEAR(created_at) as y,
                MONTH(created_at) as m,
                COUNT(*) as masuk,
                SUM(CASE WHEN tahap_sekarang >= 5 THEN 1 ELSE 0 END) as keluar
            ')
            ->where('created_at', '>=', $from12)
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->y, $row->m));

        $monthlySelesai = Surat::query()
            ->selectRaw('
                YEAR(updated_at) as y,
                MONTH(updated_at) as m,
                COUNT(*) as total,
                SUM(CASE WHEN deadline_sla IS NULL OR updated_at <= deadline_sla THEN 1 ELSE 0 END) as on_time
            ')
            ->where('status', 'selesai')
            ->where('updated_at', '>=', $from12)
            ->groupByRaw('YEAR(updated_at), MONTH(updated_at)')
            ->get()
            ->keyBy(fn ($row) => sprintf('%04d-%02d', $row->y, $row->m));

        $chartAreaMonths = [];
        $chartAreaMasuk = [];
        $chartAreaKeluar = [];
        $chartAreaSelesai = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $row = $monthlyCreated->get($key);

            $chartAreaMonths[] = $date->translatedFormat('M');
            $chartAreaMasuk[] = (int) ($row->masuk ?? 0);
            $chartAreaKeluar[] = (int) ($row->keluar ?? 0);
            $chartAreaSelesai[] = (int) ($monthlySelesai->get($key)->total ?? 0);
        }

        $chartMixedMonths = array_slice($chartAreaMonths, -6);
        $chartMixedMasuk = array_slice($chartAreaMasuk, -6);
        $chartMixedKeluar = array_slice($chartAreaKeluar, -6);
        $chartMixedSLA = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $selesai = $monthlySelesai->get($key);
            $total = (int) ($selesai->total ?? 0);
            $onTime = (int) ($selesai->on_time ?? 0);
            $chartMixedSLA[] = $total > 0 ? (int) round(($onTime / $total) * 100) : 100;
        }

        $jenisCounts = Surat::query()
            ->select('jenis', DB::raw('COUNT(*) as total'))
            ->groupBy('jenis')
            ->pluck('total', 'jenis');

        $doughnutLabels = [];
        $doughnutData = [];
        $totalAll = $totalSuratMasuk ?: 1;

        foreach (Surat::JENIS_LABEL as $key => $label) {
            $count = (int) ($jenisCounts[$key] ?? 0);
            $doughnutLabels[] = $label;
            $doughnutData[] = [
                'count' => $count,
                'pct' => (int) round(($count / $totalAll) * 100),
            ];
        }

        $thisMonthKey = now()->format('Y-m');
        $lastMonthKey = now()->subMonth()->format('Y-m');
        $thisMonth = (int) ($monthlyCreated->get($thisMonthKey)->masuk ?? 0);
        $lastMonth = (int) ($monthlyCreated->get($lastMonthKey)->masuk ?? 0);

        if ($lastMonth > 0) {
            $growth = (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100);
        } elseif ($thisMonth > 0) {
            $growth = 100;
        } else {
            $growth = 0;
        }

        $slaPerJenis = $this->buildSlaPerJenis();

        return compact(
            'totalSuratMasuk',
            'totalSuratKeluar',
            'totalPengguna',
            'totalDokumenTerarsip',
            'averageRating',
            'chartMixedMonths',
            'chartMixedMasuk',
            'chartMixedKeluar',
            'chartMixedSLA',
            'doughnutLabels',
            'doughnutData',
            'growth',
            'chartAreaMonths',
            'chartAreaMasuk',
            'chartAreaKeluar',
            'chartAreaSelesai',
            'slaPerJenis'
        );
    }

    private function buildSlaPerJenis(): array
    {
        $now = now();
        $rows = Surat::query()
            ->select('jenis')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(CASE
                WHEN deadline_sla IS NULL THEN 1
                WHEN status = ? AND updated_at <= deadline_sla THEN 1
                WHEN status != ? AND deadline_sla > ? THEN 1
                ELSE 0
            END) as on_time', ['selesai', 'selesai', $now])
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->groupBy('jenis')
            ->get()
            ->keyBy('jenis');

        $slaPerJenis = [];

        foreach (Surat::JENIS_LABEL as $key => $label) {
            $row = $rows->get($key);
            $total = (int) ($row->total ?? 0);
            $pct = $total > 0
                ? (int) round(((int) ($row->on_time ?? 0) / $total) * 100)
                : 100;

            if ($pct >= 90) {
                $color = '#1D9E75';
            } elseif ($pct >= 80) {
                $color = '#5DCAA5';
            } elseif ($pct >= 70) {
                $color = '#EF9F27';
            } else {
                $color = '#D85A30';
            }

            $slaPerJenis[] = [
                'name' => $label,
                'pct' => $pct,
                'color' => $color,
            ];
        }

        return $slaPerJenis;
    }
}
