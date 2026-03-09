@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Surat Masuk</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600">Surat Masuk</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('surat-masuk.show', $surat->id) }}" class="hover:text-blue-600">{{ $surat->no_agenda }}</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Edit</span>
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
    <form action="{{ route('surat-masuk.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="bi bi-envelope-fill text-blue-600"></i> Ubah Informasi Surat
                </h2>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- No Agenda --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No Agenda <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_agenda" value="{{ old('no_agenda', $surat->no_agenda) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('no_agenda') border-red-400 @enderror">
                    @error('no_agenda')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Asal Instansi --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Asal Instansi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="asal_instansi" value="{{ old('asal_instansi', $surat->asal_instansi) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('asal_instansi') border-red-400 @enderror">
                    @error('asal_instansi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- No Surat Pengirim --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        No Surat Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="no_surat_pengirim" value="{{ old('no_surat_pengirim', $surat->no_surat_pengirim) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('no_surat_pengirim') border-red-400 @enderror">
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
                        <option value="{{ $jenis }}" {{ old('jenis_surat', $surat->jenis_surat) == $jenis ? 'selected' : '' }}>{{ $jenis }}</option>
                        @endforeach
                    </select>
                    @error('jenis_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Surat --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_surat"
                           value="{{ old('tgl_surat', $surat->tgl_surat ? $surat->tgl_surat->format('Y-m-d') : '') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tgl_surat') border-red-400 @enderror">
                    @error('tgl_surat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Tanggal Diterima --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Tanggal Diterima <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tgl_diterima"
                           value="{{ old('tgl_diterima', $surat->tgl_diterima ? $surat->tgl_diterima->format('Y-m-d') : '') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none @error('tgl_diterima') border-red-400 @enderror">
                    @error('tgl_diterima')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Perihal --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Perihal / Isi Ringkas <span class="text-red-500">*</span>
                    </label>
                    <textarea name="perihal" rows="3" required
                              class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none @error('perihal') border-red-400 @enderror"
                              placeholder="Tuliskan perihal surat...">{{ old('perihal', $surat->perihal) }}</textarea>
                    @error('perihal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- File Uploads Section --}}
                <div class="md:col-span-2">
                    <div class="mb-3">
                        <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                            <i class="fas fa-paperclip text-slate-500"></i> Upload Dokumen
                            <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span>
                        </h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        {{-- Scan Surat Pengantar --}}
                        <div>
                            <label class="block text-xs font-semibold text-blue-700 mb-1.5 uppercase tracking-wide">
                                <i class="fas fa-file-alt mr-1"></i> Scan Surat Pengantar
                            </label>
                            @if($surat->file_pengantar_path)
                            <div class="mb-2 flex items-center gap-2 p-2 bg-blue-50 border border-blue-200 rounded-lg">
                                <i class="fas fa-file-pdf text-blue-500 text-sm flex-shrink-0"></i>
                                <p class="text-xs text-blue-800 truncate flex-1">{{ basename($surat->file_pengantar_path) }}</p>
                                <a href="{{ asset('storage/' . $surat->file_pengantar_path) }}" target="_blank"
                                   class="text-xs text-blue-600 hover:underline flex-shrink-0"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                            @endif
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-3 border-2 border-dashed border-blue-200 rounded-xl hover:border-blue-400 bg-blue-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.filePengantar.click()">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-blue-500 text-sm"></i>
                                </div>
                                <p class="text-xs font-medium text-slate-700" x-text="filename || 'Pilih file baru'"></p>
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
                            @if($surat->file_pernyataan_path)
                            <div class="mb-2 flex items-center gap-2 p-2 bg-purple-50 border border-purple-200 rounded-lg">
                                <i class="fas fa-file-pdf text-purple-500 text-sm flex-shrink-0"></i>
                                <p class="text-xs text-purple-800 truncate flex-1">{{ basename($surat->file_pernyataan_path) }}</p>
                                <a href="{{ asset('storage/' . $surat->file_pernyataan_path) }}" target="_blank"
                                   class="text-xs text-purple-600 hover:underline flex-shrink-0"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                            @endif
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-3 border-2 border-dashed border-purple-200 rounded-xl hover:border-purple-400 bg-purple-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.filePernyataan.click()">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-purple-500 text-sm"></i>
                                </div>
                                <p class="text-xs font-medium text-slate-700" x-text="filename || 'Pilih file baru'"></p>
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
                            @if($surat->file_lampiran_path)
                            <div class="mb-2 flex items-center gap-2 p-2 bg-green-50 border border-green-200 rounded-lg">
                                <i class="fas fa-file-pdf text-green-500 text-sm flex-shrink-0"></i>
                                <p class="text-xs text-green-800 truncate flex-1">{{ basename($surat->file_lampiran_path) }}</p>
                                <a href="{{ asset('storage/' . $surat->file_lampiran_path) }}" target="_blank"
                                   class="text-xs text-green-600 hover:underline flex-shrink-0"><i class="fas fa-external-link-alt"></i></a>
                            </div>
                            @endif
                            <div x-data="{ filename: '' }"
                                 class="relative flex flex-col items-center gap-2 p-3 border-2 border-dashed border-green-200 rounded-xl hover:border-green-400 bg-green-50/30 transition-colors cursor-pointer text-center"
                                 @click="$refs.fileLampiran.click()">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-file-upload text-green-500 text-sm"></i>
                                </div>
                                <p class="text-xs font-medium text-slate-700" x-text="filename || 'Pilih file baru'"></p>
                                <input x-ref="fileLampiran" type="file" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png"
                                       class="hidden" @change="filename = $event.target.files[0]?.name || ''">
                            </div>
                            @error('file_lampiran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="px-5 pb-5 flex flex-wrap justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('surat-masuk.show', $surat->id) }}"
                   class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
