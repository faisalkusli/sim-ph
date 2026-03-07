@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pencarian Surat</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Cari Surat</li>
    </ol>

    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <i class="fas fa-search me-1"></i> Filter Pencarian
        </div>
        <div class="card-body bg-light">
            <form action="{{ route('pencarian.index') }}" method="GET">
                <div class="input-group input-group-lg">
                    <input type="text" name="keyword" class="form-control" 
                           placeholder="Ketik Nomor Surat, Perihal, atau Instansi Pengirim..." 
                           value="{{ $keyword ?? '' }}">
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    @if(isset($keyword))
                        <a href="{{ route('pencarian.index') }}" class="btn btn-secondary px-3" title="Reset">
                            <i class="fas fa-sync"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4 border-0 shadow">
        <div class="card-header bg-white">
            <i class="fas fa-table me-1"></i> Hasil Pencarian
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th width="5%">No</th>
                            <th width="20%">No. Surat</th>
                            <th width="20%">Pengirim & Tanggal</th>
                            <th>Perihal</th>
                            <th width="15%">File Lampiran</th> </tr>
                    </thead>
                    <tbody>
                        @forelse($surats as $surat)
                            <tr>
                                <td class="text-center">{{ $loop->iteration + $surats->firstItem() - 1 }}</td>
                                
                                <td class="fw-bold text-primary">
                                    {{ $surat->no_surat_pengirim }}
                                </td>

                                <td>
                                    <div class="fw-bold text-dark">{{ $surat->pengirim }}</div>
                                    <div class="small text-muted">
                                        <i class="far fa-calendar-alt"></i> 
                                        {{ \Carbon\Carbon::parse($surat->tgl_surat_pengirim)->format('d M Y') }}
                                    </div>
                                </td>

                                <td>
                                    {{ Str::limit($surat->perihal, 100) }}
                                </td>

                                <td class="text-center">
                                    @if($surat->file_surat)
                                        <a href="{{ asset('storage/' . $surat->file_surat) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-danger shadow-sm px-3">
                                            <i class="fas fa-file-pdf me-1"></i> Lihat File
                                        </a>
                                    @else
                                        <span class="badge bg-light text-muted border border-secondary fw-normal">
                                            <i class="fas fa-times-circle"></i> Tidak ada file
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <h5 class="text-muted">Data tidak ditemukan</h5>
                                    <p class="text-muted small">Coba kata kunci lain.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Halaman {{ $surats->currentPage() }} dari {{ $surats->lastPage() }}
                </div>
                {{ $surats->links() }}
            </div>
        </div>
    </div>
</div>
@endsection