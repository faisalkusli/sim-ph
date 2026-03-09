@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <div>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Pengguna</h1>
        <nav class="flex items-center gap-2 text-sm text-slate-400 mt-1">
            <a href="{{ route('home') }}" class="hover:text-blue-600">Dashboard</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <a href="{{ route('users.index') }}" class="hover:text-blue-600">Pengguna</a>
            <i class="bi bi-chevron-right text-xs"></i>
            <span class="text-slate-600">Tambah</span>
        </nav>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex gap-3">
        <i class="fas fa-exclamation-circle text-red-500 text-lg flex-shrink-0 mt-0.5"></i>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="p-5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                    <i class="fas fa-user-plus text-blue-600"></i> Data Pengguna Baru
                </h2>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap / Nama Instansi <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Budi Santoso / Dinas Kesehatan"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           placeholder="email@contoh.com"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">No. Telepon / WhatsApp</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6"
                           placeholder="Minimal 6 karakter"
                           class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role (Jabatan & Hak Akses) <span class="text-red-500">*</span></label>
                    <select name="role" required
                            class="w-full border border-slate-300 rounded-xl text-sm px-3 py-2.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <option value="">-- Pilih Role --</option>
                        <optgroup label="Internal">
                            <option value="admin" {{ old('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            <option value="operator" {{ old('role')=='operator' ? 'selected' : '' }}>Operator</option>
                            <option value="kabag" {{ old('role')=='kabag' ? 'selected' : '' }}>Kepala Bagian (Kabag)</option>
                            <option value="kasubag" {{ old('role')=='kasubag' ? 'selected' : '' }}>Kepala Sub Bagian (Kasubag)</option>
                            <option value="staf" {{ old('role')=='staf' ? 'selected' : '' }}>Staf / Pelaksana</option>
                        </optgroup>
                        <optgroup label="Eksternal">
                            <option value="tamu" {{ old('role')=='tamu' ? 'selected' : '' }}>Tamu / Instansi Luar</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="px-5 pb-5 flex justify-end gap-3 border-t border-slate-100 pt-4">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Pengguna
                </button>
            </div>
        </div>
    </form>
</div>
@endsection