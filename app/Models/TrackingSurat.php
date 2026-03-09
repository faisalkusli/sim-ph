<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingSurat extends Model
{
    use HasFactory;

    protected $table = 'tracking_surats';

    protected $fillable = [
        'surat_masuk_id',
        'status_log',
        'tgl_status',
        'user_id',
        'catatan'
    ];

    protected $casts = [
        'tgl_status' => 'datetime',
    ];

    // Tambahkan relationship ini
    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}