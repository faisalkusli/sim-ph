<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SuratMasukStatus;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuks';

    protected $fillable = [
        'user_id',
        'no_agenda',
        'no_surat_pengirim',
        'asal_instansi',
        'jenis_surat', 
        'perihal',
        'tgl_surat',
        'tgl_diterima',
        'status',
        'alasan_tolak',
        'file_scan_path',
        'file_pengantar_path',
        'file_pernyataan_path',
        'file_lampiran_path',
        'file_draft_path',
        'catatan_verifikasi',
        'validasi_oleh',
        'tgl_validasi',
        'catatan_revisi',
        'catatan_staff',
        'no_npknd',
        'tgl_naik_bupati',
        'tgl_turun_bupati'
    ];

    protected $casts = [
        'tgl_surat' => 'date',
        'tgl_diterima' => 'date',
        'tgl_naik_bupati' => 'datetime',
        'tgl_turun_bupati' => 'datetime',
        'tgl_validasi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship ke User yang menginput surat
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship ke User pengirim surat (jika ada)
     */
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dari_user_id')->withDefault();
    }
    
    /**
     * Relationship ke Disposisi yang terkait
     */
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class);
    }

    /**
     * Relationship ke Pengambilan Produk Hukum
     */
    public function pengambilan()
    {
        return $this->hasOne(PengambilanProdukHukum::class, 'surat_masuk_id');
    }

    /**
     * Relationship ke Tracking Surat
     */
    public function tracking()
    {
        return $this->hasMany(TrackingSurat::class, 'surat_masuk_id');
    }

    /**
     * Get status label untuk display
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->status;
    }

    /**
     * Get status badge untuk display 
     * @return string HTML
     */
    public function getStatusBadgeAttribute(): string
    {
        $status = SuratMasukStatus::tryFrom($this->status);
        return $status ? $status->badge() : '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Check if surat is waiting validation
     * @return bool
     */
    public function isWaitingValidation(): bool
    {
        return $this->status === SuratMasukStatus::MenungguValidasi->value;
    }

    /**
     * Check if surat is rejected
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->status === SuratMasukStatus::Ditolak->value;
    }

    /**
     * Check if surat is ready for disposition
     * @return bool
     */
    public function isReadyForDisposition(): bool
    {
        return $this->status === SuratMasukStatus::SiapDisposisi->value;
    }

    /**
     * Check if surat is completed
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === SuratMasukStatus::Selesai->value;
    }
}