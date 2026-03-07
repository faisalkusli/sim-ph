@extends('layouts.app')

@section('title', 'Data Pengambilan Produk Hukum')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Pengambilan Produk Hukum</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Pengambilan PH</li>
    </ol>

    <div class="mb-3">
        <a href="{{ route('pengambilan.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Input Pengambilan Baru
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Riwayat Pengambilan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="datatablesSimple">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Tanggal Ambil</th>
                            <th>Instansi</th>
                            <th>Nama Pengambil</th>
                            <th>No. HP Pengambil</th>
                            <th>No. Register</th>
                            <th>Perihal Surat (Asal)</th>
                            <th>File Produk</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ date('d-m-Y', strtotime($item->tanggal_pengambilan)) }}</td>
                            <td>{{ $item->instansi_pengambil }}</td>
                            <td>{{ $item->nama_pengambil }}</td>
                            <td>{{ $item->no_hp_pengambil ?? '-' }}</td>
                            <td><span class="badge bg-info text-dark">{{ $item->nomor_register }}</span></td>
                            <td>
                                {{ Str::limit($item->surat->perihal ?? 'Data Surat Terhapus', 50) }}
                            </td>
                            <td class="text-center">
                                @if($item->file_produk)
                                    <a href="{{ asset('storage/produk_hukum/'.$item->file_produk) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Lihat File">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(optional($item->surat)->status !== 'Selesai')
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('pengambilan.cetak', $item->id) }}" target="_blank" class="btn btn-sm btn-warning" title="Cetak Tanda Terima">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        <form action="{{ route('pengambilan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-danger btn-delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-success">Selesai</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada data pengambilan produk hukum.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection