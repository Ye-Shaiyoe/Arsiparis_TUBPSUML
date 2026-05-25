<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'changes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'changes' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk mendapatkan icon berdasarkan action
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'create' => 'bi-plus-circle',
            'update' => 'bi-pencil-square',
            'delete' => 'bi-trash',
            'download' => 'bi-download',
            'view' => 'bi-eye',
            'approve' => 'bi-check-circle',
            'reject' => 'bi-x-circle',
            'submit' => 'bi-send',
            'login' => 'bi-box-arrow-in-right',
            'logout' => 'bi-box-arrow-right',
            default => 'bi-info-circle',
        };
    }

    /**
     * Accessor untuk mendapatkan warna badge berdasarkan action
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'create' => '#10b981',
            'update' => '#f59e0b',
            'delete' => '#ef4444',
            'download' => '#3b82f6',
            'view' => '#6366f1',
            'approve' => '#059669',
            'reject' => '#dc2626',
            'submit' => '#2563eb',
            'login' => '#10b981',
            'logout' => '#6b7280',
            default => '#64748b',
        };
    }
}
