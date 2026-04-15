<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ChartController extends Controller
{
    public function index()
    {
        return view('admin.chart.index', [
            'title' => 'Statistik & Grafik',
        ]);
    }

    public function data(Request $request)
    {
        $tahun = (int) $request->get('tahun', now()->year);

        if ($tahun < 2000 || $tahun > 2099) {
            return response()->json(['error' => 'Tahun tidak valid.'], 422);
        }

        $data = Cache::remember("chart_data_{$tahun}", now()->addMinutes(5), function () use ($tahun) {
            return [
                'suratPerBulan'  => $this->suratPerBulan($tahun),
                'suratPerJenis'  => $this->suratPerJenis($tahun),
                'suratPerStatus' => $this->suratPerStatus(),
                'slaChart'       => $this->slaChart($tahun),
                'suratPerTahap'  => $this->suratPerTahap(),
                'trendHarian'    => $this->trendHarian(),
                'topPengusul'    => $this->topPengusul($tahun),
                'topAdmin'       => $this->topAdmin($tahun),
            ];
        });

        return response()->json($data);
    }

    // ── Surat per bulan ───────────────────────────────────────────────────────
    private function suratPerBulan(int $tahun): array
    {
        $rows = DB::table('surats')
            ->whereYear('created_at', $tahun)
            ->selectRaw('
                MONTH(created_at) as bulan,
                COUNT(*) as total,
                SUM(status = "selesai") as selesai,
                SUM(status = "proses")  as proses,
                SUM(status = "ditolak") as ditolak
            ')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        $labels  = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $total = $selesai = $proses = $ditolak = [];

        for ($m = 1; $m <= 12; $m++) {
            $row       = $rows->get($m);
            $total[]   = (int) ($row?->total   ?? 0);
            $selesai[] = (int) ($row?->selesai ?? 0);
            $proses[]  = (int) ($row?->proses  ?? 0);
            $ditolak[] = (int) ($row?->ditolak ?? 0);
        }

        return compact('labels', 'total', 'selesai', 'proses', 'ditolak');
    }

    // ── Surat per jenis (doughnut) ────────────────────────────────────────────
    // Sesuai enum DB: nota_dinas | surat_dinas | surat_keputusan | surat_pernyataan | surat_keterangan
    private function suratPerJenis(int $tahun): array
    {
        $jenisLabel = [
            'nota_dinas'       => 'Nota Dinas',
            'surat_dinas'      => 'Surat Dinas',
            'surat_keputusan'  => 'Surat Keputusan',
            'surat_pernyataan' => 'Surat Pernyataan',
            'surat_keterangan' => 'Surat Keterangan',
        ];

        $rows = DB::table('surats')
            ->whereYear('created_at', $tahun)
            ->selectRaw('jenis, COUNT(*) as total')
            ->groupBy('jenis')
            ->orderByDesc('total')
            ->get();

        return [
            'labels' => $rows->map(fn($r) => $jenisLabel[$r->jenis] ?? ucfirst($r->jenis))->values()->toArray(),
            'data'   => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    // ── Status surat bulan ini (doughnut) ─────────────────────────────────────
    // Enum DB: proses | selesai | ditolak
    private function suratPerStatus(): array
    {
        $rows = DB::table('surats')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        return [
            'proses'  => (int) ($rows->get('proses')?->total  ?? 0),
            'selesai' => (int) ($rows->get('selesai')?->total ?? 0),
            'ditolak' => (int) ($rows->get('ditolak')?->total ?? 0),
        ];
    }

    // ── SLA: tepat waktu vs terlambat (stacked bar) ───────────────────────────
    // Kolom deadline_sla SUDAH ADA di tabel surats kamu
    private function slaChart(int $tahun): array
    {
        $labels    = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $tepat     = array_fill(0, 12, 0);
        $terlambat = array_fill(0, 12, 0);

        // Baris yang punya deadline_sla → hitung tepat/terlambat dengan tepat
        $withDeadline = DB::table('surats')
            ->whereYear('created_at', $tahun)
            ->whereNotNull('deadline_sla')
            ->selectRaw('
                MONTH(created_at) as bulan,
                SUM(status = "selesai" AND deadline_sla >= updated_at) as tepat,
                SUM(deadline_sla < NOW() AND status != "selesai")      as terlambat
            ')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Baris tanpa deadline_sla → fallback: selesai = tepat, ditolak = terlambat
        $noDeadline = DB::table('surats')
            ->whereYear('created_at', $tahun)
            ->whereNull('deadline_sla')
            ->selectRaw('
                MONTH(created_at) as bulan,
                SUM(status = "selesai") as tepat,
                SUM(status = "ditolak") as terlambat
            ')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        for ($m = 1; $m <= 12; $m++) {
            $r1 = $withDeadline->get($m);
            $r2 = $noDeadline->get($m);
            $tepat[$m - 1]     = (int) ($r1?->tepat     ?? 0) + (int) ($r2?->tepat     ?? 0);
            $terlambat[$m - 1] = (int) ($r1?->terlambat ?? 0) + (int) ($r2?->terlambat ?? 0);
        }

        return compact('labels', 'tepat', 'terlambat');
    }

    // ── Distribusi surat aktif per tahap (horizontal bar) ────────────────────
    // Tabel: surat_tahapans
    // Kolom: surat_id, tahap (int urut), nama_tahap (varchar), status (menunggu/proses/selesai/ditolak)
    private function suratPerTahap(): array
    {
        // Utama: ambil tahap yang sedang aktif dikerjakan (status = 'proses')
        $rows = DB::table('surat_tahapans as st')
            ->join('surats as s', 'st.surat_id', '=', 's.id')
            ->where('s.status', 'proses')
            ->where('st.status', 'proses')
            ->selectRaw('st.nama_tahap as tahap, COUNT(*) as total')
            ->groupBy('st.nama_tahap')
            ->orderByDesc('total')
            ->get();

        // Fallback: tidak ada yang berstatus 'proses' → ambil tahap 'menunggu' paling awal per surat
        if ($rows->isEmpty()) {
            $rows = DB::table('surat_tahapans as st')
                ->join('surats as s', 'st.surat_id', '=', 's.id')
                ->where('s.status', 'proses')
                ->where('st.status', 'menunggu')
                ->whereRaw('st.tahap = (
                    SELECT MIN(st2.tahap)
                    FROM surat_tahapans st2
                    WHERE st2.surat_id = st.surat_id
                      AND st2.status = "menunggu"
                )')
                ->selectRaw('st.nama_tahap as tahap, COUNT(*) as total')
                ->groupBy('st.nama_tahap')
                ->orderByDesc('total')
                ->get();
        }

        return [
            'labels' => $rows->pluck('tahap')->toArray(),
            'data'   => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    // ── Trend surat 30 hari terakhir (line area) ──────────────────────────────
    private function trendHarian(): array
    {
        $rows = DB::table('surats')
            ->where('created_at', '>=', now()->subDays(29)->startOfDay())
            ->selectRaw('DATE(created_at) as tgl, COUNT(*) as total')
            ->groupBy('tgl')
            ->orderBy('tgl')
            ->get()
            ->keyBy('tgl');

        $labels = [];
        $data   = [];

        for ($d = 29; $d >= 0; $d--) {
            $tgl      = now()->subDays($d)->toDateString();
            $labels[] = now()->subDays($d)->format('d/m');
            $data[]   = (int) ($rows->get($tgl)?->total ?? 0);
        }

        return compact('labels', 'data');
    }

    // ── Top 5 pengusul terbanyak bulan ini (horizontal bar) ──────────────────
    // surats.user_id → users.id | kolom nama: users.name
    private function topPengusul(int $tahun): array
    {
        $rows = DB::table('surats as s')
            ->join('users as u', 's.user_id', '=', 'u.id')
            ->whereYear('s.created_at', $tahun)
            ->whereMonth('s.created_at', now()->month)
            ->selectRaw('u.name, COUNT(*) as total')
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'data'   => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }

    // ── Top 10 admin pemroses terbanyak (bar) ──────────────────────────────────
    private function topAdmin(int $tahun): array
    {
        $rows = DB::table('surat_tahapans as st')
            ->join('users as u', 'st.diproses_oleh', '=', 'u.id')
            ->whereYear('st.created_at', $tahun)
            ->whereNotNull('st.diproses_oleh')
            ->selectRaw('u.name, COUNT(*) as total')
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return [
            'labels' => $rows->pluck('name')->toArray(),
            'data'   => $rows->pluck('total')->map(fn($v) => (int) $v)->toArray(),
        ];
    }
}