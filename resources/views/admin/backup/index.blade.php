@extends('layouts.app')

@section('title', 'Backup & Restore Database')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">System Utilities</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Backup & Restore</span>
        </nav>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

        {{-- Backup Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 bg-blue-600">
                <h2 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-download"></i> Backup Database
                </h2>
            </div>
            <div class="p-5">
                <p class="text-sm text-slate-600 leading-relaxed mb-5">
                    Unduh seluruh data aplikasi (Surat Masuk, Keluar, Disposisi, User) ke dalam format <strong>.SQL</strong>.
                    Simpan file ini di tempat aman (Harddisk/Google Drive).
                </p>
                <a href="{{ route('backup.process') }}"
                   class="w-full py-3.5 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 transition-colors flex items-center justify-center gap-2 shadow-sm">
                    <i class="fas fa-file-download text-xl"></i>
                    DOWNLOAD DATABASE SEKARANG
                </a>
            </div>
        </div>

        {{-- Restore Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-5 bg-red-600">
                <h2 class="font-bold text-white flex items-center gap-2">
                    <i class="fas fa-upload"></i> Restore Database
                </h2>
            </div>
            <div class="p-5">
                <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-3 mb-4 flex items-start gap-2">
                    <i class="fas fa-exclamation-triangle text-amber-500 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs leading-relaxed">
                        <strong>PERINGATAN:</strong> Restore akan menimpa/menghapus data saat ini dengan data dari file backup.
                        Pastikan Anda benar-benar yakin!
                    </p>
                </div>
                <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
                      onsubmit="return confirm('Yakin ingin merestore database? Data saat ini akan tertimpa!');">
                    @csrf
                    <div x-data="{ filename: '' }" class="mb-4">
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pilih File Backup (.sql)</label>
                        <label class="flex flex-col items-center justify-center w-full border border-dashed border-slate-300 rounded-xl p-5 cursor-pointer hover:border-red-400 hover:bg-red-50/30 transition-colors"
                               :class="filename ? 'border-red-400 bg-red-50/30' : ''">
                            <i class="fas fa-database text-2xl mb-1.5"
                               :class="filename ? 'text-red-500' : 'text-slate-400'"></i>
                            <span class="text-sm font-semibold"
                                  :class="filename ? 'text-red-700' : 'text-slate-500'"
                                  x-text="filename || 'Klik untuk pilih file .SQL'"></span>
                            <input type="file" name="file_sql" required accept=".sql" class="hidden"
                                   @change="filename = $event.target.files[0]?.name || ''">
                        </label>
                    </div>
                    <button type="submit"
                            class="w-full py-3.5 bg-red-600 text-white font-bold text-sm rounded-xl hover:bg-red-700 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-history text-xl"></i> PROSES RESTORE
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-download me-2"></i> Backup Database
                </div>
                <div class="card-body">
                    <p class="card-text">
                        Unduh seluruh data aplikasi (Surat Masuk, Keluar, Disposisi, User) ke dalam format <strong>.SQL</strong>. 
                        Simpan file ini di tempat aman (Harddisk/Google Drive).
                    </p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('backup.process') }}" class="btn btn-outline-primary py-3 fw-bold">
                            <i class="fas fa-file-download fa-lg me-2"></i> DOWNLOAD DATABASE SEKARANG
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-upload me-2"></i> Restore Database
                </div>
                <div class="card-body">
                    <div class="alert alert-warning py-2 mb-3">
                        <small><i class="fas fa-exclamation-triangle"></i> <strong>PERINGATAN:</strong> Restore akan menimpa/menghapus data saat ini dengan data dari file backup. Pastikan Anda yakin!</small>
                    </div>

                    <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Yakin ingin merestore database? Data saat ini akan tertimpa!');">
                        @csrf
                        <div class="mb-3">
                            <label for="file_sql" class="form-label">Pilih File Backup (.sql)</label>
                            <input class="form-control" type="file" id="file_sql" name="file_sql" required accept=".sql">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger fw-bold">
                                <i class="fas fa-history me-2"></i> PROSES RESTORE
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection