<nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav pt-4"> 
            <div class="d-flex flex-column align-items-center mb-4">
                <div class="p-2 bg-white rounded-circle shadow-lg mb-3 d-flex justify-content-center align-items-center" style="width: 80px; height: 80px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo" style="width: 50px;">
                </div>
                
                <div class="text-center text-white fw-bold lh-sm px-2">
                    <div style="font-size: 1.2rem; letter-spacing: 1px;">SISTEM</div>
                    <div style="font-size: 1.2rem; letter-spacing: 1px;">PERSURATAN</div>
                    <div style="font-size: 1.2rem; letter-spacing: 1px;">DIGITAL</div>
                </div>
            </div>

            <div class="mx-3 border-top border-secondary opacity-25 mb-3"></div>
            <div class="sb-sidenav-menu-heading">Utama</div>
            
            {{-- Sidebar rules --}}
            @php $role = auth()->user()->role; @endphp

            @if($role == 'tamu')
                <a class="nav-link {{ Request::is('surat-masuk*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    Surat Masuk
                </a>
                <a class="nav-link {{ Request::is('surat-masuk/create') ? 'active' : '' }}" href="{{ route('surat-masuk.create') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                    Input Surat
                </a>
                <a class="nav-link {{ Request::is('produk-hukum*') ? 'active' : '' }}" href="{{ route('produk-hukum.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Produk Hukum
                </a>
            @elseif($role == 'operator')
                <a class="nav-link {{ Request::is('surat-masuk*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    Surat Masuk
                </a>
                <a class="nav-link {{ Request::is('surat-masuk/create') ? 'active' : '' }}" href="{{ route('surat-masuk.create') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div>
                    Input Surat
                </a>
                <a class="nav-link {{ Request::is('produk-hukum*') ? 'active' : '' }}" href="{{ route('produk-hukum.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Produk Hukum
                </a>
                <a class="nav-link {{ Request::is('pengambilan*') ? 'active' : '' }}" href="{{ route('pengambilan.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                    Pengambilan Produk Hukum
                </a>
                <a class="nav-link {{ Request::is('disposisi.monitoring') ? 'active' : '' }}" href="{{ route('disposisi.monitoring') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    Monitoring Disposisi
                </a>
            @elseif(in_array($role, ['kabag','kasubag','staf','staff']))
                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-grid-1x2-fill"></i></div> Dashboard
                </a>
                <a class="nav-link {{ Request::is('surat-masuk*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    Surat Masuk
                </a>
                @if($role != 'staf' && $role != 'staff')
                <a class="nav-link {{ Request::is('surat-keluar*') ? 'active' : '' }}" href="{{ route('surat-keluar.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-send-fill"></i></div>
                    Surat Keluar
                </a>
                <a class="nav-link {{ Request::is('produk-hukum*') ? 'active' : '' }}" href="{{ route('produk-hukum.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Produk Hukum
                </a>
                @endif
                <a class="nav-link {{ Request::is('pengambilan*') ? 'active' : '' }}" href="{{ route('pengambilan.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                    Pengambilan Produk Hukum
                </a>
                <a class="nav-link {{ Request::is('disposisi.monitoring') ? 'active' : '' }}" href="{{ route('disposisi.monitoring') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    Disposisi
                </a>
                <a class="nav-link {{ Request::is('inbox*') ? 'active' : '' }}" href="{{ route('inbox') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-inbox-fill"></i></div>
                    Inbox
                </a>
            @elseif($role == 'admin')
                <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-grid-1x2-fill"></i></div> Dashboard
                </a>
                <a class="nav-link {{ Request::is('surat-masuk*') ? 'active' : '' }}" href="{{ route('surat-masuk.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-envelope-check-fill"></i></div>
                    Surat Masuk
                </a>
                <a class="nav-link {{ Request::is('surat-keluar*') ? 'active' : '' }}" href="{{ route('surat-keluar.index') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-send-fill"></i></div>
                    Surat Keluar
                </a>
                <a class="nav-link {{ Request::is('produk-hukum*') ? 'active' : '' }}" href="{{ route('produk-hukum.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-gavel"></i></div>
                    Produk Hukum
                </a>
                <a class="nav-link {{ Request::is('pengambilan*') ? 'active' : '' }}" href="{{ route('pengambilan.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                    Pengambilan Produk Hukum
                </a>
                <a class="nav-link {{ Request::is('disposisi.monitoring') ? 'active' : '' }}" href="{{ route('disposisi.monitoring') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-diagram-3-fill"></i></div>
                    Disposisi
                </a>
                <a class="nav-link {{ Request::is('inbox*') ? 'active' : '' }}" href="{{ route('inbox') }}">
                    <div class="sb-nav-link-icon"><i class="bi bi-inbox-fill"></i></div>
                    Inbox
                </a>
                {{-- Menu pencarian dihapus --}}
                <div class="sb-sidenav-menu-heading">Pengaturan</div>
                @php
                    $isActiveSetting = Request::is('users*') || 
                                       Request::is('laporan*') || 
                                       Request::is('system/backup*');
                @endphp
                <a class="nav-link {{ $isActiveSetting ? '' : 'collapsed' }}" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePengaturan" aria-expanded="{{ $isActiveSetting ? 'true' : 'false' }}" aria-controls="collapsePengaturan">
                    <div class="sb-nav-link-icon"><i class="bi bi-gear-wide-connected"></i></div>
                    Administrasi
                    <div class="sb-sidenav-collapse-arrow"><i class="bi bi-chevron-down"></i></div>
                </a>
                <div class="collapse {{ $isActiveSetting ? 'show' : '' }}" id="collapsePengaturan" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav ms-3"> 
                        <a class="nav-link {{ Request::is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                            <span class="sb-nav-link-icon"><i class="bi bi-people"></i></span> User
                        </a>
                        <a class="nav-link {{ Request::is('laporan*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
                            <span class="sb-nav-link-icon"><i class="bi bi-bar-chart"></i></span> Laporan
                        </a>
                        <div class="my-1 border-top border-secondary opacity-25" style="width: 90%;"></div>
                        <div class="small text-muted ms-3 mt-2 mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">DATA & SYSTEM</div>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#exportModal">
                            <span class="sb-nav-link-icon"><i class="bi bi-file-earmark-spreadsheet-fill text-success"></i></span> Export Excel
                        </a>
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#importModal">
                            <span class="sb-nav-link-icon"><i class="bi bi-upload text-info"></i></span> Import Excel
                        </a>
                        <a class="nav-link text-danger {{ Request::routeIs('backup.*') ? 'active' : '' }}" href="{{ route('backup.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-database text-danger"></i></div>
                            Backup & Restore
                        </a>
                    </nav>
                </div>
            @endif

            <!-- User card dan tombol Sign Out dihapus sesuai permintaan -->

        </div>
    </div>
</nav>