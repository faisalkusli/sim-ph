@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Inbox & Tugas Saya</h1>
    
    {{-- 1. ERROR ALERT (Supaya ketahuan kalau ada form yang salah isi) --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Terjadi Kesalahan!</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- 2. SUCCESS ALERT --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="card mb-4 mt-3 shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <div><i class="fas fa-inbox me-1"></i> Daftar Tugas Masuk</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Dari / Waktu</th>
                            <th>Perihal Surat</th>
                            <th>Status & Catatan</th>
                            <th class="text-center" style="min-width: 180px;">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disposisi_masuk as $d)
                        <tr>
                            <td>
                                {{ $loop->iteration }}
                                {{-- Indikator tipe disposisi --}}
                                @if($d->tujuan_user_id === auth()->id())
                                    <br><span class="badge bg-primary" title="Untuk dikerjakan"><small>TUGAS</small></span>
                                @elseif($d->dari_user_id === auth()->id() && in_array($d->status, [2, 3]))
                                    <br><span class="badge bg-warning text-dark" title="Untuk diverifikasi"><small>VERIFIKASI</small></span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $d->pengirim->name ?? 'Sistem' }}</strong><br>
                                <small class="text-muted">{{ $d->created_at->format('d M, H:i') }}</small>
                            </td>
                            <td>
                                <span class="badge bg-secondary mb-1">{{ $d->surat->no_surat ?? 'Tanpa No' }}</span><br>
                                {{ Str::limit($d->surat->perihal ?? '-', 50) }}
                            </td>
                            <td>
                                {{-- Menampilkan Status menggunakan Enum Badge --}}
                                {!! \App\Enums\DisposisiStatus::tryFrom($d->status)?->badge() !!}
                                
                                {{-- Tampilkan status perubahan berdasarkan status --}}
                                @if($d->status == 0)
                                    <br><small class="text-muted">Menunggu Anda dibaca</small>
                                
                                @elseif($d->status == 1)
                                    <br><small class="text-muted">Anda membaca: {{ $d->created_at->diffForHumans() }}</small>
                                
                                @elseif($d->status == 2)
                                    <br><small class="text-warning">⏳ Menunggu verifikasi Kasubag</small>
                                
                                @elseif($d->status == 3)
                                    <br><small class="text-warning">⏳ Menunggu verifikasi Kabag</small>
                                
                                @elseif($d->status == 4)
                                    <br><small class="text-danger fw-bold">⚠️ Mohon segera direvisi!</small>
                                    @if($d->catatan_revisi)
                                        <div class="alert alert-danger p-2 mt-1 mb-0" style="font-size: 0.8rem;">
                                            <strong>Catatan:</strong> {{ $d->catatan_revisi }}
                                        </div>
                                    @endif
                                
                                @elseif($d->status == 5)
                                    <br><small class="text-success">✓ Selesai: {{ $d->updated_at->format('d M Y') }}</small>
                                @endif
                                
                                {{-- Tampilkan penerima jika sudah di-forward --}}
                                @if($d->penerima && $d->status >= 0)
                                    <br><small class="badge bg-light text-dark mt-1">
                                        → {{ $d->penerima->name }}
                                    </small>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    
                                    {{-- A. TOMBOL DETAIL (Semua Role) --}}
                                    @if($d->surat)
                                        <a href="{{ route('surat-masuk.show', $d->surat->id) }}" class="btn btn-info btn-sm text-white" title="Lihat Detail Lengkap">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif

                                    {{-- ========================================== --}}
                                    {{-- B. FITUR UNTUK STAFF (Terima & Lapor)      --}}
                                    {{-- ========================================== --}}
                                    {{-- Pastikan role di DB 'staff' atau 'staf', sesuaikan di sini --}}
                                    @if(auth()->user()->role == 'staff' || auth()->user()->role == 'staf')
                                        @if($d->status == 0)
                                            {{-- Tombol Terima dengan Form POST --}}
                                            <form action="{{ route('disposisi.terima', $d->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Mulai kerjakan tugas ini?');">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-hand-holding me-1"></i> Terima
                                                </button>
                                            </form>
                                        @elseif($d->status == 1)
                                            {{-- Tombol Lapor Selesai --}}
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalSelesai{{ $d->id }}">
                                                <i class="fas fa-check-double"></i> Lapor
                                            </button>
                                        @endif
                                    @endif

                                    {{-- ========================================== --}}
                                    {{-- C. VERIFIKASI KASUBAG (Status 2)           --}}
                                    {{-- ========================================== --}}
                                    @if((auth()->user()->role == 'kasubag' || auth()->user()->role == 'admin') && $d->status == 2 && $d->dari_user_id === auth()->id())
                                        <button type="button" class="btn btn-warning btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalVerifikasi{{ $d->id }}" title="Verifikasi Kasubag">
                                            <i class="fas fa-clipboard-check"></i> Verifikasi Kasubag
                                        </button>
                                    @endif

                                    {{-- ========================================== --}}
                                    {{-- D. VERIFIKASI KABAG (Status 3)            --}}
                                    {{-- ========================================== --}}
                                    @if((auth()->user()->role == 'kabag' || auth()->user()->role == 'admin') && $d->status == 3 && $d->dari_user_id === auth()->id())
                                        <button type="button" class="btn btn-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalVerifikasiKabag{{ $d->id }}" title="Verifikasi Kabag">
                                            <i class="fas fa-shield-alt"></i> Verifikasi Kabag
                                        </button>
                                    @endif

                                    {{-- ========================================== --}}
                                    {{-- E. DISPOSISI LANJUTAN (Atasan) --}}
                                    {{-- ========================================== --}}
                                    @if(in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']) && $d->status < 2)
                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalDisposisi{{ $d->surat->id }}" title="Teruskan Disposisi">
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    @endif

                                    {{-- E. TOMBOL HAPUS (Atasan) --}}
                                    @if(in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']))
                                        <form action="{{ route('disposisi.destroy', $d->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus disposisi ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Riwayat">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Tidak ada tugas / pesan masuk.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ========================================================== --}}
{{-- AREA MODAL POPUP (DI LUAR TABEL - AMAN DARI DATATABLES) --}}
{{-- ========================================================== --}}

@foreach($disposisi_masuk as $d)

    {{-- 1. MODAL STAFF: LAPOR SELESAI --}}
    @if((auth()->user()->role == 'staff' || auth()->user()->role == 'staf') && $d->status == 1)
    <div class="modal fade" id="modalSelesai{{ $d->id }}" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {{-- Form dengan Multipart untuk Upload File --}}
                <form action="{{ route('disposisi.selesai', $d->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">Lapor Pekerjaan Selesai</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="fw-bold">File Hasil Kerja (Opsional)</label>
                            <input type="file" name="file_hasil" class="form-control">
                            <small class="text-muted">Upload dokumen PDF/Word/Gambar jika ada.</small>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Catatan Pengerjaan <span class="text-danger">*</span></label>
                            <textarea name="catatan_staff" class="form-control" rows="3" required placeholder="Contoh: Surat balasan sudah saya print dan taruh di meja bapak."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- 2. MODAL KASUBAG/KABAG: VERIFIKASI HASIL KERJA STAFF --}}
    @if(in_array(auth()->user()->role, ['admin', 'kasubag', 'kabag']) && $d->status == 2)
    <div class="modal fade" id="modalVerifikasi{{ $d->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-warning">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark"><i class="fas fa-clipboard-check"></i> Verifikasi Pekerjaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Info Staff --}}
                    <div class="alert alert-light border mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Dari Staff:</strong> {{ $d->penerima->name ?? 'Bawahan' }}
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">{{ $d->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <hr class="my-1">
                        <strong>Catatan:</strong> <em class="text-primary">"{{ $d->catatan_staff }}"</em>
                    </div>

                    {{-- AREA LIVE PREVIEW FILE --}}
                    @if($d->file_hasil)
                        @php
                            $ext = pathinfo($d->file_hasil, PATHINFO_EXTENSION);
                            $fileUrl = asset('storage/'.$d->file_hasil);
                        @endphp

                        <div class="card mb-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                                <small class="fw-bold text-uppercase">Preview File ({{ $ext }})</small>
                                <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-primary" target="_blank" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            <div class="card-body p-0 text-center bg-secondary bg-opacity-10">
                                {{-- Logic Preview (Image/PDF/Word) --}}
                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fileUrl }}" class="img-fluid" style="max-height: 400px;">
                                @elseif(strtolower($ext) == 'pdf')
                                    <iframe src="{{ $fileUrl }}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                @else
                                    <div class="py-5">
                                        <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                                        <p>Preview tidak tersedia untuk format ini.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning text-center">Staff tidak melampirkan file.</div>
                    @endif
                    
                    {{-- Form Keputusan --}}
                    <form action="{{ route('disposisi.verifikasi', $d->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold">Keputusan:</label>
                            <input type="text" name="catatan_revisi" class="form-control" placeholder="Tulis catatan jika minta revisi...">
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" name="status_akhir" value="Revisi" class="btn btn-danger w-100">
                                    <i class="fas fa-undo"></i> Minta Revisi
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="status_akhir" value="Selesai" class="btn btn-success w-100 fw-bold" onclick="return confirm('ACC pekerjaan ini?')">
                                    <i class="fas fa-check-double"></i> ACC Selesai
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 2B. MODAL VERIFIKASI KABAG (Status 3) --}}
    @if((auth()->user()->role == 'kabag' || auth()->user()->role == 'admin') && $d->status == 3 && $d->surat)
    <div class="modal fade" id="modalVerifikasiKabag{{ $d->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title"><i class="fas fa-shield-alt"></i> Verifikasi Akhir Kabag</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-light border mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Dari Kasubag:</strong> {{ $d->penerima->name ?? 'Kasubag' }}
                            </div>
                            <div class="col-md-6 text-md-end">
                                <small class="text-muted">{{ $d->updated_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>
                        <hr class="my-1">
                        <strong>Status Sebelumnya:</strong> <span class="badge bg-warning text-dark">Menunggu Verifikasi Kasubag</span>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <strong>Catatan Staff:</strong> <em class="text-primary">"{{ $d->catatan_staff }}"</em>
                        </div>
                    </div>

                    {{-- FILE PREVIEW --}}
                    @if($d->file_hasil)
                        @php
                            $ext = pathinfo($d->file_hasil, PATHINFO_EXTENSION);
                            $fileUrl = asset('storage/'.$d->file_hasil);
                        @endphp
                        <div class="card mb-3">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center py-1">
                                <small class="fw-bold text-uppercase">File Hasil ({{ $ext }})</small>
                                <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-primary" target="_blank" download>
                                    <i class="fas fa-download"></i> Download
                                </a>
                            </div>
                            <div class="card-body p-0 text-center bg-secondary bg-opacity-10">
                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ $fileUrl }}" class="img-fluid" style="max-height: 400px;">
                                @elseif(strtolower($ext) == 'pdf')
                                    <iframe src="{{ $fileUrl }}" style="width: 100%; height: 500px;" frameborder="0"></iframe>
                                @else
                                    <div class="py-5">
                                        <i class="fas fa-file-alt fa-3x text-secondary mb-3"></i>
                                        <p>Preview tidak tersedia.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- KEPUTUSAN FINAL KABAG --}}
                    <form action="{{ route('disposisi.verifikasi', $d->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold d-block mb-2">Keputusan Akhir Kabag:</label>
                            <textarea name="catatan_revisi" class="form-control" rows="3" placeholder="Tulis catatan jika perlu revisi atau ACC..."></textarea>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button type="submit" name="status_akhir" value="Revisi" class="btn btn-danger w-100 fw-bold">
                                    <i class="fas fa-undo"></i> Minta Revisi
                                </button>
                            </div>
                            <div class="col-6">
                                <button type="submit" name="status_akhir" value="Selesai" class="btn btn-success w-100 fw-bold" onclick="return confirm('ACC verifikasi akhir ini? Disposisi akan selesai.')">
                                    <i class="fas fa-check-circle"></i> ACC Selesai
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- 3. MODAL DISPOSISI LANJUTAN --}}
    @if(in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']) && $d->status < 2 && $d->surat)
    <div class="modal fade" id="modalDisposisi{{ $d->surat->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('disposisi.store') }}" method="POST">
                @csrf
                <input type="hidden" name="surat_masuk_id" value="{{ $d->surat->id }}">

                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Disposisi Lanjutan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="fw-bold">Diteruskan Kepada:</label>
                            <select name="tujuan_user_id" class="form-select" required>
                                <option value="">-- Pilih Bawahan --</option>
                                @if(isset($listTujuan) && $listTujuan->count() > 0)
                                    @foreach($listTujuan as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option> 
                                    @endforeach
                                @else
                                    <option value="" disabled>Tidak ada bawahan</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-3">
                             <label class="fw-bold">Jenis Surat <span class="text-danger">*</span></label>
                             <input type="text" name="jenis_surat" class="form-control" value="{{ $d->surat->jenis_surat ?? 'Surat' }}" required>
                        </div>
                        
                        <div class="mb-3">
                             <label class="fw-bold">Sifat <span class="text-danger">*</span></label>
                             <select name="sifat" class="form-select" required>
                                <option value="Biasa">Biasa</option>
                                <option value="Segera">Segera</option>
                             </select>
                        </div>
                        
                        <div class="mb-3">
                             <label class="fw-bold">Instruksi <span class="text-danger">*</span></label>
                             <textarea name="instruksi" class="form-control" rows="3" required placeholder="Berikan instruksi untuk penyelesaian surat ini..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    @endif

@endforeach

@endsection