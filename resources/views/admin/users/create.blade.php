@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Tambah User</h1>

    <div class="card mb-4 col-md-8">
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label>Nama Lengkap / Nama Instansi</label>
                    <input type="text" name="name" class="form-control" required placeholder="Contoh: Budi (Kasubag) / Dinas Kesehatan">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="email@contoh.com">
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon / WhatsApp</label>
                    <input type="number" name="no_hp" class="form-control" placeholder="08xxxxxxxx">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                </div>

                <div class="mb-3">
                    <label>Role (Jabatan & Hak Akses)</label>
                    <select name="role" class="form-select" required>
                        <option value="">-- Pilih Role --</option>
                        
                        {{-- Opsi Internal --}}
                        <option value="admin">Admin</option>
                        <option value="kabag">Kepala Bagian (Kabag)</option>
                        <option value="kasubag">Kepala Sub Bagian (Kasubag)</option>
                        <option value="staf">Staf</option>
                        
                        {{-- Opsi Eksternal --}}
                        <option value="tamu">Instansi</option>
                    </select>

                    
                </div>

                <button type="submit" class="btn btn-primary">Simpan User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection