@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 text-sm mt-1">Selamat datang, <span class="font-semibold text-blue-600">{{ Auth::user()->name }}</span>!</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Surat Masuk --}}
        <a href="{{ route('surat-masuk.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 transition-colors">
                <i class="bi bi-envelope-check-fill text-blue-600 text-2xl group-hover:text-white transition-colors"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800">{{ $totalSuratMasuk }}</p>
                <p class="text-sm text-slate-500 font-medium">Surat Masuk</p>
                <p class="text-xs text-blue-500 mt-0.5">Total diterima</p>
            </div>
        </a>

        {{-- Surat Keluar --}}
        @if(in_array(auth()->user()->role, ['admin','operator']))
        <a href="{{ route('surat-keluar.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 transition-colors">
                <i class="bi bi-send-fill text-emerald-600 text-2xl group-hover:text-white transition-colors"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800">{{ $totalSuratKeluar }}</p>
                <p class="text-sm text-slate-500 font-medium">Surat Keluar</p>
                <p class="text-xs text-emerald-500 mt-0.5">Total dikirim</p>
            </div>
        </a>
        @endif

        {{-- Disposisi --}}
        <a href="{{ route('disposisi.monitoring') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500 transition-colors">
                <i class="bi bi-diagram-3-fill text-amber-500 text-2xl group-hover:text-white transition-colors"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800">{{ $totalDisposisi }}</p>
                <p class="text-sm text-slate-500 font-medium">Total Disposisi</p>
                <p class="text-xs text-amber-500 mt-0.5">Instruksi diteruskan</p>
            </div>
        </a>

        {{-- Users (admin only) / Inbox (others) --}}
        @if(in_array(auth()->user()->role, ['admin','super_admin']))
        <a href="{{ route('users.index') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center flex-shrink-0 group-hover:bg-slate-700 transition-colors">
                <i class="bi bi-people-fill text-slate-600 text-2xl group-hover:text-white transition-colors"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800">{{ $totalUser }}</p>
                <p class="text-sm text-slate-500 font-medium">Pengguna Aktif</p>
                <p class="text-xs text-slate-400 mt-0.5">Admin & Staff</p>
            </div>
        </a>
        @else
        <a href="{{ route('inbox') }}"
           class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 flex items-center gap-4 hover:shadow-md transition-shadow group">
            <div class="w-14 h-14 rounded-2xl bg-cyan-100 flex items-center justify-center flex-shrink-0 group-hover:bg-cyan-600 transition-colors">
                <i class="bi bi-inbox-fill text-cyan-600 text-2xl group-hover:text-white transition-colors"></i>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800">{{ $totalUser }}</p>
                <p class="text-sm text-slate-500 font-medium">Inbox</p>
                <p class="text-xs text-cyan-500 mt-0.5">Disposisi masuk</p>
            </div>
        </a>
        @endif

    </div>

    {{-- Quick Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="text-base font-bold text-slate-700 mb-4">Aksi Cepat</h2>
        <div class="flex flex-wrap gap-3">

            @if(in_array(auth()->user()->role, ['admin','operator','tamu']))
            <a href="{{ route('surat-masuk.create') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
                <i class="fas fa-plus"></i> Input Surat Masuk
            </a>
            @endif

            <a href="{{ route('surat-masuk.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">
                <i class="bi bi-envelope-check-fill text-blue-500"></i> Lihat Surat Masuk
            </a>

            @if(in_array(auth()->user()->role, ['admin','operator','kabag','kasubag','staf']))
            <a href="{{ route('inbox') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">
                <i class="bi bi-inbox-fill text-cyan-500"></i> Buka Inbox
            </a>
            @endif

            @if(in_array(auth()->user()->role, ['admin','operator']))
            <a href="{{ route('surat-keluar.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">
                <i class="bi bi-send-fill text-emerald-500"></i> Surat Keluar
            </a>
            @endif

            @if(auth()->user()->role === 'admin')
            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold hover:bg-slate-200 transition-colors">
                <i class="bi bi-bar-chart-fill text-purple-500"></i> Laporan
            </a>
            @endif

        </div>
    </div>

</div>
@endsection
