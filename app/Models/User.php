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
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
            'nip' => 'encrypted',
        ];
    }
}
