<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\Request;
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

        // Stats Dasar
        // Surat Masuk = Total Surat
        $totalSuratMasuk = Surat::count();
        // Surat Keluar = Tahap 5 ke atas (Penomoran s/d Selesai)
        $totalSuratKeluar = Surat::where('tahap_sekarang', '>=', 5)->count();
        // Pengguna Terdaftar
        $totalPengguna = User::count();
        // Dokumen Terarsip = Status Selesai
        $totalDokumenTerarsip = Surat::where('status', 'selesai')->count();
        // Average rating of finished letters (if any, default to 5.0)
        $averageRating = Surat::whereNotNull('rating')->avg('rating') ?: 5.0;

        // Chart Mixed (6 Bulan Terakhir)
        $chartMixedMonths = [];
        $chartMixedMasuk = [];
        $chartMixedKeluar = [];
        $chartMixedSLA = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMixedMonths[] = $date->translatedFormat('M');
            
            $countMasuk = Surat::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Keluar di chart juga dihitung sebagai yang sudah mencapai tahap 5+ di bulan tersebut
            $countKeluar = Surat::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->where('tahap_sekarang', '>=', 5)
                ->count();
            
            $chartMixedMasuk[] = $countMasuk;
            $chartMixedKeluar[] = $countKeluar;

            // SLA Rate calculation
            $totalSelesaiBulanIni = Surat::whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('status', 'selesai')
                ->count();

            $onTime = Surat::whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->where('status', 'selesai')
                ->where(function($q) {
                    $q->whereNull('deadline_sla')->orWhereColumn('updated_at', '<=', 'deadline_sla');
                })
                ->count();
            
            $chartMixedSLA[] = $totalSelesaiBulanIni > 0 ? (int) round(($onTime / $totalSelesaiBulanIni) * 100) : 100;
        }

        // Chart Doughnut (Distribusi Jenis)
        $jenisCounts = Surat::select('jenis', DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->get();
        
        $doughnutLabels = [];
        $doughnutData = [];
        $totalAll = $totalSuratMasuk ?: 1;

        foreach (Surat::JENIS_LABEL as $key => $label) {
            $count = $jenisCounts->where('jenis', $key)->first()?->total ?? 0;
            $doughnutLabels[] = $label;
            $doughnutData[] = [
                'count' => (int) $count,
                'pct' => (int) round(($count / $totalAll) * 100)
            ];
        }

        // Growth Percentage (Bulan ini vs Bulan lalu)
        $thisMonth = Surat::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $lastMonth = Surat::whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->count();
        $growth = 0;
        if ($lastMonth > 0) {
            $growth = (int) round((($thisMonth - $lastMonth) / $lastMonth) * 100);
        } elseif ($thisMonth > 0) {
            $growth = 100;
        }

        // Chart Area (12 Bulan)
        $chartAreaMonths = [];
        $chartAreaMasuk = [];
        $chartAreaKeluar = [];
        $chartAreaSelesai = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartAreaMonths[] = $date->translatedFormat('M');
            
            $chartAreaMasuk[] = Surat::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->count();
            $chartAreaKeluar[] = Surat::whereMonth('created_at', $date->month)->whereYear('created_at', $date->year)->where('tahap_sekarang', '>=', 5)->count();
            $chartAreaSelesai[] = Surat::whereMonth('updated_at', $date->month)->whereYear('updated_at', $date->year)->where('status', 'selesai')->count();
        }

        // SLA per Jenis (Bulan Ini)
        $slaPerJenis = [];
        foreach (Surat::JENIS_LABEL as $key => $label) {
            $totalJenis = Surat::where('jenis', $key)->whereMonth('created_at', now()->month)->count();
            $pct = 100;
            if ($totalJenis > 0) {
                $onTimeJenis = Surat::where('jenis', $key)
                    ->whereMonth('created_at', now()->month)
                    ->where(function($q) {
                        $q->whereNull('deadline_sla')
                          ->orWhere(function($sq) {
                              $sq->where('status', 'selesai')->whereColumn('updated_at', '<=', 'deadline_sla');
                          })
                          ->orWhere(function($sq) {
                              $sq->where('status', '!=', 'selesai')->where('deadline_sla', '>', now());
                          });
                    })
                    ->count();
                $pct = (int) round(($onTimeJenis / $totalJenis) * 100);
            }
            
            $color = '#C8A96E';
            if ($pct >= 90) $color = '#1D9E75';
            elseif ($pct >= 80) $color = '#5DCAA5';
            elseif ($pct >= 70) $color = '#EF9F27';
            else $color = '#D85A30';

            $slaPerJenis[] = [
                'name' => $label,
                'pct' => $pct,
                'color' => $color
            ];
        }

        return view('welcome', compact(
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
        ));
    }
}
