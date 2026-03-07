@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">Catat Surat Keluar Baru</div>

                <div class="card-body">
                    <form action="{{ route('surat-keluar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label>Nomor Surat</label>
                            <input type="text" name="no_surat" class="form-control" placeholder="Contoh: 005/HK/XI/2023" required>
                        </div>

                        <div class="mb-3">
                            <label>Tujuan Surat (Kepada)</label>
                            <input type="text" name="tujuan_surat" class="form-control" placeholder="Contoh: Kepala Dinas Pendidikan" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tanggal Surat</label>
                                <input type="date" name="tgl_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Tanggal Dikirim (Opsional)</label>
                                <input type="date" name="tgl_kirim" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Referensi Surat Masuk (Jika ada)</label>
                            <select name="surat_masuk_id" class="form-select">
                                <option value="">-- Tidak ada / Surat Baru --</option>
                                @foreach($daftar_surat_masuk as $sm)
                                <option value="{{ $sm->id }}">
                                    [Agenda: {{ $sm->no_agenda }}] {{ $sm->perihal }} - {{ $sm->asal_instansi }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Pilih surat jika surat keluar ini adalah balasan.</small>
                        </div>

                        <div class="mb-3">
                            <label>Perihal</label>
                            <textarea name="perihal" class="form-control" rows="3" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label>File Arsip (Scan Surat) - PDF/Gambar</label>
                            <input type="file" name="file_arsip" class="form-control">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Simpan Surat Keluar</button>
                            <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection