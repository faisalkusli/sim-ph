@extends('layouts.app')

@section('title', 'Upload Produk Hukum')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Upload Produk Hukum</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('produk-hukum.index') }}" class="hover:text-blue-600">Produk Hukum</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Upload</span>
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

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-upload text-blue-600"></i> Form Upload Produk Hukum
                </h2>
            </div>
            <form action="{{ route('produk-hukum.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-5 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nama Produk Hukum <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama" required placeholder="Contoh: Peraturan Daerah No. 1 Tahun 2024"
                               value="{{ old('nama') }}"
                               class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Keterangan</label>
                        <textarea name="keterangan" rows="3" placeholder="Deskripsi singkat produk hukum..."
                                  class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none">{{ old('keterangan') }}</textarea>
                    </div>
                    <div x-data="{ filename: '' }">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                            File Produk Hukum <span class="text-red-500">*</span>
                            <span class="text-slate-400 font-normal">(PDF/DOC/DOCX, max 10MB)</span>
                        </label>
                        <label class="flex flex-col items-center justify-center w-full border border-dashed border-slate-300 rounded-xl p-6 cursor-pointer hover:border-blue-400 hover:bg-blue-50/30 transition-colors"
                               :class="filename ? 'border-green-400 bg-green-50/30' : ''">
                            <i class="fas fa-cloud-upload-alt text-3xl mb-2"
                               :class="filename ? 'text-green-500' : 'text-slate-400'"></i>
                            <span class="text-sm font-semibold"
                                  :class="filename ? 'text-green-700' : 'text-slate-500'"
                                  x-text="filename || 'Klik untuk pilih file'"></span>
                            <span class="text-xs text-slate-400 mt-1" x-show="!filename">PDF, DOC, DOCX</span>
                            <input type="file" name="file" required accept=".pdf,.doc,.docx" class="hidden"
                                   @change="filename = $event.target.files[0]?.name || ''">
                        </label>
                    </div>
                </div>
                <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                    <a href="{{ route('produk-hukum.index') }}"
                       class="px-4 py-2.5 border border-slate-200 text-slate-600 rounded-xl text-sm hover:bg-slate-50">Batal</a>
                    <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors flex items-center gap-2">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
