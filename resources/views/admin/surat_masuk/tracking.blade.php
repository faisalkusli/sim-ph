@extends('layouts.app')

@section('title', 'Tracking Surat - ' . $surat->no_agenda)

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Riwayat Surat: {{ $surat->no_agenda }}</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <a href="{{ route('surat-masuk.index') }}" class="hover:text-blue-600">Surat Masuk</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Tracking</span>
            </nav>
        </div>
        <a href="{{ route('surat-masuk.show', $surat->id) }}"
           class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- Surat Info Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 bg-blue-50 rounded-t-2xl">
            <h2 class="font-semibold text-blue-800 flex items-center gap-2">
                <i class="fas fa-envelope text-blue-600"></i> Informasi Surat
            </h2>
        </div>
        <div class="p-5 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">No. Agenda</p>
                <p class="font-bold text-slate-800">{{ $surat->no_agenda }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Perihal</p>
                <p class="text-slate-700">{{ Str::limit($surat->perihal, 50) }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Asal Instansi</p>
                <p class="text-slate-700">{{ $surat->asal_instansi }}</p>
            </div>
            <div>
                <p class="text-xs text-slate-400 font-semibold uppercase mb-1">Status Saat Ini</p>
                @php
                    $st = $surat->status ?? $surat->status_terakhir ?? '-';
                    $stColor = match(true) {
                        str_contains($st,'Baru') || str_contains($st,'Diterima') => 'bg-blue-100 text-blue-700',
                        str_contains($st,'Disposisi') => 'bg-purple-100 text-purple-700',
                        str_contains($st,'Dikerjakan') => 'bg-amber-100 text-amber-700',
                        str_contains($st,'Selesai') => 'bg-green-100 text-green-700',
                        str_contains($st,'Bupati') => 'bg-slate-700 text-white',
                        str_contains($st,'Ditolak') => 'bg-red-100 text-red-700',
                        default => 'bg-slate-100 text-slate-600',
                    };
                @endphp
                <span class="inline-block {{ $stColor }} text-xs font-semibold px-2.5 py-1 rounded-full">{{ $st }}</span>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 bg-blue-600 rounded-t-2xl">
            <h2 class="font-semibold text-white flex items-center gap-2">
                <i class="fas fa-history"></i> Timeline Riwayat Proses
                <span class="bg-white/20 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $tracking->count() }}</span>
            </h2>
        </div>
        <div class="p-5">
            @if($tracking->count() > 0)
            <div class="relative">
                {{-- Vertical line --}}
                <div class="absolute left-5 top-0 bottom-0 w-0.5 bg-slate-200"></div>

                <div class="space-y-6">
                    @foreach($tracking as $log)
                    @php
                        $tColor = match(true) {
                            str_contains($log->status_log, 'Baru') || str_contains($log->status_log, 'Diterima') => 'bg-blue-600',
                            str_contains($log->status_log, 'Disposisi') => 'bg-purple-600',
                            str_contains($log->status_log, 'Dikerjakan') => 'bg-amber-500',
                            str_contains($log->status_log, 'Selesai') => 'bg-green-600',
                            str_contains($log->status_log, 'Bupati') => 'bg-slate-700',
                            str_contains($log->status_log, 'Ditolak') || str_contains($log->status_log, 'Revisi') => 'bg-red-600',
                            default => 'bg-slate-400',
                        };
                        $tIcon = match(true) {
                            str_contains($log->status_log, 'Baru') || str_contains($log->status_log, 'Diterima') => 'fa-envelope',
                            str_contains($log->status_log, 'Disposisi') => 'fa-paper-plane',
                            str_contains($log->status_log, 'Dikerjakan') => 'fa-spinner',
                            str_contains($log->status_log, 'Selesai') => 'fa-check-circle',
                            str_contains($log->status_log, 'Bupati') => 'fa-star',
                            str_contains($log->status_log, 'Ditolak') || str_contains($log->status_log, 'Revisi') => 'fa-times-circle',
                            default => 'fa-circle',
                        };
                    @endphp
                    <div class="flex gap-4 pl-2">
                        {{-- Dot --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-7 h-7 {{ $tColor }} rounded-full flex items-center justify-center shadow-sm border-2 border-white z-10 relative">
                                <i class="fas {{ $tIcon }} text-white text-xs"></i>
                            </div>
                        </div>
                        {{-- Content --}}
                        <div class="flex-1 pb-2">
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                                <h3 class="font-bold text-slate-800 text-sm">{{ $log->status_log }}</h3>
                                <div class="flex items-center gap-4 mt-1 text-xs text-slate-400">
                                    <span><i class="far fa-calendar-alt mr-1"></i>{{ $log->tgl_status->format('d M Y, H:i') }}</span>
                                    <span><i class="fas fa-user-circle mr-1"></i>{{ $log->user->name ?? '-' }}
                                        @if(isset($log->user->role)) ({{ ucfirst($log->user->role) }})@endif
                                    </span>
                                </div>
                                @if($log->catatan)
                                <p class="mt-2 text-sm text-slate-600 italic border-l-2 border-slate-300 pl-3">"{{ $log->catatan }}"</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="bg-blue-50 border border-blue-200 text-blue-700 rounded-xl p-4 flex items-center gap-3">
                <i class="fas fa-info-circle text-blue-500"></i>
                <span class="text-sm">Belum ada riwayat tracking untuk surat ini.</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Disposisi List --}}
    @if($surat->disposisi->count() > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100 bg-amber-50 rounded-t-2xl">
            <h2 class="font-semibold text-amber-800 flex items-center gap-2">
                <i class="fas fa-tasks text-amber-600"></i>
                Informasi Disposisi
                <span class="bg-amber-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $surat->disposisi->count() }}</span>
            </h2>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($surat->disposisi as $disp)
            @php
                $dBadge = match($disp->status) {
                    0 => 'bg-amber-100 text-amber-700',
                    1 => 'bg-blue-100 text-blue-700',
                    2 => 'bg-purple-100 text-purple-700',
                    3 => 'bg-orange-100 text-orange-700',
                    4 => 'bg-red-100 text-red-700',
                    5 => 'bg-green-100 text-green-700',
                    default => 'bg-slate-100 text-slate-600',
                };
                $dLabels = [0=>'Belum Dibaca',1=>'Sedang Dikerjakan',2=>'Tunggu Verif Kasubag',3=>'Tunggu Verif Kabag',4=>'Perlu Revisi',5=>'Selesai'];
            @endphp
            <div class="p-4 grid grid-cols-2 md:grid-cols-5 gap-3 text-sm hover:bg-slate-50 transition-colors">
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Dari</p>
                    <p class="font-semibold text-slate-800">{{ $disp->pengirim->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Ke</p>
                    <p class="font-semibold text-slate-800">{{ $disp->penerima->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Instruksi</p>
                    <p class="text-slate-600">{{ Str::limit($disp->instruksi, 40) }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Status</p>
                    <span class="inline-block {{ $dBadge }} text-xs font-semibold px-2 py-0.5 rounded-full">
                        {{ $dLabels[$disp->status] ?? 'Unknown' }}
                    </span>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase mb-0.5">Tanggal Terima</p>
                    <p class="text-slate-600">
                        @if($disp->tanggal_diterima) {{ $disp->tanggal_diterima->format('d M Y') }}
                        @else <span class="text-slate-400 italic">Belum dibaca</span>
                        @endif
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
