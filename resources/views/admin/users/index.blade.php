@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Manajemen User</h1>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span><i class="fas fa-users me-1"></i> Daftar Pengguna</span>
                
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah User
                </a>
            </div>
        </div>
        <div class="card-body">
            
            <form action="{{ route('users.index') }}" method="GET" class="mb-3">
                <div class="row g-2 align-items-center">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">-- Semua Role --</option>
                            <option value="super_admin" {{ request('role')=='super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            <option value="kabag" {{ request('role')=='kabag' ? 'selected' : '' }}>Kabag</option>
                            <option value="kasubag" {{ request('role')=='kasubag' ? 'selected' : '' }}>Kasubag</option>
                            <option value="staf" {{ request('role')=='staf' ? 'selected' : '' }}>Staf</option>
                            <option value="tamu" {{ request('role')=='tamu' ? 'selected' : '' }}>Tamu</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-secondary w-100" type="submit"><i class="fas fa-search"></i> Cari</button>
                    </div>
                    <div class="col-md-2">
                        @if(request('search') || request('role'))
                            <a href="{{ route('users.index') }}" class="btn btn-outline-danger w-100" title="Reset"><i class="fas fa-times"></i></a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>No. Telepon</th> <th>Email</th>
                            <th>Role</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $key => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->no_hp ?? '-' }}</td> <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleColors = [
                                        'super_admin' => 'bg-dark',
                                        'admin' => 'bg-danger',
                                        'kabag' => 'bg-primary',
                                        'kasubag' => 'bg-info text-dark',
                                        'staf' => 'bg-success',
                                        'tamu' => 'bg-secondary',
                                    ];
                                @endphp
                                <span class="badge {{ $roleColors[$user->role] ?? 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                            </td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger btn-delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Data user tidak ditemukan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
<script>
    document.querySelectorAll('.btn-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: 'Hapus User?',
                text: 'User yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection