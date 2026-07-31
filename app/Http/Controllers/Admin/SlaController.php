<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Exports\SlaSuratExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SlaController extends Controller
{
    public function index(Request $request)
    {
        $statusSla = $request->get('status_sla');
        $jenis     = $request->get('jenis');
        $bulan     = $request->get('bulan', date('m'));
        $tahun     = $request->get('tahun', date('Y'));
        $search    = $request->get('q');

        $query = Surat::with(['user', 'tahapans.diprosesByUser']);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        }

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        if ($search) {
            $s = $search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('nomor_surat', 'like', "%{$s}%")
                  ->orWhere('tujuan', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $allSurats = $query->orderBy('created_at', 'desc')->get();

        // Olah data setiap surat (kalkulasi durasi per tahap & bottleneck)
        $processedSurats = $allSurats->map(function ($surat) {
            $tahapansMap = $surat->tahapans->keyBy('tahap');
            $prevTime = $surat->created_at;

            $durations = [];
            $maxDuration = -1;
            $bottleneckStageNumber = 1;
            $bottleneckStageName = '-';
            $bottleneckPemrosesName = '-';
            $bottleneckRole = '-';
            $totalHours = 0;

            for ($i = 1; $i <= 10; $i++) {
                $t = $tahapansMap->get($i);
                if (!$t) {
                    $durations[$i] = 0;
                    continue;
                }

                if ($t->status === 'selesai') {
                    $endTime = $t->selesai_pada ?? $t->updated_at ?? $prevTime;
                    $durJam = round(max(0, $prevTime->diffInMinutes($endTime) / 60), 1);
                    $prevTime = $endTime;
                } elseif ($t->status === 'proses' || ($surat->tahap_sekarang == $i && $surat->status !== 'selesai')) {
                    $endTime = now();
                    $durJam = round(max(0, $prevTime->diffInMinutes($endTime) / 60), 1);
                } else {
                    $durJam = 0;
                }

                $durations[$i] = $durJam;
                $totalHours += $durJam;

                if ($durJam > $maxDuration && $durJam > 0) {
                    $maxDuration = $durJam;
                    $bottleneckStageNumber = $i;
                    $bottleneckStageName = "Tahap {$i}: {$t->nama_tahap}";
                    $bottleneckPemrosesName = $t->diprosesByUser?->name ?? $this->getDefaultRoleLabel($i);
                    $bottleneckRole = $t->diprosesByUser?->getRoleLabel() ?? $this->getDefaultRoleLabel($i);
                }
            }

            // Pemroses tahap aktif sekarang
            $currentTahapan = $tahapansMap->get($surat->tahap_sekarang);
            $currentPemroses = $currentTahapan?->diprosesByUser?->name ?? $this->getDefaultRoleLabel($surat->tahap_sekarang);
            $currentRole = $currentTahapan?->diprosesByUser?->getRoleLabel() ?? $this->getDefaultRoleLabel($surat->tahap_sekarang);

            $surat->calculated_durations         = $durations;
            $surat->calculated_total_hours       = round($totalHours, 1);
            $surat->bottleneck_stage_number      = $bottleneckStageNumber;
            $surat->bottleneck_stage_name        = $bottleneckStageName;
            $surat->bottleneck_pemroses_name     = $bottleneckPemrosesName;
            $surat->bottleneck_role              = $bottleneckRole;
            $surat->bottleneck_duration          = $maxDuration > 0 ? $maxDuration : 0;
            $surat->current_pemroses_name        = $currentPemroses;
            $surat->current_role                 = $currentRole;

            return $surat;
        });

        // Filter berdasarkan status SLA jika dipiliih
        if ($statusSla) {
            $processedSurats = $processedSurats->filter(function ($surat) use ($statusSla) {
                if ($statusSla === 'terlambat') {
                    return $surat->sla_status === 'terlambat';
                } elseif ($statusSla === 'ok') {
                    return $surat->sla_status === 'ok' && $surat->status === 'selesai';
                } elseif ($statusSla === 'proses') {
                    return $surat->status !== 'selesai' && $surat->status !== 'ditolak';
                }
                return true;
            });
        }

        // Summary Metrics
        $totalSurat     = $allSurats->count();
        $suratTerlambat = $allSurats->filter(fn($s) => $s->sla_status === 'terlambat')->count();
        $suratTepat     = $allSurats->filter(fn($s) => $s->sla_status === 'ok' && $s->status === 'selesai')->count();
        $suratProses    = $allSurats->filter(fn($s) => $s->status !== 'selesai' && $s->status !== 'ditolak')->count();
        $avgDuration    = $processedSurats->count() > 0 ? round($processedSurats->avg('calculated_total_hours'), 1) : 0;

        // Cari pemroses paling sering terlambat / paling sering jadi bottleneck
        $bottleneckCounts = [];
        foreach ($processedSurats as $s) {
            if ($s->bottleneck_pemroses_name && $s->bottleneck_pemroses_name !== '-') {
                $pName = $s->bottleneck_pemroses_name;
                $bottleneckCounts[$pName] = ($bottleneckCounts[$pName] ?? 0) + 1;
            }
        }
        arsort($bottleneckCounts);
        $topBottleneckPemroses = !empty($bottleneckCounts) ? array_key_first($bottleneckCounts) : '-';
        $topBottleneckCount = !empty($bottleneckCounts) ? reset($bottleneckCounts) : 0;

        return view('admin.sla.index', compact(
            'processedSurats',
            'totalSurat',
            'suratTerlambat',
            'suratTepat',
            'suratProses',
            'avgDuration',
            'topBottleneckPemroses',
            'topBottleneckCount',
            'statusSla',
            'jenis',
            'bulan',
            'tahun',
            'search'
        ));
    }

    public function export(Request $request)
    {
        $statusSla = $request->get('status_sla');
        $jenis     = $request->get('jenis');
        $bulan     = $request->get('bulan', date('m'));
        $tahun     = $request->get('tahun', date('Y'));
        $search    = $request->get('q');

        $fileName = 'Laporan_SLA_Persurat_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new SlaSuratExport($statusSla, $jenis, $bulan, $tahun, $search), $fileName);
    }

    private function getDefaultRoleLabel(int $tahap): string
    {
        return match ($tahap) {
            1       => 'Pengusul',
            3       => 'Kasubbag TU',
            4       => 'Kepala Balai',
            default => 'Arsiparis',
        };
    }
}
