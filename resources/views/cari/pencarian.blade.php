@extends('layouts.app')

@section('content')
<div class="container-fluid">
    
    <h1 class="h3 mb-4 text-gray-800 fw-bold">Pencarian Surat</h1>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ route('pencarian.index') }}" method="GET">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-white border-end-0 rounded-start-4 ps-3">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" name="q" class="form-control border-start-0 rounded-end-4" 
                           placeholder="Ketik Nomor Surat atau Perihal..." 
                           value="{{ $keyword ?? '' }}" autofocus>
                    <button class="btn btn-primary rounded-4 ms-2 px-4 fw-bold" type="submit">Cari</button>
                </div>
                <small class="text-muted ms-2 mt-2 d-block">
                    *Masukkan minimal 3 karakter agar hasil lebih akurat.
                </small>
            </form>
        </div>
    </div>

    @if(isset($keyword))
        @if($hasil->count() > 0)
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">
                        <i class="bi bi-list-check me-2"></i>Ditemukan {{ $hasil->count() }} Surat
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">No. Surat</th>
                                    <th>Perihal</th>
                                    <th>Tanggal</th>
                                    <th>Pengirim</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hasil as $surat)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $surat->no_surat }}</td>
                                    <td>{{ $surat->perihal }}</td>
                                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d M Y') }}</td>
                                    <td>{{ $surat->pengirim }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-info text-white rounded-pill px-3" 
                                                data-bs-toggle="modal" data-bs-target="#detailModal{{ $surat->id }}">
                                            Detail
                                        </button>
                                    </td>
                                </tr>

                                <div class="modal fade" id="detailModal{{ $surat->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Detail Surat</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-borderless">
                                                    <tr><td width="30%">Nomor</td><td>: <strong>{{ $surat->no_surat }}</strong></td></tr>
                                                    <tr><td>Jenis</td><td>: <span class="badge bg-secondary">{{ $surat->jenis_surat ?? '-' }}</span></td></tr>
                                                    <tr><td>Perihal</td><td>: {{ $surat->perihal }}</td></tr>
                                                    <tr><td>Pengirim</td><td>: {{ $surat->pengirim }}</td></tr>
                                                    <tr><td>Tanggal</td><td>: {{ $surat->tanggal_surat }}</td></tr>
                                                    <tr><td>Keterangan</td><td>: {{ $surat->keterangan ?? '-' }}</td></tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-search display-1 text-muted opacity-25"></i>
                <h5 class="text-muted mt-3">Surat tidak ditemukan</h5>
                <p class="text-muted small">Coba kata kunci lain atau pastikan nomor surat benar.</p>
            </div>
        @endif
    @endif
</div>
@endsection