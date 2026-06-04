<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

use App\Traits\LogsUserActivity;

class Surat extends Model
{
    use LogsUserActivity;

    protected $fillable = [
        'uuid',
        'user_id',
        'judul',
        'jenis',
        'sifat',
        'tujuan',
        'catatan_pengusul',
        'file_word',
        'file_lampiran',
        'nomor_surat',
        'tanggal_surat',
        'tahap_sekarang',
        'status',
        'perlu_follow_up',
        'catatan_follow_up',
        'deadline_sla',
        'alasan_keterlambatan',
        'disetujui_pada',
        'file_dihapus_pada',
        'file_expires_at',
        'status_revisi',
        'revisi_count',
        'revisi_uploaded_at',
        'rating',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected $casts = [
        'tanggal_surat' => 'date',
        'deadline_sla' => 'datetime',
        'perlu_follow_up' => 'boolean',
        'disetujui_pada' => 'datetime',
        'file_dihapus_pada' => 'datetime',
        'file_expires_at' => 'datetime',
        'status_revisi' => 'boolean',
        'revisi_uploaded_at' => 'datetime',
    ];

    // Label tampilan
    const JENIS_LABEL = [
        'nota_dinas' => 'Nota Dinas',
        'surat_dinas' => 'Surat Dinas',
        'surat_keputusan' => 'Surat Keputusan',
        'surat_pernyataan' => 'Surat Pernyataan',
        'surat_keterangan' => 'Surat Keterangan',
        'surat_undangan' => 'Surat Undangan',
        'surat_lainnya' => 'Surat Lainnya',
    ];

    const NAMA_TAHAP = [
        1 => 'Usulan Diajukan',
        2 => 'Verifikasi Arsiparis',
        3 => 'Verifikasi Kasubbag TU',
        4 => 'Persetujuan Kepala Balai',
        5 => 'Penomoran Surat',
        6 => 'Tanda Tangan (DS)',
        7 => 'Pengiriman via TNDe',
        8 => 'Pengiriman via Srikandi',
        9 => 'Pengarsipan',
        10 => 'Follow Up / Selesai',
    ];

    const ALASAN_KETERLAMBATAN = [
        'Volume surat tinggi',
        'Pejabat sedang dinas luar',
        'Kendala teknis sistem',
        'Menunggu konfirmasi pihak terkait',
        'Hari Libur/Tanggal Merah.',
        'Banyak data masuk',
        'Lainnya',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deleteRequests()
    {
        return $this->hasMany(SuratDeleteRequest::class);
    }

    public function pendingDeleteRequest()
    {
        return $this->hasOne(SuratDeleteRequest::class)->where('status', 'pending');
    }

    public function tahapans()
    {
        return $this->hasMany(SuratTahapan::class)->orderBy('tahap');
    }

    public function tahapanSekarang()
    {
        return $this->hasOne(SuratTahapan::class)->where('tahap', $this->tahap_sekarang);
    }

    // Helpers
    public function getJenisLabelAttribute(): string
    {
        return self::JENIS_LABEL[$this->jenis] ?? $this->jenis;
    }

    public function getNamaTahapAttribute(): string
    {
        return self::NAMA_TAHAP[$this->tahap_sekarang] ?? '-';
    }

    public function getProsesPersenAttribute(): int
    {
        return (int) round(($this->tahap_sekarang / 10) * 100);
    }

    public function getSlaStatusAttribute(): string
    {
        if (!$this->deadline_sla || $this->status === 'selesai')
            return 'ok';
        return now()->gt($this->deadline_sla) ? 'terlambat' : 'ok';
    }

    public function getSisaJamAttribute(): string
    {
        if (!$this->deadline_sla)
            return '-';
        if (now()->gt($this->deadline_sla)) {
            $diff = round(now()->diffInHours($this->deadline_sla), 1);
            return 'Terlambat ' . $diff . 'j';
        }
        $diff = now()->diff($this->deadline_sla);
        return $diff->h . 'j ' . $diff->i . 'm';
    }
    public function getJamTerlambatAttribute(): float
    {
        if (!$this->deadline_sla || $this->status === 'selesai')
            return 0;
        if (now()->gt($this->deadline_sla)) {
            return round(now()->diffInHours($this->deadline_sla), 1);
        }
        return 0;
    }
    public function getSlaColorAttribute(): string
    {
        if ($this->status === 'selesai') return '#22c55e';
        if ($this->sla_status === 'terlambat') return '#ef4444';
        
        $sisaJam = $this->deadline_sla ? now()->diffInHours($this->deadline_sla, false) : 99;
        if ($sisaJam <= 12) return '#f59e0b'; // Kuning jika sisa <= 12 jam
        
        return '#22c55e'; // Hijau jika sisa > 12 jam
    }

    public function getSlaIconAttribute(): string
    {
        if ($this->status === 'selesai') return '🟢';
        if ($this->sla_status === 'terlambat') return '🔴';
        
        $sisaJam = $this->deadline_sla ? now()->diffInHours($this->deadline_sla, false) : 99;
        if ($sisaJam <= 12) return '🟡';
        
        return '🟢';
    }

    public function bisaRevisi(): bool
    {
        return $this->status === 'ditolak';
    }

    public function sedangRevisi(): bool
    {
        return $this->status === 'revisi';
    }

    public function initTahapan(): void
    {
        foreach (self::NAMA_TAHAP as $tahap => $nama) {
            $this->tahapans()->create([
                'tahap' => $tahap,
                'nama_tahap' => $nama,
                'status' => $tahap === 1 ? 'selesai' : 'menunggu',
            ]);
        }
    }
}