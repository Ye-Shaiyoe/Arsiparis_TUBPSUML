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
        $query = User::query();

        // Jika ada pencarian, filter berdasarkan nama atau NIP
        if ($request->filled('search')) {
            $search = trim($request->search);
            
            \Log::info('Pegawai Index Search', [
                'search' => $search,
                'search_filled' => $request->filled('search'),
                'request_all' => $request->all()
            ]);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%");
            });
            
            \Log::info('Pegawai Query SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);
        }

        $users = $query->latest()
            ->paginate(12)
            ->withQueryString();
        
        \Log::info('Pegawai Results Count', ['count' => $users->count(), 'total' => $users->total()]);

        return view('user.pegawai.index', [
            'title' => 'Direktori Pegawai',
            'users' => $users
        ]);
    }

    public function show(User $user)
    {
        // Statistik Dasar
        $totalSurat = $user->surats()->count();
        $suratSelesai = $user->surats()->where('status', 'selesai')->count();
        $suratProses = $user->surats()->whereNotIn('status', ['selesai', 'ditolak', 'draft'])->count();
        
        // Data Mingguan (Heatmap/Line Chart)
        $weeklyData = $user->getWeeklyActivityData();

        // Data Bulanan (Mixed Chart: Bar Pengajuan + Line SLA)
        $chartMonths = [];
        $chartSubmissions = [];
        $chartSlaRate = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonths[] = $date->translatedFormat('M');
            
            $submissions = $user->surats()
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            $chartSubmissions[] = $submissions;

            // SLA Rate for this user in that month
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

        return view('user.pegawai.show', [
            'title' => 'Profil Pegawai: ' . $user->name,
            'user' => $user,
            'stats' => [
                'total' => $totalSurat,
                'selesai' => $suratSelesai,
                'proses' => $suratProses,
                'sla_avg' => count($chartSlaRate) > 0 ? round(array_sum($chartSlaRate) / count($chartSlaRate)) : 100
            ],
            'chartData' => [
                'months' => $chartMonths,
                'submissions' => $chartSubmissions,
                'sla_rate' => $chartSlaRate,
                'weekly' => $weeklyData
            ]
        ]);
    }
}
