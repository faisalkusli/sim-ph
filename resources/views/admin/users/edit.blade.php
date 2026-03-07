@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit User</h1>

    <div class="card mb-4 col-md-8">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label>Nama Lengkap / Nama Instansi</label>
                    <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Telepon / WhatsApp</label>
                    <input type="number" name="no_hp" class="form-control" value="{{ $user->no_hp ?? '' }}">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diganti">
                </div>

                <div class="mb-3">
                    <label>Role</label>
                    <select name="role" class="form-select" required>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        
                        <option value="kabag" {{ $user->role == 'kabag' ? 'selected' : '' }}>Kepala Bagian (Kabag)</option>
                        <option value="kasubag" {{ $user->role == 'kasubag' ? 'selected' : '' }}>Kepala Sub Bagian (Kasubag)</option>
                        
                        {{-- Logika agar data lama 'user' tetap terbaca sebagai 'staf' --}}
                        <option value="staf" {{ $user->role == 'staf' || $user->role == 'user' ? 'selected' : '' }}>Staf / Pelaksana</option>
                        
                        <option value="tamu" {{ $user->role == 'tamu' ? 'selected' : '' }}>Tamu (Instansi Luar)</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection