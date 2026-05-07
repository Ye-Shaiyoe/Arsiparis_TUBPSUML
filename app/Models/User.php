<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'role_selected',
        'profile_photo',
        'switch_token',
        'switch_token_expires_at',
    ];

    /**
     * Cek apakah user adalah admin (selain 'user')
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'admin_aspirasi', 'admin_kasubbag_tu', 'admin_kepala_balai']);
    }

    /**
     * Cek apakah user sudah memilih role admin
     */
    public function hasSelectedRole(): bool
    {
        return (bool) $this->role_selected;
    }

    /**
     * Label role untuk ditampilkan
     */
    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'admin_aspirasi' => 'Arsiparis',
            'admin_kasubbag_tu' => 'Kasubbag TU',
            'admin_kepala_balai' => 'Kepala Balai',
            'admin' => 'Admin (belum update role)',
            default => 'User',
        };
    }

    /**
     * Cek apakah format input adalah NIP yang valid (hanya angka)
     */
    public static function isValidNipFormat(string $nip): bool
    {
        return preg_match('/^\d+$/', $nip) === 1;
    }

    public function isITSupport(): bool
    {
        return $this->role === 'it_support';
    }

    /**
     * Cek apakah user bisa approve tahap tertentu
     */
    public function canApproveTahap(int $tahap): bool
    {
        return match ($this->role) {
            'admin_aspirasi' => $tahap === 2 || $tahap >= 5,
            'admin_kasubbag_tu' => $tahap === 3,
            'admin_kepala_balai' => $tahap === 4,
            'admin' => true, // admin lama masih bisa semua
            default => false,
        };
    }

    public function surats()
    {
        return $this->hasMany(\App\Models\Surat::class);
    }

    /**
     * Data Heatmap untuk User (Pengajuan & Revisi)
     */
    public function getActivityHeatmapData()
    {
        $startDate = \Carbon\Carbon::create(2026, 1, 1)->startOfDay();

        // Ambil data pengajuan
        $submissions = $this->surats()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Ambil data revisi (jika ada field revisi_uploaded_at)
        $revisions = $this->surats()
            ->whereNotNull('revisi_uploaded_at')
            ->where('revisi_uploaded_at', '>=', $startDate)
            ->selectRaw('DATE(revisi_uploaded_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // Gabungkan
        $combined = [];
        foreach ($submissions as $date => $count) {
            $combined[$date] = ($combined[$date] ?? 0) + $count;
        }
        foreach ($revisions as $date => $count) {
            $combined[$date] = ($combined[$date] ?? 0) + $count;
        }

        return $combined;
    }

    /**
     * Data Heatmap untuk Admin (Pemrosesan Surat)
     */
    public function getAdminActivityHeatmapData()
    {
        $startDate = \Carbon\Carbon::create(2026, 1, 1)->startOfDay();

        return \App\Models\SuratTahapan::where('diproses_oleh', $this->id)
            ->whereNotNull('selesai_pada')
            ->where('selesai_pada', '>=', $startDate)
            ->selectRaw('DATE(selesai_pada) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date')
            ->toArray();
    }

    /**
     * Data Aktivitas Mingguan untuk User (Chart)
     */
    public function getWeeklyActivityData()
    {
        $last7Days = collect();
        for ($i = 6; $i >= 0; $i--) {
            $last7Days->put(now()->subDays($i)->format('Y-m-d'), 0);
        }

        $activity = $this->getActivityHeatmapData();
        
        foreach ($last7Days as $date => $val) {
            if (isset($activity[$date])) {
                $last7Days[$date] = $activity[$date];
            }
        }

        return [
            'labels' => $last7Days->keys()->map(fn($d) => \Carbon\Carbon::parse($d)->translatedFormat('D'))->toArray(),
            'values' => $last7Days->values()->toArray(),
            'total' => $last7Days->sum(),
        ];
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'switch_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'switch_token_expires_at' => 'datetime',
            'password' => 'hashed',
            'nip' => 'encrypted',
        ];
    }
}
