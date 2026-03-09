<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\TrackingSurat;
use App\Models\User;
use App\Enums\DisposisiStatus;
use App\Enums\SuratMasukStatus;
use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\LaporkanHasilKerjaRequest;
use App\Http\Requests\VerifikasiDisposisiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

/**
 * DisposisiController
 * 
 * Mengelola workflow disposisi (penugasan) kepada staff
 * 
 * Workflow:
 * Status 0 (Belum Dibaca) → Status 1 (Proses) → Status 2 (Tunggu Verifikasi) → Status 4 (Selesai)
 *                                                  ↓
 *                                           Status 3 (Perlu Revisi)
 */
class DisposisiController extends Controller
{
    
    public function index()
    {
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
        } elseif (in_array($userLogin->role, ['admin', 'super_admin'])) {
            $listTujuan = User::where('id', '!=', auth()->id())->get();
        }

        return view('inbox', compact('disposisi_masuk', 'listTujuan'));
    }

    /**
     * View detail disposisi (Auto-mark as read when opened)
     */
    public function show($id)
    {
        $disposisi = Disposisi::with(['surat', 'pengirim', 'penerima'])->findOrFail($id);

        // Check permission - only recipient can view
        if ($disposisi->tujuan_user_id !== auth()->id() && 
            !in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403, 'Anda tidak berhak melihat disposisi ini.');
        }

        // Auto-mark as read (status 0 → 1)
        if ($disposisi->status === DisposisiStatus::Belum->value) {
            DB::transaction(function() use ($disposisi) {
                $disposisi->update([
                    'status' => DisposisiStatus::Proses->value,
                    'tanggal_diterima' => now()
                ]);

                // Create tracking log
                TrackingSurat::create([
                    'surat_masuk_id' => $disposisi->surat_masuk_id,
                    'status_log' => 'Disposisi Dibaca',
                    'tgl_status' => now(),
                    'user_id' => auth()->id(),
                    'catatan' => $disposisi->penerima->name . ' telah membaca disposisi'
                ]);
            });
        }

        $userLogin = auth()->user();
        $listTujuan = collect();

        // Tentukan siapa yang bisa dituju untuk forward
        if ($userLogin->role == 'kabag') {
            // Kabag dapat disposisi ke kasubag MAUPUN langsung ke seluruh staf
            $listTujuan = User::whereIn('role', ['kasubag', 'staf', 'staff'])->get();
        } elseif ($userLogin->role == 'kasubag') {
            $listTujuan = User::whereIn('role', ['staf', 'staff'])->get();
        } elseif (in_array($userLogin->role, ['admin', 'super_admin'])) {
            $listTujuan = User::where('id', '!=', auth()->id())->get();
        }

        return view('admin.disposisi.show', compact('disposisi', 'listTujuan'));
    }

    /**
     * Menampilkan inbox disposisi untuk user yang menerima
     */
    public function inbox()
    {
        $userId = Auth::id();
        $userRole = auth()->user()->role;
        
        // ===== AUTO-MARK AS READ =====
        // Semua disposisi berstatus "Belum Dibaca" (0) yang ditujukan ke saya
        // langsung ditandai "Sedang Diproses" (1) saat inbox dibuka
        DB::transaction(function() use ($userId) {
            $unread = Disposisi::where('tujuan_user_id', $userId)
                        ->where('status', DisposisiStatus::Belum->value)
                        ->get();

            foreach ($unread as $d) {
                $d->update([
                    'status'           => DisposisiStatus::Proses->value,
                    'tanggal_diterima' => now(),
                ]);
                TrackingSurat::create([
                    'surat_masuk_id' => $d->surat_masuk_id,
                    'status_log'     => 'Disposisi Dibaca',
                    'tgl_status'     => now(),
                    'user_id'        => $userId,
                    'catatan'        => ($d->penerima->name ?? 'User') . ' telah membaca disposisi',
                ]);
            }
        });

        // ===== BAGIAN 1: DISPOSISI UNTUK DIKERJAKAN =====
        // Disposisi yang ditugaskan KEPADA saya (tujuan_user_id = me)
        $disposisi_masuk = Disposisi::with(['surat', 'pengirim', 'penerima'])
                            ->where('tujuan_user_id', $userId)
                            ->orderBy('created_at', 'desc')
                            ->get();
        
        // ===== BAGIAN 2: DISPOSISI UNTUK DIVERIFIKASI =====
        // Logika verifikasi berbeda per role:
        // - Kabag: semua disposisi berstatus 3 (MenungguVerifikasiKabag), sebab kabag adalah verifikator akhir
        // - Kasubag: disposisi yang SAYA kirim berstatus 2 (saya yang bertanggung jawab verifikasi level pertama)
        // - Admin: semua disposisi berstatus 2 atau 3
        $disposisi_verifikasi = collect();
        if ($userRole === 'kabag') {
            // Kabag melihat semua disposisi yang menunggu verifikasi kabag (status 3),
            // termasuk dari kasubag yang meneruskan tugas staf
            $disposisi_verifikasi = Disposisi::with(['surat', 'pengirim', 'penerima'])
                                ->where('status', DisposisiStatus::MenungguVerifikasiKabag->value)
                                ->orderBy('updated_at', 'desc')
                                ->get();
        } elseif ($userRole === 'kasubag') {
            // Kasubag melihat SEMUA disposisi yang dia kirim ke staf (status 0–4),
            // bukan hanya yang sudah selesai dikerjakan (status 2).
            // Ini memastikan kasubag bisa memantau tugas sejak dikirim hingga selesai diverifikasi.
            $disposisi_verifikasi = Disposisi::with(['surat', 'pengirim', 'penerima'])
                                ->where('dari_user_id', $userId)
                                ->whereNotIn('status', [DisposisiStatus::Selesai->value])
                                ->orderBy('updated_at', 'desc')
                                ->get();
        } elseif (in_array($userRole, ['admin', 'super_admin'])) {
            // Admin melihat semua yang menunggu verifikasi
            $disposisi_verifikasi = Disposisi::with(['surat', 'pengirim', 'penerima'])
                                ->whereIn('status', [
                                    DisposisiStatus::MenungguVerifikasiKasubag->value,
                                    DisposisiStatus::MenungguVerifikasiKabag->value,
                                ])
                                ->orderBy('updated_at', 'desc')
                                ->get();
        }
        
        // Gabungkan kedua list, hapus duplikat (berdasarkan id), sort by updated_at
        $disposisi_masuk = $disposisi_masuk->merge($disposisi_verifikasi)->unique('id')->sortByDesc('updated_at');

        // Tentukan siapa yang bisa dituju untuk forward
        $userLogin = auth()->user();
        $listTujuan = collect();

        if ($userLogin->role == 'kabag') {
            // Kabag dapat disposisi ke kasubag MAUPUN langsung ke seluruh staf
            $listTujuan = User::whereIn('role', ['kasubag', 'staf', 'staff'])->get();
        } elseif ($userLogin->role == 'kasubag') {
            $listTujuan = User::whereIn('role', ['staf', 'staff'])->get();
        } elseif (in_array($userLogin->role, ['admin', 'super_admin'])) {
            $listTujuan = User::where('id', '!=', $userId)->get();
        }

        return view('admin.users.inbox', compact('disposisi_masuk', 'listTujuan'));
    }

    /**
     * Menampilkan monitoring disposisi yang dikirim current user
     */
    public function monitoring(Request $request)
    {
        $user = auth()->user();

        $query = Disposisi::with(['surat', 'pengirim', 'penerima']);

        // Jika diminta monitoring untuk surat tertentu, tampilkan riwayat disposisi surat itu
        if ($request->filled('surat_id')) {
            $query->where('surat_masuk_id', $request->surat_id);

        } else {
            // Default: batasi data berdasarkan peran
            if (in_array($user->role, ['admin', 'super_admin', 'kabag', 'kasubag'])) {
                // atasan dan admin melihat seluruh disposisi
            } else {
                // staff dan tamu melihat disposisi yang berkaitan dengan mereka (pengirim atau penerima)
                $query->where(function($q) use ($user) {
                    $q->where('tujuan_user_id', $user->id)
                      ->orWhere('dari_user_id', $user->id);
                });
            }
        }

        $semua_disposisi = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Some legacy views expect $monitoring_list variable — provide alias
        $monitoring_list = $semua_disposisi;

        // Return the consolidated view under admin.monitordisposisi
        return view('admin.monitordisposisi.disposisi_rekap', compact('semua_disposisi', 'monitoring_list'));
    }

    // ========================================================
    // 2. CREATE & DELETE FUNCTIONS
    // ========================================================

    /**
     * Menyimpan disposisi baru
     * 
     * @param StoreDisposisiRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreDisposisiRequest $request)
    {
        $validated = $request->validated();
        
        // Pastikan surat siap untuk disposisi
        $surat = SuratMasuk::findOrFail($validated['surat_masuk_id']);
        
        // Reject jika surat sudah ditolak
        if ($surat->status === SuratMasukStatus::Ditolak->value) {
            return back()->with('error', 'Surat telah DITOLAK dan tidak bisa didisposisikan!');
        }
        
        if (!in_array($surat->status, [
            SuratMasukStatus::SiapDisposisi->value,
            SuratMasukStatus::SedangDiproses->value
        ])) {
            return back()->with('error', 'Surat belum siap untuk didisposisikan!');
        }

        DB::transaction(function() use ($validated, $surat) {
            // Buat disposisi baru
            $disposisi = Disposisi::create([
                'surat_masuk_id' => $validated['surat_masuk_id'],
                'dari_user_id' => auth()->id(),
                'tujuan_user_id' => $validated['tujuan_user_id'],
                'sifat' => $validated['sifat'],
                'jenis_surat' => $validated['jenis_surat'] ?? $surat->jenis_surat ?? 'Surat Masuk',
                'instruksi' => $validated['instruksi'],
                'status' => DisposisiStatus::Belum->value,
                'tanggal_diterima' => null,
            ]);

            // Update status surat menjadi sedang diproses
            $surat->update(['status' => SuratMasukStatus::SedangDiproses->value]);

            // Catat di tracking
            TrackingSurat::create([
                'surat_masuk_id' => $surat->id,
                'status_log' => 'Disposisi Dibuat',
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => "Disposisi dibuat untuk {$disposisi->penerima->name}"
            ]);
        });

        return back()->with('success', 'Disposisi berhasil dikirim!');
    }

    /**
     * Menghapus disposisi
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        // Hanya admin, kabag, atau kasubag yang bisa hapus disposisi
        if (!in_array(auth()->user()->role, ['admin', 'super_admin', 'kabag', 'kasubag'])) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus disposisi ini.');
        }
        
        // Jangan hapus jika sudah diproses
        if ($disposisi->status > DisposisiStatus::Belum->value) {
            return back()->with('error', 'Tidak bisa menghapus disposisi yang sudah diproses.');
        }

        // Hapus file jika ada
        if ($disposisi->file_hasil && Storage::disk('public')->exists($disposisi->file_hasil)) {
            Storage::disk('public')->delete($disposisi->file_hasil);
        }
        
        $disposisi->delete();

        return back()->with('success', 'Disposisi berhasil dihapus.');
    }

    // ========================================================
    // 3. WORKFLOW STATUS TRANSITIONS
    // ========================================================

    /**
     * Staff menerima tugas disposisi
     * 
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function terima($id)
    {
        $disposisi = Disposisi::findOrFail($id);
        
        // Pastikan yang menerima adalah tujuan disposisi
        if (auth()->id() !== $disposisi->tujuan_user_id) {
            abort(403, 'Anda tidak berhak menerima tugas orang lain.');
        }
        
        DB::transaction(function() use ($disposisi) {
            $disposisi->update([
                'status' => DisposisiStatus::Proses->value,
                'tanggal_diterima' => now(),
            ]);

            // Create tracking log
            TrackingSurat::create([
                'surat_masuk_id' => $disposisi->surat_masuk_id,
                'status_log' => 'Disposisi Diterima',
                'tgl_status' => now(),
                'user_id' => auth()->id(),
                'catatan' => $disposisi->penerima->name . ' telah menerima tugas disposisi'
            ]);
        });

        return back()->with('success', 'Tugas diterima! Selamat bekerja.');
    }

    /**
     * Staff melaporkan hasil kerja
     * 
     * @param LaporkanHasilKerjaRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function selesai(LaporkanHasilKerjaRequest $request, $id)
    {
        $validated = $request->validated();
        $disposisi = Disposisi::findOrFail($id);

        // Pastikan yang melapor adalah yang ditugasi
        if (auth()->id() !== $disposisi->tujuan_user_id) {
            abort(403, 'Anda tidak berhak melaporkan tugas orang lain.');
        }

        // Hanya bisa lapor jika status sedang diproses
        if ($disposisi->status !== DisposisiStatus::Proses->value) {
            return back()->with('error', 'Disposisi tidak dalam status sedang diproses.');
        }

        $dataUpdate = [
            'status' => DisposisiStatus::MenungguVerifikasiKasubag->value,
            'catatan_staff' => $validated['catatan_staff'],
        ];

        // Upload file jika ada
        if ($request->hasFile('file_hasil')) {
            // Hapus file lama jika ada
            if ($disposisi->file_hasil && Storage::disk('public')->exists($disposisi->file_hasil)) {
                Storage::disk('public')->delete($disposisi->file_hasil);
            }

            $path = $request->file('file_hasil')->store('disposisi_hasil', 'public');
            $dataUpdate['file_hasil'] = $path;
        }

        $disposisi->update($dataUpdate);

        return back()->with('success', 'Laporan pekerjaan berhasil dikirim ke atasan!');
    }

    /**
     * Kabag/Kasubag memverifikasi hasil kerja
     * 
     * Workflow 2-Level:
     * 1. Kasubag verifikasi (status 2 → 3 atau 4)
     * 2. Kabag verifikasi (status 3 → 5 atau 4)
     * 
     * @param VerifikasiDisposisiRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifikasi(VerifikasiDisposisiRequest $request, $id)
    {
        $validated = $request->validated();
        $disposisi = Disposisi::findOrFail($id);
        $userRole = auth()->user()->role;
        $currentStatus = $disposisi->status;

        // Pastikan yang verifikasi adalah atasan/kabag/kasubag
        if (!in_array($userRole, ['admin', 'kabag', 'kasubag'])) {
            abort(403, 'Hanya Kabag/Kasubag yang bisa memverifikasi.');
        }

        // Validasi: Kasubag hanya bisa verifikasi status 2, Kabag hanya status 3
        if ($userRole === 'kasubag' && $currentStatus !== DisposisiStatus::MenungguVerifikasiKasubag->value) {
            return back()->with('error', 'Kasubag hanya bisa verifikasi status "Menunggu Verifikasi Kasubag".');
        }
        
        if ($userRole === 'kabag' && $currentStatus !== DisposisiStatus::MenungguVerifikasiKabag->value) {
            return back()->with('error', 'Kabag hanya bisa verifikasi status "Menunggu Verifikasi Kabag".');
        }
        
        if ($userRole === 'admin') {
            if (!in_array($currentStatus, [DisposisiStatus::MenungguVerifikasiKasubag->value, DisposisiStatus::MenungguVerifikasiKabag->value])) {
                return back()->with('error', 'Disposisi tidak dalam status yang menunggu verifikasi.');
            }
        }

        DB::transaction(function() use ($validated, $disposisi, $userRole, $currentStatus) {
            if ($validated['status_akhir'] === 'Revisi') {
                // ===== CASE 1: REQUEST REVISION =====
                $disposisi->update([
                    'status' => DisposisiStatus::PerluRevisi->value,
                    'catatan_revisi' => $validated['catatan_revisi'],
                ]);

                $revisiBy = ($userRole === 'kasubag') ? 'Kasubag' : 'Kabag';
                TrackingSurat::create([
                    'surat_masuk_id' => $disposisi->surat_masuk_id,
                    'status_log' => 'Perlu Revisi',
                    'tgl_status' => now(),
                    'user_id' => auth()->id(),
                    'catatan' => auth()->user()->name . ' (' . $revisiBy . ') meminta revisi: ' . $validated['catatan_revisi']
                ]);
                
            } else {
                // ===== CASE 2: APPROVE =====
                if ($userRole === 'kasubag' || ($userRole === 'admin' && $currentStatus === DisposisiStatus::MenungguVerifikasiKasubag->value)) {
                    // Kasubag approved → waiting for Kabag
                    $disposisi->update([
                        'status' => DisposisiStatus::MenungguVerifikasiKabag->value,
                    ]);

                    TrackingSurat::create([
                        'surat_masuk_id' => $disposisi->surat_masuk_id,
                        'status_log' => 'Verifikasi Kasubag Disetujui',
                        'tgl_status' => now(),
                        'user_id' => auth()->id(),
                        'catatan' => auth()->user()->name . ' (Kasubag) menyetujui - Menunggu verifikasi Kabag'
                    ]);

                } else if ($userRole === 'kabag' || ($userRole === 'admin' && $currentStatus === DisposisiStatus::MenungguVerifikasiKabag->value)) {
                    // Kabag approved → Final approval
                    $disposisi->update([
                        'status' => DisposisiStatus::Selesai->value,
                        'tanggal_selesai' => now(),
                        'catatan_revisi' => null,
                    ]);

                    // Update surat status ke Selesai
                    $disposisi->surat()->update([
                        'status' => SuratMasukStatus::Selesai->value
                    ]);

                    TrackingSurat::create([
                        'surat_masuk_id' => $disposisi->surat_masuk_id,
                        'status_log' => 'Verifikasi Kabag Disetujui',
                        'tgl_status' => now(),
                        'user_id' => auth()->id(),
                        'catatan' => auth()->user()->name . ' (Kabag) menyetujui - Disposisi Selesai. Siap naik ke Bupati'
                    ]);
                }
            }
        });

        if ($validated['status_akhir'] === 'Revisi') {
            $message = 'Permintaan revisi dikirim ke Staff.';
        } else {
            if ($userRole === 'kasubag' || ($userRole === 'admin' && $currentStatus === DisposisiStatus::MenungguVerifikasiKasubag->value)) {
                $message = 'Verifikasi Kasubag selesai! Menunggu verifikasi Kabag.';
            } else {
                $message = 'Verifikasi Kabag selesai! Disposisi SELESAI - Siap naik ke Bupati.';
            }
        }

        return back()->with('success', $message);
    }

    // ========================================================
    // 4. HELPER FUNCTIONS
    // ========================================================

    /**
     * Rekapitulasi disposisi dengan filter tanggal
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
     * Get status disposisi via AJAX
     */
    public function checkStatus($id)
    {
        $disposisi = Disposisi::with(['surat.disposisi.penerima', 'pengirim'])
                        ->findOrFail($id);
        
        return response()->json([
            'id' => $disposisi->id,
            'status' => $disposisi->status,
            'status_label' => $disposisi->status_label,
            'perihal' => $disposisi->surat->perihal,
            'penerima' => $disposisi->penerima->name,
            'last_update' => $disposisi->updated_at->format('d-m-Y H:i:s')
        ]);
    }
}
