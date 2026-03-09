@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Monitoring Disposisi</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Monitoring</span>
        </nav>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-blue-700 flex items-center gap-2">
                <i class="fas fa-chart-line text-blue-600"></i> Pantauan Progres Disposisi
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Staff Tujuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-64">Perihal Surat</th>
                        <th class="px-4 py-3 text-center font-semibold">Posisi / Status Surat</th>
                        <th class="px-4 py-3 text-center font-semibold">Status Pengerjaan</th>
                        <th class="px-4 py-3 text-left font-semibold">Catatan Kabag</th>
                        <th class="px-4 py-3 text-center font-semibold w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($monitoring_list as $m)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-center text-slate-400">{{ $loop->iteration }}</td>

                        <td class="px-4 py-3">
                            <div class="font-semibold text-slate-800">{{ $m->penerima->name ?? '-' }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $m->created_at->format('d/m/Y') }}</div>
                        </td>

                        <td class="px-4 py-3">
                            <div class="text-slate-700">{{ $m->surat->perihal ?? '-' }}</div>
                            @if($m->catatan)
                            <div class="text-xs text-blue-600 italic mt-0.5">"{{ Str::limit($m->catatan, 50) }}"</div>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if($m->surat)
                            @php
                                $st = $m->surat->status_terakhir ?? $m->surat->status ?? '-';
                                $stColor = match(true) {
                                    $st == 'Baru' => 'bg-blue-100 text-blue-700',
                                    $st == 'Didisposisikan' => 'bg-purple-100 text-purple-700',
                                    str_contains($st, 'Dikerjakan') => 'bg-amber-100 text-amber-700',
                                    str_contains($st, 'Selesai') => 'bg-green-100 text-green-700',
                                    str_contains($st, 'Bupati') => 'bg-slate-700 text-white',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-block {{ $stColor }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $st }}</span>
                            @else
                            <span class="text-red-500 text-xs">Data Surat Terhapus</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            @if(($m->status_disposisi ?? null) == 'Selesai')
                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="fas fa-check-circle"></i> Selesai (ACC)
                            </span>
                            @elseif(($m->status_disposisi ?? null) == 'Revisi')
                            <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="fas fa-exclamation-circle"></i> Perlu Revisi
                            </span>
                            @elseif(!empty($m->file_laporan))
                            <span class="inline-flex items-center gap-1 bg-amber-100 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="fas fa-clock"></i> Menunggu ACC
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 bg-slate-100 text-slate-600 text-xs font-semibold px-2.5 py-1 rounded-full">
                                <i class="fas fa-spinner"></i> Proses Staff
                            </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-slate-600 text-sm">
                            @if(!empty($m->catatan_kabag))
                            <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs">{{ $m->catatan_kabag }}</div>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('surat-masuk.show', $m->surat_masuk_id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 text-blue-600 text-xs font-semibold rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-chart-line text-4xl block mb-2 opacity-20"></i>
                            Belum ada data disposisi untuk dimonitor.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
