@extends('layouts.app')

@section('title', 'Input Pengambilan Produk Hukum')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Input Pengambilan Produk Hukum</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i> Form Data Pengambilan
        </div>
        <div class="card-body">
            <form action="{{ route('pengambilan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Surat Masuk (Agenda / Perihal)</label>
                    <select name="surat_masuk_id" class="form-select select2" required id="selectSurat">
                        <option value="">-- Pilih Nomor Agenda Surat --</option>
                        @foreach($surat_masuk as $surat)
                            <option value="{{ $surat->id }}" data-instansi="{{ $surat->asal_surat }}">
                                Agenda No: {{ $surat->no_agenda }} | Perihal: {{ Str::limit($surat->perihal, 50) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-primary" id="detailSurat"></div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nomor Register Produk Hukum</label>
                        <input type="text" name="nomor_register" class="form-control" required placeholder="Contoh: 180/SK/2026">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Pengambilan</label>
                        <input type="date" name="tanggal_pengambilan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>


                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Instansi Pengambil</label>
                        <input type="text" name="instansi_pengambil" id="instansiPengambil" class="form-control" required placeholder="Nama Instansi/Lembaga">
                        <small class="text-muted">*Otomatis terisi dari Asal Surat (Bisa diedit)</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Nama Orang yang Mengambil</label>
                        <input type="text" name="nama_pengambil" class="form-control" required placeholder="Nama Lengkap Pengambil">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">No. HP Pengambil</label>
                        <input type="text" name="no_hp_pengambil" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Upload File Produk Hukum (Jadi)</label>
                    <input type="file" name="file_produk" class="form-control" accept=".pdf,.doc,.docx">
                    <div class="form-text">Format: PDF/Word. Maksimal 5MB.</div>
                </div>

                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Data</button>
                <a href="{{ route('pengambilan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('selectSurat').addEventListener('change', function() {
        var selectedOption = this.options[this.selectedIndex];
        var instansi = selectedOption.getAttribute('data-instansi');
        if(instansi) {
            document.getElementById('instansiPengambil').value = instansi;
            document.getElementById('detailSurat').innerHTML = "Asal Surat: <strong>" + instansi + "</strong>";
        } else {
            document.getElementById('instansiPengambil').value = "";
            document.getElementById('detailSurat').innerHTML = "";
        }
    });
</script>
@endsection