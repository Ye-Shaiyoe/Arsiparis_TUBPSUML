<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Aspirasi extends Model
{
    protected $fillable = [
        'uuid',
        'user_id',
        'judul',
        'isi',
        'kategori',
        'status',
        'balasan',
        'dibalas_at',
    ];

    protected $casts = [
        'dibalas_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
