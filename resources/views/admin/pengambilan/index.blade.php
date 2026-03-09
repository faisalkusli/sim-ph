@extends('layouts.app')

@section('title', 'Data Pengambilan Produk Hukum')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Pengambilan Produk Hukum</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Pengambilan PH</span>
            </nav>
        </div>
        <a href="{{ route('pengambilan.create') }}"
           class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
            <i class="fas fa-plus-circle"></i> Input Pengambilan Baru
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-blue-600"></i> Daftar Riwayat Pengambilan
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal Ambil</th>
                        <th class="px-4 py-3 text-left font-semibold">Instansi</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Pengambil</th>
                        <th class="px-4 py-3 text-left font-semibold">No. HP</th>
                        <th class="px-4 py-3 text-left font-semibold">No. Register</th>
                        <th class="px-4 py-3 text-left font-semibold">Perihal Surat</th>
                        <th class="px-4 py-3 text-center font-semibold w-20">File</th>
                        <th class="px-4 py-3 text-center font-semibold w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $key => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-center text-slate-400">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ date('d M Y', strtotime($item->tanggal_pengambilan)) }}</td>
                        <td class="px-4 py-3 font-medium text-slate-800">{{ $item->instansi_pengambil }}</td>
                        <td class="px-4 py-3 text-slate-700">{{ $item->nama_pengambil }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $item->no_hp_pengambil ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="inline-block bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">{{ $item->nomor_register }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ Str::limit($item->surat->perihal ?? 'Data Surat Terhapus', 50) }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->file_produk)
                            <a href="{{ asset('storage/produk_hukum/'.$item->file_produk) }}" target="_blank"
                               class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors mx-auto"
                               title="Lihat File">
                                <i class="fas fa-file-pdf text-xs"></i>
                            </a>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('pengambilan.cetak', $item->id) }}" target="_blank"
                                   class="w-8 h-8 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center hover:bg-amber-200 transition-colors"
                                   title="Cetak Tanda Terima">
                                    <i class="fas fa-print text-xs"></i>
                                </a>
                                <form action="{{ route('pengambilan.destroy', $item->id) }}" method="POST" class="inline-block delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors btn-delete"
                                            title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-clipboard-list text-4xl block mb-2 opacity-20"></i>
                            Belum ada data pengambilan produk hukum.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function() {
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
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endsection

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