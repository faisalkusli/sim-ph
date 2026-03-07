<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::query();

            if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function($q) use ($cari) {
            $q->where('perihal', 'like', '%' . $cari . '%')
              ->orWhere('tujuan_surat', 'like', '%' . $cari . '%')
              ->orWhere('no_surat', 'like', '%' . $cari . '%');
            });
            if (auth()->user()->role == 'tamu') {
                    abort(403, 'Anda tidak memiliki akses ke halaman ini.');
                }
            }
        $surat_keluar = $query->orderBy('tgl_surat', 'desc')->get();
        return view('admin.surat_keluar.index', compact('surat_keluar'));
    }

    public function create()
    {
        $daftar_surat_masuk = \App\Models\SuratMasuk::orderBy('tgl_diterima', 'desc')->get();
        return view('admin.surat_keluar.create', compact('daftar_surat_masuk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_surat' => 'required|unique:surat_keluars',
            'tujuan_surat' => 'required',
            'tgl_surat' => 'required|date',
            'perihal' => 'required',
            'file_arsip' => 'nullable|mimes:pdf,jpg,png|max:2048',
        ]);

        $filePath = null;
        if ($request->hasFile('file_arsip')) {
            $filePath = $request->file('file_arsip')->store('arsip_surat_keluar', 'public');
        }

        SuratKeluar::create([
            'no_surat' => $request->no_surat,
            'surat_masuk_id' => $request->surat_masuk_id,
            'tujuan_surat' => $request->tujuan_surat,
            'tgl_surat' => $request->tgl_surat,
            'tgl_kirim' => $request->tgl_kirim,
            'perihal' => $request->perihal,
            'file_arsip_path' => $filePath ?? null,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('surat-keluar.index')->with('success', 'Surat Keluar berhasil dicatat!');
    }

    // 4. HAPUS DATA
    public function destroy($id)
    {
        $surat = SuratKeluar::findOrFail($id);
        
        // Hapus file fisik jika ada
        if ($surat->file_arsip_path) {
            Storage::disk('public')->delete($surat->file_arsip_path);
        }

        $surat->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus.');
    }
}