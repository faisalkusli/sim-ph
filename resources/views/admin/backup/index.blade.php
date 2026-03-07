@extends('layouts.app')

@section('title', 'Backup & Restore Database')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">System Utilities</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Backup & Restore</li>
    </ol>

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