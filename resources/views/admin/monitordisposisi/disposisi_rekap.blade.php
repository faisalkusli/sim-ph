@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Monitoring Disposisi</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Rekapitulasi Disposisi</span>
        </nav>
    </div>

    {{-- Filter Bar --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 flex flex-wrap gap-3">
        <div class="flex-1 min-w-48">
            <input type="text" id="searchDisposisi" placeholder="Cari perihal, staff, atau status..."
                   class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>
        <div class="w-52">
            <select id="filterStatus"
                    class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <option value="">-- Semua Status --</option>
                <option value="selesai">Selesai</option>
                <option value="proses">Proses</option>
                <option value="revisi">Perlu Revisi</option>
            </select>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm" id="tableMonitoring">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-center font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Staff Tujuan</th>
                        <th class="px-4 py-3 text-left font-semibold w-64">Perihal Surat</th>
                        <th class="px-4 py-3 text-center font-semibold">Status Surat</th>
                        <th class="px-4 py-3 text-center font-semibold">Status Disposisi</th>
                        <th class="px-4 py-3 text-left font-semibold">Instruksi</th>
                        <th class="px-4 py-3 text-center font-semibold w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($monitoring_list as $disposisi)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-center text-slate-400">
                            {{ $loop->iteration + $monitoring_list->firstItem() - 1 }}
                        </td>
                        <td class="px-4 py-3">
                            @if($disposisi->penerima)
                            <div class="font-semibold text-slate-800">{{ $disposisi->penerima->name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($disposisi->created_at)->format('d/m/Y H:i') }}</div>
                            @else
                            <span class="text-red-500 text-xs">User Tidak Ditemukan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($disposisi->surat)
                            <div class="font-semibold text-slate-800">{{ Str::limit($disposisi->surat->perihal, 50) }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $disposisi->surat->asal_instansi }}</div>
                            @else
                            <em class="text-slate-400 text-xs">Data surat terhapus</em>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($disposisi->surat)
                            @php
                                $st = $disposisi->surat->status ?? '-';
                                $stColor = match(true) {
                                    $st == 'Menunggu' => 'bg-amber-100 text-amber-700',
                                    $st == 'Disetujui' || $st == 'Diterima' => 'bg-green-100 text-green-700',
                                    $st == 'Ditolak' => 'bg-red-100 text-red-700',
                                    $st == 'Naik ke Bupati' => 'bg-slate-700 text-white',
                                    $st == 'Turun dari Bupati' => 'bg-blue-100 text-blue-700',
                                    $st == 'Disposisi' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-block {{ $stColor }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $st }}</span>
                            @if($st == 'Naik ke Bupati' && $disposisi->surat->tgl_naik_bupati)
                            <div class="text-xs text-slate-400 mt-0.5"><i class="fas fa-arrow-up"></i> {{ date('d/m', strtotime($disposisi->surat->tgl_naik_bupati)) }}</div>
                            @elseif($st == 'Turun dari Bupati' && $disposisi->surat->tgl_turun_bupati)
                            <div class="text-xs text-green-500 mt-0.5"><i class="fas fa-arrow-down"></i> {{ date('d/m', strtotime($disposisi->surat->tgl_turun_bupati)) }}</div>
                            @endif
                            @else
                            <span class="text-slate-300 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $dStatus = $disposisi->status;
                                if ($dStatus == 5 || $dStatus == 'Selesai') {
                                    $badge = 'bg-green-100 text-green-700'; $label = 'Selesai';
                                } elseif ($dStatus == 4 || $dStatus == 'Perlu Revisi') {
                                    $badge = 'bg-red-100 text-red-700'; $label = 'Perlu Revisi';
                                } elseif ($dStatus == 2 || $dStatus == 3) {
                                    $badge = 'bg-amber-100 text-amber-700'; $label = 'Tunggu Verifikasi';
                                } else {
                                    $badge = 'bg-slate-100 text-slate-600'; $label = 'Proses';
                                }
                            @endphp
                            <span class="inline-block {{ $badge }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $label }}</span>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $disposisi->updated_at->format('d/m/y') }}</div>
                        </td>
                        <td class="px-4 py-3 text-slate-500 text-xs italic">"{{ Str::limit($disposisi->instruksi, 40) }}"</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($disposisi->surat)
                                <a href="{{ route('surat-masuk.show', $disposisi->surat->id) }}"
                                   class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center hover:bg-blue-200 transition-colors"
                                   title="Detail Surat">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-inbox text-4xl block mb-2 opacity-20"></i>
                            Belum ada riwayat disposisi.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($monitoring_list->hasPages())
        <div class="p-4 border-t border-slate-100 flex justify-end">
            {{ $monitoring_list->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('searchDisposisi').addEventListener('input', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('#tableMonitoring tbody tr').forEach(function(row) {
            row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
        });
    });
    document.getElementById('filterStatus').addEventListener('change', function() {
        let val = this.value.toLowerCase();
        document.querySelectorAll('#tableMonitoring tbody tr').forEach(function(row) {
            let statusCell = row.querySelector('td:nth-child(5)');
            if (!statusCell) return;
            let status = statusCell.innerText.toLowerCase();
            row.style.display = !val || status.includes(val) ? '' : 'none';
        });
    });
</script>
@endsection
