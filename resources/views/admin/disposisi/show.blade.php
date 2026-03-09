@extends('layouts.app')

@section('title', 'Detail Disposisi')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Disposisi</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <a href="{{ route('inbox') }}" class="hover:text-blue-600">Inbox</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Detail</span>
            </nav>
        </div>
        <a href="{{ route('inbox') }}"
           class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Inbox
        </a>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-triangle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Surat Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 bg-blue-50 rounded-t-2xl">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2">
                <i class="fas fa-envelope text-blue-600"></i> Informasi Surat
            </h2>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-5 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">No. Agenda</p>
                <p class="font-bold text-slate-800">{{ $disposisi->surat->no_agenda }}</p>
            </div>
            <div class="md:col-span-2">
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Perihal</p>
                <p class="font-semibold text-slate-800">{{ $disposisi->surat->perihal }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Asal Instansi</p>
                <p class="text-slate-700">{{ $disposisi->surat->asal_instansi }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Tgl Diterima</p>
                <p class="text-slate-600">{{ optional($disposisi->surat->tgl_diterima)->format('d-m-Y') ?? '-' }}</p>
            </div>
            @if($disposisi->surat->validasi_oleh && $disposisi->surat->tgl_validasi)
            <div class="col-span-2 md:col-span-5">
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Info Validasi</p>
                <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5 text-sm">
                    <i class="fas fa-check-circle text-green-500"></i>
                    <span class="text-green-700">Divalidasi oleh <strong>{{ $disposisi->surat->validasi_oleh }}</strong> pada {{ \Carbon\Carbon::parse($disposisi->surat->tgl_validasi)->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Disposisi Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 bg-blue-600 rounded-t-2xl">
            <h2 class="font-semibold text-white flex items-center gap-2">
                <i class="fas fa-paper-plane"></i> Informasi Disposisi
            </h2>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Dari</p>
                <p class="font-semibold text-slate-800">{{ $disposisi->pengirim->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Ke</p>
                <p class="font-semibold text-slate-800">{{ $disposisi->penerima->name ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Sifat Surat</p>
                @php $sifatColor = match($disposisi->sifat) { 'Sangat Segera' => 'bg-red-100 text-red-700', 'Segera' => 'bg-amber-100 text-amber-700', default => 'bg-slate-100 text-slate-600' }; @endphp
                <span class="inline-block {{ $sifatColor }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $disposisi->sifat }}</span>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Tgl Diterima</p>
                <p class="text-slate-600">{{ optional($disposisi->tanggal_diterima)->format('d-m-Y H:i') ?? 'Belum dibaca' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Tgl Selesai</p>
                <p class="text-slate-600">{{ optional($disposisi->tanggal_selesai)->format('d-m-Y H:i') ?? 'Belum selesai' }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Status</p>
                @php
                    $dBadge = match($disposisi->status) { 0 => 'bg-amber-100 text-amber-700', 1 => 'bg-blue-100 text-blue-700', 2 => 'bg-purple-100 text-purple-700', 3 => 'bg-orange-100 text-orange-700', 4 => 'bg-red-100 text-red-700', 5 => 'bg-green-100 text-green-700', default => 'bg-slate-100 text-slate-600' };
                    $dLabels = [0=>'Belum Dibaca',1=>'Sedang Dikerjakan',2=>'Tunggu Verif Kasubag',3=>'Tunggu Verif Kabag',4=>'Perlu Revisi',5=>'Selesai'];
                @endphp
                <span class="inline-block {{ $dBadge }} text-xs font-semibold px-2.5 py-1 rounded-full">
                    {{ $dLabels[$disposisi->status] ?? 'Unknown' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Instruksi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-amber-200 bg-amber-50 rounded-t-2xl">
            <h2 class="font-semibold text-amber-800 flex items-center gap-2">
                <i class="fas fa-clipboard-list text-amber-600"></i> Instruksi
            </h2>
        </div>
        <div class="p-5">
            <p class="text-slate-700 text-sm leading-relaxed">{{ $disposisi->instruksi }}</p>
        </div>
    </div>

    {{-- File Scan --}}
    @if($disposisi->surat->file_scan_path)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="font-semibold text-slate-800 text-sm">File Scan Surat</p>
                <p class="text-xs text-slate-400">Berkas digital surat masuk</p>
            </div>
        </div>
        <a href="{{ asset('storage/' . $disposisi->surat->file_scan_path) }}" target="_blank"
           class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 flex items-center gap-1.5">
            <i class="fas fa-download"></i> Unduh File
        </a>
    </div>
    @endif

    {{-- Hasil Kerja --}}
    @if($disposisi->catatan_staff || $disposisi->file_hasil)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-green-200 bg-green-50 rounded-t-2xl">
            <h2 class="font-semibold text-green-800 flex items-center gap-2">
                <i class="fas fa-check-double text-green-600"></i> Hasil Kerja
            </h2>
        </div>
        <div class="p-5 space-y-3">
            @if($disposisi->catatan_staff)
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Catatan Staff</p>
                <p class="text-slate-700 text-sm italic border-l-2 border-green-400 pl-3">"{{ $disposisi->catatan_staff }}"</p>
            </div>
            @endif
            @if($disposisi->file_hasil)
            <div class="flex items-center gap-3">
                <a href="{{ asset('storage/' . $disposisi->file_hasil) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-xs font-bold rounded-xl hover:bg-green-700">
                    <i class="fas fa-download"></i> Unduh File Hasil
                </a>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- Catatan Revisi --}}
    @if($disposisi->catatan_revisi)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5">
        <h2 class="font-semibold text-red-800 flex items-center gap-2 mb-2">
            <i class="fas fa-exclamation-circle text-red-600"></i> Catatan Revisi
        </h2>
        <p class="text-sm text-red-700">{{ $disposisi->catatan_revisi }}</p>
    </div>
    @endif

    {{-- Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="font-semibold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fas fa-bolt text-blue-600"></i> Aksi
        </h2>
        <div class="flex flex-wrap gap-3">
            @if($disposisi->status == 0)
            <form action="{{ route('disposisi.terima', $disposisi->id) }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 flex items-center gap-2">
                    <i class="fas fa-check"></i> Terima Tugas
                </button>
            </form>
            @elseif($disposisi->status == 1 && auth()->id() == $disposisi->tujuan_user_id)
            <button onclick="document.getElementById('modalLapor').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-file-upload"></i> Laporkan Hasil Kerja
            </button>
            @elseif($disposisi->status == 2 && in_array(auth()->user()->role, ['admin','kabag','kasubag']))
            <button onclick="document.getElementById('modalVerifikasi').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 flex items-center gap-2">
                <i class="fas fa-check-circle"></i> Verifikasi (Approve)
            </button>
            <button onclick="document.getElementById('modalRevisi').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 flex items-center gap-2">
                <i class="fas fa-redo"></i> Minta Revisi
            </button>
            @elseif($disposisi->status == 4 && auth()->id() == $disposisi->tujuan_user_id)
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-3 text-sm flex items-start gap-2 max-w-md">
                <i class="fas fa-exclamation-circle flex-shrink-0 mt-0.5"></i>
                <span><strong>Perlu Revisi:</strong> {{ $disposisi->catatan_revisi }}</span>
            </div>
            <button onclick="document.getElementById('modalLapor').classList.remove('hidden')"
                    class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                <i class="fas fa-redo"></i> Laporkan Revisi
            </button>
            @elseif($disposisi->status == 5)
            <div class="bg-green-50 border border-green-200 text-green-700 rounded-xl p-3 text-sm flex items-center gap-2">
                <i class="fas fa-check-circle text-green-500 text-lg"></i> Disposisi berhasil diselesaikan
            </div>
            @endif

            @if($disposisi->penerima && ($disposisi->penerima->id == auth()->id() || in_array(auth()->user()->role, ['admin','kabag','kasubag'])))
            <a href="{{ route('surat-masuk.tracking', $disposisi->surat->id) }}"
               class="px-5 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 flex items-center gap-2">
                <i class="fas fa-history"></i> Lihat Tracking Surat
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Modal: Lapor Hasil --}}
<div id="modalLapor" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
        <form action="{{ route('disposisi.selesai', $disposisi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-file-upload text-blue-600"></i> Lapor Hasil Kerja
                </h3>
                <button type="button" onclick="document.getElementById('modalLapor').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Catatan Hasil Kerja <span class="text-red-500">*</span>
                    </label>
                    <textarea name="catatan_staff" rows="5" required
                              class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none">{{ old('catatan_staff') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        File Hasil <span class="text-slate-400 font-normal">(Opsional, max 5MB)</span>
                    </label>
                    <input type="file" name="file_hasil" accept=".pdf,.doc,.docx"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5">
                </div>
            </div>
            <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                <button type="button" onclick="document.getElementById('modalLapor').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Laporan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Verifikasi Approve --}}
<div id="modalVerifikasi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">
        <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status_akhir" value="Selesai">
            <div class="flex items-center justify-between p-5 border-b border-green-200 bg-green-50 rounded-t-2xl">
                <h3 class="font-bold text-green-800 flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-600"></i> Verifikasi - Approve
                </h3>
                <button type="button" onclick="document.getElementById('modalVerifikasi').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5">
                <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl p-4 text-sm mb-4">
                    <strong>Hasil kerja dinyatakan sesuai.</strong>
                    <p class="text-blue-600">Disposisi akan ditandai Selesai.</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                <button type="button" onclick="document.getElementById('modalVerifikasi').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> Approve
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Minta Revisi --}}
<div id="modalRevisi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
        <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
            @csrf
            <input type="hidden" name="status_akhir" value="Revisi">
            <div class="flex items-center justify-between p-5 border-b border-amber-200 bg-amber-50 rounded-t-2xl">
                <h3 class="font-bold text-amber-800 flex items-center gap-2">
                    <i class="fas fa-redo text-amber-600"></i> Minta Revisi
                </h3>
                <button type="button" onclick="document.getElementById('modalRevisi').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                    Catatan Revisi <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_revisi" rows="5" required
                          placeholder="Jelaskan apa yang perlu diperbaiki..."
                          class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none resize-none">{{ old('catatan_revisi') }}</textarea>
            </div>
            <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                <button type="button" onclick="document.getElementById('modalRevisi').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-5 py-2 text-sm bg-amber-500 text-white font-semibold rounded-xl hover:bg-amber-600 flex items-center gap-2">
                    <i class="fas fa-redo"></i> Kirim Catatan Revisi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
                </a>
            </div>
        </div>
    </div>

    <!-- Surat Info -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Informasi Surat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <small class="form-text text-muted">No. Agenda</small>
                            <p><strong>{{ $disposisi->surat->no_agenda }}</strong></p>
                        </div>
                        <div class="col-md-3">
                            <small class="form-text text-muted">Perihal</small>
                            <p><strong>{{ $disposisi->surat->perihal }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Asal Instansi</small>
                            <p>{{ $disposisi->surat->asal_instansi }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Diterima</small>
                            <p>{{ $disposisi->surat->tgl_diterima->format('d-m-Y') }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Status Surat</small>
                            <p>{!! $disposisi->surat->status_badge !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Disposisi Info -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Disposisi</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2">
                            <small class="form-text text-muted">Dari</small>
                            <p><strong>{{ $disposisi->pengirim->name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Ke</small>
                            <p><strong>{{ $disposisi->penerima->name ?? 'N/A' }}</strong></p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Status Disposisi</small>
                            <p>{!! $disposisi->status_badge !!}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Sifat Surat</small>
                            <p>{{ $disposisi->sifat }}</p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Diterima</small>
                            <p>
                                @if($disposisi->tanggal_diterima)
                                    {{ $disposisi->tanggal_diterima->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-muted">Belum dibaca</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-2">
                            <small class="form-text text-muted">Tgl Selesai</small>
                            <p>
                                @if($disposisi->tanggal_selesai)
                                    {{ $disposisi->tanggal_selesai->format('d-m-Y H:i') }}
                                @else
                                    <span class="text-muted">Belum selesai</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instruksi -->
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Instruksi</h5>
                </div>
                <div class="card-body">
                    <p>{{ $disposisi->instruksi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- File Scan -->
    @if($disposisi->surat->file_scan_path)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">File Scan Surat</h5>
                    </div>
                    <div class="card-body">
                        <a href="{{ asset('storage/' . $disposisi->surat->file_scan_path) }}" 
                           target="_blank" class="btn btn-sm btn-info">
                            <i class="fas fa-download"></i> Unduh File Scan
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Catatan Staff & File Hasil (Jika ada) -->
    @if($disposisi->catatan_staff || $disposisi->file_hasil)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Hasil Kerja</h5>
                    </div>
                    <div class="card-body">
                        @if($disposisi->catatan_staff)
                            <div class="mb-3">
                                <h6>Catatan:</h6>
                                <p>{{ $disposisi->catatan_staff }}</p>
                            </div>
                        @endif

                        @if($disposisi->file_hasil)
                            <div class="mb-3">
                                <h6>File Hasil:</h6>
                                <a href="{{ asset('storage/' . $disposisi->file_hasil) }}" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    <i class="fas fa-download"></i> Unduh File Hasil
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Catatan Revisi (Jika ada) -->
    @if($disposisi->catatan_revisi)
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card border-warning">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">Catatan Revisi</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $disposisi->catatan_revisi }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body">
                    @if($disposisi->status == 0)
                        <!-- Belum Dibaca -->
                        <form action="{{ route('disposisi.terima', $disposisi->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> Terima Tugas
                            </button>
                        </form>

                    @elseif($disposisi->status == 1)
                        <!-- Sedang Diproses -->
                        @if(auth()->id() == $disposisi->tujuan_user_id)
                            <button class="btn btn-primary" data-toggle="modal" data-target="#laporHasilModal">
                                <i class="fas fa-file-upload"></i> Laporkan Hasil Kerja
                            </button>
                        @endif

                    @elseif($disposisi->status == 2)
                        <!-- Menunggu Verifikasi -->
                        @if(in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']))
                            <button class="btn btn-success" data-toggle="modal" data-target="#verifikasiModal">
                                <i class="fas fa-check-circle"></i> Verifikasi (Approve)
                            </button>
                            <button class="btn btn-warning" data-toggle="modal" data-target="#revisiModal">
                                <i class="fas fa-redo"></i> Minta Revisi
                            </button>
                        @endif

                    @elseif($disposisi->status == 3)
                        <!-- Perlu Revisi -->
                        @if(auth()->id() == $disposisi->tujuan_user_id)
                            <div class="alert alert-warning mb-3">
                                <strong>Perlu Revisi:</strong> {{ $disposisi->catatan_revisi }}
                            </div>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#laporHasilModal">
                                <i class="fas fa-redo"></i> Laporkan Revisi
                            </button>
                        @endif

                    @elseif($disposisi->status == 4)
                        <!-- Selesai -->
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle"></i> Disposisi berhasil diselesaikan
                        </div>
                    @endif

                    @if($disposisi->penerima->id == auth()->id() || in_array(auth()->user()->role, ['admin', 'kabag', 'kasubag']))
                        <a href="{{ route('surat-masuk.tracking', $disposisi->surat->id) }}" class="btn btn-info">
                            <i class="fas fa-history"></i> Lihat Tracking Surat
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Lapor Hasil Kerja -->
<div class="modal fade" id="laporHasilModal" tabindex="-1" role="dialog" aria-labelledby="laporHasilLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.selesai', $disposisi->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="laporHasilLabel">Lapor Hasil Kerja</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_staff">Catatan Hasil Kerja <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('catatan_staff') is-invalid @enderror" 
                                  id="catatan_staff" name="catatan_staff" rows="5" required>{{ old('catatan_staff') }}</textarea>
                        @error('catatan_staff')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="file_hasil">File Hasil (Optional)</label>
                        <input type="file" class="form-control @error('file_hasil') is-invalid @enderror" 
                               id="file_hasil" name="file_hasil" accept=".pdf,.doc,.docx">
                        <small class="form-text text-muted">Max 5MB (PDF, DOC, DOCX)</small>
                        @error('file_hasil')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Verifikasi Approve -->
<div class="modal fade" id="verifikasiModal" tabindex="-1" role="dialog" aria-labelledby="verifikasiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_akhir" value="OK">
                <div class="modal-header">
                    <h5 class="modal-title" id="verifikasiLabel">Verifikasi - Approve</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Hasil kerja dinyatakan sesuai dengan ketentuan.</strong>
                        <p>Surat akan siap untuk naik ke Bupati.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Revisi Minta -->
<div class="modal fade" id="revisiModal" tabindex="-1" role="dialog" aria-labelledby="revisiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form action="{{ route('disposisi.verifikasi', $disposisi->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status_akhir" value="Revisi">
                <div class="modal-header">
                    <h5 class="modal-title" id="revisiLabel">Minta Revisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="catatan_revisi">Catatan Revisi <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('catatan_revisi') is-invalid @enderror" 
                                  id="catatan_revisi" name="catatan_revisi" rows="5" required>{{ old('catatan_revisi') }}</textarea>
                        @error('catatan_revisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-redo"></i> Kirim Revisi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
