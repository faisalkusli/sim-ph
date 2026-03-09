@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Surat Masuk</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Surat Masuk</span>
            </nav>
        </div>
        @if(in_array(auth()->user()->role, ['admin','operator','tamu']))
        <a href="{{ route('surat-masuk.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
            <i class="fas fa-plus"></i> Input Surat Baru
        </a>
        @endif
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif
    @if(session('warning'))
    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-exclamation-triangle text-amber-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('warning') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-times-circle text-red-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('error') }}</span>
    </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">

        {{-- Search & Filters --}}
        <div class="p-4 border-b border-slate-100">
            <form action="{{ route('surat-masuk.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-48 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="cari"
                           class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="Cari No Surat / Perihal / Instansi..."
                           value="{{ request('cari') }}">
                </div>
                <select name="jenis"
                        class="py-2.5 px-3 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white text-slate-600">
                    <option value="">-- Semua Jenis --</option>
                    @foreach(['Peraturan Bupati','SK Bupati','Surat Undangan','Surat Tembusan','Surat Lainnya'] as $j)
                    <option value="{{ $j }}" {{ request('jenis') == $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
                <button type="submit"
                        class="px-4 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900 transition-colors">
                    Cari
                </button>
                @if(request('cari') || request('jenis'))
                <a href="{{ route('surat-masuk.index') }}"
                   class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200 transition-colors">
                    Reset
                </a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Info Agenda</th>
                        <th class="px-4 py-3 text-left font-semibold">Asal & Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Jenis Surat</th>
                        <th class="px-4 py-3 text-left font-semibold">Perihal</th>
                        <th class="px-4 py-3 text-center font-semibold w-20">File</th>
                        <th class="px-4 py-3 text-center font-semibold w-36">Status</th>
                        <th class="px-4 py-3 text-center font-semibold w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surat_masuk as $surat)
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- No --}}
                        <td class="px-4 py-3 text-slate-400 text-center">
                            {{ $loop->iteration + $surat_masuk->firstItem() - 1 }}
                        </td>

                        {{-- Agenda --}}
                        <td class="px-4 py-3">
                            <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $surat->no_agenda }}
                            </span>
                            <div class="text-xs text-slate-400 mt-1">
                                Diterima: {{ \Carbon\Carbon::parse($surat->tgl_diterima)->format('d/m/Y') }}
                            </div>
                        </td>

                        {{-- Asal --}}
                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $surat->asal_instansi }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">No: {{ $surat->no_surat_pengirim }}</div>
                            <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($surat->tgl_surat)->format('d M Y') }}</div>
                        </td>

                        {{-- Jenis Surat --}}
                        <td class="px-4 py-3">
                            @if($surat->jenis_surat)
                            @php
                                $jenisCls = match($surat->jenis_surat) {
                                    'Peraturan Bupati' => 'bg-red-100 text-red-700',
                                    'SK Bupati'        => 'bg-orange-100 text-orange-700',
                                    'Surat Undangan'   => 'bg-blue-100 text-blue-700',
                                    'Surat Tembusan'   => 'bg-purple-100 text-purple-700',
                                    default            => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-block {{ $jenisCls }} text-xs font-semibold px-2.5 py-1 rounded-full whitespace-nowrap">
                                {{ $surat->jenis_surat }}
                            </span>
                            @else
                            <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Perihal --}}
                        <td class="px-4 py-3 text-slate-700 max-w-xs">
                            {{ Str::limit($surat->perihal, 80) }}
                        </td>

                        {{-- File --}}
                        <td class="px-4 py-3 text-center">
                            @if($surat->file_scan_path)
                            <button onclick="document.getElementById('previewModal{{ $surat->id }}').classList.remove('hidden')"
                                    class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mx-auto hover:bg-red-200 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-4 py-3 text-center">
                            @php
                                $st = $surat->status;
                                $badgeCls = match(true) {
                                    str_contains($st,'Menunggu') => 'bg-amber-100 text-amber-700',
                                    in_array($st,['Siap Disposisi','Disetujui','Diterima']) => 'bg-green-100 text-green-700',
                                    $st=='Ditolak' => 'bg-red-100 text-red-700',
                                    str_contains($st,'Naik') => 'bg-slate-200 text-slate-700',
                                    str_contains($st,'Turun') => 'bg-cyan-100 text-cyan-700',
                                    str_contains($st,'Selesai') => 'bg-emerald-100 text-emerald-700',
                                    str_contains($st,'Revisi') => 'bg-yellow-100 text-yellow-700',
                                    str_contains($st,'Diproses') => 'bg-indigo-100 text-indigo-700',
                                    str_contains($st,'Disposisi') => 'bg-blue-100 text-blue-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-block {{ $badgeCls }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ $st }}
                            </span>
                            {{-- Validasi info --}}
                            @if($surat->validasi_oleh && $surat->tgl_validasi)
                            <div class="text-xs mt-1 {{ $st == 'Ditolak' ? 'text-red-500' : 'text-green-600' }}">
                                <i class="fas fa-user-check text-[10px]"></i>
                                {{ $surat->validasi_oleh }}<br>
                                <span class="text-slate-400">{{ \Carbon\Carbon::parse($surat->tgl_validasi)->format('d/m/Y H:i') }}</span>
                            </div>
                            @endif
                            @if(str_contains($st,'Naik') && $surat->tgl_naik_bupati)
                            <div class="text-xs text-slate-500 mt-1">
                                <i class="fas fa-arrow-up"></i> {{ \Carbon\Carbon::parse($surat->tgl_naik_bupati)->format('d/m/y') }}
                                @if($surat->no_npknd)
                                <br><small class="italic">{{ $surat->no_npknd }}</small>
                                @endif
                            </div>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-1 flex-wrap">

                                {{-- Cetak (Disetujui) --}}
                                @if($surat->status == 'Disetujui')
                                <a href="{{ route('surat-masuk.cetak', $surat->id) }}" target="_blank"
                                   class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center hover:bg-amber-600 transition-colors"
                                   title="Cetak">
                                    <i class="fas fa-print text-xs"></i>
                                </a>
                                @endif

                                {{-- Dropdown Opsi --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false"
                                            class="px-2.5 py-1.5 bg-slate-100 text-slate-700 rounded-lg text-xs font-medium hover:bg-slate-200 transition-colors flex items-center gap-1">
                                        Opsi <i class="bi bi-chevron-down text-xs"></i>
                                    </button>
                                    <div x-show="open" x-cloak x-transition
                                         class="absolute right-0 mt-1 w-44 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-10">
                                        <a href="{{ route('surat-masuk.show', $surat->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                            <i class="fas fa-eye text-blue-500"></i> Detail & Disposisi
                                        </a>
                                        <a href="{{ route('surat-masuk.tracking', $surat->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                            <i class="fas fa-history text-purple-500"></i> Tracking
                                        </a>

                                        @if(in_array(auth()->user()->role, ['admin','kabag','super_admin']))
                                        <div class="border-t border-slate-100 my-1"></div>
                                        @if(!in_array($surat->status, ['Naik ke Bupati','Turun dari Bupati','Ditolak']))
                                        <button type="button"
                                                onclick="document.getElementById('modalNaik{{ $surat->id }}').classList.remove('hidden')"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 w-full text-left">
                                            <i class="fas fa-arrow-circle-up text-red-500"></i> Naik ke Bupati
                                        </button>
                                        @endif
                                        @if($surat->status == 'Naik ke Bupati')
                                        <button type="button"
                                                onclick="document.getElementById('modalTurun{{ $surat->id }}').classList.remove('hidden')"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 w-full text-left">
                                            <i class="fas fa-arrow-circle-down text-green-500"></i> Turun dari Bupati
                                        </button>
                                        @endif
                                        @endif

                                        @if(in_array(auth()->user()->role, ['admin','super_admin']))
                                        <div class="border-t border-slate-100 my-1"></div>
                                        <a href="{{ route('surat-masuk.edit', $surat->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                                            <i class="fas fa-edit text-amber-500"></i> Edit Data
                                        </a>
                                        <button type="button"
                                                onclick="document.getElementById('modalHapus{{ $surat->id }}').classList.remove('hidden')"
                                                class="flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 w-full text-left">
                                            <i class="fas fa-trash"></i> Hapus Surat
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                    {{-- Preview Modal --}}
                    @if($surat->file_scan_path)
                    <div id="previewModal{{ $surat->id }}"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                         onclick="if(event.target===this)this.classList.add('hidden')">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl h-[85vh] flex flex-col" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
                                <span class="font-semibold text-slate-700">File Surat — {{ $surat->no_agenda }}</span>
                                <button onclick="document.getElementById('previewModal{{ $surat->id }}').classList.add('hidden')"
                                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                            </div>
                            <div class="flex-1 p-2">
                                <iframe src="{{ asset('storage/' . $surat->file_scan_path) }}" class="w-full h-full rounded-xl border-0"></iframe>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Modal Naik Bupati --}}
                    <div id="modalNaik{{ $surat->id }}"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                         onclick="if(event.target===this)this.classList.add('hidden')">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" onclick="event.stopPropagation()">
                            <form action="{{ route('surat.naik_bupati', $surat->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-file-signature text-red-500"></i> Naik ke Bupati
                                    </h3>
                                    <button type="button" onclick="document.getElementById('modalNaik{{ $surat->id }}').classList.add('hidden')"
                                            class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                                </div>
                                <div class="p-5 space-y-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Nomor NPKND <span class="text-red-500">*</span></label>
                                        <input type="text" name="no_npknd" required placeholder="Nomor NPKND"
                                               class="w-full border-slate-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Naik <span class="text-red-500">*</span></label>
                                        <input type="date" name="tgl_naik_bupati" value="{{ date('Y-m-d') }}" required
                                               class="w-full border-slate-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 px-5 pb-5">
                                    <button type="button" onclick="document.getElementById('modalNaik{{ $surat->id }}').classList.add('hidden')"
                                            class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">
                                        Proses Naik
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Turun Bupati --}}
                    <div id="modalTurun{{ $surat->id }}"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
                         onclick="if(event.target===this)this.classList.add('hidden')">
                        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm" onclick="event.stopPropagation()">
                            <form action="{{ route('surat.turun_bupati', $surat->id) }}" method="POST">
                                @csrf
                                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                        <i class="fas fa-check-double text-green-500"></i> Turun dari Bupati
                                    </h3>
                                    <button type="button" onclick="document.getElementById('modalTurun{{ $surat->id }}').classList.add('hidden')"
                                            class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
                                </div>
                                <div class="p-5 space-y-3">
                                    <div class="bg-slate-50 rounded-xl p-3">
                                        <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider mb-1">Nomor NPKND</p>
                                        <p class="text-lg font-bold text-slate-800">{{ $surat->no_npknd ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-1">Tanggal Turun <span class="text-red-500">*</span></label>
                                        <input type="date" name="tgl_turun_bupati" value="{{ date('Y-m-d') }}" required
                                               class="w-full border-slate-300 rounded-xl text-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-2 px-5 pb-5">
                                    <button type="button" onclick="document.getElementById('modalTurun{{ $surat->id }}').classList.add('hidden')"
                                            class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
                                        Simpan & Selesai
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- Modal Konfirmasi Hapus --}}
                    <form id="formHapus{{ $surat->id }}" action="{{ route('surat-masuk.destroy', $surat->id) }}" method="POST" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                    <div id="modalHapus{{ $surat->id }}"
                         class="hidden fixed inset-0 z-50 flex items-center justify-center p-4"
                         onclick="if(event.target===this)this.classList.add('hidden')">
                        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
                        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden" onclick="event.stopPropagation()">
                            <div class="bg-gradient-to-br from-red-50 to-rose-50 px-6 pt-8 pb-5 text-center border-b border-red-100">
                                <div class="w-16 h-16 bg-red-100 border-4 border-red-200 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                                    <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Hapus Surat?</h3>
                                <p class="text-sm text-slate-500 mt-1">Tindakan ini <span class="font-semibold text-red-600">tidak dapat dibatalkan</span></p>
                            </div>
                            <div class="px-6 py-4">
                                <div class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-3">
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="bi bi-envelope-fill text-blue-500 text-xs"></i>
                                        <span class="text-sm font-bold text-slate-700">{{ $surat->no_agenda }}</span>
                                    </div>
                                    <p class="text-xs text-slate-500">{{ $surat->asal_instansi }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ Str::limit($surat->perihal, 55) }}</p>
                                </div>
                                <div class="flex items-start gap-2 mt-3 bg-red-50 border border-red-100 rounded-xl px-3 py-2.5">
                                    <i class="fas fa-exclamation-triangle text-red-400 text-xs mt-0.5 flex-shrink-0"></i>
                                    <p class="text-xs text-red-600">Semua data termasuk file scan, lampiran, dan riwayat tracking akan dihapus permanen.</p>
                                </div>
                            </div>
                            <div class="flex gap-3 px-6 pb-6">
                                <button type="button"
                                        onclick="document.getElementById('modalHapus{{ $surat->id }}').classList.add('hidden')"
                                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                                    Batal
                                </button>
                                <button type="button"
                                        onclick="document.getElementById('formHapus{{ $surat->id }}').submit()"
                                        class="flex-1 px-4 py-2.5 text-sm font-semibold rounded-xl bg-red-600 text-white hover:bg-red-700 active:bg-red-800 transition-colors flex items-center justify-center gap-2 shadow-sm shadow-red-200">
                                    <i class="fas fa-trash"></i> Ya, Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            <i class="bi bi-envelope text-4xl block mb-2 opacity-30"></i>
                            Data surat masuk tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-100 flex justify-end">
            {{ $surat_masuk->links() }}
        </div>
    </div>
</div>
@endsection
