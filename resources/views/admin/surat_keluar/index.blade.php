@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-send"></i> Daftar Surat Keluar</span>
                    <a href="{{ route('surat-keluar.create') }}" class="btn btn-light btn-sm text-success fw-bold">
                        + Catat Surat Keluar
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <form action="{{ route('surat-keluar.index') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="cari" class="form-control" placeholder="Cari Perihal / Tujuan..." value="{{ request('cari') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No Surat</th>
                                <th>Tujuan</th>
                                <th>Tgl Surat</th>
                                <th>Perihal</th>
                                <th>File Arsip</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surat_keluar as $s)
                            <tr>
                                <td>{{ $s->no_surat }}</td>
                                <td>{{ $s->tujuan_surat }}</td>
                                <td>{{ date('d M Y', strtotime($s->tgl_surat)) }}</td>
                                <td>
                                    {{ $s->perihal }}
                                    @if($s->suratMasuk)
                                        <br>
                                        <span class="badge bg-warning text-dark">
                                            Balasan Agenda: {{ $s->suratMasuk->no_agenda }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('surat-keluar.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data surat keluar.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection