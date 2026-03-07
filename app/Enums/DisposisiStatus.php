<?php

namespace App\Enums;

/**
 * Status untuk Disposisi (Penugasan)
 * 
 * Workflow:
 * 0 (Belum Dibaca) → 1 (Sedang Diproses) → 2 (Menunggu Verifikasi Kasubag) → 3 (Menunggu Verifikasi Kabag) → 5 (Selesai)
 *                                              ↓                                    ↓
 *                                           4 (Perlu Revisi dari Kasubag)    4 (Perlu Revisi dari Kabag)
 */
enum DisposisiStatus: int
{
    case Belum = 0;
    case Proses = 1;
    case MenungguVerifikasiKasubag = 2;
    case MenungguVerifikasiKabag = 3;
    case PerluRevisi = 4;
    case Selesai = 5;

    public function label(): string
    {
        return match($this) {
            self::Belum => 'Belum Dibaca',
            self::Proses => 'Sedang Diproses',
            self::MenungguVerifikasiKasubag => 'Menunggu Verifikasi Kasubag',
            self::MenungguVerifikasiKabag => 'Menunggu Verifikasi Kabag',
            self::PerluRevisi => 'Perlu Revisi',
            self::Selesai => 'Selesai',
        };
    }

    public function badge(): string
    {
        return match($this) {
            self::Belum => '<span class="badge bg-secondary"><i class="fas fa-clock"></i> Belum Dibaca</span>',
            self::Proses => '<span class="badge bg-info"><i class="fas fa-spinner"></i> Sedang Diproses</span>',
            self::MenungguVerifikasiKasubag => '<span class="badge bg-warning text-dark"><i class="fas fa-hourglass"></i> Menunggu Kasubag</span>',
            self::MenungguVerifikasiKabag => '<span class="badge bg-warning text-dark"><i class="fas fa-hourglass"></i> Menunggu Kabag</span>',
            self::PerluRevisi => '<span class="badge bg-danger"><i class="fas fa-exclamation"></i> Perlu Revisi</span>',
            self::Selesai => '<span class="badge bg-success"><i class="fas fa-check-circle"></i> Selesai</span>',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Belum => 'secondary',
            self::Proses => 'info',
            self::MenungguVerifikasiKasubag => 'warning',
            self::MenungguVerifikasiKabag => 'warning',
            self::PerluRevisi => 'danger',
            self::Selesai => 'success',
        };
    }
}
