<?php

namespace App\Imports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuratMasukImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new SuratMasuk([
            'no_agenda'             => $row['no_agenda'] ?? null,
            'no_surat_pengirim'     => $row['no_surat_pengirim'] ?? null,
            'asal_instansi'         => $row['asal_instansi'] ?? null,
            'jenis_surat'           => $row['jenis_surat'] ?? null,
            'perihal'               => $row['perihal'] ?? null,
            'tgl_surat'             => isset($row['tgl_surat']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_surat']) : null,
            'tgl_diterima'          => isset($row['tgl_diterima']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_diterima']) : null,
            'status'                => $row['status'] ?? 'Menunggu Validasi',
            'alasan_tolak'          => $row['alasan_tolak'] ?? null,
            'file_scan_path'        => $row['file_scan_path'] ?? null,
            'file_pengantar_path'   => $row['file_pengantar_path'] ?? null,
            'file_pernyataan_path'  => $row['file_pernyataan_path'] ?? null,
            'file_lampiran_path'    => $row['file_lampiran_path'] ?? null,
            'file_draft_path'       => $row['file_draft_path'] ?? null,
            'catatan_verifikasi'    => $row['catatan_verifikasi'] ?? null,
            'validasi_oleh'         => $row['validasi_oleh'] ?? null,
            'tgl_validasi'          => $row['tgl_validasi'] ?? null,
            'catatan_revisi'        => $row['catatan_revisi'] ?? null,
            'catatan_staff'         => $row['catatan_staff'] ?? null,
            'no_npknd'              => $row['no_npknd'] ?? null,
            'tgl_naik_bupati'       => $row['tgl_naik_bupati'] ?? null,
            'tgl_turun_bupati'      => $row['tgl_turun_bupati'] ?? null,
        ]);
    }
}