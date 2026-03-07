@extends('layouts.app')

@section('content')
<div class="container-fluid px-4"> 
    
    <div class="row mb-4 mt-4">
        <div class="col-md-12">
            <h2>Dashboard</h2>
            <p class="text-muted">Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3 h-100 shadow-sm">
                <div class="card-header">Surat Masuk</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalSuratMasuk }}</h1>
                    <p class="card-text">Total surat diterima</p>
                    <a href="{{ route('surat-masuk.index') }}" class="text-white text-decoration-none stretched-link">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success mb-3 h-100 shadow-sm">
                <div class="card-header">Surat Keluar</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalSuratKeluar }}</h1>
                    <p class="card-text">Total surat dikirim</p>
                    <a href="{{ route('surat-keluar.index') }}" class="text-white text-decoration-none stretched-link">Lihat Detail &rarr;</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-dark bg-warning mb-3 h-100 shadow-sm">
                <div class="card-header">Total Disposisi</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalDisposisi }}</h1>
                    <p class="card-text">Instruksi diteruskan</p>
                    @if(auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin')
                         <a href="{{ route('disposisi.monitoring') }}" class="text-dark text-decoration-none stretched-link">Lihat Monitoring &rarr;</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-secondary mb-3 h-100 shadow-sm">
                <div class="card-header">Pengguna Aktif</div>
                <div class="card-body">
                    <h1 class="card-title">{{ $totalUser }}</h1>
                    <p class="card-text">Admin & Staff</p>
                </div>
            </div>
        </div>

        @if(auth()->user()->role != 'admin' && auth()->user()->role != 'super_admin')
        <div class="col-md-3 mt-3">
            <div class="card text-white bg-info mb-3 h-100 shadow-sm">
                <div class="card-header">Inbox Disposisi</div>
                <div class="card-body">
                    <div class="sb-nav-link-icon mb-2"><i class="bi bi-inbox fs-1"></i></div>
                    <p class="card-text">Cek Surat Masuk untuk Anda</p>
                    <a href="{{ route('inbox') }}" class="text-white text-decoration-none stretched-link">Buka Inbox &rarr;</a>
                </div>
            </div>
        </div>
        @endif

        @if(auth()->user()->role == 'admin' || auth()->user()->role == 'super_admin')
        <div class="col-md-3 mt-3">
            <div class="card text-white bg-dark mb-3 h-100 shadow-sm">
                <div class="card-header">Laporan</div>
                <div class="card-body">
                    <div class="sb-nav-link-icon mb-2"><i class="bi bi-file-earmark-text fs-1"></i></div>
                    <p class="card-text">Rekap Laporan</p>
                    <a href="{{ route('laporan.index') }}" class="text-white text-decoration-none stretched-link">Buka Laporan &rarr;</a>
                </div>
            </div>
        </div>
        @endif
    </div> 

    @if(auth()->user()->role == 'super_admin' || auth()->user()->email == 'admin@malangkab.go.id')
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3 text-primary">
                                <i class="bi bi-database-gear fs-3"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Pemeliharaan Data Sistem</h6>
                                <p class="text-muted small mb-0">Backup data secara rutin atau pulihkan data jika terjadi kesalahan.</p>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('backup.db') }}" class="btn btn-outline-success fw-bold px-3" onclick="return confirm('Backup database sekarang?')">
                                <i class="bi bi-cloud-download me-2"></i>Backup
                            </a>

                            <button type="button" class="btn btn-outline-danger fw-bold px-3" data-bs-toggle="modal" data-bs-target="#modalRestore">
                                <i class="bi bi-cloud-upload me-2"></i>Restore
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRestore" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill me-2"></i>Restore Database</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('restore.db') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning small">
                            <strong>PERHATIAN!</strong> Proses ini akan menimpa seluruh data saat ini dengan data dari file backup. Data yang ditimpa tidak bisa dikembalikan.
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih File Backup (.sql)</label>
                            <input type="file" name="file_backup" class="form-control" required accept=".sql">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Pulihkan Database</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer dihapus sesuai permintaan -->
</div>
@endsection