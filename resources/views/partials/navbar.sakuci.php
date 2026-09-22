{{-- ============================================================
     SIDEBAR — konversi dari navbar.blade.php
     Logika PHP/Blade (dbConnected, currentUser, canRegister, csrf)
     dipertahankan persis seperti versi navbar.
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

{{-- Topbar: selalu tampil di semua ukuran layar --}}
<nav class="navbar bg-body border-bottom sticky-top app-topbar">
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

        <button id="themeToggle" type="button" class="logo-toggle"
                aria-label="Ganti tema terang/gelap" title="Ganti tema terang/gelap">
            <svg width="26" height="26" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg" style="display: block;" aria-hidden="true">
                <circle class="logo-ring" cx="16" cy="16" r="15"/>
            </svg>
        </button>

        <a class="navbar-brand fw-semibold m-0" href="{{ route('home') }}">{{ config('app.name') }}</a>
    </div>
</nav>

{{-- Sidebar. offcanvas di mobile, fixed + bisa diciutkan/dilebarkan lewat tombol topbar di lg ke atas --}}
<aside class="offcanvas-lg offcanvas-start app-sidebar bg-body border-end" tabindex="-1" id="appSidebar" aria-labelledby="appSidebarLabel">

    <div class="offcanvas-header d-lg-none">
        <span class="offcanvas-title fw-semibold" id="appSidebarLabel">{{ config('app.name') }}</span>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#appSidebar" aria-label="Tutup"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column p-3">

        {{-- Menu utama --}}
        <ul class="nav nav-pills flex-column gap-1 mb-auto">
            <li class="nav-item">
                <a class="nav-link {{ is_route('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                    <span class="sidebar-label">Beranda</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ is_route('kategori.index') ? 'active' : '' }}" href="{{ route('kategori.index') }}">
                    <svg class="me-2 flex-shrink-0" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="8" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/><rect x="13" y="13" width="8" height="8" rx="1.5"/></svg>
                    <span class="sidebar-label">Kategori</span>
                </a>
            </li>

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

        {{-- Area bawah: login / user aktif --}}
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
        </div>

    </div>
</aside>

<style>
    :root {
        --topbar-h: 56px;
    }
    .app-topbar {
        height: var(--topbar-h);
    }

    /* Badge status database, nempel di pojok tombol sidebar */
    .sidebar-toggle-wrap {
        position: relative;
        display: inline-flex;
    }
    .db-status-dot {
        position: absolute;
        top: 2px;
        right: 0;
        width: 9px;
        height: 9px;
        border-radius: 50%;
        border: 2px solid var(--bs-body-bg);
        pointer-events: none;
    }

    /* Lebar sidebar & posisinya di layar besar */
    .app-sidebar {
        --sidebar-w: 240px;
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
            transition: width .18s ease, transform .18s ease;
        }
        /* Dorong konten utama ke kanan sejauh lebar sidebar.
           Bungkus konten halaman kamu dalam <div class="app-content"> */
        .app-content {
            margin-left: var(--sidebar-w);
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left .18s ease;
        }

        /* State tertutup: sidebar disembunyikan total, konten memenuhi ruang */
        .app-sidebar.collapsed {
            transform: translateX(-100%);
        }
        .app-sidebar.collapsed ~ .app-content,
        body:has(.app-sidebar.collapsed) .app-content {
            margin-left: 0;
        }
    }

    .app-sidebar .nav-link {
        display: flex;
        align-items: center;
        color: var(--bs-body-color);
        border-radius: .5rem;
        padding: .55rem .75rem;
        font-size: .92rem;
        font-weight: 500;
    }
    .app-sidebar .nav-link:hover {
        background-color: var(--bs-tertiary-bg);
    }
    .app-sidebar .nav-link.active {
        background-color: var(--bs-primary-bg-subtle, rgba(13,110,253,.1));
        color: var(--bs-primary);
    }

    .sidebar-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: var(--bs-tertiary-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        font-weight: 700;
        flex-shrink: 0;
    }
</style>

<script>
    // Terapkan state tertutup sebelum render, supaya tidak ada "kedipan" sidebar
    (function () {
        var el = document.getElementById('appSidebar');
        if (localStorage.getItem('sidebarCollapsed') === '1') {
            el.classList.add('collapsed');
        }
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var sidebar = document.getElementById('appSidebar');
        var btn = document.getElementById('sidebarToggleBtn');
        if (!sidebar || !btn) return;

        btn.addEventListener('click', function () {
            if (window.innerWidth < 992) {
                // Mobile/tablet: pakai Bootstrap Offcanvas API secara manual
                // (bukan lewat data-bs-toggle, supaya tidak dobel listener dengan kode di bawah)
                var oc = bootstrap.Offcanvas.getOrCreateInstance(sidebar);
                oc.toggle();
                return;
            }

            // Desktop: sembunyikan/tampilkan sidebar fixed
            var collapsed = sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0');
            btn.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        });
    });
</script>