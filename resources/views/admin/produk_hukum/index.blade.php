@extends('layouts.app')

@section('title', 'Master Produk Hukum')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Master Produk Hukum</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Produk Hukum</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i> Daftar Produk Hukum
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="datatablesSimple">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Keterangan</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produk as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td class="text-center">
                                @if($item->file)
                                    <a href="{{ asset('storage/produk_hukum/'.$item->file) }}" target="_blank" class="btn btn-sm btn-outline-danger" title="Download File">
                                        <i class="fas fa-file-pdf"></i> Download
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                Belum ada produk hukum.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(auth()->user() && auth()->user()->role == 'admin')
    <div class="mb-3">
        <a href="{{ route('produk-hukum.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i> Upload Produk Hukum
        </a>
    </div>
    @endif
</div>
@endsection
