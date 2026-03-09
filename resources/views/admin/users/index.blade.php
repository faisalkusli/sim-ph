@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h1>
            <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
                <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
                <i class="bi bi-chevron-right text-xs"></i>
                <span class="text-slate-600">Pengguna</span>
            </nav>
        </div>
        <a href="{{ route('users.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors shadow-sm">
            <i class="fas fa-plus"></i> Tambah Pengguna
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl p-4 flex items-center gap-3">
        <i class="fas fa-check-circle text-green-500 text-lg flex-shrink-0"></i>
        <span class="text-sm">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        {{-- Filters --}}
        <div class="p-4 border-b border-slate-100">
            <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap gap-3">
                <div class="flex-1 min-w-48 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search"
                           class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                           placeholder="Cari nama atau email..."
                           value="{{ $search ?? '' }}">
                </div>
                <select name="role"
                        class="border border-slate-200 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                    <option value="">-- Semua Role --</option>
                    @foreach(['super_admin' => 'Super Admin', 'admin' => 'Admin', 'operator' => 'Operator', 'kabag' => 'Kabag', 'kasubag' => 'Kasubag', 'staf' => 'Staf', 'tamu' => 'Tamu'] as $val => $label)
                    <option value="{{ $val }}" {{ request('role') == $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <button type="submit" class="px-4 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-medium hover:bg-slate-900">Cari</button>
                @if(request('search') || request('role'))
                <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-sm font-medium hover:bg-slate-200">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-800 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                        <th class="px-4 py-3 text-left font-semibold">Nama</th>
                        <th class="px-4 py-3 text-left font-semibold">No. Telepon</th>
                        <th class="px-4 py-3 text-left font-semibold">Email</th>
                        <th class="px-4 py-3 text-center font-semibold w-32">Role</th>
                        <th class="px-4 py-3 text-center font-semibold w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $key => $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-4 py-3 text-slate-400 text-center">{{ $users->firstItem() + $key }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->no_hp ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $roleCls = match($user->role) {
                                    'super_admin' => 'bg-slate-800 text-white',
                                    'admin' => 'bg-red-100 text-red-700',
                                    'operator' => 'bg-orange-100 text-orange-700',
                                    'kabag' => 'bg-blue-100 text-blue-700',
                                    'kasubag' => 'bg-cyan-100 text-cyan-700',
                                    'staf' => 'bg-green-100 text-green-700',
                                    'tamu' => 'bg-slate-100 text-slate-600',
                                    default => 'bg-slate-100 text-slate-600',
                                };
                            @endphp
                            <span class="inline-block {{ $roleCls }} text-xs font-semibold px-2.5 py-1 rounded-full">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('users.edit', $user->id) }}"
                                   class="w-8 h-8 bg-amber-100 text-amber-700 rounded-lg flex items-center justify-center hover:bg-amber-200 transition-colors"
                                   title="Edit">
                                    <i class="fas fa-edit text-xs"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                      onsubmit="return confirm('Yakin hapus pengguna {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 bg-red-100 text-red-600 rounded-lg flex items-center justify-center hover:bg-red-200 transition-colors"
                                            title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-slate-400">
                            <i class="fas fa-users text-4xl block mb-2 opacity-30"></i>
                            Data pengguna tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-100 flex justify-end">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
