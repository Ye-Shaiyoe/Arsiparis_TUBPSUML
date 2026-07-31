<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SlaSuratExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $statusSla;
    protected $jenis;
    protected $bulan;
    protected $tahun;
    protected $search;
    private $rowNumber = 0;

    public function __construct($statusSla = null, $jenis = null, $bulan = null, $tahun = null, $search = null)
    {
        $this->statusSla = $statusSla;
        $this->jenis     = $jenis;
        $this->bulan     = $bulan;
        $this->tahun     = $tahun;
        $this->search    = $search;
    }

    public function collection()
    {
        $query = Surat::with(['user', 'tahapans.diprosesByUser']);

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        if ($this->bulan) {
            $query->whereMonth('created_at', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('created_at', $this->tahun);
        }

        if ($this->search) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('judul', 'like', "%{$s}%")
                  ->orWhere('nomor_surat', 'like', "%{$s}%")
                  ->orWhere('tujuan', 'like', "%{$s}%")
                  ->orWhereHas('user', function ($uq) use ($s) {
                      $uq->where('name', 'like', "%{$s}%");
                  });
            });
        }

        $surats = $query->orderBy('created_at', 'desc')->get();

        if ($this->statusSla) {
            $surats = $surats->filter(function ($surat) {
                if ($this->statusSla === 'terlambat') {
                    return $surat->sla_status === 'terlambat';
                } elseif ($this->statusSla === 'ok') {
                    return $surat->sla_status === 'ok' && $surat->status === 'selesai';
                } elseif ($this->statusSla === 'proses') {
                    return $surat->status !== 'selesai' && $surat->status !== 'ditolak';
                }
                return true;
            });
        }

        return $surats;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nomor Surat',
            'Judul Surat',
            'Pengusul',
            'Jenis Surat',
            'Tgl Pengajuan',
            'Deadline SLA',
            'Status Surat',
            'Status SLA',
            'Total Waktu Pemrosesan (Jam)',
            'Tahap Saat Ini',
            'Pemroses Tahap Saat Ini',
            'Tahap Paling Lama (Bottleneck)',
            'Pemroses Tahap Paling Lama',
            'Durasi Tahap Paling Lama (Jam)',
            'Durasi Tahap 1 (Jam)',
            'Durasi Tahap 2 (Jam)',
            'Durasi Tahap 3 (Jam)',
            'Durasi Tahap 4 (Jam)',
            'Durasi Tahap 5 (Jam)',
            'Durasi Tahap 6 (Jam)',
            'Durasi Tahap 7 (Jam)',
            'Durasi Tahap 8 (Jam)',
            'Durasi Tahap 9 (Jam)',
            'Durasi Tahap 10 (Jam)',
        ];
    }

    public function map($surat): array
    {
        $this->rowNumber++;

        $tahapansMap = $surat->tahapans->keyBy('tahap');
        $durations = [];
        $maxDuration = -1;
        $maxTahapName = '-';
        $maxPemrosesName = '-';
        $totalHours = 0;

        $prevTime = $surat->created_at;

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
                $maxTahapName = "Tahap {$i}: {$t->nama_tahap}";
                $maxPemrosesName = $t->diprosesByUser?->name ?? $this->getDefaultRoleLabel($i);
            }
        }

        $currentTahapan = $tahapansMap->get($surat->tahap_sekarang);
        $currentPemroses = $currentTahapan?->diprosesByUser?->name ?? $this->getDefaultRoleLabel($surat->tahap_sekarang);

        return [
            $this->rowNumber,
            $surat->nomor_surat ?? '-',
            $surat->judul,
            $surat->user?->name ?? '-',
            $surat->jenis_label,
            $surat->created_at->format('d/m/Y H:i'),
            $surat->deadline_sla ? $surat->deadline_sla->format('d/m/Y H:i') : '-',
            ucfirst($surat->status),
            $surat->sla_status === 'terlambat' ? 'Terlambat' : 'Tepat Waktu',
            round($totalHours, 1),
            "Tahap {$surat->tahap_sekarang}: {$surat->nama_tahap}",
            $currentPemroses,
            $maxTahapName,
            $maxPemrosesName,
            $maxDuration > 0 ? $maxDuration : 0,
            $durations[1] ?? 0,
            $durations[2] ?? 0,
            $durations[3] ?? 0,
            $durations[4] ?? 0,
            $durations[5] ?? 0,
            $durations[6] ?? 0,
            $durations[7] ?? 0,
            $durations[8] ?? 0,
            $durations[9] ?? 0,
            $durations[10] ?? 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B'],
                ],
            ],
        ];
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
