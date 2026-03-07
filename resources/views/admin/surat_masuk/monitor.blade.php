@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Monitoring Disposisi</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Monitoring</li>
    </ol>

    <div class="card mb-4 shadow-sm border-0 border-top border-primary border-4">
        <div class="card-header bg-white py-3">
            <h5 class="m-0 fw-bold text-primary"><i class="fas fa-chart-line me-2"></i> Pantauan Progres Disposisi</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Staff Tujuan</th>
                            <th width="30%">Perihal Surat</th>
                            <th>Posisi / Status Surat</th> <th>Status Pengerjaan (Staff)</th>
                            <th>Catatan Kabag</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($monitoring_list as $m)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            
                            <td>
                                <div class="fw-bold">{{ $m->penerima->name ?? '-' }}</div>
                                <small class="text-muted">Tgl: {{ $m->created_at->format('d/m/Y') }}</small>
                            </td>

                            <td>
                                {{ $m->surat->perihal ?? '-' }}
                                <div class="small text-muted mt-1 fst-italic">
                                    "{{ Str::limit($m->catatan, 50) }}"
                                </div>
                            </td>

                            <td class="text-center">
                                @if($m->surat)
                                    @php
                                        $st = $m->surat->status_terakhir;
                                        $warna = 'secondary';
                                        if($st == 'Baru') $warna = 'primary';
                                        elseif($st == 'Didisposisikan') $warna = 'info text-dark';
                                        elseif($st == 'Dikerjakan Staff') $warna = 'warning text-dark';
                                        elseif($st == 'Disposisi Selesai') $warna = 'success';
                                        elseif($st == 'Naik ke Bupati') $warna = 'dark';
                                        elseif($st == 'Turun dari Bupati') $warna = 'success';
                                    @endphp
                                    <span class="badge bg-{{ $warna }} border border-{{ $warna }}">
                                        {{ $st }}
                                    </span>
                                @else
                                    <span class="text-danger small">Data Surat Terhapus</span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($m->status_disposisi == 'Selesai')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Selesai (ACC)</span>
                                @elseif($m->status_disposisi == 'Revisi')
                                    <span class="badge bg-danger"><i class="fas fa-exclamation-circle"></i> Perlu Revisi</span>
                                @elseif($m->file_laporan)
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock"></i> Menunggu ACC</span>
                                @else
                                    <span class="badge bg-secondary">Proses Staff</span>
                                @endif
                            </td>

                            <td>
                                @if($m->catatan_kabag)
                                    <div class="alert alert-light border p-1 mb-0 small text-center">
                                        {{ $m->catatan_kabag }}
                                    </div>
                                @else
                                    <div class="text-center">-</div>
                                @endif
                            </td>

                            <td class="text-center">
                                <a href="{{ route('surat-masuk.show', $m->surat_masuk_id) }}" class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection