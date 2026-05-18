<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItSupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'subjek',
        'kategori',
        'detail',
        'status',
        'catatan_it'
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
