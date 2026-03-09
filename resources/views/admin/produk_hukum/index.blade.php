@extends('layouts.app')

@section('title', 'Master Produk Hukum')

@section('content')
<div class="space-y-5">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Master Produk Hukum</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Produk Hukum</span>
            </nav>
        </div>
        @if(auth()->user() && auth()->user()->role == 'admin')
        <a href="{{ route('produk-hukum.create') }}"
           class="px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm flex items-center gap-2">
            <i class="fas fa-upload"></i> Upload Produk Hukum
        </a>
        @endif
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Table Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="p-5 border-b border-slate-100">
            <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                <i class="fas fa-gavel text-blue-600"></i> Daftar Produk Hukum
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama Produk</th>
                        <th class="px-4 py-3 text-left font-semibold">Keterangan</th>
                        <th class="px-4 py-3 text-center font-semibold w-32">File</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($produk as $key => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-slate-400 text-center">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 font-semibold text-slate-800">{{ $item->nama }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $item->keterangan ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($item->file)
                            <a href="{{ asset('storage/produk_hukum/'.$item->file) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-100 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-200 transition-colors">
                                <i class="fas fa-file-pdf"></i> Download
                            </a>
                            @else
                            <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-gavel text-4xl block mb-2 opacity-20"></i>
                            Belum ada data produk hukum.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
