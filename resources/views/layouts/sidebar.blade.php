@php
    $role = auth()->user()->role;
    $currentPath = request()->path();

    // Hitung notifikasi inbox: disposisi baru (belum dibaca) yang ditugaskan ke saya
    $inboxUnread = \App\Models\Disposisi::where('tujuan_user_id', auth()->id())
        ->where('status', 0)
        ->count();

    // Kabag juga mendapat notifikasi untuk disposisi menunggu verifikasinya (status 3)
    $verifikasiPending = 0;
    if ($role === 'kabag') {
        $verifikasiPending = \App\Models\Disposisi::where('status', 3)->count();
    } elseif ($role === 'kasubag') {
        $verifikasiPending = \App\Models\Disposisi::where('dari_user_id', auth()->id())
            ->where('status', 2)
            ->count();
    } elseif (in_array($role, ['admin', 'super_admin'])) {
        $verifikasiPending = \App\Models\Disposisi::whereIn('status', [2, 3])->count();
    }
    $inboxBadgeTotal = $inboxUnread + $verifikasiPending;

    function navLink($href, $icon, $label, $active) {
        $base = 'flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group w-full mb-1 ';
        $cls  = $active
            ? $base . 'nav-link-active'
            : $base . 'text-slate-400 hover:text-white hover:bg-slate-800';
        return "<a href=\"{$href}\" class=\"{$cls}\"><i class=\"{$icon} text-base w-5 text-center flex-shrink-0\"></i><span>{$label}</span></a>";
    }
@endphp

{{-- ================================================================ --}}
{{-- TAMU: Hanya Surat Masuk (milik sendiri) + Input Surat            --}}
{{-- ================================================================ --}}
@if($role == 'tamu')

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mb-2">Utama</p>

    {!! navLink(route('surat-masuk.index'), 'bi bi-envelope-check-fill', 'Surat Masuk',
        request()->is('surat-masuk') && !request()->is('surat-masuk/create')) !!}

    {!! navLink(route('surat-masuk.create'), 'fas fa-plus', 'Input Surat Baru',
        request()->is('surat-masuk/create')) !!}


{{-- ================================================================ --}}
{{-- OPERATOR: Semua fitur KECUALI menu Administrasi                  --}}
{{-- ================================================================ --}}
@elseif($role == 'operator')

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mb-2">Utama</p>

    {!! navLink(route('home'), 'bi bi-grid-1x2-fill', 'Dashboard', request()->is('home')) !!}

    {!! navLink(route('surat-masuk.index'), 'bi bi-envelope-check-fill', 'Surat Masuk',
        request()->is('surat-masuk*') && !request()->is('surat-masuk/create')) !!}

    {!! navLink(route('surat-masuk.create'), 'fas fa-plus', 'Input Surat',
        request()->is('surat-masuk/create')) !!}

    {!! navLink(route('surat-keluar.index'), 'bi bi-send-fill', 'Surat Keluar',
        request()->is('surat-keluar*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Referensi</p>

    {!! navLink(route('produk-hukum.index'), 'fas fa-gavel', 'Produk Hukum',
        request()->is('produk-hukum*')) !!}

    {!! navLink(route('pengambilan.index'), 'fas fa-hand-holding', 'Pengambilan Produk Hukum',
        request()->is('pengambilan*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Alur Kerja</p>

    {!! navLink(route('disposisi.monitoring'), 'bi bi-diagram-3-fill', 'Disposisi',
        request()->is('disposisi*')) !!}

    {!! navLink(route('inbox'), 'bi bi-inbox-fill', 'Inbox',
        request()->is('inbox*')) !!}


{{-- ================================================================ --}}
{{-- KABAG & KASUBAG: Surat Masuk + Disposisi + Inbox                 --}}
{{-- ================================================================ --}}
@elseif(in_array($role, ['kabag', 'kasubag']))

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mb-2">Utama</p>

    {!! navLink(route('home'), 'bi bi-grid-1x2-fill', 'Dashboard', request()->is('home')) !!}

    {!! navLink(route('surat-masuk.index'), 'bi bi-envelope-check-fill', 'Surat Masuk',
        request()->is('surat-masuk*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Alur Kerja</p>

    {!! navLink(route('disposisi.monitoring'), 'bi bi-diagram-3-fill', 'Disposisi',
        request()->is('disposisi*')) !!}

    @php
        $inboxLabel = 'Inbox';
        if ($inboxBadgeTotal > 0) {
            $inboxLabel .= ' <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs font-bold text-white bg-red-500 rounded-full leading-none">' . $inboxBadgeTotal . '</span>';
        }
    @endphp
    {!! navLink(route('inbox'), 'bi bi-inbox-fill', $inboxLabel,
        request()->is('inbox*')) !!}


{{-- ================================================================ --}}
{{-- STAF: Surat Masuk + Disposisi + Inbox                            --}}
{{-- ================================================================ --}}
@elseif(in_array($role, ['staf', 'staff']))

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mb-2">Utama</p>

    {!! navLink(route('home'), 'bi bi-grid-1x2-fill', 'Dashboard', request()->is('home')) !!}

    {!! navLink(route('surat-masuk.index'), 'bi bi-envelope-check-fill', 'Surat Masuk',
        request()->is('surat-masuk*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Alur Kerja</p>

    {!! navLink(route('disposisi.monitoring'), 'bi bi-diagram-3-fill', 'Disposisi',
        request()->is('disposisi*')) !!}

    @php
        $inboxLabelStaf = 'Inbox';
        if ($inboxBadgeTotal > 0) {
            $inboxLabelStaf .= ' <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs font-bold text-white bg-red-500 rounded-full leading-none">' . $inboxBadgeTotal . '</span>';
        }
    @endphp
    {!! navLink(route('inbox'), 'bi bi-inbox-fill', $inboxLabelStaf,
        request()->is('inbox*')) !!}


{{-- ================================================================ --}}
{{-- ADMIN: Semua fitur termasuk Administrasi                         --}}
{{-- ================================================================ --}}
@elseif($role == 'admin')

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mb-2">Utama</p>

    {!! navLink(route('home'), 'bi bi-grid-1x2-fill', 'Dashboard', request()->is('home')) !!}

    {!! navLink(route('surat-masuk.index'), 'bi bi-envelope-check-fill', 'Surat Masuk',
        request()->is('surat-masuk*') && !request()->is('surat-masuk/create')) !!}

    {!! navLink(route('surat-masuk.create'), 'fas fa-plus', 'Input Surat',
        request()->is('surat-masuk/create')) !!}

    {!! navLink(route('surat-keluar.index'), 'bi bi-send-fill', 'Surat Keluar',
        request()->is('surat-keluar*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Referensi</p>

    {!! navLink(route('produk-hukum.index'), 'fas fa-gavel', 'Produk Hukum',
        request()->is('produk-hukum*')) !!}

    {!! navLink(route('pengambilan.index'), 'fas fa-hand-holding', 'Pengambilan Produk Hukum',
        request()->is('pengambilan*')) !!}

    <p class="text-slate-500 text-xs font-bold uppercase tracking-widest px-3 mt-4 mb-2">Alur Kerja</p>

    {!! navLink(route('disposisi.monitoring'), 'bi bi-diagram-3-fill', 'Disposisi',
        request()->is('disposisi*')) !!}

    @php
        $inboxLabelAdmin = 'Inbox';
        if ($inboxBadgeTotal > 0) {
            $inboxLabelAdmin .= ' <span class="inline-flex items-center justify-center min-w-[1.25rem] h-5 px-1 text-xs font-bold text-white bg-red-500 rounded-full leading-none">' . $inboxBadgeTotal . '</span>';
        }
    @endphp
    {!! navLink(route('inbox'), 'bi bi-inbox-fill', $inboxLabelAdmin, request()->is('inbox*')) !!}

    @php
        $adminMenuActive = request()->is('users*') || request()->is('laporan*') || request()->is('system/backup*');
    @endphp
    <div x-data="{ open: {{ $adminMenuActive ? 'true' : 'false' }} }" class="mb-1">
        {{-- Accordion Toggle --}}
        <button @click="open = !open"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 w-full
                       {{ $adminMenuActive ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
            <i class="fas fa-cogs text-base w-5 text-center flex-shrink-0 {{ $adminMenuActive ? 'text-amber-400' : '' }}"></i>
            <span class="flex-1 text-left">Administrasi</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
        </button>

        {{-- Submenu --}}
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
             class="mt-1 ml-3 pl-3 border-l border-slate-700 space-y-0.5">

            <a href="{{ route('users.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200 w-full
                      {{ request()->is('users*') ? 'nav-link-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="bi bi-people-fill text-base w-5 text-center flex-shrink-0"></i>
                <span>Manajemen User</span>
            </a>

            <a href="{{ route('laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200 w-full
                      {{ request()->is('laporan*') ? 'nav-link-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="bi bi-bar-chart-fill text-base w-5 text-center flex-shrink-0"></i>
                <span>Laporan</span>
            </a>

            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 w-full">
                <i class="bi bi-upload text-base w-5 text-center flex-shrink-0 text-cyan-400"></i>
                <span>Import Excel</span>
            </button>

            <button onclick="document.getElementById('exportModal').classList.remove('hidden')"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200 w-full">
                <i class="bi bi-file-earmark-spreadsheet-fill text-base w-5 text-center flex-shrink-0 text-green-400"></i>
                <span>Export Excel</span>
            </button>

            <a href="{{ route('backup.index') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all duration-200 w-full
                      {{ request()->is('system/backup*') ? 'nav-link-active' : 'text-slate-400 hover:text-white hover:bg-slate-800' }}">
                <i class="fas fa-database text-base w-5 text-center flex-shrink-0"></i>
                <span>Backup & Restore</span>
            </a>

        </div>
    </div>

@endif
