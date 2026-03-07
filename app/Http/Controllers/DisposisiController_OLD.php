<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisposisiController extends Controller
{
    // ========================================================
    // 1. FUNGSI VIEW & READ (INDEX, INBOX, MONITORING)
    // ========================================================
    
    public function index()
    {
        // PERBAIKAN: Menambahkan ->get() di akhir query agar data dieksekusi
        $disposisi_masuk = Disposisi::with(['surat', 'pengirim'])
                            ->where('tujuan_user_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->get(); 
                            
        $userLogin = auth()->user();
        $listTujuan = collect();

        if ($userLogin->role == 'kabag') {
            $listTujuan = User::where('role', 'kasubag')->get();
        } elseif ($userLogin->role == 'kasubag') {
            $listTujuan = User::whereIn('role', ['staf'])->get(); 
        } elseif ($userLogin->role == 'Admin' || $userLogin->role == 'super_admin') {
            $listTujuan = User::where('id', '!=', auth()->id())->get();
        }

        return view('inbox', compact('disposisi_masuk', 'listTujuan'));
    }

    public function inbox()
    {
        $userId = Auth::id();
        $disposisi_masuk = Disposisi::with(['surat', 'pengirim'])
                            ->where('tujuan_user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('admin.users.inbox', compact('disposisi_masuk'));
    }

    public function monitoring()
    {
        $monitoring_list = Disposisi::with(['surat', 'penerima'])
                            ->where('dari_user_id', Auth::id())
                            ->orderBy('updated_at', 'desc')
                            ->paginate(10); 

        return view('admin.surat_masuk.monitor', compact('monitoring_list'));
    }

    // ========================================================
    // 2. FUNGSI CREATE & DELETE
    // ========================================================

    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id'    => 'required',
            'tujuan_user_id'    => 'required',
            'sifat'             => 'required',
            'jenis_surat'       => 'required',
            'instruksi'         => 'required',
        ]);

        $surat = SuratMasuk::findOrFail($request->surat_masuk_id);

        Disposisi::create([
            'surat_masuk_id'    => $request->surat_masuk_id,
            'dari_user_id'      => auth()->id(),
            'tujuan_user_id'    => $request->tujuan_user_id, 
            'sifat'             => $request->sifat,
            'jenis_surat'       => $request->jenis_surat,
            'instruksi'         => $request->instruksi,
            'status_disposisi'  => 'Belum Dibaca',
            'tanggal_disposisi' => now(),
        ]);
        
        $surat->update(['status' => 'Sedang Diproses']);

        return back()->with('success', 'Disposisi berhasil dikirim!');
    }

    public function destroy($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        if(!in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag'])){
            abort(403, 'Anda tidak memiliki akses untuk menghapus data ini.');
        }
        
        $disposisi->delete();

        return back()->with('success', 'Data disposisi berhasil dihapus.');
    }

    // ========================================================
    // 3. FUNGSI ALUR STATUS TERBARU (Terima -> Lapor -> Verifikasi)
    // ========================================================

    // STAFF: Menerima Tugas
    public function terima($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        if(auth()->id() != $disposisi->tujuan_user_id){
            return back()->with('error', 'Anda tidak berhak menerima tugas orang lain.');
        }
        
        $disposisi->update([
            'status' => 1 // Status 1: Sedang Dikerjakan
        ]);

        return back()->with('success', 'Tugas diterima! Selamat bekerja.');
    }

    // STAFF: Melaporkan Hasil Kerja (Upload File)
    public function selesai(Request $request, $id)
    {
        // PERBAIKAN: Semua validasi dan variabel sekarang konsisten menggunakan 'file_hasil'
        $request->validate([
            'catatan_staff' => 'required|string',
            'file_hasil'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $disposisi = Disposisi::findOrFail($id);

        $dataUpdate = [
            'status' => 2, // Status 2: Menunggu Verifikasi Kabag
            'catatan_staff' => $request->catatan_staff,
        ];

        // Logika Upload File
        if ($request->hasFile('file_hasil')) {
            
            // Hapus file lama jika ada (agar storage tidak penuh saat revisi berulang)
            if ($disposisi->file_hasil && Storage::disk('public')->exists($disposisi->file_hasil)) {
                Storage::disk('public')->delete($disposisi->file_hasil);
            }

            // Simpan file baru
            $path = $request->file('file_hasil')->store('laporan', 'public');
            $dataUpdate['file_hasil'] = $path;
        }

        $disposisi->update($dataUpdate);

        return back()->with('success', 'Laporan pekerjaan berhasil dikirim ke atasan!');
    }

    // KABAG: Memverifikasi Hasil Kerja (BARU DITAMBAHKAN DARI SuratMasukController)
    public function verifikasi(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        // Asumsi form select/button Kabag menggunakan name="status_akhir" dengan value "Revisi" atau "ACC"
        if ($request->status_akhir == 'Revisi') {
            
            // Skenario Revisi
            $disposisi->update([
                'status' => 3, // Status 3: Perlu Revisi
                'catatan_revisi' => $request->catatan_revisi,
            ]);
            
            return back()->with('warning', 'Tugas dikembalikan ke Staff untuk direvisi.');
            
        } else {
            
            // Skenario ACC / Selesai
            $disposisi->update([
                'status' => 4, // Status 4: Selesai Final
                'catatan_revisi' => null, // Bersihkan catatan revisi sebelumnya
            ]);
            
            return back()->with('success', 'Tugas berhasil di-ACC dan dinyatakan Selesai.');
        }
    }

    // ========================================================
    // 4. FUNGSI LAMA (Dibiarkan agar tidak merusak view lama)
    // ========================================================

    public function reply(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);

        if (Auth::id() != $disposisi->user_id_penerima) {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'file_produk_hukum' => 'required|file|mimes:pdf,doc,docx|max:10240', 
        ]);

        if ($request->hasFile('file_produk_hukum')) {
            $path = $request->file('file_produk_hukum')->store('produk_hukum', 'public');
            
            $disposisi->update([
                'catatan_staff' => $request->catatan_staff,
                'file_hasil' => $path,
            ]);
            
            SuratMasuk::where('id', $disposisi->surat_masuk_id)
                      ->update(['status_terakhir' => 'Dikerjakan Staff']);
        }

        return back()->with('success', 'Produk Hukum berhasil diupload. Menunggu persetujuan Kabag.');
    }

    public function complete($id)
    {
        $disposisi = Disposisi::findOrFail($id);

        if (Auth::id() != $disposisi->user_id_pengirim && Auth::user()->role != 'admin') {
            return back()->with('error', 'Akses ditolak.');
        }

        $disposisi->update(['status_disposisi' => 'Selesai']);

        SuratMasuk::where('id', $disposisi->surat_masuk_id)
                  ->update(['status_terakhir' => 'Disposisi Selesai']);

        return back()->with('success', 'Disposisi disetujui dan ditandai SELESAI.');
    }

    public function approve(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);

        if (Auth::id() != $disposisi->user_id_pengirim && Auth::user()->role != 'admin') {
            return back()->with('error', 'Akses ditolak.');
        }

        $disposisi->update([
            'status_disposisi' => 'Selesai',
            'catatan_kabag' => $request->catatan_kabag
        ]);
        
        return redirect()->route('disposisi.monitoring')
                         ->with('success', 'Disposisi DISETUJUI. Tugas selesai.');
    }

    public function reject(Request $request, $id)
    {
        $disposisi = Disposisi::findOrFail($id);

        if (Auth::id() != $disposisi->user_id_pengirim && Auth::user()->role != 'admin') {
            return back()->with('error', 'Akses ditolak.');
        }

        $request->validate(['catatan_kabag' => 'required']);

        $disposisi->update([
            'status_disposisi' => 'Revisi', 
            'catatan_kabag' => $request->catatan_kabag
        ]);

        return redirect()->route('disposisi.monitoring')
                         ->with('warning', 'Disposisi DITOLAK. Staff diminta revisi.');
    }
}