<?php

namespace App\Helpers;

/**
 * Helper functions untuk workflow tracking
 */
class WorkflowHelper
{
    /**
     * Get status color class untuk timeline marker
     */
    public static function getStatusColor($statusLog)
    {
        $colors = [
            'Surat Diterima & Divalidasi' => 'approved',
            'Surat Disetujui' => 'approved',
            'Surat Ditolak' => 'rejected',
            'Disposisi Dibuat' => 'processing',
            'Disposisi Dibaca' => 'processing',
            'Diteruskan ke Kabag' => 'processing',
            'Diteruskan ke Kasubag' => 'processing',
            'Diteruskan ke Staff' => 'processing',
            'Sedang Diproses' => 'processing',
            'Hasil Kerja Dilaporkan' => 'processing',
            'Verifikasi Disetujui' => 'approved',
            'Perlu Revisi' => 'revision',
            'Naik ke Bupati' => 'bupati',
            'Turun dari Bupati' => 'approved',
        ];

        return $colors[$statusLog] ?? 'default';
    }

    /**
     * Get status icon untuk timeline marker
     */
    public static function getStatusIcon($statusLog)
    {
        $icons = [
            'Surat Diterima & Divalidasi' => 'check',
            'Surat Disetujui' => 'check-circle',
            'Surat Ditolak' => 'times',
            'Disposisi Dibuat' => 'paper-plane',
            'Disposisi Dibaca' => 'eye',
            'Diteruskan ke Kabag' => 'arrow-right',
            'Diteruskan ke Kasubag' => 'arrow-right',
            'Diteruskan ke Staff' => 'arrow-right',
            'Sedang Diproses' => 'spinner',
            'Hasil Kerja Dilaporkan' => 'file-upload',
            'Verifikasi Disetujui' => 'thumbs-up',
            'Perlu Revisi' => 'exclamation-triangle',
            'Naik ke Bupati' => 'arrow-up',
            'Turun dari Bupati' => 'arrow-down',
        ];

        return $icons[$statusLog] ?? 'circle';
    }

    /**
     * Get status label dengan badge HTML
     */
    public static function getStatusBadge($status, $type = 'surat')
    {
        if ($type === 'surat') {
            $badges = [
                'Menunggu Validasi' => '<span class="badge badge-secondary"><i class="fas fa-clock"></i> Menunggu Validasi</span>',
                'Siap Disposisi' => '<span class="badge badge-primary"><i class="fas fa-check"></i> Siap Disposisi</span>',
                'Ditolak' => '<span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>',
                'Sedang Diproses' => '<span class="badge badge-info"><i class="fas fa-spinner"></i> Sedang Diproses</span>',
                'Perlu Revisi' => '<span class="badge badge-warning text-dark"><i class="fas fa-exclamation"></i> Perlu Revisi</span>',
                'Selesai' => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>',
                'Naik Bupati' => '<span class="badge badge-info"><i class="fas fa-arrow-up"></i> Naik Bupati</span>',
                'Turun Bupati' => '<span class="badge badge-success"><i class="fas fa-arrow-down"></i> Turun Bupati</span>',
            ];
        } else {
            $badges = [
                0 => '<span class="badge badge-secondary"><i class="fas fa-clock"></i> Belum Dibaca</span>',
                1 => '<span class="badge badge-info"><i class="fas fa-spinner"></i> Sedang Diproses</span>',
                2 => '<span class="badge badge-warning text-dark"><i class="fas fa-hourglass"></i> Menunggu Verifikasi</span>',
                3 => '<span class="badge badge-danger"><i class="fas fa-exclamation"></i> Perlu Revisi</span>',
                4 => '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Selesai</span>',
            ];
        }

        return $badges[$status] ?? '<span class="badge badge-secondary">Unknown</span>';
    }

    /**
     * Check apakah user bisa validate surat
     */
    public static function canValidateSurat($user)
    {
        return in_array($user->role, ['admin', 'kabag']);
    }

    /**
     * Check apakah user bisa forward disposisi
     */
    public static function canForwardDisposisi($user)
    {
        return in_array($user->role, ['admin', 'kabag', 'kasubag']);
    }

    /**
     * Check apakah user bisa verify disposisi
     */
    public static function canVerifyDisposisi($user)
    {
        return in_array($user->role, ['admin', 'kabag', 'kasubag']);
    }

    /**
     * Check apakah user bisa naik/turun bupati
     */
    public static function canNaikTurunBupati($user)
    {
        return in_array($user->role, ['kabag', 'kasubag']);
    }
}
