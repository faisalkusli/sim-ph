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
            'No Agenda',
            'Pengirim / Instansi',
            'No Surat',
            'Tanggal Surat',
            'Tanggal Diterima',
            'Perihal',
        ];
    }

    public function map($surat): array
    {
        return [
            $surat->no_agenda,
            $surat->asal_instansi,
            $surat->no_surat_pengirim,
            $surat->tgl_surat,
            $surat->tgl_diterima,
            $surat->perihal,
        ];
    }
}