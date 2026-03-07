@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4 text-primary"><i class="bi bi-diagram-3"></i> Monitoring Disposisi </h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" id="searchDisposisi" class="form-control" placeholder="Cari perihal, staff, atau status...">
                </div>
                <div class="col-md-3">
                    <select id="filterStatus" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Selesai">Selesai</option>
                        <option value="Proses">Proses</option>
                        <option value="Revisi">Perlu Revisi</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="tableMonitoring">
                    <thead class="table-light text-center">
                        <tr>
                            <th>No</th>
                            <th>Staff Tujuan</th>
                            <th>Perihal Surat</th>
                            <th>Status Surat</th>
                            <th>Status Disposisi</th>
                            <th>Instruksi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($monitoring_list as $disposisi)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration + $monitoring_list->firstItem() - 1 }}
                                </td>
                                <td>
                                    @if($disposisi->penerima)
                                        <div class="fw-bold text-dark">
                                            <i class="fas fa-user-circle text-secondary me-1"></i>
                                            {{ $disposisi->penerima->name }}
                                        </div>
                                        <div class="small text-muted mt-1">
                                            Tgl: {{ \Carbon\Carbon::parse($disposisi->created_at)->format('d/m/Y H:i') }}
                                        </div>
                                    @else
                                        <span class="text-danger">- User Tidak Ditemukan -</span>
                                    @endif
                                </td>
\
                                <td>
                                    @if($disposisi->surat)
                                        <div class="fw-bold">{{ Str::limit($disposisi->surat->perihal, 50) }}</div>
                                        <div class="small text-muted">Asal: {{ $disposisi->surat->asal_instansi }}</div>
                                    @else
                                        <em class="text-muted">Data surat terhapus</em>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if($disposisi->surat)
                                        @php
                                            $st = $disposisi->surat->status; 
                                            $cls = 'secondary';
                                            
                                            if($st == 'Menunggu') $cls = 'warning text-dark';
                                            elseif($st == 'Disetujui' || $st == 'Diterima') $cls = 'success';
                                            elseif($st == 'Ditolak') $cls = 'danger';
                                            elseif($st == 'Naik ke Bupati') $cls = 'dark'; 
                                            elseif($st == 'Turun dari Bupati') $cls = 'info text-dark'; 
                                            elseif($st == 'Disposisi') $cls = 'primary';
                                        @endphp

                                        <span class="badge bg-{{ $cls }} border border-{{ $cls }}">
                                            {{ $st }}
                                        </span>

                                        <div class="small mt-1">
                                            @if($st == 'Naik ke Bupati' && $disposisi->surat->tgl_naik_bupati)
                                                <span class="text-muted" style="font-size: 0.7rem;">
                                                    <i class="fas fa-arrow-up"></i> {{ date('d/m', strtotime($disposisi->surat->tgl_naik_bupati)) }}
                                                </span>
                                            @elseif($st == 'Turun dari Bupati' && $disposisi->surat->tgl_turun_bupati)
                                                <span class="text-success" style="font-size: 0.7rem;">
                                                    <i class="fas fa-arrow-down"></i> {{ date('d/m', strtotime($disposisi->surat->tgl_turun_bupati)) }}
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-center">
                                    @php
                                        $status = $disposisi->status;
                                        $badge = 'bg-secondary';
                                        $label = 'Proses';
                                        if ($status == 4 || $status == 'Selesai') { $badge = 'bg-success'; $label = 'Selesai'; }
                                        elseif ($status == 3 || $status == 'Perlu Revisi') { $badge = 'bg-warning text-dark'; $label = 'Perlu Revisi'; }
                                        elseif ($status == 2) { $badge = 'bg-info'; $label = 'Tunggu Verifikasi'; }
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $label }}</span>
                                    <div class="small text-muted mt-1">{{ $disposisi->updated_at->format('d/m/y') }}</div>
                                </td>

                                <td>
                                    <small class="fst-italic text-muted">"{{ Str::limit($disposisi->instruksi, 40) }}"</small>
                                </td>

                                <td class="text-center">
                                    @if($disposisi->surat)
                                        <a href="{{ route('surat-masuk.show', $disposisi->surat->id) }}" class="btn btn-sm btn-outline-primary" title="Detail Surat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" onclick="showDisposisiDetail({{ $disposisi->id }})" title="Detail Disposisi"><i class="fas fa-info-circle"></i></button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                        <i class="fas fa-inbox fa-3x mb-3 text-secondary"></i>
                                        <div class="text-muted" style="font-size:1.1rem;">Belum ada riwayat disposisi yang Anda buat.</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-3">
                    {{ $monitoring_list->links() }}
                </div>
            </div>

            <div class="mt-3">
                {{ $semua_disposisi->links() }}
            </div>
            <script>
                document.getElementById('searchDisposisi').addEventListener('input', function() {
                    let val = this.value.toLowerCase();
                    document.querySelectorAll('#tableMonitoring tbody tr').forEach(function(row) {
                        row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
                    });
                });
                document.getElementById('filterStatus').addEventListener('change', function() {
                    let val = this.value.toLowerCase();
                    document.querySelectorAll('#tableMonitoring tbody tr').forEach(function(row) {
                        let status = row.querySelector('td:nth-child(5) .badge').innerText.toLowerCase();
                        row.style.display = !val || status.includes(val) ? '' : 'none';
                    });
                });
                function showDisposisiDetail(id) {
                    Swal.fire({
                        title: 'Detail Disposisi',
                        html: 'Memuat detail disposisi...<br>ID: ' + id,
                        icon: 'info',
                        showCloseButton: true,
                        showConfirmButton: false
                    });
                    // Untuk implementasi detail AJAX, tambahkan fetch data di sini
                }
            </script>
        </div>
    </div>
</div>
@endsection