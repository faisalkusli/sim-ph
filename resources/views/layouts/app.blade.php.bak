<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Sistem Persuratan Digital Bagian Hukum Setda') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="https://startbootstrap.github.io/startbootstrap-sb-admin/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #f1f5f9;
        }
        
        .sb-sidenav-dark { 
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important; 
        }

        .sb-topnav { 
            background-color: #0f172a !important; 
            box-shadow: none; 
            height: 60px;
            padding-left: 0;
            border-bottom: none;
        }

        .sb-topnav .navbar-brand {
            background-color: transparent; 
            width: 225px; 
            margin: 0;
            padding: 0;
            height: 100%;
        }

        #sidebarToggle { color: #e2e8f0; margin-left: 15px; }
        #sidebarToggle:hover { color: #ffffff; }
        .sb-topnav .navbar-nav .nav-link { color: #e2e8f0; }
        .sb-topnav .navbar-nav .nav-link:hover { color: #ffffff; }
        .sb-sidenav-menu .nav { padding: 0 12px; }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link { 
            color: #94a3b8; 
            border-radius: 8px; 
            margin-bottom: 4px;
            transition: all 0.2s ease-in-out;
            font-weight: 400;
            font-size: 0.9rem;
            padding: 0.8rem 1rem;
        }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link:hover { 
            color: #fff; 
            background-color: rgba(255,255,255,0.08); 
            transform: translateX(3px);
        }
        .sb-sidenav-dark .sb-sidenav-menu .nav-link.active { 
            color: #fff; 
            background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%); 
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .sb-nav-link-icon { color: inherit !important; opacity: 0.8; }
        .sb-sidenav-menu-heading {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            padding-top: 1.5rem !important;
            padding-bottom: 0.5rem !important;
        }

        .sb-sidenav-menu::-webkit-scrollbar { width: 5px; }
        .sb-sidenav-menu::-webkit-scrollbar-track { background: transparent; }
        .sb-sidenav-menu::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 10px; }

        .bg-opacity-10 { --bs-bg-opacity: 0.1; }
        .bg-opacity-20 { --bs-bg-opacity: 0.2; }
        .text-xs { font-size: 0.75rem !important; }
        .text-sm { font-size: 0.875rem !important; }

        .dropdown-item { font-size: 0.85rem; padding: 0.5rem 1rem; }
        .dropdown-item:active { background-color: #e9ecef; color: #1e293b; }
        .dropdown-header { font-size: 0.7rem; letter-spacing: 0.05em; }
    </style>
    @stack('styles')

</head>

<body class="sb-nav-fixed">

    <nav class="sb-topnav navbar navbar-expand navbar-dark"> 
        <a class="navbar-brand" href="{{ route('home') }}"></a>
        
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
            <i class="fas fa-bars fa-lg"></i>
        </button>
        
        <ul class="navbar-nav ms-auto me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle fw-semibold" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="d-none d-md-inline me-2 small">{{ Auth::user()->name }}</span>
                    <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm" style="width: 35px; height: 35px;">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-3 mt-2 animate slideIn" aria-labelledby="navbarDropdown">
                    <li><div class="dropdown-header text-uppercase small fw-bold text-muted">Akun</div></li>
                    <li><a class="dropdown-item py-2" href="#!"><i class="bi bi-person me-2 text-primary"></i>Profil Saya</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger fw-bold" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('layouts.sidebar')
        </div>

        <div id="layoutSidenav_content">
            <main>
                @yield('content')
            </main>
            
            <footer class="py-4 bg-light mt-auto border-top">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small text-muted">
                        <div>&copy; {{ date('Y') }} Sistem Persuratan Digital Bagian Hukum Setda</div>
                        <div>Versi 1.0</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('surat-masuk.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="importModalLabel">
                            <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i>Import Excel Surat Masuk
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <i class="bi bi-info-circle-fill me-1"></i>
                            Pastikan format Excel sesuai dengan template sistem.
                        </div>
                        <div class="mb-3">
                            <label for="fileExcel" class="form-label fw-bold">Pilih File Excel (.xlsx / .xls)</label>
                            <input type="file" name="file" id="fileExcel" class="form-control" required accept=".xlsx, .xls">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-upload me-1"></i> Import Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('surat-masuk.export') }}" method="GET">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="exportModalLabel">
                            <i class="bi bi-file-earmark-spreadsheet me-2"></i>Export Data Excel
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">Silakan pilih rentang tanggal surat yang ingin didownload. Kosongkan jika ingin mendownload semua data.</p>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-download me-1"></i> Download Excel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        window.addEventListener('DOMContentLoaded', event => {
            const sidebarToggle = document.body.querySelector('#sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', event => {
                    event.preventDefault();
                    document.body.classList.toggle('sb-sidenav-toggled');
                    localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>