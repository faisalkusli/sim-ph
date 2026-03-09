@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Detail Surat Masuk</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600">Surat Masuk</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">{{ $surat->no_agenda }}</span>
            </nav>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @if(in_array(auth()->user()->role, ['admin','kabag','super_admin']) && !in_array($surat->status, ['Naik ke Bupati','Turun dari Bupati','Ditolak']))
            <button onclick="document.getElementById('modalNaikBupati').classList.remove('hidden')"
                    class="flex items-center gap-2 px-3 py-2 bg-red-100 text-red-700 rounded-xl text-sm font-semibold hover:bg-red-200 transition-colors">
                <i class="fas fa-arrow-up"></i> Naik Bupati
            </button>
            @endif
            @if($surat->status == 'Naik ke Bupati' && in_array(auth()->user()->role, ['admin','kabag','super_admin']))
            <button onclick="document.getElementById('modalTurunBupati').classList.remove('hidden')"
                    class="flex items-center gap-2 px-3 py-2 bg-green-100 text-green-700 rounded-xl text-sm font-semibold hover:bg-green-200 transition-colors">
                <i class="fas fa-arrow-down"></i> Turun Bupati
            </button>
            @endif
            <a href="{{ route('surat-masuk.tracking', $surat->id) }}"
               class="flex items-center gap-2 px-3 py-2 bg-purple-100 text-purple-700 rounded-xl text-sm font-semibold hover:bg-purple-200 transition-colors">
                <i class="fas fa-history"></i> Tracking
            </a>
            <a href="{{ route('surat-masuk.cetak', $surat->id) }}" target="_blank"
               class="flex items-center gap-2 px-3 py-2 bg-amber-100 text-amber-700 rounded-xl text-sm font-semibold hover:bg-amber-200 transition-colors">
                <i class="fas fa-print"></i> Cetak Penerima
            </a>
            <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank"
               class="flex items-center gap-2 px-3 py-2 bg-indigo-100 text-indigo-700 rounded-xl text-sm font-semibold hover:bg-indigo-200 transition-colors">
                <i class="fas fa-file-alt"></i> Cetak Disposisi
            </a>
            @if(in_array(auth()->user()->role, ['admin','super_admin']))
            <a href="{{ route('surat-masuk.edit', $surat->id) }}"
               class="flex items-center gap-2 px-3 py-2 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">
                <i class="fas fa-edit"></i> Edit
            </a>
            @endif
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-times-circle text-red-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

        {{-- Left: Surat Detail --}}
        <div class="xl:col-span-2 space-y-5">

            {{-- Surat Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-envelope-fill text-blue-600"></i> Informasi Surat
                    </h2>
                    @php
                        $st = $surat->status;
                        $badgeCls = match(true) {
                            str_contains($st,'Menunggu') => 'bg-amber-100 text-amber-700',
                            in_array($st,['Siap Disposisi','Disetujui','Diterima']) => 'bg-green-100 text-green-700',
                            $st=='Ditolak' => 'bg-red-100 text-red-700',
                            $st=='Naik ke Bupati' => 'bg-slate-200 text-slate-700',
                            $st=='Turun dari Bupati' => 'bg-cyan-100 text-cyan-700',
                            str_contains($st,'Disposisi') => 'bg-blue-100 text-blue-700',
                            default => 'bg-slate-100 text-slate-600',
                        };
                    @endphp
                    <span class="inline-block {{ $badgeCls }} text-xs font-semibold px-3 py-1.5 rounded-full">
                        {{ $surat->status }}
                    </span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">No Agenda</p>
                        <p class="font-bold text-slate-800 text-base">{{ $surat->no_agenda }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Jenis Surat</p>
                        <p class="text-slate-700">{{ $surat->jenis_surat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Asal Instansi</p>
                        <p class="font-semibold text-slate-800">{{ $surat->asal_instansi }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">No Surat Pengirim</p>
                        <p class="text-slate-700">{{ $surat->no_surat_pengirim }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Tanggal Surat</p>
                        <p class="text-slate-700">{{ \Carbon\Carbon::parse($surat->tgl_surat)->isoFormat('DD MMMM YYYY') }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Tanggal Diterima</p>
                        <p class="text-slate-700">{{ \Carbon\Carbon::parse($surat->tgl_diterima)->isoFormat('DD MMMM YYYY') }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Perihal</p>
                        <p class="text-slate-700 leading-relaxed">{{ $surat->perihal }}</p>
                    </div>
                    @if($surat->alasan_tolak)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-red-400 font-semibold uppercase tracking-wide mb-1">Alasan Penolakan</p>
                        <p class="text-red-600 bg-red-50 rounded-lg p-3">{{ $surat->alasan_tolak }}</p>
                    </div>
                    @endif
                    @if($surat->no_npknd)
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">No NPKND</p>
                        <p class="text-slate-700 font-semibold">{{ $surat->no_npknd }}</p>
                    </div>
                    @endif
                    @if($surat->tgl_naik_bupati)
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Tanggal Naik Bupati</p>
                        <p class="text-slate-700">{{ \Carbon\Carbon::parse($surat->tgl_naik_bupati)->isoFormat('DD MMMM YYYY') }}</p>
                    </div>
                    @endif
                    @if($surat->tgl_turun_bupati)
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Tanggal Turun Bupati</p>
                        <p class="text-slate-700">{{ \Carbon\Carbon::parse($surat->tgl_turun_bupati)->isoFormat('DD MMMM YYYY') }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Diinput Oleh</p>
                        <p class="text-slate-700">{{ $surat->user->name ?? '-' }}</p>
                    </div>
                    @if($surat->validasi_oleh && $surat->tgl_validasi)
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-1">Info Validasi</p>
                        @if($surat->status == 'Ditolak')
                        <div class="flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2.5 text-sm">
                            <i class="fas fa-times-circle text-red-500"></i>
                            <span class="text-red-700">Ditolak oleh <strong>{{ $surat->validasi_oleh }}</strong> pada {{ \Carbon\Carbon::parse($surat->tgl_validasi)->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</span>
                        </div>
                        @else
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-4 py-2.5 text-sm">
                            <i class="fas fa-check-circle text-green-500"></i>
                            <span class="text-green-700">Divalidasi oleh <strong>{{ $surat->validasi_oleh }}</strong> pada {{ \Carbon\Carbon::parse($surat->tgl_validasi)->isoFormat('DD MMMM YYYY, HH:mm') }} WIB</span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- File Dokumen --}}
                @if($surat->file_scan_path || $surat->file_pengantar_path || $surat->file_pernyataan_path || $surat->file_lampiran_path)
                <div class="px-5 pb-5 space-y-3">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Dokumen Terlampir</p>

                    @if($surat->file_scan_path)
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <i class="fas fa-file-pdf text-red-500"></i> File Scan Surat
                            </div>
                            <a href="{{ asset('storage/' . $surat->file_scan_path) }}" target="_blank"
                               class="text-xs text-blue-600 hover:underline">Buka di tab baru</a>
                        </div>
                        <iframe src="{{ asset('storage/' . $surat->file_scan_path) }}"
                                class="w-full h-64 border-0"></iframe>
                    </div>
                    @endif

                    @if($surat->file_pengantar_path)
                    <div class="border border-blue-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 bg-blue-50 border-b border-blue-200">
                            <div class="flex items-center gap-2 text-sm font-semibold text-blue-700">
                                <i class="fas fa-file-alt text-blue-500"></i> Scan Surat Pengantar
                            </div>
                            <a href="{{ asset('storage/' . $surat->file_pengantar_path) }}" target="_blank"
                               class="text-xs text-blue-600 hover:underline">Buka di tab baru</a>
                        </div>
                        <iframe src="{{ asset('storage/' . $surat->file_pengantar_path) }}"
                                class="w-full h-64 border-0"></iframe>
                    </div>
                    @endif

                    @if($surat->file_pernyataan_path)
                    <div class="border border-purple-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 bg-purple-50 border-b border-purple-200">
                            <div class="flex items-center gap-2 text-sm font-semibold text-purple-700">
                                <i class="fas fa-file-signature text-purple-500"></i> Scan Surat Pernyataan
                            </div>
                            <a href="{{ asset('storage/' . $surat->file_pernyataan_path) }}" target="_blank"
                               class="text-xs text-purple-600 hover:underline">Buka di tab baru</a>
                        </div>
                        <iframe src="{{ asset('storage/' . $surat->file_pernyataan_path) }}"
                                class="w-full h-64 border-0"></iframe>
                    </div>
                    @endif

                    @if($surat->file_lampiran_path)
                    <div class="border border-green-200 rounded-xl overflow-hidden">
                        <div class="flex items-center justify-between px-4 py-3 bg-green-50 border-b border-green-200">
                            <div class="flex items-center gap-2 text-sm font-semibold text-green-700">
                                <i class="fas fa-folder-open text-green-500"></i> Dokumen Lampiran / Pendukung
                            </div>
                            <a href="{{ asset('storage/' . $surat->file_lampiran_path) }}" target="_blank"
                               class="text-xs text-green-600 hover:underline">Buka di tab baru</a>
                        </div>
                        <iframe src="{{ asset('storage/' . $surat->file_lampiran_path) }}"
                                class="w-full h-64 border-0"></iframe>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Workflow: Validasi Awal (Kabag/Admin) --}}
            @if($surat->status == 'Menunggu Validasi' && in_array(auth()->user()->role, ['kabag','admin','super_admin']))

            {{-- Hidden forms --}}
            <form id="formSetuju" action="{{ route('surat-masuk.validasi', $surat->id) }}" method="POST" class="hidden">
                @csrf @method('PATCH')
                <input type="hidden" name="status_verifikasi" value="Setuju">
            </form>

            <div class="bg-white rounded-2xl shadow-sm border-2 border-amber-300">
                <div class="px-5 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-amber-200 rounded-t-2xl flex items-center gap-3">
                    <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-check text-amber-600"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-amber-800 text-sm">Validasi Surat Diperlukan</h2>
                        <p class="text-xs text-amber-600">Surat ini menunggu persetujuan Anda</p>
                    </div>
                    <span class="ml-auto inline-flex items-center gap-1.5 bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1.5 rounded-full border border-amber-200">
                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                        Menunggu Validasi
                    </span>
                </div>
                <div class="p-5">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 mb-5 grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-0.5">No Agenda</p>
                            <p class="font-bold text-slate-800">{{ $surat->no_agenda }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Jenis Surat</p>
                            <p class="text-slate-700">{{ $surat->jenis_surat ?? '-' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wide mb-0.5">Perihal</p>
                            <p class="text-slate-700">{{ Str::limit($surat->perihal, 120) }}</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="button"
                                onclick="document.getElementById('modalTolakShow').classList.remove('hidden')"
                                class="flex-1 py-2.5 bg-white border-2 border-red-300 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <i class="fas fa-times-circle"></i> Tolak Surat
                        </button>
                        <button type="button"
                                onclick="document.getElementById('modalSetujuShow').classList.remove('hidden')"
                                class="flex-1 py-2.5 bg-green-600 text-white text-sm font-semibold rounded-xl hover:bg-green-700 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-green-200">
                            <i class="fas fa-check-circle"></i> Setujui & Siap Disposisi
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Konfirmasi Setuju --}}
            <div id="modalSetujuShow"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
                 onclick="if(event.target===this)this.classList.add('hidden')">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden animate-pop" onclick="event.stopPropagation()">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-50 px-6 pt-8 pb-5 text-center border-b border-green-100">
                        <div class="w-16 h-16 bg-green-100 border-4 border-green-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Konfirmasi Validasi</h3>
                        <p class="text-sm text-slate-500 mt-1">Surat akan disetujui dan status berubah menjadi <span class="font-semibold text-green-600">Siap Disposisi</span></p>
                    </div>
                    <div class="flex gap-3 px-6 py-5">
                        <button type="button"
                                onclick="document.getElementById('modalSetujuShow').classList.add('hidden')"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                            Batal
                        </button>
                        <button type="button" onclick="document.getElementById('formSetuju').submit()"
                                class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-green-600 text-white hover:bg-green-700 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-green-200">
                            <i class="fas fa-check"></i> Ya, Validasi
                        </button>
                    </div>
                </div>
            </div>

            {{-- Modal Tolak --}}
            <div id="modalTolakShow"
                 class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
                 onclick="if(event.target===this)this.classList.add('hidden')">
                <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden animate-pop" onclick="event.stopPropagation()">
                    <div class="bg-gradient-to-br from-red-50 to-rose-50 px-6 pt-8 pb-5 text-center border-b border-red-100">
                        <div class="w-16 h-16 bg-red-100 border-4 border-red-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Tolak Surat Ini?</h3>
                        <p class="text-sm text-slate-500 mt-1">Berikan alasan penolakan yang jelas untuk pengirim</p>
                    </div>
                    <form action="{{ route('surat-masuk.validasi', $surat->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status_verifikasi" value="Tolak">
                        <div class="px-6 py-4">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="alasan_tolak" rows="3" required
                                      placeholder="Tuliskan alasan penolakan..."
                                      class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-red-400 focus:border-red-400 outline-none resize-none"></textarea>
                        </div>
                        <div class="flex gap-3 px-6 pb-6">
                            <button type="button"
                                    onclick="document.getElementById('modalTolakShow').classList.add('hidden')"
                                    class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit"
                                    class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-600 text-white hover:bg-red-700 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-red-200">
                                <i class="fas fa-times"></i> Ya, Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @endif

            {{-- Workflow: Upload Hasil Kerja (Staf) --}}
            @if(in_array(auth()->user()->role, ['staf','kasubag']) && $surat->disposisi->where('tujuan_user_id', auth()->id())->count())
            @php $myDisposisi = $surat->disposisi->where('tujuan_user_id', auth()->id())->first(); @endphp
            @if($myDisposisi && in_array($myDisposisi->status, [1, 2]))
            <div class="bg-white rounded-2xl shadow-sm border border-purple-200">
                <div class="p-5 border-b border-purple-100 bg-purple-50 rounded-t-2xl">
                    <h2 class="font-semibold text-purple-800 flex items-center gap-2">
                        <i class="fas fa-upload text-purple-600"></i> Upload Hasil Kerja
                    </h2>
                </div>
                <div class="p-5">
                    <form action="{{ route('surat.upload-hasil', $surat->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan</label>
                                <textarea name="catatan_staff" rows="2"
                                          class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none resize-none"
                                          placeholder="Catatan hasil kerja...">{{ old('catatan_staff') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-1.5">File Hasil <span class="text-slate-400 font-normal">(PDF/JPG/PNG)</span></label>
                                <input type="file" name="file_hasil" accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <button type="submit"
                                    class="w-full py-2.5 bg-purple-600 text-white text-sm font-semibold rounded-xl hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-cloud-upload-alt"></i> Upload & Selesaikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            @endif

            {{-- Disposisi History --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-share-alt text-blue-600"></i> Riwayat Disposisi
                        <span class="ml-1 bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $surat->disposisi->count() }}
                        </span>
                    </h2>
                    @if($surat->status == 'Siap Disposisi' && $listTujuan->count() > 0)
                    <button onclick="document.getElementById('modalDisposisi').classList.remove('hidden')"
                            class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors">
                        <i class="fas fa-share"></i> Buat Disposisi
                    </button>
                    @endif
                </div>

                @if($surat->disposisi->count())
                <div class="divide-y divide-slate-100">
                    @foreach($surat->disposisi->sortBy('created_at') as $d)
                    <div class="p-5 flex gap-4">
                        <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0 font-bold text-sm">
                            {{ strtoupper(substr($d->pengirim->name ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2 flex-wrap">
                                <div>
                                    <span class="font-semibold text-slate-800 text-sm">{{ $d->pengirim->name ?? '-' }}</span>
                                    <i class="fas fa-arrow-right text-xs text-slate-400 mx-1"></i>
                                    <span class="font-semibold text-blue-700 text-sm">{{ $d->penerima->name ?? '-' }}</span>
                                    <span class="text-xs text-slate-400 ml-1">({{ $d->penerima->role ?? '-' }})</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $ds = $d->status_label ?? 'Unknown';
                                        $dBadge = match($d->status) {
                                            0 => 'bg-amber-100 text-amber-700',
                                            1 => 'bg-blue-100 text-blue-700',
                                            2 => 'bg-purple-100 text-purple-700',
                                            3 => 'bg-green-100 text-green-700',
                                            4 => 'bg-red-100 text-red-700',
                                            default => 'bg-slate-100 text-slate-600',
                                        };
                                    @endphp
                                    <span class="inline-block {{ $dBadge }} text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ $ds }}
                                    </span>
                                    <span class="text-xs text-slate-400">{{ $d->created_at->format('d/m/y H:i') }}</span>
                                </div>
                            </div>
                            @if($d->instruksi)
                            <p class="text-sm text-slate-600 mt-1.5 bg-slate-50 rounded-lg px-3 py-2">
                                <i class="fas fa-quote-left text-xs text-slate-300 mr-1"></i>{{ $d->instruksi }}
                            </p>
                            @endif
                            @if($d->catatan_staff)
                            <p class="text-sm text-purple-600 mt-1.5 bg-purple-50 rounded-lg px-3 py-2">
                                <i class="fas fa-reply text-xs mr-1"></i>{{ $d->catatan_staff }}
                            </p>
                            @endif
                            @if($d->file_hasil)
                            <a href="{{ asset('storage/' . $d->file_hasil) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 mt-2 text-xs text-blue-600 hover:underline">
                                <i class="fas fa-paperclip"></i> Lihat file hasil
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center text-slate-400">
                    <i class="fas fa-share-alt text-3xl block mb-2 opacity-30"></i>
                    <p class="text-sm">Belum ada disposisi untuk surat ini.</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Right: Sidebar Info --}}
        <div class="space-y-5">
            {{-- Input Info --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="font-semibold text-slate-700 mb-4 flex items-center gap-2">
                    <i class="fas fa-info-circle text-slate-400"></i> Info Input
                </h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Diinput oleh</span>
                        <span class="font-semibold text-slate-700">{{ $surat->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tanggal Input</span>
                        <span class="text-slate-700">{{ $surat->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Terakhir Update</span>
                        <span class="text-slate-700">{{ $surat->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <h3 class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-link text-slate-400"></i> Aksi Cepat
                </h3>
                <div class="space-y-2">
                    <a href="{{ route('surat-masuk.tracking', $surat->id) }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 text-purple-700 hover:bg-purple-100 transition-colors text-sm font-medium">
                        <i class="fas fa-history"></i> Lihat Tracking Surat
                    </a>
                    @if($surat->disposisi->count())
                    <a href="{{ route('surat.cetak', $surat->id) }}" target="_blank"
                       class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 text-blue-700 hover:bg-blue-100 transition-colors text-sm font-medium">
                        <i class="fas fa-print"></i> Cetak Lembar Disposisi
                    </a>
                    @endif
                    <a href="{{ route('surat-masuk.index') }}"
                       class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 text-slate-700 hover:bg-slate-100 transition-colors text-sm font-medium">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Buat Disposisi --}}
<div id="modalDisposisi" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg" onclick="event.stopPropagation()">
        <form action="{{ route('disposisi.store') }}" method="POST">
            @csrf
            <input type="hidden" name="surat_masuk_id" value="{{ $surat->id }}">
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-share text-blue-600"></i> Buat Disposisi
                </h3>
                <button type="button" onclick="document.getElementById('modalDisposisi').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tujuan Disposisi <span class="text-red-500">*</span></label>
                    <select name="tujuan_user_id" required
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih Tujuan --</option>
                        @foreach($listTujuan as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ ucfirst($user->role) }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sifat Surat</label>
                    <select name="sifat"
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="Biasa">Biasa</option>
                        <option value="Segera">Segera</option>
                        <option value="Sangat Segera">Sangat Segera</option>
                        <option value="Rahasia">Rahasia</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instruksi <span class="text-red-500">*</span></label>
                    <textarea name="instruksi" rows="3" required placeholder="Tuliskan instruksi disposisi..."
                              class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-5 pb-5 border-t border-slate-100 pt-4">
                <button type="button" onclick="document.getElementById('modalDisposisi').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit"
                        class="px-5 py-2 text-sm bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Disposisi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Naik Bupati --}}
<div id="modalNaikBupati" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" onclick="event.stopPropagation()">
        <form action="{{ route('surat.naik_bupati', $surat->id) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-file-signature text-red-500"></i> Naik ke Bupati
                </h3>
                <button type="button" onclick="document.getElementById('modalNaikBupati').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor NPKND <span class="text-red-500">*</span></label>
                    <input type="text" name="no_npknd" value="{{ old('no_npknd', $surat->no_npknd) }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Naik <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_naik_bupati" value="{{ date('Y-m-d') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 pb-5">
                <button type="button" onclick="document.getElementById('modalNaikBupati').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700">
                    Proses Naik
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Turun Bupati --}}
<div id="modalTurunBupati" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" onclick="event.stopPropagation()">
        <form action="{{ route('surat.turun_bupati', $surat->id) }}" method="POST">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-check-double text-green-500"></i> Turun dari Bupati
                </h3>
                <button type="button" onclick="document.getElementById('modalTurunBupati').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-3">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xs text-slate-500 font-semibold uppercase mb-1">No NPKND</p>
                    <p class="text-lg font-bold text-slate-800">{{ $surat->no_npknd ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Turun <span class="text-red-500">*</span></label>
                    <input type="date" name="tgl_turun_bupati" value="{{ date('Y-m-d') }}" required
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 pb-5">
                <button type="button" onclick="document.getElementById('modalTurunBupati').classList.add('hidden')"
                        class="px-4 py-2 text-sm border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
