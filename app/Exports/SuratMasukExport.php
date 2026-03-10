<?php

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromQuery; // Ganti FromCollection jadi FromQuery
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\Exportable; // Tambahan trait

class SuratMasukExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }
    public function query()
    {
        $query = SuratMasuk::query();

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tgl_surat', [$this->startDate, $this->endDate]);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
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
            'tgl_turun_bupati',
        ];
    }

    public function map($surat): array
    {
        return [
            $surat->no_agenda,
            $surat->no_surat_pengirim,
            $surat->asal_instansi,
            $surat->jenis_surat,
            $surat->perihal,
            $surat->tgl_surat,
            $surat->tgl_diterima,
            $surat->status,
            $surat->alasan_tolak,
            $surat->file_scan_path,
            $surat->file_pengantar_path,
            $surat->file_pernyataan_path,
            $surat->file_lampiran_path,
            $surat->file_draft_path,
            $surat->catatan_verifikasi,
            $surat->validasi_oleh,
            $surat->tgl_validasi,
            $surat->catatan_revisi,
            $surat->catatan_staff,
            $surat->no_npknd,
            $surat->tgl_naik_bupati,
            $surat->tgl_turun_bupati,
        ];
    }
}