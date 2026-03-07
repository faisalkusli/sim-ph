@extends('layouts.app')

@section('content')

<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mt-4 mb-3">
        <h3>Detail Surat & Disposisi</h3>
        <div class="d-flex gap-2">
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali</a>
            <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank" class="btn btn-warning btn-sm fw-bold">
                <i class="fas fa-print"></i> Cetak Lembar Disposisi
            </a>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="text-primary mb-3">{{ $surat->perihal }}</h5>
            <div class="row">
                <div class="col-md-6">
                    <strong>Pengirim:</strong> {{ $surat->asal_instansi }}<br>
                    <strong>No Surat:</strong> {{ $surat->no_surat_pengirim }}<br>
                    <strong>Status Saat Ini:</strong> 
                    <span class="badge bg-secondary">{{ $surat->status }}</span>
                </div>
                <div class="col-md-6 text-end">
                    @if($surat->file_scan_path)
                        <a href="{{ asset('storage/'.$surat->file_scan_path) }}" target="_blank" class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf"></i> Lihat File Surat
                        </a>
                    @else
                        <span class="text-muted">Tidak ada file scan</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle"></i> Gagal Disposisi!</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold"><i class="fas fa-history"></i> Riwayat Disposisi</h6>
            @if(in_array(auth()->user()->role, ['admin', 'kabag', 'super_admin', 'kasubag']) && in_array($surat->status, ['Siap Disposisi', 'Sedang Diproses']))
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi">
                    <i class="fas fa-plus"></i> Tambah Disposisi
                </button>
            @endif
        </div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Dari</th>
                        <th>Kepada Staff</th>
                        <th>Instruksi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($surat->disposisi as $d)
                    <tr>
                        <td>{{ $d->pengirim->name ?? 'Admin' }}<br><small class="text-muted">{{ $d->created_at->format('d/m/Y') }}</small></td>
                        <td><span class="badge bg-info text-dark">{{ $d->penerima->name ?? '-' }}</span></td>
                        <td>{{ $d->catatan }}</td>
                        <td>
                            @if($d->file_laporan) <i class="fas fa-check-circle text-success"></i> Selesai @else <i class="fas fa-clock text-warning"></i> Proses @endif
                        </td>
                    </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada data disposisi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- HANYA MUNCUL JIKA STATUS SURAT "MENUNGGU VERIFIKASI" DAN USER ADALAH KABAG/ADMIN --}}
    @if($surat->status == 'Menunggu Verifikasi' && in_array(auth()->user()->role, ['admin', 'kabag']))
        
        <div class="card mb-4 border-warning shadow-sm">
            <div class="card-header bg-warning text-dark fw-bold">
                <i class="fas fa-clipboard-check me-2"></i> Verifikasi Hasil Pekerjaan Staff
            </div>
            <div class="card-body">
                <h6 class="fw-bold">Laporan dari Staff:</h6>
                
                {{-- Tampilkan Catatan Staff --}}
                <div class="alert alert-light border">
                    <em>"{{ $surat->catatan_staff ?? 'Tidak ada catatan tambahan.' }}"</em>
                </div>

                {{-- Tampilkan Tombol Download File Hasil --}}
                @if($surat->file_draft_path)
                    <div class="mb-3">
                        <strong>File Hasil:</strong><br>
                        <a href="{{ asset('storage/' . $surat->file_draft_path) }}" class="btn btn-outline-primary btn-sm mt-1" target="_blank">
                            <i class="fas fa-download"></i> Download / Lihat File Pekerjaan
                        </a>
                    </div>
                @else
                    <div class="alert alert-danger py-1"><small>Tidak ada file yang dilampirkan.</small></div>
                @endif

                <hr>

                {{-- FORM PERSETUJUAN / REVISI --}}
                <form action="{{ route('surat.verifikasi', $surat->id) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-8">
                            <input type="text" name="catatan_revisi" class="form-control" placeholder="Tulis catatan jika minta revisi...">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                            {{-- Tombol Minta Revisi --}}
                            <button type="submit" name="aksi" value="revisi" class="btn btn-danger w-50">
                                <i class="fas fa-undo"></i> Revisi
                            </button>
                            {{-- Tombol ACC / Selesai --}}
                            <button type="submit" name="aksi" value="acc" class="btn btn-success w-50" onclick="return confirm('Yakin pekerjaan ini sudah benar?')">
                                <i class="fas fa-check-double"></i> ACC
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if(in_array($surat->status, ['Sedang Diproses', 'Perlu Revisi']) && $surat->disposisi->isNotEmpty() && auth()->id() == $surat->disposisi->last()->tujuan_user_id)
        <div class="card mb-4 border-primary shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Hasil Tindak Lanjut</h5>
            </div>
            <div class="card-body">
                
                @if($surat->status == 'Perlu Revisi')
                    <div class="alert alert-warning">
                        <strong><i class="fas fa-exclamation-triangle"></i> PERLU REVISI!</strong><br>
                        Catatan Kabag: <em>"{{ $surat->catatan_revisi }}"</em>
                    </div>
                @endif

                <form action="{{ route('surat.upload-hasil', $surat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">File Laporan / Draft Hasil <span class="text-danger">*</span></label>
                        <input type="file" name="file_hasil" class="form-control" accept=".pdf,.doc,.docx" required>
                        <small class="text-muted">Format: PDF atau Word (Max 10MB)</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Pengerjaan</label>
                        <textarea name="catatan_staff" class="form-control" rows="3" placeholder="Contoh: Surat balasan sudah saya buat, mohon dicek.">{{ $surat->catatan_staff }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Kirim ke Kabag
                    </button>
                </form>
            </div>
        </div>
    @endif

    @if($surat->status == 'Menunggu Verifikasi Akhir' && in_array(auth()->user()->role, ['admin', 'kabag']))
        <div class="card mb-4 border-info shadow">
            <div class="card-header bg-info text-dark">
                <h5 class="mb-0"><i class="fas fa-clipboard-check me-2"></i>Verifikasi Hasil Kerja Staf</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-light border d-flex justify-content-between align-items-center">
                    <div>
                        <strong>File Upload Staf:</strong><br>
                        <span class="text-muted fst-italic">"{{ $surat->catatan_staff ?? 'Tidak ada catatan.' }}"</span>
                    </div>
                    @if($surat->file_draft_path)
                        <a href="{{ asset('storage/' . $surat->file_draft_path) }}" class="btn btn-sm btn-outline-primary fw-bold" target="_blank">
                            <i class="fas fa-download me-1"></i> Download File
                        </a>
                    @else
                        <span class="badge bg-danger">File Tidak Ditemukan</span>
                    @endif
                </div>

                <hr>

                <form action="{{ route('surat.verifikasi-akhir', $surat->id) }}" method="POST">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" name="catatan_revisi" class="form-control" placeholder="Tulis catatan revisi jika ditolak...">
                                <button type="submit" name="status_akhir" value="Revisi" class="btn btn-warning">
                                    <i class="fas fa-undo me-1"></i> Minta Revisi
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="submit" name="status_akhir" value="Selesai" class="btn btn-success w-100 fw-bold" onclick="return confirm('Yakin pekerjaan ini sudah selesai?')">
                                <i class="fas fa-check-double me-1"></i> ACC & Selesai
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div> 

@if(in_array(auth()->user()->role, ['admin', 'kabag', 'super_admin', 'kasubag']))
<div class="modal fade" id="modalDisposisi" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('disposisi.store', $surat->id) }}" method="POST">
            @csrf
            <input type="hidden" name="surat_masuk_id" value="{{ $surat->id }}">

            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Tambah Disposisi Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="fw-bold">Diteruskan Kepada:</label>
                        <select name="tujuan_user_id" class="form-select" required>
                            <option value="">-- Pilih Tujuan --</option>
                            @foreach($listTujuan as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} - {{ ucfirst($u->role) }}</option> 
                            @endforeach
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="fw-bold">Sifat Surat:</label>
                            <select name="sifat" class="form-select" required>
                                <option value="Biasa">Biasa</option>
                                <option value="Penting">Penting</option>
                                <option value="Segera">Segera</option>
                                <option value="Rahasia">Rahasia</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold">Jenis Surat:</label>
                            <select name="jenis_surat" class="form-select" required>
                                <option value="Fisik">Fisik</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Instruksi / Catatan:</label>
                        <textarea name="instruksi" class="form-control" rows="3" required placeholder="Tulis instruksi pengerjaan di sini..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Kirim Disposisi</button>
                </div>
            </div>
        </form>
    </div>
     @if ($errors->any())
        <div class="alert alert-danger px-2 py-1">
            <ul class="mb-0 small">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endif

@endsection