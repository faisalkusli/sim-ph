@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Laporan Arsip Surat</h1>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-printer"></i> Filter Cetak Laporan
                </div>
                <div class="card-body">
                    <form action="{{ route('laporan.cetak') }}" method="POST" target="_blank">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Laporan</label>
                            <select name="jenis_surat" class="form-select">
                                <option value="surat_masuk">Surat Masuk</option>
                                <option value="surat_keluar">Surat Keluar</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Dari Tanggal</label>
                                <input type="date" name="tgl_awal" class="form-control" required value="{{ date('Y-m-01') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sampai Tanggal</label>
                                <input type="date" name="tgl_akhir" class="form-control" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-file-earmark-pdf"></i> Cetak Laporan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection