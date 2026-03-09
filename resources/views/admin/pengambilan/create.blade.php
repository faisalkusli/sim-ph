@extends('layouts.app')

@section('title', 'Input Pengambilan Produk Hukum')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Input Pengambilan Produk Hukum</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('pengambilan.index') }}" class="hover:text-blue-600">Pengambilan PH</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Input Baru</span>
        </nav>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-triangle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                <i class="fas fa-edit text-blue-600"></i> Form Data Pengambilan
            </h2>
        </div>
        <form action="{{ route('pengambilan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-5 space-y-5">

                {{-- Pilih Surat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Pilih Surat Masuk (Agenda / Perihal) <span class="text-red-500">*</span>
                    </label>
                    <select name="surat_masuk_id" id="selectSurat" required
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih Nomor Agenda Surat --</option>
                        @foreach($surat_masuk as $surat)
                        <option value="{{ $surat->id }}" data-instansi="{{ $surat->asal_surat }}">
                            Agenda No: {{ $surat->no_agenda }} | Perihal: {{ Str::limit($surat->perihal, 50) }}
                        </option>
                        @endforeach
                    </select>
                    <p id="detailSurat" class="text-xs text-blue-600 mt-1"></p>
                </div>

                {{-- Row 1 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nomor Register Produk Hukum <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_register" required placeholder="Contoh: 180/SK/2026"
                               value="{{ old('nomor_register') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Tanggal Pengambilan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_pengambilan" required value="{{ date('Y-m-d') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Instansi Pengambil <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="instansi_pengambil" id="instansiPengambil" required
                               placeholder="Nama Instansi/Lembaga"
                               value="{{ old('instansi_pengambil') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <p class="text-xs text-slate-400 mt-1">*Otomatis terisi dari Asal Surat (bisa diedit)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nama Orang yang Mengambil <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pengambil" required placeholder="Nama Lengkap Pengambil"
                               value="{{ old('nama_pengambil') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. HP Pengambil</label>
                        <input type="text" name="no_hp_pengambil" placeholder="08xxxxxxxxxx"
                               value="{{ old('no_hp_pengambil') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                </div>

                {{-- File Upload --}}
                <div x-data="{ filename: '' }">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Upload File Produk Hukum (Jadi)
                        <span class="text-slate-400 font-normal">(PDF/Word, max 5MB)</span>
                    </label>
                    <label class="flex flex-col items-center justify-center w-full border border-dashed border-slate-300 rounded-xl p-6 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-colors"
                           :class="filename ? 'border-green-400 bg-green-50/30' : ''">
                        <i class="fas fa-cloud-upload-alt text-3xl mb-2"
                           :class="filename ? 'text-green-500' : 'text-slate-400'"></i>
                        <span class="text-sm font-semibold"
                              :class="filename ? 'text-green-700' : 'text-slate-500'"
                              x-text="filename || 'Klik untuk pilih file'"></span>
                        <span class="text-xs text-slate-400 mt-1" x-show="!filename">PDF, DOC, DOCX</span>
                        <input type="file" name="file_produk" accept=".pdf,.doc,.docx" class="hidden"
                               @change="filename = $event.target.files[0]?.name || ''">
                    </label>
                </div>

            </div>
            <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                <a href="{{ route('pengambilan.index') }}"
                   class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50">Batal</a>
                <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('selectSurat').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var instansi = selectedOption.getAttribute('data-instansi');
        if (instansi) {
            document.getElementById('instansiPengambil').value = instansi;
            document.getElementById('detailSurat').innerHTML = 'Asal Surat: <strong>' + instansi + '</strong>';
        } else {
            document.getElementById('instansiPengambil').value = '';
            document.getElementById('detailSurat').innerHTML = '';
        }
    });
</script>
@endsection