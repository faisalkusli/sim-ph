@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Laporan Arsip Surat</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Laporan</span>
        </nav>
    </div>

    <div class="max-w-xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 bg-blue-600">
                <h2 class="font-bold text-white flex items-center gap-2">
                    <i class="bi bi-printer"></i> Filter Cetak Laporan
                </h2>
                <p class="text-blue-200 text-xs mt-0.5">Pilih jenis laporan dan rentang tanggal</p>
            </div>
            <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank">
                @csrf
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jenis Laporan</label>
                        <select name="jenis_surat"
                                class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                            <option value="surat_masuk">Surat Masuk</option>
                            <option value="surat_keluar">Surat Keluar</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Dari Tanggal</label>
                            <input type="date" name="tgl_awal" required value="{{ date('Y-m-01') }}"
                                   class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" required value="{{ date('Y-m-d') }}"
                                   class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        </div>
                    </div>
                </div>
                <div class="px-5 pb-5">
                    <button type="submit"
                            class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                        <i class="bi bi-file-earmark-pdf text-base"></i> Cetak Laporan (PDF)
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection