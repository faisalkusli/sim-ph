@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">

    <div class="row justify-content-center">
        <div class="col-md-10">
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-file-earmark-plus me-2"></i>Input Surat Masuk Baru
                    </h5>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('surat-masuk.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Agenda</label>
                                    <input type="text" 
                                        name="no_agenda" 
                                        class="form-control bg-light fw-bold text-primary" 
                                        value="{{ old('no_agenda', $no_agenda_baku) }}" 
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Asal Instansi</label>
                                    <input type="text" name="asal_instansi" class="form-control @error('asal_instansi') is-invalid @enderror" value="{{ old('asal_instansi') }}">
                                    @error('asal_instansi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Jenis Surat</label>
                                    <select class="form-select @error('jenis_surat') is-invalid @enderror" name="jenis_surat">
                                        <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                        @php
                                            $jenis = ['Perda', 'Perbup', 'SK Bupati', 'SK Sekda', 'SK Hibah', 'Surat Undangan', 'Surat Tembusan', 'Surat Lainnya'];
                                        @endphp
                                        @foreach($jenis as $j)
                                            <option value="{{ $j }}" {{ old('jenis_surat') == $j ? 'selected' : '' }}>
                                                {{ $j }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jenis_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">No. Surat Pengirim</label>
                                    <input type="text" name="no_surat_pengirim" class="form-control @error('no_surat_pengirim') is-invalid @enderror" value="{{ old('no_surat_pengirim') }}">
                                    @error('no_surat_pengirim') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" class="form-control @error('tgl_surat') is-invalid @enderror" value="{{ old('tgl_surat') }}">
                                    @error('tgl_surat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Tanggal Diterima</label>
                                    <input type="date" name="tgl_diterima" class="form-control @error('tgl_diterima') is-invalid @enderror" value="{{ old('tgl_diterima', date('Y-m-d')) }}">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Perihal</label>
                                    <textarea name="perihal" class="form-control @error('perihal') is-invalid @enderror" rows="3">{{ old('perihal') }}</textarea>
                                    @error('perihal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">File Scan Surat (PDF/Gambar)</label>
                                    <input type="file" name="file_scan" class="form-control @error('file_scan') is-invalid @enderror">
                                    <small class="text-muted">Maksimal upload 2MB.</small>
                                    @error('file_scan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i> Simpan Data Surat
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection