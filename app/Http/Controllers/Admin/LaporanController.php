<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use App\Exports\LaporanExport;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $jenis = $request->get('jenis');

        $query = Surat::with('user')
                      ->whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $surats = $query->orderBy('created_at')->get();

        // Hitung ringkasan
        $ringkasan = [
            'total'     => $surats->count(),
            'selesai'   => $surats->where('status', 'selesai')->count(),
            'proses'    => $surats->where('status', 'proses')->count(),
            'ditolak'   => $surats->where('status', 'ditolak')->count(),
            'sla_ok'    => $surats->filter(fn($s) => $s->sla_status !== 'terlambat')->count(),
            'sla_telat' => $surats->filter(fn($s) => $s->sla_status === 'terlambat')->count(),
        ];

        // Rekap per jenis
        $rekapJenis = $surats->groupBy('jenis')->map->count();

        return view('admin.laporan.index', compact(
            'surats', 'bulan', 'tahun', 'jenis', 'ringkasan', 'rekapJenis'
        ));
    }

    public function export(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $jenis = $request->get('jenis');

        $query = Surat::with('user')
                      ->whereMonth('created_at', $bulan)
                      ->whereYear('created_at', $tahun);

        if ($jenis) {
            $query->where('jenis', $jenis);
        }

        $surats = $query->orderBy('created_at')->get();

        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F_Y');
        $filename  = "Rekap_Surat_{$namaBulan}.csv";

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($surats) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'No', 'Jenis Surat', 'Nama Pengusul', 'Judul Surat',
                'Tujuan Surat', 'Nomor Surat', 'Tanggal Surat',
                'Progress (Tahap)', 'Status', 'SLA',
            ]);

            foreach ($surats as $i => $surat) {
                fputcsv($file, [
                    $i + 1,
                    $surat->jenis_label,
                    $surat->user->name,
                    $surat->judul,
                    $surat->tujuan,
                    $surat->nomor_surat ?? '-',
                    $surat->tanggal_surat ? $surat->tanggal_surat->format('d/m/Y') : '-',
                    "Tahap {$surat->tahap_sekarang}/10 — {$surat->nama_tahap}",
                    ucfirst($surat->status),
                    $surat->sla_status === 'terlambat' ? 'Terlambat' : 'OK',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExcel(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $jenis = $request->get('jenis');

        $namaBulan = \Carbon\Carbon::createFromDate($tahun, $bulan, 1)->translatedFormat('F_Y');
        $filename  = "Rekap_Surat_{$namaBulan}.xlsx";

        return Excel::download(new LaporanExport($bulan, $tahun, $jenis), $filename);
    }
}