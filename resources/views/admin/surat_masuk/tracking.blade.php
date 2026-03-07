@extends('layouts.app')

@section('title', 'Tracking Surat - ' . $surat->no_agenda)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2>
                    <i class="fas fa-history"></i>
                    Riwayat Surat: {{ $surat->no_agenda }}
                </h2>
                <a href="{{ route('surat-masuk.show', $surat->id) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Surat Information Card -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Surat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <p><strong>No. Agenda:</strong></p>
                            <p>{{ $surat->no_agenda }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Perihal:</strong></p>
                            <p>{{ Str::limit($surat->perihal, 50) }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Asal Instansi:</strong></p>
                            <p>{{ $surat->asal_instansi }}</p>
                        </div>
                        <div class="col-md-3">
                            <p><strong>Status Saat Ini:</strong></p>
                            <p>{!! $surat->status_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Timeline -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-timeline"></i>
                        Timeline Riwayat Proses
                    </h5>
                </div>
                <div class="card-body">
                    @if($tracking->count() > 0)
                        <div class="timeline">
                            @foreach($tracking as $log)
                                <div class="timeline-item">
                                    <div class="timeline-marker {{ getStatusColor($log->status_log) }}">
                                        <i class="fas fa-{{ getStatusIcon($log->status_log) }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">
                                                    <strong>{{ $log->status_log }}</strong>
                                                </h6>
                                                <p class="text-muted mb-2">
                                                    <small>
                                                        <i class="far fa-calendar-alt"></i>
                                                        {{ $log->tgl_status->format('d-m-Y H:i:s') }}
                                                    </small>
                                                </p>
                                                <p class="text-muted">
                                                    <small>
                                                        <i class="fas fa-user-circle"></i>
                                                        {{ $log->user->name }}
                                                        @if($log->user->role)
                                                            ({{ ucfirst($log->user->role) }})
                                                        @endif
                                                    </small>
                                                </p>
                                            </div>
                                        </div>
                                        <p class="card-text">
                                            {{ $log->catatan }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Belum ada riwayat tracking untuk surat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Disposisi Information (Optional) -->
    @if($surat->disposisi->count() > 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-tasks"></i>
                            Informasi Disposisi ({{ $surat->disposisi->count() }} Disposisi)
                        </h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($surat->disposisi as $disp)
                                <li class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <strong>Dari:</strong>
                                            <p>{{ $disp->pengirim->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Ke:</strong>
                                            <p>{{ $disp->penerima->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Instruksi:</strong>
                                            <p>{{ Str::limit($disp->instruksi, 40) }}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Status:</strong>
                                            <p>{!! $disp->status_badge !!}</p>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>Tanggal Terima:</strong>
                                            <p>
                                                @if($disp->tanggal_diterima)
                                                    {{ $disp->tanggal_diterima->format('d-m-Y') }}
                                                @else
                                                    <span class="text-muted">Belum dibaca</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}

.timeline-item {
    display: flex;
    margin-bottom: 30px;
    position: relative;
}

.timeline-marker {
    position: absolute;
    left: -44px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    background-color: #6c757d;
    border: 3px solid white;
    box-shadow: 0 0 0 3px #dee2e6;
    z-index: 1;
}

.timeline-marker.approved {
    background-color: #28a745;
}

.timeline-marker.rejected {
    background-color: #dc3545;
}

.timeline-marker.processing {
    background-color: #007bff;
}

.timeline-marker.revision {
    background-color: #ffc107;
    color: #000;
}

.timeline-marker.bupati {
    background-color: #6f42c1;
}

.timeline-content {
    flex: 1;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 4px;
}
</style>

<script>
function getStatusColor(statusLog) {
    const colors = {
        'Surat Diterima & Divalidasi': 'approved',
        'Surat Ditolak': 'rejected',
        'Surat Disetujui': 'approved',
        'Disposisi Dibuat': 'processing',
        'Disposisi Dibaca': 'processing',
        'Hasil Kerja Dilaporkan': 'processing',
        'Verifikasi Disetujui': 'approved',
        'Perlu Revisi': 'revision',
        'Naik ke Bupati': 'bupati',
        'Turun dari Bupati': 'approved',
    };
    return colors[statusLog] || 'default';
}

function getStatusIcon(statusLog) {
    const icons = {
        'Surat Diterima & Divalidasi': 'check',
        'Surat Ditolak': 'times',
        'Surat Disetujui': 'check-circle',
        'Disposisi Dibuat': 'paper-plane',
        'Disposisi Dibaca': 'eye',
        'Hasil Kerja Dilaporkan': 'file-upload',
        'Perlu Revisi': 'exclamation-triangle',
        'Verifikasi Disetujui': 'thumbs-up',
        'Naik ke Bupati': 'arrow-up',
        'Turun dari Bupati': 'arrow-down',
    };
    return icons[statusLog] || 'circle';
}
</script>
@endsection
