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
            'no_agenda'         => $row['no_agenda'], 
            'asal_instansi'     => $row['asal_instansi'],
            'no_surat_pengirim' => $row['no_surat'], 
            'tgl_surat'         => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_surat']),
            'tgl_diterima'      => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tgl_diterima']),
            'perihal'           => $row['perihal'],
            // file_scan_path dibiarkan null dulu
        ]);
    }
}