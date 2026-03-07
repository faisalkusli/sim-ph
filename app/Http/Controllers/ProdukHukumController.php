<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProdukHukum;
use Illuminate\Support\Facades\Storage;

class ProdukHukumController extends Controller
{
    // Tampilkan daftar produk hukum (bisa diakses semua user)
    public function index()
    {
        $produk = ProdukHukum::latest()->get();
        return view('admin.produk_hukum.index', compact('produk'));
    }

    // Form upload (khusus admin)
    public function create()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        return view('admin.produk_hukum.create');
    }

    // Proses upload (khusus admin)
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'file' => 'required|mimes:pdf,doc,docx|max:10240',
        ]);
        $data = $request->only(['nama', 'keterangan']);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/produk_hukum', $filename);
            $data['file'] = $filename;
        }
        ProdukHukum::create($data);
        return redirect()->route('produk-hukum.index')->with('success', 'Produk hukum berhasil diupload!');
    }
}
