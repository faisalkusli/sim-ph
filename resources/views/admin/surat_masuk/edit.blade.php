@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Surat Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('surat-masuk.index') }}">Surat Masuk</a></li>
        <li class="breadcrumb-item active">Edit Data</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-warning text-dark fw-bold">
            <i class="fas fa-edit me-1"></i> Form Edit Surat Masuk
        </div>
        
        <div class="card-body">
            <form action="{{ route('surat-masuk.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Agenda</label>
                            <input type="text" name="no_agenda" 
                                   class="form-control @error('no_agenda') is-invalid @enderror" 
                                   value="{{ old('no_agenda', $surat->no_agenda) }}" required>
                            @error('no_agenda')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Asal Instansi / Pengirim</label>
                            <input type="text" name="asal_instansi" 
                                   class="form-control @error('asal_instansi') is-invalid @enderror" 
                                   value="{{ old('asal_instansi', $surat->asal_instansi) }}" required>
                            @error('asal_instansi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nomor Surat</label>
                            <input type="text" name="no_surat_pengirim" 
                                   class="form-control @error('no_surat_pengirim') is-invalid @enderror" 
                                   value="{{ old('no_surat_pengirim', $surat->no_surat_pengirim) }}" required>
                            @error('no_surat_pengirim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Surat</label>
                            <input type="date" name="tgl_surat" 
                                   class="form-control @error('tgl_surat') is-invalid @enderror" 
                                   value="{{ old('tgl_surat', $surat->tgl_surat) }}" required>
                            @error('tgl_surat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Diterima</label>
                            <input type="date" name="tgl_diterima" 
                                   class="form-control @error('tgl_diterima') is-invalid @enderror" 
                                   value="{{ old('tgl_diterima', $surat->tgl_diterima) }}" required>
                            @error('tgl_diterima')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">File Scan Surat (PDF)</label>
                            @if($surat->file_scan_path)
                                <div class="mb-2 p-2 border bg-light rounded d-flex align-items-center justify-content-between">
                                    <small class="text-success"><i class="fas fa-check-circle me-1"></i> File saat ini tersedia</small>
                                    <a href="{{ asset('storage/' . $surat->file_scan_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            @else
                                <div class="mb-2 p-2 border bg-light rounded text-muted small">
                                    <i class="fas fa-times-circle me-1"></i> Belum ada file
                                </div>
                            @endif

                            <input type="file" name="file_scan" class="form-control @error('file_scan') is-invalid @enderror" accept=".pdf">
                            <small class="text-muted fst-italic">*Kosongkan jika tidak ingin mengubah file.</small>
                            @error('file_scan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold">Perihal</label>
                    <textarea name="perihal" class="form-control @error('perihal') is-invalid @enderror" rows="3" required>{{ old('perihal', $surat->perihal) }}</textarea>
                    @error('perihal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-warning fw-bold text-dark">
                        <i class="fas fa-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection