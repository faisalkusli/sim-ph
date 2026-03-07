<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\TrackingSurat;
use App\Models\Disposisi;
use App\Models\User;
use App\Enums\SuratMasukStatus;
use App\Enums\DisposisiStatus;
use App\Http\Requests\StoreSuratMasukRequest;
use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\ValidasiAwalSuratRequest;
use App\Http\Requests\LaporkanHasilKerjaRequest;
use App\Http\Requests\VerifikasiDisposisiRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\SuratMasukExport;
use App\Imports\SuratMasukImport;
use Maatwebsite\Excel\Facades\Excel;



class SuratMasukController extends Controller
{
    public function index(Request $request)
{
    $query = SuratMasuk::query();

        if (in_array(auth()->user()->role, ['tamu', 'operator'])) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
                $q->where('perihal', 'like', '%' . $cari . '%')
                ->orWhere('asal_instansi', 'like', '%' . $cari . '%')
                ->orWhere('no_surat_pengirim', 'like', '%' . $cari . '%');
            });
        }
        $surat_masuk = $query->latest()->paginate(10);
        $surat_masuk->appends(['cari' => $request->cari]);
        $listTujuan = collect(); 

        if (auth()->check()) {
            $role = auth()->user()->role;
            if (in_array($role, ['admin', 'kabag'])) {
                $listTujuan = User::whereIn('role', ['kasubag', 'staf'])->get();
            } elseif ($role == 'kasubag') {
                $listTujuan = User::where('role', 'staf')->get();
            }
        }
        return view('admin.surat_masuk.index', compact('surat_masuk', 'listTujuan'));
    }

    public function create()
    {
        $tahun = date('Y');
        $lastSurat = SuratMasuk::where('no_agenda', 'LIKE', "%/HK/$tahun")
                    ->orderBy('id', 'desc')->first();
        
        $urutan = $lastSurat ? (intval(explode('/', $lastSurat->no_agenda)[0]) + 1) : 1;
        $no_agenda_baku = "$urutan/HK/$tahun";

        return view('admin.surat_masuk.create', compact('no_agenda_baku'));
    }

    public function store(StoreSuratMasukRequest $request)
    {
        $validated = $request->validated();
        
        DB::transaction(function() use ($request, $validated) {
            $path = null;
            if ($request->hasFile('file_scan')) {
                $path = $request->file('file_scan')->store('surat_masuk', 'public');
            }

            $surat = SuratMasuk::create([
                'user_id' => auth()->id(),
                'no_agenda' => $validated['no_agenda'],
                'no_surat_pengirim' => $validated['no_surat_pengirim'],
                'asal_instansi' => $validated['asal_instansi'],
                'perihal' => $validated['perihal'],
                'tgl_surat' => $validated['tgl_surat'],
                'tgl_diterima' => $validated['tgl_diterima'],
                'file_scan_path' => $path,
                'status' => SuratMasukStatus::MenungguValidasi->value, 
            ]);

            TrackingSurat::create([
                'surat_masuk_id' => $surat->id,
                'status_log' => 'Baru (Menunggu Validasi)',
                'tgl_status' => now(),
                'user_id' => Auth::id(),
                'catatan' => 'Surat masuk diinput ke sistem.'
            ]);
        });
        
        return redirect()->route('surat-masuk.index')->with('success', 'Surat berhasil disimpan! Menunggu Validasi Kabag.');
    } 

    public function show($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        
        $userLogin = auth()->user();
        $listTujuan = collect(); 

        if (in_array($userLogin->role, ['admin', 'super_admin'])) {
            $listTujuan = User::where('role', 'kabag')->get();
        } elseif ($userLogin->role === 'kabag') {
            $listTujuan = User::where('role', 'kasubag')->get();
        } elseif ($userLogin->role === 'kasubag') {
            $listTujuan = User::where('role', 'staf')->get();
        }

        return view('admin.surat_masuk.show', compact('surat', 'listTujuan'));
    }

    public function destroy($id)
    {
        $surat = SuratMasuk::findOrFail($id);
        if ($surat->file_scan_path && Storage::exists('public/' . $surat->file_scan_path)) {
            Storage::delete('public/' . $surat->file_scan_path);
        }
        $surat->delete();

        return back()->with('success', 'Surat berhasil dihapus.');
    }

    public function validasiAwal(ValidasiAwalSuratRequest $request, $id) 
    {
        $validated = $request->validated();
        $surat = SuratMasuk::findOrFail($id);
        
        // Cek apakah surat menunggu validasi
        if ($surat->status !== SuratMasukStatus::MenungguValidasi->value) {
            return back()->with('error', 'Surat tidak dalam status menunggu validasi!');
        }

        if ($validated['status_verifikasi'] === 'Tolak') {
            $surat->update([
                'status' => SuratMasukStatus::Ditolak->value,
                'alasan_tolak' => $validated['alasan_tolak'],
                'catatan_verifikasi' => 'Ditolak oleh ' . auth()->user()->name . ' pada ' . now()->format('d-m-Y H:i:s')
            ]);

            TrackingSurat::create([
                'surat_masuk_id' => $id,
                'status_log' => SuratMasukStatus::Ditolak->value,
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => $validated['alasan_tolak']
            ]);

            return back()->with('warning', 'Validasi Awal: Surat DITOLAK.');
        } else {
            $surat->update([
                'status' => SuratMasukStatus::SiapDisposisi->value,
                'alasan_tolak' => null,
                'catatan_verifikasi' => 'Validasi Awal OK oleh ' . auth()->user()->name
            ]);

            TrackingSurat::create([
                'surat_masuk_id' => $id,
                'status_log' => SuratMasukStatus::SiapDisposisi->value,
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => 'Siap untuk didisposisikan'
            ]);

            return back()->with('success', 'Validasi Awal Selesai - Surat Siap Disposisi.');
        }
    }


    public function uploadHasilKerja(LaporkanHasilKerjaRequest $request, $id)
    {
        $validated = $request->validated();
        
        $surat = SuratMasuk::findOrFail($id);
        
        $path = null;
        if ($request->hasFile('file_hasil')) {
            // Delete old file if exists
            if ($surat->file_draft_path && Storage::disk('public')->exists($surat->file_draft_path)) {
                Storage::disk('public')->delete($surat->file_draft_path);
            }
            $path = $request->file('file_hasil')->store('surat_draft', 'public');
        }

        $surat->update([
            'file_draft_path' => $path,
            'catatan_staff' => $validated['catatan_staff'],
            'status' => SuratMasukStatus::MenungguValidasi->value // or appropriate status
        ]);

        return back()->with('success', 'File hasil kerja berhasil diupload!');
    }

    // public function verifikasiAkhir(Request $request, $id)
    // {
    //    $surat = SuratMasuk::findOrFail($id);
    //    $keputusan = $request->input('status_akhir'); // Mengambil value dari tombol submit
    //
    //    if ($keputusan == 'Revisi') {
            // KASUS: MINTA REVISI
    //        $surat->update([
    //           'status' => 'Perlu Revisi',
    //            'catatan_revisi' => $request->input('catatan_revisi')
    //        ]);

            // Opsional: Jika ingin status di Inbox Staff berubah jadi 'Proses' lagi
            // $lastDisposisi = $surat->disposisi()->latest()->first();
            // if($lastDisposisi) { $lastDisposisi->update(['status' => 1]); }

     //       return back()->with('warning', 'Surat dikembalikan ke Staf untuk Revisi.');

    //    } else {
    //        // KASUS: ACC / SELESAI
    //        $surat->update([
    //            'status' => 'Selesai',
    //            'catatan_verifikasi' => 'Pekerjaan Selesai & Final pada ' . now()->format('d-m-Y')
    //        ]);

    //        return back()->with('success', 'Verifikasi Akhir Selesai. Proses Tuntas!');
    //    }
    // }

    public function cetakDisposisi($id)
    {
        $surat = SuratMasuk::with(['disposisi.penerima', 'disposisi.pengirim'])->findOrFail($id);
        $no_agenda_bersih = str_replace(['/', '\\'], '-', $surat->no_agenda);
        if (empty($no_agenda_bersih)) {
            $no_agenda_bersih = 'ID_' . $surat->id;
        }
        $pdf = Pdf::loadView('admin.surat_masuk.cetak', compact('surat'));

        return $pdf->stream('Lembar_Disposisi_' . $no_agenda_bersih . '.pdf');
    }
    public function cetak($id)
    {
        $item = SuratMasuk::findOrFail($id);
        return view('admin.surat_masuk.cetak_penerima', compact('item'));
    }

    /**
     * Naik Bupati - Escalate letter to Bupati for approval
     */
    public function naikBupati(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Validate surat status
        if ($surat->status !== SuratMasukStatus::Selesai->value) {
            return back()->with('error', 'Surat harus status Selesai sebelum naik Bupati!');
        }

        // Validate role
        if (!in_array(auth()->user()->role, ['kabag', 'kasubag'])) {
            return back()->with('error', 'Hanya Kabag/Kasubag yang bisa naik Bupati!');
        }

        $request->validate([
            'no_npknd' => 'nullable|string|max:100',
        ]);

        DB::transaction(function() use ($surat, $request) {
            $surat->update([
                'status' => SuratMasukStatus::NaikBupati->value,
                'no_npknd' => $request->no_npknd,
                'tgl_naik_bupati' => now()
            ]);

            // Create tracking log
            TrackingSurat::create([
                'surat_masuk_id' => $surat->id,
                'status_log' => 'Naik ke Bupati',
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => 'Surat dikirim ke Bupati oleh ' . auth()->user()->name . 
                            ($request->no_npknd ? ' (NPKND: ' . $request->no_npknd . ')' : '')
            ]);
        });

        return back()->with('success', 'Surat berhasil dinaikkan ke Bupati untuk persetujuan!');
    }

    /**
     * Turun Bupati - Letter received back from Bupati (FINAL)
     */
    public function turunBupati(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);

        // Validate surat status
        if ($surat->status !== SuratMasukStatus::NaikBupati->value) {
            return back()->with('error', 'Surat harus status Naik Bupati terlebih dahulu!');
        }

        // Validate role
        if (!in_array(auth()->user()->role, ['kabag', 'kasubag'])) {
            return back()->with('error', 'Hanya Kabag/Kasubag yang bisa terima dari Bupati!');
        }

        DB::transaction(function() use ($surat) {
            $surat->update([
                'status' => SuratMasukStatus::TurunBupati->value,
                'tgl_turun_bupati' => now()
            ]);

            // Create tracking log
            TrackingSurat::create([
                'surat_masuk_id' => $surat->id,
                'status_log' => 'Turun dari Bupati',
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => 'Surat diterima dari Bupati oleh ' . auth()->user()->name . ' - PROSES PENUH SELESAI ✓'
            ]);
        });

        return back()->with('success', 'Surat berhasil diterima dari Bupati! Proses selesai.');
    }

    /**
     * View tracking history for a letter
     */
    public function tracking($id)
    {
        $surat = SuratMasuk::with(['user', 'disposisi.pengirim', 'disposisi.penerima', 'tracking.user'])
                            ->findOrFail($id);

        $tracking = TrackingSurat::where('surat_masuk_id', $id)
                                ->with('user')
                                ->orderBy('tgl_status', 'asc')
                                ->get();

        return view('admin.surat_masuk.tracking', compact('surat', 'tracking'));
    }

    public function update(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $request->validate([
            'no_agenda'         => 'required|string',
            'asal_instansi'     => 'required|string',
            'no_surat_pengirim' => 'required|string',
            'tgl_surat'         => 'required|date',
            'tgl_diterima'      => 'required|date',
            'perihal'           => 'required|string',
            'file_scan'         => 'nullable|mimes:pdf|max:10240',
        ]);

        $data = [
            'no_agenda'         => $request->no_agenda,
            'asal_instansi'     => $request->asal_instansi,
            'no_surat_pengirim' => $request->no_surat_pengirim,
            'tgl_surat'         => $request->tgl_surat,
            'tgl_diterima'      => $request->tgl_diterima,
            'perihal'           => $request->perihal,
        ];

        if ($request->hasFile('file_scan')) {

            if ($surat->file_scan_path && Storage::exists('public/' . $surat->file_scan_path)) {
                Storage::delete('public/' . $surat->file_scan_path);
            }

            $path = $request->file('file_scan')->store('surat_masuk', 'public');
            $data['file_scan_path'] = $path;
        }

        $surat->update($data);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Data surat berhasil diperbarui!');
    }

    public function export(Request $request) 
    {
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        return Excel::download(new SuratMasukExport($startDate, $endDate), 'surat_masuk_' . date('Y-m-d_His') . '.xlsx');
    }
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
    
        Excel::import(new SuratMasukImport, $request->file('file'));
        
        return back()->with('success', 'Data berhasil diimport!');
    }

    public function inbox(Request $request)
    {
        $userId = auth()->id();
        $disposisi = Disposisi::with(['surat', 'pengirim'])
                        ->where('tujuan_user_id', $userId)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('admin.surat_masuk.inbox', compact('disposisi'));
    }

    /**
     * Monitor letter status and workflow tracking
     */
    public function monitor($id)
    {
        $surat = SuratMasuk::with(['disposisi.penerima', 'disposisi.pengirim'])
                    ->findOrFail($id);
        $tracking = TrackingSurat::where('surat_masuk_id', $id)
                        ->orderBy('tgl_status', 'desc')
                        ->get();

        return view('admin.surat_masuk.monitor', compact('surat', 'tracking'));
    }

    /**
     * Show recap of all dispositions
     */
    public function rekapDisposisi(Request $request)
    {
        $query = Disposisi::with(['surat', 'penerima', 'pengirim']);
        
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59'
            ]);
        }
        
        $disposisi = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.surat_masuk.recap_disposisi', compact('disposisi'));
    }

    /**
     * Verify letter after staff completed work
     */
    public function verifikasi(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $keputusan = $request->input('status_akhir');

        if ($keputusan === 'Revisi') {
            $surat->update(['status' => SuratMasukStatus::PerluRevisi->value]);
        } else {
            $surat->update(['status' => SuratMasukStatus::Selesai->value]);
        }
            return back()->with('success', 'Verifikasi Akhir Selesai. Proses Tuntas!');
    }

    /**
     * Check status of a letter
     */
    public function checkStatus($id)
    {
        $surat = SuratMasuk::with(['disposisi.penerima', 'disposisi.pengirim'])
                    ->findOrFail($id);
        
        return response()->json([
            'id' => $surat->id,
            'status' => $surat->status,
            'perihal' => $surat->perihal,
            'disposisi_count' => $surat->disposisi->count(),
            'last_update' => $surat->updated_at
        ]);
    }

    /**
     * Update status of letter
     */
    public function updateStatus(Request $request, $id)
    {
        $surat = SuratMasuk::findOrFail($id);
        $request->validate(['status' => 'required']);
        
        $surat->update(['status' => $request->status]);
        
        TrackingSurat::create([
            'surat_masuk_id' => $id,
            'status_log' => $request->status,
            'tgl_status' => now(),
            'user_id' => auth()->id(),
            'catatan' => $request->input('catatan', 'Status updated')
        ]);

        return back()->with('success', 'Status surat berhasil diupdate.');
    }

    /**
     * Store disposition for a letter
     */
    public function storeDisposisi(StoreDisposisiRequest $request, $id)
    {
        $validated = $request->validated();
        $surat = SuratMasuk::findOrFail($id);
        
        if (!in_array($surat->status, [
            SuratMasukStatus::SiapDisposisi->value,
            SuratMasukStatus::SedangDiproses->value
        ])) {
            return back()->with('error', 'Surat belum siap didisposisikan!');
        }

        Disposisi::create([
            'surat_masuk_id' => $id,
            'dari_user_id' => auth()->id(),
            'tujuan_user_id' => $validated['tujuan_user_id'],
            'instruksi' => $validated['instruksi'],
            'sifat' => $validated['sifat'],
            'jenis_surat' => $validated['jenis_surat'],
            'status' => DisposisiStatus::Belum->value
        ]);

        $surat->update(['status' => SuratMasukStatus::SedangDiproses->value]);

        return back()->with('success', 'Disposisi berhasil dikirim.');
    }

    /**
     * Validate letter (alias for validasiAwal for consistency)
     */
    public function validasiSurat(ValidasiAwalSuratRequest $request, $id)
    {
        return $this->validasiAwal($request, $id);
    }

    /**
     * Delete disposition for a letter
     */
    public function hapusDisposisi($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        $disposisi->delete();

        return back()->with('success', 'Disposisi berhasil dihapus.');
    }
}