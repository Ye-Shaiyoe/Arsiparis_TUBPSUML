<?php
// =============================================
// app/Http/Controllers/User/DashboardController.php
// =============================================

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $userId = $user->id;

        $bulanSelected = (int) $request->input('bulan', now()->month);
        $tahunSelected = (int) $request->input('tahun', now()->year);

        $totalSurat = Surat::where('user_id', $userId)->whereMonth('created_at', $bulanSelected)->whereYear('created_at', $tahunSelected)->count();
        $suratSelesai = Surat::where('user_id', $userId)->whereMonth('created_at', $bulanSelected)->whereYear('created_at', $tahunSelected)->where('status', 'selesai')->count();
        $suratProses = Surat::where('user_id', $userId)->whereMonth('created_at', $bulanSelected)->whereYear('created_at', $tahunSelected)->where('status', 'proses')->count();
        $suratDitolak = Surat::where('user_id', $userId)->whereMonth('created_at', $bulanSelected)->whereYear('created_at', $tahunSelected)->whereIn('status', ['ditolak', 'revisi', 'revisi_admin'])->count();
        $suratActionUrgent = Surat::where('user_id', $userId)->whereIn('status', ['ditolak', 'revisi', 'revisi_admin'])->count();

        // Surat terbaru + tahapan untuk tracking
        $suratTerbaru = Surat::where('user_id', $userId)
            ->with(['tahapans.diprosesByUser'])
            ->latest()
            ->limit(5)
            ->get();

        // Surat aktif untuk SLA bar
        $suratAktif = Surat::where('user_id', $userId)
            ->whereIn('status', ['proses', 'revisi', 'revisi_admin'])
            ->latest()
            ->limit(3)
            ->get();

        // Data untuk chart: distribusi jenis surat (sesuai filter)
        $jenisSurat = Surat::where('user_id', $userId)
            ->whereMonth('created_at', $bulanSelected)
            ->whereYear('created_at', $tahunSelected)
            ->select('jenis', DB::raw('count(*) as total'))
            ->groupBy('jenis')
            ->pluck('total', 'jenis')
            ->toArray();

        // Data untuk chart: tren surat per bulan (6 bulan terakhir)
        $sixMonthsAgo = now()->subMonths(5)->startOfMonth();
        $trenBulanan = Surat::where('user_id', $userId)
            ->where('created_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%M %Y') as month"),
                DB::raw('count(*) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->pluck('total', 'month')
            ->toArray();

        /** @var FilesystemAdapter $privateDisk */
        $privateDisk = Storage::disk('private');
        $templates = collect($privateDisk->files('templates'))
            ->map(fn($path) => [
                'nama' => basename($path),
                'url' => route('user.template.download', ['nama' => basename($path)]),
                'size' => round($privateDisk->size($path) / 1024, 1) . ' KB',
                'ext' => pathinfo($path, PATHINFO_EXTENSION),
            ])
            ->values();

        // Deteksi Layanan Tutup
        $now = now();
        $isLibur = false;
        if ($now->isWeekend()) {
            $isLibur = true;
        } else {
            $dayOfWeek = $now->dayOfWeek; // 1 (Mon) - 7 (Sun)
            $timeInMinutes = $now->hour * 60 + $now->minute;

            if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
                // Senin-Kamis: 07:30 - 16:00
                if ($timeInMinutes < 7 * 60 + 30 || $timeInMinutes >= 16 * 60) {
                    $isLibur = true;
                }
            } elseif ($dayOfWeek === 5) {
                // Jumat: 07:30 - 16:30
                if ($timeInMinutes < 7 * 60 + 30 || $timeInMinutes >= 16 * 60 + 30) {
                    $isLibur = true;
                }
            } else {
                $isLibur = true;
            }
        }

        $weeklyActivity = $user->getWeeklyActivityData();

        return view('dashboard', compact(
            'totalSurat',
            'suratSelesai',
            'suratProses',
            'suratDitolak',
            'suratTerbaru',
            'suratAktif',
            'templates',
            'jenisSurat',
            'trenBulanan',
            'isLibur',
            'bulanSelected',
            'tahunSelected',
            'weeklyActivity',
            'suratActionUrgent'
        ));
    }

    public function liveData(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $userId = $user->id;

        $bulan = (int) $request->input('bulan', now()->month);
        $tahun = (int) $request->input('tahun', now()->year);

        // 1. Stats
        $stats = [
            'totalSurat' => Surat::where('user_id', $userId)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->count(),
            'suratSelesai' => Surat::where('user_id', $userId)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->where('status', 'selesai')->count(),
            'suratProses' => Surat::where('user_id', $userId)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->where('status', 'proses')->count(),
            'suratDitolak' => Surat::where('user_id', $userId)->whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->whereIn('status', ['ditolak', 'revisi', 'revisi_admin'])->count(),
            'suratActionUrgent' => Surat::where('user_id', $userId)->whereIn('status', ['ditolak', 'revisi', 'revisi_admin'])->count(),
        ];

        // 2. Notifications
        $allNotifications = $user->notifications()->latest()->limit(20)->get();
        $unreadCount = $user->unreadNotifications()->count();

        $notifications = $allNotifications->whereNull('read_at')->merge($allNotifications->whereNotNull('read_at'))
            ->take(10)
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->data['type'] ?? 'info',
                    'title' => $n->data['title'] ?? 'Notifikasi',
                    'message' => \Illuminate\Support\Str::limit($n->data['message'] ?? '', 60),
                    'created_at_human' => $n->created_at->diffForHumans(),
                    'read_at' => $n->read_at,
                    'url' => route('notif.read', $n->id),
                ];
            })->values();

        // 3. Surat Aktif (SLA)
        $suratAktif = Surat::where('user_id', $userId)
            ->whereIn('status', ['proses', 'revisi'])
            ->latest()
            ->limit(3)
            ->get()
            ->map(function ($s) {
                // Progress calculation
                $pct = $s->deadline_sla
                    ? min(100, now()->diffInMinutes($s->created_at) /
                        max(1, $s->deadline_sla->diffInMinutes($s->created_at)) * 100)
                    : 50;

                return [
                    'id' => $s->id,
                    'judul_short' => \Illuminate\Support\Str::limit($s->judul, 30),
                    'sla_status' => $s->sla_status,
                    'sisa_jam' => $s->sisa_jam,
                    'sisa_jam_angka' => $s->deadline_sla ? now()->diffInHours($s->deadline_sla, false) : 99,
                    'pct' => $pct,
                    'color' => $s->sla_color, // Gunakan sla_color dari model
                    'tahap' => $s->tahap_sekarang,
                    'nama_tahap' => $s->nama_tahap,
                ];
            });

        return response()->json([
            'stats' => $stats,
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'suratAktif' => $suratAktif,
            'suratTerbaru' => Surat::where('user_id', $userId)
                ->with(['tahapans'])
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($s) {
                    return [
                        'uuid' => $s->uuid,
                        'judul' => $s->judul,
                        'status' => $s->status,
                        'jenis_label' => $s->jenis_label,
                        'sifat' => $s->sifat,
                        'tahap_sekarang' => $s->tahap_sekarang,
                        'proses_persen' => $s->proses_persen,
                        'sla_status' => $s->sla_status,
                        'sla_color' => $s->sla_color, // Tambahkan ini
                        'show_url' => route('user.surat.show', $s),
                        'tahapans' => $s->tahapans->map(function ($t) {
                            return [
                                'tahap' => $t->tahap,
                                'nama_tahap' => $t->nama_tahap,
                                'status' => $t->status,
                            ];
                        }),
                    ];
                }),
        ]);
    }

    public function faq()
    {
        return view('user.faq.index', ['title' => 'Pertanyaan Umum (FAQ)']);
    }
}