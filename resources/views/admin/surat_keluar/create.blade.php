@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Catat Surat Keluar</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('surat-keluar.index') }}" class="hover:text-blue-600">Surat Keluar</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Catat Baru</span>
        </nav>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="bi bi-send-fill text-emerald-600"></i> Data Surat Keluar
                </h2>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor Surat <span class="text-red-500">*</span></label>
                    <input type="text" name="no_surat" value="{{ old('no_surat') }}" required placeholder="Contoh: 005/HK/XI/2023"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tujuan Surat (Kepada) <span class="text-red-500">*</span></label>
                    <input type="text" name="tujuan_surat" value="{{ old('tujuan_surat') }}" required placeholder="Kepala Dinas Pendidikan"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Surat <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', date('Y-m-d')) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Dikirim <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input type="date" name="tgl_kirim" value="{{ old('tgl_kirim') }}"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Referensi Surat Masuk <span class="text-slate-400 font-normal">(Jika surat balasan)</span></label>
                    <select name="surat_masuk_id"
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Tidak ada / Surat Baru --</option>
                        @foreach($daftar_surat_masuk as $sm)
                        <option value="{{ $sm->id }}" {{ old('surat_masuk_id') == $sm->id ? 'selected' : '' }}>
                            [{{ $sm->no_agenda }}] {{ Str::limit($sm->perihal, 60) }} — {{ $sm->asal_instansi }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Perihal <span class="text-red-500">*</span></label>
                    <textarea name="perihal" rows="3" required
                              class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                              placeholder="Isi perihal surat...">{{ old('perihal') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">File Arsip <span class="text-slate-400 font-normal">(PDF/JPG/PNG, Opsional)</span></label>
                    <div x-data="{ filename: '' }"
                         class="flex items-center gap-3 p-3 border border-dashed border-slate-300 rounded-xl hover:border-blue-400 transition-colors cursor-pointer"
                         @click="$refs.fileInput.click()">
                        <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-file-upload text-emerald-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-700" x-text="filename || 'Klik untuk pilih file'"></p>
                            <p class="text-xs text-slate-400">PDF, JPG, atau PNG</p>
                        </div>
                        <input x-ref="fileInput" type="file" name="file_arsip" accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden" @change="filename = $event.target.files[0]?.name || ''">
                    </div>
                </div>
            </div>

            <div class="px-5 pb-5 flex justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('surat-keluar.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</a>
                <button type="submit"
                        class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Surat Keluar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection