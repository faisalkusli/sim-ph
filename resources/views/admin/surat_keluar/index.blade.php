@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Surat Keluar</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Surat Keluar</span>
            </nav>
        </div>
        <a href="{{ route('surat-keluar.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors shadow-sm">
            <i class="fas fa-plus"></i> Catat Surat Keluar
        </a>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        {{-- Search --}}
        <div class="p-4 border-b border-slate-100">
            <form action="{{ route('surat-keluar.index') }}" method="GET" class="flex items-center gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="cari"
                           class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="Cari Perihal / No Surat / Tujuan..."
                           value="{{ request('cari') }}">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900">Cari</button>
                @if(request('cari'))
                <a href="{{ route('surat-keluar.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">No Surat</th>
                        <th class="px-4 py-3 text-left font-semibold">Tujuan</th>
                        <th class="px-4 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-4 py-3 text-left font-semibold">Perihal</th>
                        <th class="px-4 py-3 text-center font-semibold w-20">File</th>
                        <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($surat_keluar as $key => $s)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-slate-400 text-center">{{ $key + 1 }}</td>
                        <td class="px-4 py-3">
                            <span class="font-semibold text-slate-800">{{ $s->no_surat }}</span>
                        </td>
                        <td class="px-4 py-3 text-slate-700">{{ $s->tujuan_surat }}</td>
                        <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                            {{ date('d M Y', strtotime($s->tgl_surat)) }}
                            @if($s->tgl_kirim)
                            <div class="text-xs text-slate-400">Kirim: {{ date('d M Y', strtotime($s->tgl_kirim)) }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $s->perihal }}
                            @if($s->suratMasuk)
                            <div class="mt-1">
                                <span class="inline-block bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                    Balasan Agenda: {{ $s->suratMasuk->no_agenda }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($s->file_arsip)
                            <a href="{{ asset('storage/' . $s->file_arsip) }}" target="_blank"
                               class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mx-auto hover:bg-red-200 transition-colors">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <form action="{{ route('surat-keluar.destroy', $s->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus surat keluar ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center mx-auto hover:bg-red-200 transition-colors">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            <i class="bi bi-send text-4xl block mb-2 opacity-30"></i>
                            Belum ada data surat keluar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div
@endsection