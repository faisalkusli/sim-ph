<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function cetak(Request $request)
    {
            $request->validate(['tgl_awal' => 'required|date', 'tgl_akhir' => 'required|date', 'jenis_surat' => 'required']);

        $tgl_awal = $request->tgl_awal;
        $tgl_akhir = $request->tgl_akhir;
        $jenis = $request->jenis_surat;

        if ($jenis == 'surat_masuk') {
            $data = SuratMasuk::whereBetween('tgl_diterima', [$tgl_awal, $tgl_akhir])
                        ->orderBy('tgl_diterima', 'asc')
                        ->get();
            $judul = "Laporan Surat Masuk";
        } else {
            $data = SuratKeluar::whereBetween('tgl_surat', [$tgl_awal, $tgl_akhir])
                        ->orderBy('tgl_surat', 'asc')
                        ->get();
            $judul = "Laporan Surat Keluar";
        }

        return view('admin.laporan.cetak', compact('data', 'tgl_awal', 'tgl_akhir', 'jenis', 'judul'));
    }
}