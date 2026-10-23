{{-- ============================================================
     SIDEBAR — judul di atas, tombol tema di bawah
     Logika PHP/Blade (dbConnected, currentUser, canRegister, csrf)
     dipertahankan persis seperti versi sebelumnya.
     ============================================================ --}}

@php
    $dbConnected = false;
    try {
        \Sakuci\Database\Connection::pdo();
        $dbConnected = true;
    } catch (\Throwable $e) {
        $dbConnected = false;
    }
    $currentUser = \App\Models\User::current();
    $canRegister = false;
    if (!$currentUser && $dbConnected) {
        try {
            $canRegister = \App\Models\Role::where('can_register', 1)->exists();
        } catch (\Throwable $e) {
            $canRegister = false;
        }
    }
@endphp

{{-- Topbar: hanya tampil di HP/tablet --}}
<nav class="navbar bg-body border-bottom sticky-top app-topbar d-lg-none">
    <div class="container-fluid d-flex align-items-center gap-2">
        <div class="sidebar-toggle-wrap">
            <button id="sidebarToggleBtn" class="btn btn-sm border-0 p-1 d-flex align-items-center" type="button"
                    aria-controls="appSidebar" aria-expanded="false" aria-label="Buka/tutup sidebar" title="Buka/tutup sidebar">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
            </button>
            <span class="db-status-dot" aria-hidden="true"
                  style="background: {{ $dbConnected ? '#28a745' : '#dc3545' }};"></span>
            <span class="visually-hidden">Status database: {{ $dbConnected ? 'terhubung' : 'tidak terhubung' }}</span>
        </div>
        <a class="navbar-brand m-0" href="{{ route('home') }}">{{ config('app.name') }}</a>
    </div>
</nav>

{{-- Sidebar: offcanvas di mobile, fixed terbuka permanen di layar besar (lg ke atas) --}}
<aside class="offcanvas-lg offcanvas-start app-sidebar bg-body border-end" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">

    <div class="offcanvas-header d-lg-none">
        <span class="offcanvas-title fw-semibold" id="appSidebarLabel">{{ config('app.name') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-3">

        {{-- Judul aplikasi di atas sidebar (layar besar) --}}
        <a class="sidebar-brand d-none d-lg-flex" href="{{ route('home') }}">
            <span class="brand-mark">{{ strtoupper(mb_substr(config('app.name'), 0, 1)) }}</span>
            <span class="sidebar-brand-name">{{ config('app.name') }}</span>
            <span class="db-dot" title="Database {{ $dbConnected ? 'terhubung' : 'tidak terhubung' }}"
                  style="background: {{ $dbConnected ? '#28a745' : '#dc3545' }};"></span>
        </a>

        {{-- Menu utama --}}
        <ul class="nav nav-pills flex-column gap-1 mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ is_route('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                    <span class="sidebar-label">Beranda</span>
                </a>
            </li>

            @if ($currentUser && $currentUser->role === 'admin')
                <li class="nav-item">
                    <a class="nav-link {{ is_route('kategori.index') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>
                        <span class="sidebar-label">Kategori</span>
                    </a>
                </li>
            
<li class="nav-item">
    <a class="nav-link {{ is_route('buku.index') ? 'active' : '' }}"
       href="{{ route('buku.index') }}">

        <svg class="me-2 flex-shrink-0"
             width="18"
             height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2"
             stroke-linecap="round"
             stroke-linejoin="round">

            <path d="M12 7v14"/>
            <path d="M3 18V5a2 2 0 0 1 2-2h3a4 4 0 0 1 4 4 4 4 0 0 1 4-4h3a2 2 0 0 1 2 2v13a1 1 0 0 1-1 1h-4a4 4 0 0 0-4 2 4 4 0 0 0-4-2H4a1 1 0 0 1-1-1z"/>

        </svg>

        <span class="sidebar-label">Buku</span>
    </a>
</li>

                <li class="nav-item">
                    <a class="nav-link {{ is_route('peminjaman.index') ? 'active' : '' }}" href="{{ route('peminjaman.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="14" rx="1.5"/><path d="M3 9h18"/><path d="M8 4v4M16 4v4"/></svg>
                        <span class="sidebar-label">Peminjaman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('pengembalian.index') ? 'active' : '' }}" href="{{ route('pengembalian.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                        <span class="sidebar-label">Pengembalian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('admin.log.index') ? 'active' : '' }}" href="{{ route('admin.log.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                        <span class="sidebar-label">Log Aktivitas</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ is_route('admin.laporan.cetak') ? 'active' : '' }}" href="{{ route('admin.laporan.cetak') }}">
                    <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18h12v4H6zM6 14h12M4 9h16a2 2 0 0 1 2 2v5h-4v-3H6v3H2v-5a2 2 0 0 1 2-2z"/></svg>
                    <span class="sidebar-label">Cetak Laporan</span>
                    </a>
                </li>

            @elseif ($currentUser && $currentUser->role === 'siswa')
                <li class="nav-item">
                    <a class="nav-link {{ is_route('peminjam.createPeminjam') ? 'active' : '' }}" href="{{ route('peminjam.createPeminjam') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="14" rx="1.5"/><path d="M3 9h18"/><path d="M8 4v4M16 4v4"/></svg>
                        <span class="sidebar-label">Ajukan Peminjaman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('peminjam.riwayat') ? 'active' : '' }}" href="{{ route('peminjam.riwayat') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8v4l3 3"/><circle cx="12" cy="12" r="9"/></svg>
                        <span class="sidebar-label">Riwayat Peminjaman</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('peminjam.pengembalian.create') ? 'active' : '' }}" href="{{ route('peminjam.pengembalian.create') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                        <span class="sidebar-label">Ajukan Pengembalian</span>
                    </a>
                </li>
            @elseif ($currentUser && $currentUser->role === 'petugas')
                <li class="nav-item">
                    <a class="nav-link {{ is_route('petugas.peminjaman.index') ? 'active' : '' }}" href="{{ route('petugas.peminjaman.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="14" rx="1.5"/><path d="M3 9h18"/><path d="M8 4v4M16 4v4"/></svg>
                        <span class="sidebar-label">Peminjaman Masuk</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('petugas.pengembalian.index') ? 'active' : '' }}" href="{{ route('petugas.pengembalian.index') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg>
                        <span class="sidebar-label">Pengembalian</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ is_route('petugas.pengembalian.cetak') ? 'active' : '' }}" href="{{ route('petugas.pengembalian.cetak') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18h12v4H6zM6 14h12M4 9h16a2 2 0 0 1 2 2v5h-4v-3H6v3H2v-5a2 2 0 0 1 2-2z"/></svg>
                        <span class="sidebar-label">Cetak Laporan</span>
                    </a>
                </li>

                <li class="nav-item">
                     <a class="nav-link {{ is_route('petugas.pengembalian.cetak') ? 'active' : '' }}" href="{{ route('petugas.pengembalian.cetak') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7M6 18h12v4H6zM6 14h12M4 9h16a2 2 0 0 1 2 2v5h-4v-3H6v3H2v-5a2 2 0 0 1 2-2z"/></svg>
                        <span class="sidebar-label">Cetak Laporan</span>
                        </a>
                </li>

            @endif

            @if ($currentUser)
                <li class="nav-item">
                    <a class="nav-link {{ is_route('admin.dashboard', 'dashboard') ? 'active' : '' }}"
                       href="{{ $currentUser->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}">
                        <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>
                        <span class="sidebar-label">Dashboard</span>
                    </a>
                </li>
            @else
                @if ($canRegister)
                    <li class="nav-item">
                        <a class="nav-link {{ is_route('register') ? 'active' : '' }}" href="{{ route('register') }}">
                            <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="7" r="3.2"/><path d="M2.5 20c0-3.6 2.9-6.2 6.5-6.2s6.5 2.6 6.5 6.2"/><path d="M17 8v6M20 11h-6"/></svg>
                            <span class="sidebar-label">Daftar</span>
                        </a>
                    </li>
                @endif
            @endif
        </ul>

        {{-- Area bawah: user aktif / login + tombol tema --}}
        <div class="pt-3 border-top mt-3">
            @if ($currentUser)
                <div class="d-flex align-items-center gap-2 mb-2 px-1">
                    <div class="sidebar-avatar">{{ strtoupper(substr($currentUser->username, 0, 2)) }}</div>
                    <div class="small text-truncate sidebar-label">
                        <div class="fw-semibold text-truncate">{{ $currentUser->username }}</div>
                        <div class="text-secondary text-truncate" style="font-size:.75rem">{{ ucfirst($currentUser->role ?? 'user') }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
                        <span class="sidebar-label">Logout</span>
                        <span class="sidebar-label-collapsed d-none">⏻</span>
                    </button>
                </form>
            @else
                <a class="btn btn-sm btn-brand rounded-pill w-100 d-inline-flex align-items-center justify-content-center gap-2" href="{{ route('login') }}">
                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" class="flex-shrink-0">
                        <circle cx="8" cy="5" r="3" fill="currentColor" stroke="none"/>
                        <path d="M2.5 14c0-3.6 2.9-5.8 5.5-5.8s5.5 2.2 5.5 5.8"/>
                    </svg>
                    <span class="sidebar-label">Masuk</span>
                </a>
            @endif

            {{-- Tombol tema terang/gelap --}}
           
{{-- Tombol tema terang/gelap --}}
<div class="theme-row">
    <span>Tema terang / gelap</span>

    <button id="themeToggle"
            type="button"
            class="logo-toggle"
            aria-label="Ganti tema terang/gelap"
            title="Ganti tema terang/gelap">

        <svg width="26" height="26"
             viewBox="0 0 24 24"
             fill="none"
             xmlns="http://www.w3.org/2000/svg"
             aria-hidden="true">

            {{-- Ikon matahari --}}
            <g class="theme-sun">
                <circle cx="12" cy="12" r="4"
                        stroke="currentColor"
                        stroke-width="1.8"/>

                <path d="M12 2V4
                         M12 20V22
                         M4.93 4.93L6.35 6.35
                         M17.65 17.65L19.07 19.07
                         M2 12H4
                         M20 12H22
                         M4.93 19.07L6.35 17.65
                         M17.65 6.35L19.07 4.93"
                      stroke="currentColor"
                      stroke-width="1.8"
                      stroke-linecap="round"/>
            </g>

            {{-- Ikon bulan --}}
            <path class="theme-moon"
                  d="M20.5 14.2A8.5 8.5 0 0 1 9.8 3.5
                     A8.5 8.5 0 1 0 20.5 14.2Z"
                  stroke="currentColor"
                  stroke-width="1.8"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  fill="none"/>
        </svg>
    </button>
</div>
        </div>

    </div>
</aside>

<style>
    :root { --topbar-h: 56px; }
    @media (min-width: 992px) {
        :root { --topbar-h: 0px; }
    }

    .app-topbar {
        height: var(--topbar-h);
        border-color: var(--line) !important;
    }
    .app-topbar .navbar-brand {
        font-family: "Literata", Georgia, serif;
        font-weight: 700;
        letter-spacing: -.01em;
        color: var(--brand-dark);
    }

    /* Titik status database di topbar HP */
    .sidebar-toggle-wrap { position: relative; display: inline-flex; }
    .db-status-dot {
        position: absolute; top: 2px; right: 0;
        width: 9px; height: 9px; border-radius: 50%;
        border: 2px solid var(--bs-body-bg);
        pointer-events: none;
    }

    /* Judul aplikasi di atas sidebar */
    .sidebar-brand {
        align-items: center;
        gap: .65rem;
        padding: .25rem .35rem 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid var(--line);
        text-decoration: none;
        color: var(--brand-dark);
    }
    .sidebar-brand-name {
        flex: 1;
        min-width: 0;
        font-family: "Literata", Georgia, serif;
        font-weight: 700;
        font-size: 1.02rem;
        line-height: 1.25;
        letter-spacing: -.01em;
    }
    .db-dot {
        width: 9px; height: 9px;
        border-radius: 50%;
        flex: 0 0 auto;
    }

    /* Lebar sidebar & posisinya di layar besar */
    .app-sidebar {
        --sidebar-w: 260px;
        border-color: var(--line) !important;
    }
    @media (min-width: 992px) {
        .app-sidebar {
            width: var(--sidebar-w);
            position: fixed;
            top: var(--topbar-h);
            left: 0;
            bottom: 0;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 100;
            transform: none !important;
            visibility: visible !important;
        }
        .app-content {
            margin-left: var(--sidebar-w);
            min-height: calc(100vh - var(--topbar-h));
        }
    }

    /* Menu */
    .app-sidebar .nav-link {
        display: flex;
        align-items: center;
        color: var(--bs-secondary-color);
        border-radius: 12px;
        padding: .6rem .85rem;
        font-size: .92rem;
        font-weight: 600;
        transition: background-color .15s ease, color .15s ease;
    }
    .app-sidebar .nav-link:hover {
        background-color: var(--brand-subtle);
        color: var(--brand-dark);
    }
    .app-sidebar .nav-link.active {
        background-color: var(--brand-subtle);
        color: var(--brand-dark);
        font-weight: 700;
        box-shadow: inset 3px 0 0 var(--bookmark);
    }

    /* Avatar */
    .sidebar-avatar {
        width: 34px; height: 34px;
        border-radius: 50%;
        background: var(--brand-subtle);
        color: var(--brand-dark);
        display: flex; align-items: center; justify-content: center;
        font-size: .75rem; font-weight: 700;
        flex-shrink: 0;
    }

    /* Baris tombol tema di paling bawah */
    .theme-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: .9rem;
        padding: .6rem .35rem 0;
        border-top: 1px dashed var(--line);
        font-size: .82rem;
        font-weight: 600;
        color: var(--bs-secondary-color);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.getElementById('appSidebar');
        var btn = document.getElementById('sidebarToggleBtn');
        if (!sidebar || !btn) return;

        // Tombol toggle hanya dipakai untuk memunculkan offcanvas di versi mobile
        btn.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                var oc = bootstrap.Offcanvas.getOrCreateInstance(sidebar);
                oc.toggle();
            }
        });
    });
</script>