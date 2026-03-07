<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengambilanProdukHukum;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Storage;

class PengambilanProdukController extends Controller
{
    // TAMPILAN TABEL (Index) - Kita buat nanti di Tahap 2
    public function index()
    {
        $items = PengambilanProdukHukum::with('surat')->latest()->get();
        return view('admin.pengambilan.index', compact('items'));
    }

    // FORM TAMBAH DATA
    public function create()
    {
        // Ambil surat masuk yang BELUM diambil produk hukumnya (supaya tidak dobel)
        // Atau ambil semua surat masuk juga boleh
        $surat_masuk = SuratMasuk::orderBy('no_agenda', 'desc')->get();
        
        return view('admin.pengambilan.create', compact('surat_masuk'));
    }

    // PROSES SIMPAN
    public function store(Request $request)
    {

        $request->validate([
            'surat_masuk_id'      => 'required|exists:surat_masuks,id',
            'tanggal_pengambilan' => 'required|date',
            'instansi_pengambil'  => 'required|string',
            'nama_pengambil'      => 'required|string',
            'no_hp_pengambil'     => 'nullable|string',
            'nomor_register'      => 'required|string',
            'file_produk'         => 'nullable|mimes:pdf,doc,docx|max:5120', // Max 5MB
        ]);

        $data = $request->all();

        // Upload File jika ada
        if ($request->hasFile('file_produk')) {
            $file = $request->file('file_produk');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/produk_hukum', $filename);
            $data['file_produk'] = $filename;
        }


        $pengambilan = PengambilanProdukHukum::create($data);

        // Update status surat masuk menjadi 'Selesai'
        if ($pengambilan && $pengambilan->surat_masuk_id) {
            $surat = \App\Models\SuratMasuk::find($pengambilan->surat_masuk_id);
            if ($surat) {
                $surat->status = \App\Enums\SuratMasukStatus::Selesai->value;
                $surat->save();
            }
        }

        return redirect()->route('pengambilan.index')->with('success', 'Data Pengambilan Berhasil Disimpan!');
    }

    public function destroy($id)
    {
        $item = PengambilanProdukHukum::findOrFail($id);
        
        // Hapus file fisik jika ada
        if ($item->file_produk && Storage::exists('public/produk_hukum/' . $item->file_produk)) {
            Storage::delete('public/produk_hukum/' . $item->file_produk);
        }

        $item->delete();
        return redirect()->route('pengambilan.index')->with('success', 'Data berhasil dihapus');
    }
    
    public function cetak($id)
    {
        // Ambil data berdasarkan ID, beserta relasi suratnya
        $item = PengambilanProdukHukum::with('surat')->findOrFail($id);
        
        return view('admin.pengambilan.cetak', compact('item'));
    }
}