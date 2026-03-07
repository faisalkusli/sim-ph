@extends('layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    <i class="fas fa-file-alt"></i>
                    Detail Disposisi
                </h2>
                <a href="{{ route('inbox') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali ke Inbox
                </a>
            </div>
        </div>
    </div>

    <!-- Surat Info -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Surat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <small class="form-text text-muted">No. Agenda</small>
                            <p><strong>{{ $disposisi->surat->no_agenda }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="form-text text-muted">Perihal</small>
                            <p><strong>{{ $disposisi->surat->perihal }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Asal Instansi</small>
                            <p>{{ $disposisi->surat->asal_instansi }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Diterima</small>
                            <p>{{ $disposisi->surat->tgl_diterima->format('d-m-Y') }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Status Surat</small>
                            <p>{!! $disposisi->surat->status_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disposisi Info -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Disposisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <small class="form-text text-muted">Dari</small>
                            <p><strong>{{ $disposisi->pengirim->name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Ke</small>
                            <p><strong>{{ $disposisi->penerima->name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Status Disposisi</small>
                            <p>{!! $disposisi->status_badge !!}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Sifat Surat</small>
                            <p>{{ $disposisi->sifat }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Diterima</small>
                            <p>
                                @if($disposisi->tanggal_diterima)
                                    {{ $disposisi->tanggal_diterima->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-muted">Belum dibaca</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Selesai</small>
                            <p>
                                @if($disposisi->tanggal_selesai)
                                    {{ $disposisi->tanggal_selesai->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-muted">Belum selesai</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruksi -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Instruksi</h5>
                </div>
                <div class="card-body">
                    <p>{{ $disposisi->instruksi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- File Scan -->
    @if($disposisi->surat->file_scan_path)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">File Scan Surat</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ asset('storage/' . $disposisi->surat->file_scan_path) }}" 
                           target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Unduh File Scan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Catatan Staff & File Hasil (Jika ada) -->
    @if($disposisi->catatan_staff || $disposisi->file_hasil)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Hasil Kerja</h5>
                    </div>
                    <div class="card-body">
                        @if($disposisi->catatan_staff)
                            <div class="mb-3">
                                <h6>Catatan:</h6>
                                <p>{{ $disposisi->catatan_staff }}</p>
                            </div>
                        @endif

                        @if($disposisi->file_hasil)
                            <div class="mb-3">
                                <h6>File Hasil:</h6>
                                <a href="{{ asset('storage/' . $disposisi->file_hasil) }}" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i> Unduh File Hasil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Catatan Revisi (Jika ada) -->
    @if($disposisi->catatan_revisi)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Catatan Revisi</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $disposisi->catatan_revisi }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if($disposisi->status == 0)
                        <!-- Belum Dibaca -->
                        <form action="{{ route('disposisi.terima', $disposisi->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Terima Tugas
                            </button>
                        </form>

                    @elseif($disposisi->status == 1)
                        <!-- Sedang Diproses -->
                        @if(auth()->id() == $disposisi->tujuan_user_id)
                            <button class="btn btn-primary" data-toggle="modal" data-target="#laporHasilModal">
                                <i class="fas fa-file-upload"></i> Laporkan Hasil Kerja
                            </button>
                        @endif

                    @elseif($disposisi->status == 2)
                        <!-- Menunggu Verifikasi -->
                        @if(in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']))
                            <button class="btn btn-success" data-toggle="modal" data-target="#verifikasiModal">
                                <i class="fas fa-check-circle"></i> Verifikasi (Approve)
                            </button>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#revisiModal">
                                <i class="fas fa-redo"></i> Minta Revisi
                            </button>
                        @endif

                    @elseif($disposisi->status == 3)
                        <!-- Perlu Revisi -->
                        @if(auth()->id() == $disposisi->tujuan_user_id)
                            <div class="alert alert-warning mb-3">
                                <strong>Perlu Revisi:</strong> {{ $disposisi->catatan_revisi }}
                            </div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#laporHasilModal">
                                <i class="fas fa-redo"></i> Laporkan Revisi
                            </button>
                        @endif

                    @elseif($disposisi->status == 4)
                        <!-- Selesai -->
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Disposisi berhasil diselesaikan
                        </div>
                    @endif

                    @if($disposisi->penerima->id == auth()->id() || in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']))
                        <a href="{{ route('surat-masuk.tracking', $disposisi->surat->id) }}" class="btn btn-info">
                            <i class="fas fa-history"></i> Lihat Tracking Surat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Lapor Hasil Kerja -->
<div class="modal fade" id="laporHasilModal" tabindex="-1" role="dialog" aria-labelledby="laporHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.selesai', $disposisi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="laporHasilLabel">Lapor Hasil Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_staff">Catatan Hasil Kerja <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('catatan_staff') is-invalid @enderror" 
                                  id="catatan_staff" name="catatan_staff" rows="5" required>{{ old('catatan_staff') }}</textarea>
                        @error('catatan_staff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file_hasil">File Hasil (Optional)</label>
                        <input type="file" class="form-control @error('file_hasil') is-invalid @enderror" 
                               id="file_hasil" name="file_hasil" accept=".pdf,.doc,.docx">
                        <small class="form-text text-muted">Max 5MB (PDF, DOC, DOCX)</small>
                        @error('file_hasil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Verifikasi Approve -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="verifikasiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_akhir" value="OK">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifikasiLabel">Verifikasi - Approve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Hasil kerja dinyatakan sesuai dengan ketentuan.</strong>
                        <p>Surat akan siap untuk naik ke Bupati.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Revisi Minta -->
<div class="modal fade" id="revisiModal" tabindex="-1" role="dialog" aria-labelledby="revisiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_akhir" value="Revisi">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiLabel">Minta Revisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_revisi">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('catatan_revisi') is-invalid @enderror" 
                                  id="catatan_revisi" name="catatan_revisi" rows="5" required>{{ old('catatan_revisi') }}</textarea>
                        @error('catatan_revisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-redo"></i> Kirim Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
