<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $bulan;
    protected $tahun;
    protected $jenis;
    private $rowNumber = 0;

    public function __construct($bulan, $tahun, $jenis = null)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
        $this->jenis = $jenis;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Surat::with('user')
                      ->whereMonth('created_at', $this->bulan)
                      ->whereYear('created_at', $this->tahun);

        if ($this->jenis) {
            $query->where('jenis', $this->jenis);
        }

        return $query->orderBy('created_at')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Jenis Surat',
            'Nama Pengusul',
            'Judul Surat',
            'Tujuan Surat',
            'Nomor Surat',
            'Tanggal Surat',
            'Progress (Tahap)',
            'Status',
            'SLA',
        ];
    }

    /**
    * @var Surat $surat
    */
    public function map($surat): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $surat->jenis_label,
            $surat->user->name ?? '-',
            $surat->judul,
            $surat->tujuan,
            $surat->nomor_surat ?? '-',
            $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-',
            "Tahap {$surat->tahap_sekarang}/10 — {$surat->nama_tahap}",
            ucfirst($surat->status),
            $surat->sla_status === 'terlambat' ? 'Terlambat' : 'OK',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
