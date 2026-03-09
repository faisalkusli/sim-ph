<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistem Persuratan Digital') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-thin::-webkit-scrollbar { width: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background-color: #334155; border-radius: 9999px; }
        .nav-link-active { background: linear-gradient(90deg,#2563eb,#3b82f6); color:#fff !important; box-shadow:0 4px 12px rgba(37,99,235,0.3); }
        @keyframes pop-in {
            0%   { opacity: 0; transform: scale(0.85) translateY(12px); }
            70%  { transform: scale(1.03) translateY(-2px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        .animate-pop { animation: pop-in 0.25s cubic-bezier(0.34,1.56,0.64,1) both; }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-100 font-sans antialiased">

{{-- ===================== TOP NAVBAR ===================== --}}
<header class="fixed top-0 left-0 right-0 z-40 h-16 bg-slate-900 flex items-center justify-between px-4 shadow-md">
    <div class="flex items-center gap-3">
        <button @click="sidebarOpen = !sidebarOpen"
                class="text-slate-300 hover:text-white p-2 rounded-lg hover:bg-slate-800 transition-colors">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <span class="text-white font-bold text-sm tracking-widest hidden sm:block uppercase">
            Sistem Persuratan Digital
        </span>
    </div>

    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false"
                class="flex items-center gap-2 text-slate-300 hover:text-white px-3 py-2 rounded-lg hover:bg-slate-800 transition-colors">
            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white font-semibold text-sm select-none">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <span class="hidden md:block text-sm font-medium">{{ Auth::user()->name }}</span>
            <i class="bi bi-chevron-down text-xs"></i>
        </button>

        <div x-show="open" x-cloak x-transition
             class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-slate-100 py-1 z-50">
            <div class="px-4 py-2 border-b border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Akun</p>
                <p class="text-sm font-semibold text-slate-700 truncate">{{ Auth::user()->name }}</p>
                <span class="inline-block mt-1 px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full font-medium capitalize">{{ Auth::user()->role }}</span>
            </div>
            <div class="border-t border-slate-100"></div>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 font-semibold hover:bg-red-50 rounded-b-xl">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
        </div>
    </div>
</header>

{{-- ===================== SIDEBAR ===================== --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed top-0 left-0 h-full w-64 bg-slate-900 z-30 pt-16 transition-transform duration-300 ease-in-out shadow-2xl flex flex-col">

    {{-- Logo area under topnav --}}
    <div class="px-4 py-4 flex flex-col items-center border-b border-slate-700/50">
        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-lg mb-2">
            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d9/Logo_Kabupaten_Malang_-_Seal_of_Malang_Regency.svg" alt="Logo" class="w-9 h-9">
        </div>
        <p class="text-white text-xs font-bold tracking-wider uppercase text-center leading-tight">
            Bagian Hukum<br><span class="text-blue-400">Setda Malang</span>
        </p>
    </div>

    <nav class="flex-1 overflow-y-auto scrollbar-thin py-3 px-2">
        @include('layouts.sidebar')
    </nav>

    <div class="px-4 py-3 border-t border-slate-700/50">
        <p class="text-slate-500 text-xs text-center">&copy; {{ date('Y') }} Bagian Hukum Setda</p>
    </div>
</aside>

{{-- Overlay for mobile --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
     class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>

{{-- ===================== MAIN CONTENT ===================== --}}
<div :class="sidebarOpen ? 'lg:pl-64' : ''"
     class="transition-all duration-300 pt-16 min-h-screen flex flex-col">

    <main class="flex-1 p-4 sm:p-6 max-w-screen-2xl w-full mx-auto">
        @yield('content')
    </main>

    <footer class="px-6 py-4 bg-white border-t border-slate-200 mt-auto">
        <div class="flex items-center justify-between text-xs text-slate-400">
            <span>&copy; {{ date('Y') }} Sistem Persuratan Digital &mdash; Bagian Hukum Setda</span>
            <span>v1.0</span>
        </div>
    </footer>
</div>

{{-- ===================== GLOBAL MODALS (Import/Export) ===================== --}}
@auth
@if(auth()->user()->role === 'admin')

{{-- Import Modal --}}
<div id="importModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">
        <form action="{{ route('surat-masuk.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-file-earmark-spreadsheet-fill text-green-500 text-lg"></i> Import Excel
                </h3>
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-3">
                <div class="bg-blue-50 text-blue-700 rounded-lg p-3 text-sm flex gap-2">
                    <i class="bi bi-info-circle-fill mt-0.5 flex-shrink-0"></i>
                    <span>Pastikan format Excel sesuai template sistem.</span>
                </div>
                <label class="block text-sm font-semibold text-slate-700">Pilih File Excel (.xlsx / .xls)</label>
                <input type="file" name="file" required accept=".xlsx,.xls"
                       class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-green-50 file:text-green-700 hover:file:bg-green-100 cursor-pointer">
            </div>
            <div class="flex justify-end gap-2 px-5 pb-5">
                <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-green-600 text-white font-semibold hover:bg-green-700">
                    <i class="bi bi-upload me-1"></i> Import
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Export Modal --}}
<div id="exportModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
     onclick="if(event.target===this)this.classList.add('hidden')">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md" onclick="event.stopPropagation()">
        <form action="{{ route('surat-masuk.export') }}" method="GET">
            <div class="flex items-center justify-between p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="bi bi-file-earmark-spreadsheet text-blue-500 text-lg"></i> Export Excel
                </h3>
                <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')"
                        class="text-slate-400 hover:text-slate-600 text-2xl leading-none">&times;</button>
            </div>
            <div class="p-5 space-y-3">
                <p class="text-sm text-slate-500">Pilih rentang tanggal. Kosongkan untuk download semua data.</p>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" class="w-full text-sm border-slate-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="w-full text-sm border-slate-300 rounded-lg">
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-2 px-5 pb-5">
                <button type="button" onclick="document.getElementById('exportModal').classList.add('hidden')"
                        class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                    <i class="bi bi-download me-1"></i> Download
                </button>
            </div>
        </form>
    </div>
</div>

@endif
@endauth

@stack('scripts')
</body>
</html>
