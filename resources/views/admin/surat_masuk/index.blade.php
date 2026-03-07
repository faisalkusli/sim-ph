@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Surat Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Surat Masuk</li>
    </ol>

    <div class="card mb-4 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white">
            <div class="fw-bold"><i class="fas fa-envelope me-1"></i> Data Surat Masuk</div>
            
            @if(auth()->user()->role == 'tamu')
            <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Input Surat Baru
            </a>
            @endif
        </div>
        
        <div class="card-body">
            <form action="{{ route('surat-masuk.index') }}" method="GET" class="mb-4">
                <div class="input-group">
                    <input type="text" name="cari" class="form-control" placeholder="Cari No Surat / Perihal..." value="{{ request('cari') }}">
                    <button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i> Cari</button>
                    @if(request('cari')) <a href="{{ route('surat-masuk.index') }}" class="btn btn-light border">Reset</a> @endif
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-1"></i> {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th width="5%">No</th>
                            <th width="15%">Info Agenda</th>
                            <th width="20%">Asal & Tgl</th>
                            <th width="25%">Perihal</th>
                            <th width="10%">File</th>
                            <th width="15%">Status & Riwayat</th> 
                            <th width="10%">Aksi</th> 
                        </tr>
                    </thead>
                    
                    <tbody>
                        @forelse($surat_masuk as $surat)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $surat_masuk->firstItem() - 1 }}</td>
                                <td class="text-center">
                                    <h6 class="badge bg-info mb-1">{{ $surat->no_agenda }}</h6>
                                    <div class="small text-muted">Diterima: {{ \Carbon\Carbon::parse($surat->tgl_diterima)->format('d/m/Y') }}</div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $surat->asal_instansi }}</div>
                                    <div class="small text-muted">No: {{ $surat->no_surat_pengirim }}</div>
                                    <div class="small text-muted">Tgl: {{ \Carbon\Carbon::parse($surat->tgl_surat)->format('d M Y') }}</div>
                                </td>
                                <td>{{ Str::limit($surat->perihal, 80) }}</td>
                                <td class="text-center">
                                    @if($surat->file_scan_path)
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#previewModal{{ $surat->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <div class="modal fade" id="previewModal{{ $surat->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">
                                                    <div class="modal-header"><h5 class="modal-title">File Surat</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                                    <div class="modal-body" style="height: 80vh;">
                                                        <iframe src="{{ asset('storage/' . $surat->file_scan_path) }}" width="100%" height="100%" style="border:none;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @php
                                        $st = $surat->status; 
                                        $cls = 'secondary';
                                        
                                        if($st == 'Menunggu') $cls = 'warning text-dark';
                                        elseif($st == 'Siap Disposisi' || $st == 'Disetujui' || $st == 'Diterima') $cls = 'success';
                                        elseif($st == 'Ditolak') $cls = 'danger';
                                        elseif($st == 'Naik ke Bupati') $cls = 'dark';
                                        elseif($st == 'Turun dari Bupati') $cls = 'info text-dark';
                                        elseif($st == 'Disposisi') $cls = 'primary';
                                    @endphp

                                    <span class="badge bg-{{ $cls }} border border-{{ $cls }} mb-1">
                                        {{ $st }}
                                    </span>

                                    <div class="small mt-1" style="font-size: 0.75rem;">
                                        @if($st == 'Disposisi' && $surat->tgl_disposisi)
                                            <div class="text-primary fw-bold">
                                                <i class="fas fa-share me-1"></i> {{ \Carbon\Carbon::parse($surat->tgl_disposisi)->format('d/m/y') }}
                                            </div>
                                        @endif

                                        @if($st == 'Naik ke Bupati' && $surat->tgl_naik_bupati)
                                            <div class="text-dark fw-bold border-top pt-1 mt-1">
                                                <i class="fas fa-arrow-up me-1"></i> {{ \Carbon\Carbon::parse($surat->tgl_naik_bupati)->format('d/m/y') }}
                                            </div>
                                            @if($surat->no_npknd)
                                                <div class="text-muted fst-italic">NPKND: {{ $surat->no_npknd }}</div>
                                            @endif
                                        @endif

                                        @if($st == 'Turun dari Bupati' && $surat->tgl_turun_bupati)
                                            <div class="text-success fw-bold border-top pt-1 mt-1">
                                                <i class="fas fa-arrow-down me-1"></i> {{ \Carbon\Carbon::parse($surat->tgl_turun_bupati)->format('d/m/y') }}
                                            </div>
                                            @if($surat->tgl_naik_bupati)
                                                <div class="text-muted" style="font-size: 0.65rem;">(Naik: {{ \Carbon\Carbon::parse($surat->tgl_naik_bupati)->format('d/m') }})</div>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                                <td class="text-center text-nowrap">
                                    @if($surat->status == 'Menunggu Validasi' && in_array(auth()->user()->role, ['admin', 'kabag', 'super_admin']))
                                        <div class="d-flex gap-1 mb-1">
                                            <form action="{{ route('surat-masuk.validasi', $surat->id) }}" method="POST" onsubmit="return confirm('Validasi OK dan siap disposisi?')">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status_verifikasi" value="Setuju">
                                                <button type="submit" class="btn btn-success btn-sm" title="Setujui/Validasi">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $surat->id }}" title="Tolak Surat">
                                                <i class="fas fa-times"></i>
                                            </button>

                                        </div>
                                    @endif

                                    @if($surat->status == 'Disetujui')
                                    <a href="{{ route('surat-masuk.cetak', $surat->id) }}" target="_blank" class="btn btn-warning btn-sm mb-1 text-dark" title="Cetak Tanda Terima">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    @endif

                                    <div class="dropdown d-inline-block">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle mb-1" type="button" data-bs-toggle="dropdown">Opsi</button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow">

                                            <li><a class="dropdown-item" href="{{ route('surat-masuk.show', $surat->id) }}">Detail & Disposisi</a></li>
                                            
                                            @if(in_array(auth()->user()->role, ['admin', 'kabag', 'super_admin']))
                                                <li><hr class="dropdown-divider"></li>

                                                @if($surat->status != 'Naik ke Bupati' && $surat->status != 'Turun dari Bupati' && $surat->status != 'Ditolak')
                                                    <li>
                                                        <button type="button" class="dropdown-item fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#modalNaik{{ $surat->id }}">
                                                            <i class="fas fa-arrow-circle-up text-danger me-2"></i> Naik ke Bupati
                                                        </button>
                                                    </li>
                                                @endif

                                                @if($surat->status == 'Naik ke Bupati')
                                                    <li>
                                                        <button type="button" class="dropdown-item fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#modalTurun{{ $surat->id }}">
                                                            <i class="fas fa-arrow-circle-down text-success me-2"></i> Turun dari Bupati
                                                        </button>
                                                    </li>
                                                @endif
                                            @endif

                                            @if(in_array(auth()->user()->role, ['admin', 'super_admin']))
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('surat-masuk.edit', $surat->id) }}"><i class="fas fa-edit text-warning me-2"></i> Edit Data</a></li>
                                                <li>
                                                    <form action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus surat ini? Data disposisi juga akan terhapus.')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash me-2"></i> Hapus Surat</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="modal fade" id="modalTolak{{ $surat->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white"><h5 class="modal-title">Tolak Surat</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                                                <form action="{{ route('surat-masuk.validasi', $surat->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="modal-body text-start" style="white-space: normal;">
                                                        <input type="hidden" name="status_verifikasi" value="Tolak">
                                                        <label class="form-label">Alasan Penolakan:</label>
                                                        <textarea name="alasan_tolak" class="form-control" rows="3" required placeholder="Wajib diisi..."></textarea>
                                                    </div>
                                                    <div class="modal-footer"><button type="submit" class="btn btn-danger">Tolak Surat</button></div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalNaik{{ $surat->id }}" tabindex="-1" data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title"><i class="fas fa-file-signature me-2"></i>Naik ke Bupati</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('surat.naik_bupati', $surat->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body bg-light text-start">
                                                        <div class="form-floating mb-3">
                                                            <input type="text" name="no_npknd" class="form-control fw-bold" id="npknd{{ $surat->id }}" placeholder="Nomor NPKND" required>
                                                            <label for="npknd{{ $surat->id }}">Nomor NPKND</label>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="date" name="tgl_naik_bupati" class="form-control" id="tglNaik{{ $surat->id }}" value="{{ date('Y-m-d') }}" required>
                                                            <label for="tglNaik{{ $surat->id }}">Tanggal Naik</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-white">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-danger fw-bold">Proses Naik</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id="modalTurun{{ $surat->id }}" tabindex="-1" data-bs-backdrop="static">
                                        <div class="modal-dialog">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title"><i class="fas fa-check-double me-2"></i>Turun dari Bupati</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('surat.turun_bupati', $surat->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body bg-light text-start">
                                                        <div class="card card-body border-success mb-3 bg-white">
                                                            <label class="small text-muted fw-bold text-uppercase">Nomor NPKND (Data Naik)</label>
                                                            <div class="fs-4 fw-bold text-dark">{{ $surat->no_npknd ?? '-' }}</div>
                                                        </div>
                                                        <div class="form-floating">
                                                            <input type="date" name="tgl_turun_bupati" class="form-control" id="tglTurun{{ $surat->id }}" value="{{ date('Y-m-d') }}" required>
                                                            <label for="tglTurun{{ $surat->id }}">Tanggal Turun / Selesai</label>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer bg-white">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success fw-bold">Simpan & Selesai</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3 d-flex justify-content-end">{{ $surat_masuk->links() }}</div>
        </div>
    </div>
</div>
@endsection