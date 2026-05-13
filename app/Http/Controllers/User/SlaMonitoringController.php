<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SlaMonitoringController extends Controller
{
    /**
     * Monitoring SLA pribadi: surat selesai tepat waktu vs terlambat per bulan,
     * plus rata-rata jam hingga selesai (chart garis pada mixed chart).
     */
    public function index()
    {
        $userId = auth()->id();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $months[] = Carbon::now()->subMonths($i)->format('Y-m');
        }

        $tepatWaktu = [];
        $terlambatSelesai = [];
        $rataJamSelesai = [];

        foreach ($months as $ym) {
            $start = Carbon::parse($ym)->startOfMonth();
            $end = Carbon::parse($ym)->endOfMonth();

            $baseSelesai = Surat::query()
                ->where('user_id', $userId)
                ->where('status', 'selesai')
                ->whereBetween(DB::raw('COALESCE(disetujui_pada, updated_at)'), [$start, $end]);

            $tepatWaktu[] = (clone $baseSelesai)->where(function ($q) {
                $q->whereNull('deadline_sla')
                    ->orWhereRaw('COALESCE(disetujui_pada, updated_at) <= deadline_sla');
            })->count();

            $terlambatSelesai[] = (clone $baseSelesai)->whereNotNull('deadline_sla')
                ->whereRaw('COALESCE(disetujui_pada, updated_at) > deadline_sla')
                ->count();

            $avg = Surat::query()
                ->where('user_id', $userId)
                ->where('status', 'selesai')
                ->whereBetween(DB::raw('COALESCE(disetujui_pada, updated_at)'), [$start, $end])
                ->selectRaw(
                    'AVG(TIMESTAMPDIFF(HOUR, created_at, COALESCE(disetujui_pada, updated_at))) as avg_h'
                )
                ->value('avg_h');

            $rataJamSelesai[] = round((float) ($avg ?? 0), 1);
        }

        $monthLabels = array_map(static function ($m) {
            return Carbon::parse($m)->translatedFormat('M Y');
        }, $months);

        $aktifTerlambat = Surat::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->whereNotNull('deadline_sla')
            ->where('deadline_sla', '<', now())
            ->count();

        $aktifNormal = Surat::query()
            ->where('user_id', $userId)
            ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->where(function ($q) {
                $q->whereNull('deadline_sla')
                    ->orWhere('deadline_sla', '>=', now());
            })
            ->count();

        return view('user.sla.index', [
            'title' => 'Monitoring SLA',
            'monthLabels' => $monthLabels,
            'tepatWaktu' => $tepatWaktu,
            'terlambatSelesai' => $terlambatSelesai,
            'rataJamSelesai' => $rataJamSelesai,
            'aktifTerlambat' => $aktifTerlambat,
            'aktifNormal' => $aktifNormal,
        ]);
    }
}
