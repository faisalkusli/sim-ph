@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Input Surat Masuk</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600">Surat Masuk</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Input Baru</span>
        </nav>
    </div>

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Form Card --}}
    <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="bi bi-envelope-fill text-blue-600"></i> Informasi Surat
                </h2>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- No Agenda --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No Agenda <span class="text-red-500">*</span>
                        <span class="ml-1 inline-flex items-center gap-1 text-xs font-normal text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full">
                            <i class="fas fa-magic text-[10px]"></i> Otomatis
                        </span>
                    </label>
                    <div class="relative">
                        <input type="text" name="no_agenda"
                               value="{{ old('no_agenda', $no_agenda_baku) }}" required
                               readonly
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 pr-20 bg-slate-50 text-slate-600 cursor-default focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none @error('no_agenda') border-red-400 @enderror">
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-mono select-none pointer-events-none">
                            {{ date('Y') }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Format: <span class="font-mono">urutan/HK/tahun</span> — dibuat otomatis oleh sistem.</p>
                    @error('no_agenda')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Asal Instansi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Asal Instansi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('asal_instansi') border-red-400 @enderror"
                           placeholder="Nama instansi pengirim">
                    @error('asal_instansi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- No Surat Pengirim --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No Surat Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_surat_pengirim" value="{{ old('no_surat_pengirim') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('no_surat_pengirim') border-red-400 @enderror"
                           placeholder="Nomor surat dari pengirim">
                    @error('no_surat_pengirim')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Jenis Surat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_surat" required
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('jenis_surat') border-red-400 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        @foreach(['Peraturan Bupati','SK Bupati','Surat Undangan','Surat Tembusan','Surat Lainnya'] as $jenis)
                        <option value="{{ $jenis }}" {{ old('jenis_surat') == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Surat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tgl_surat') border-red-400 @enderror">
                    @error('tgl_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Diterima --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Diterima <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_diterima" value="{{ old('tgl_diterima', date('Y-m-d')) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tgl_diterima') border-red-400 @enderror">
                    @error('tgl_diterima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Perihal (full width) --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Perihal / Isi Ringkas <span class="text-red-500">*</span>
                    </label>
                    <textarea name="perihal" rows="3" required
                              class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('perihal') border-red-400 @enderror"
                              placeholder="Tuliskan perihal atau isi ringkas surat...">{{ old('perihal') }}</textarea>
                    @error('perihal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- File Uploads Section (full width) --}}
                <div class="md:col-span-2">
                    <div class="mb-3">
                        <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <i class="fas fa-paperclip text-slate-500"></i> Upload Dokumen
                            <span class="text-slate-400 font-normal">(Opsional, PDF/JPG/PNG maks. 10MB)</span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Scan Surat Pengantar --}}
                        <div>
                            <label class="block text-xs font-semibold text-blue-700 mb-1.5 uppercase tracking-wide">
                                <i class="fas fa-file-alt mr-1"></i> Scan Surat Pengantar
                            </label>
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-4 border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-400 bg-blue-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.filePengantar.click()">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700" x-text="filename || 'Klik untuk pilih file'"></p>
                                    <p class="text-xs text-slate-400">PDF, JPG, atau PNG</p>
                                </div>
                                <input x-ref="filePengantar" type="file" name="file_pengantar" accept=".pdf,.jpg,.jpeg,.png"
                                       class="hidden" @change="filename = $event.target.files[0]?.name || ''">
                            </div>
                            @error('file_pengantar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Scan Surat Pernyataan --}}
                        <div>
                            <label class="block text-xs font-semibold text-purple-700 mb-1.5 uppercase tracking-wide">
                                <i class="fas fa-file-signature mr-1"></i> Scan Surat Pernyataan
                            </label>
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-4 border-2 border-dashed border-purple-200 rounded-xl hover:border-purple-400 bg-purple-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.filePernyataan.click()">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-purple-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700" x-text="filename || 'Klik untuk pilih file'"></p>
                                    <p class="text-xs text-slate-400">PDF, JPG, atau PNG</p>
                                </div>
                                <input x-ref="filePernyataan" type="file" name="file_pernyataan" accept=".pdf,.jpg,.jpeg,.png"
                                       class="hidden" @change="filename = $event.target.files[0]?.name || ''">
                            </div>
                            @error('file_pernyataan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Dokumen Lampiran --}}
                        <div>
                            <label class="block text-xs font-semibold text-green-700 mb-1.5 uppercase tracking-wide">
                                <i class="fas fa-folder-open mr-1"></i> Dokumen Lampiran / Pendukung
                            </label>
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-4 border-2 border-dashed border-green-200 rounded-xl hover:border-green-400 bg-green-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.fileLampiran.click()">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-green-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-700" x-text="filename || 'Klik untuk pilih file'"></p>
                                    <p class="text-xs text-slate-400">PDF, JPG, atau PNG</p>
                                </div>
                                <input x-ref="fileLampiran" type="file" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png"
                                       class="hidden" @change="filename = $event.target.files[0]?.name || ''">
                            </div>
                            @error('file_lampiran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-5 flex justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('surat-masuk.index') }}"
                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Surat
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
