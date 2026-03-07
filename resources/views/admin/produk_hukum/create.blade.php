@extends('layouts.app')

@section('title', 'Upload Produk Hukum')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Upload Produk Hukum</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('produk-hukum.index') }}">Produk Hukum</a></li>
        <li class="breadcrumb-item active">Upload</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-upload me-1"></i> Form Upload Produk Hukum
        </div>
        <div class="card-body">
            <form action="{{ route('produk-hukum.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Produk Hukum</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="file" class="form-label">File Produk Hukum (PDF/DOC/DOCX, max 10MB)</label>
                    <input type="file" name="file" id="file" class="form-control" accept=".pdf,.doc,.docx" required>
                </div>
                <button type="submit" class="btn btn-success">Upload</button>
                <a href="{{ route('produk-hukum.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
