<?php

namespace App\Enums;

/**
 * Status untuk Surat Masuk (Incoming Letters)
 * 
 * Workflow:
 * Menunggu Validasi → Siap Disposisi / Ditolak → Sedang Diproses → Perlu Revisi / Selesai
 */
enum SuratMasukStatus: string
{
    case MenungguValidasi = 'Menunggu Validasi';
    case SiapDisposisi = 'Siap Disposisi';
    case Ditolak = 'Ditolak';
    case SedangDiproses = 'Sedang Diproses';
    case PerluRevisi = 'Perlu Revisi';
    case Selesai = 'Selesai';
    case NaikBupati = 'Naik Bupati';
    case TurunBupati = 'Turun Bupati';

    public function label(): string
    {
        return $this->value;
    }

    public function badge(): string
    {
        return match($this) {
            self::MenungguValidasi => '<span class="badge bg-secondary"><i class="fas fa-clock"></i> Menunggu Validasi</span>',
            self::SiapDisposisi => '<span class="badge bg-primary"><i class="fas fa-check"></i> Siap Disposisi</span>',
            self::Ditolak => '<span class="badge bg-danger"><i class="fas fa-times"></i> Ditolak</span>',
            self::SedangDiproses => '<span class="badge bg-info"><i class="fas fa-spinner"></i> Sedang Diproses</span>',
            self::PerluRevisi => '<span class="badge bg-warning text-dark"><i class="fas fa-exclamation"></i> Perlu Revisi</span>',
            self::Selesai => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Selesai</span>',
            self::NaikBupati => '<span class="badge bg-info"><i class="fas fa-arrow-up"></i> Naik Bupati</span>',
            self::TurunBupati => '<span class="badge bg-warning"><i class="fas fa-arrow-down"></i> Turun Bupati</span>',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::MenungguValidasi => 'secondary',
            self::SiapDisposisi => 'primary',
            self::Ditolak => 'danger',
            self::SedangDiproses => 'info',
            self::PerluRevisi => 'warning',
            self::Selesai => 'success',
            self::NaikBupati => 'info',
            self::TurunBupati => 'warning',
        };
    }

    public static function isValid(string $status): bool
    {
        return in_array($status, array_map(fn ($case) => $case->value, self::cases()));
    }
}
