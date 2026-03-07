<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\DisposisiStatus;

class Disposisi extends Model
{
    use HasFactory;

    protected $table = 'disposisis';
    protected $guarded = ['id'];

    protected $fillable = [
        'surat_masuk_id',
        'tujuan_user_id',
        'dari_user_id',
        'instruksi',
        'status',
        'sifat',
        'jenis_surat',
        'catatan_staff',
        'file_hasil',
        'catatan_revisi',
        'tanggal_diterima',
        'tanggal_selesai'
    ];

    protected $casts = [
        'status' => 'integer',
        'tanggal_diterima' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship ke Surat Masuk
     */
    public function surat()
    {
        return $this->belongsTo(SuratMasuk::class, 'surat_masuk_id');
    }

    /**
     * Relationship ke User yang mengirim disposisi
     */
    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dari_user_id');
    }

    /**
     * Relationship ke User yang menerima disposisi
     */
    public function penerima()
    {
        return $this->belongsTo(User::class, 'tujuan_user_id');
    }

    /**
     * Get status label untuk display
     * @return string HTML badge
     */
    public function getStatusBadgeAttribute(): string
    {
        $status = DisposisiStatus::tryFrom($this->status);
        return $status ? $status->badge() : '<span class="badge bg-secondary">Unknown</span>';
    }

    /**
     * Get human-readable status
     * @return string
     */
    public function getStatusLabelAttribute(): string
    {
        $status = DisposisiStatus::tryFrom($this->status);
        return $status ? $status->label() : 'Unknown';
    }

    /**
     * Check if disposisi is completed
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->status === DisposisiStatus::Selesai->value;
    }

    /**
     * Check if disposisi needs revision
     * @return bool
     */
    public function needsRevision(): bool
    {
        return $this->status === DisposisiStatus::PerluRevisi->value;
    }

    /**
     * Check if disposisi is being processed
     * @return bool
     */
    public function isProcessing(): bool
    {
        return $this->status === DisposisiStatus::Proses->value;
    }

    /**
     * Check if disposisi is waiting for verification
     * @return bool
     */
    public function isWaitingVerification(): bool
    {
        return in_array($this->status, [
            DisposisiStatus::MenungguVerifikasiKasubag->value,
            DisposisiStatus::MenungguVerifikasiKabag->value
        ]);
    }
}