@php
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $path = rtrim($path, '/') ?: '/';

    // Belum login: tampil tanpa sidebar
    $pengguna = \App\Models\User::current();
    $tamu     = ! $pengguna;

    $namaUser = $pengguna ? ($pengguna->username ?: 'Pengguna') : 'Tamu';
    $inisial  = strtoupper(substr($namaUser, 0, 1));

    // Tentukan peran: dari data user kalau ada, kalau tidak dari awalan alamat
    $namaRole = '';
    if ($pengguna) {
        $r = $pengguna->role;
        if (is_object($r)) { $r = $r->name; }
        $namaRole = strtolower(is_scalar($r) ? (string) $r : '');
    }

    // Format menu: [alamat, label, ikon, cocokPersis]
    if ($tamu) {
        // Daftar hanya muncul kalau ada role yang boleh mendaftar (sama seperti halaman login)
        $bisaDaftar = false;
        try {
            $bisaDaftar = \App\Models\Role::where('can_register', 1)->exists();
        } catch (\Throwable $e) {
            $bisaDaftar = false;
        }

        $peran = 'Belum masuk';
        $menu = [
            ['/',      'Beranda', 'i-home',  true],
            ['/login', 'Masuk',   'i-login', true],
        ];
        if ($bisaDaftar) {
            $menu[] = ['/register', 'Daftar', 'i-userplus', true];
        }
    } elseif (strpos($namaRole, 'petugas') !== false || strpos($path, '/petugas') === 0) {
        $peran = 'Petugas';
        $menu = [
            ['/petugas',                    'Dashboard',     'i-grid',  true],
            ['/petugas/peminjaman',         'Peminjaman',    'i-swap',  false],
            ['/petugas/pengembalian',       'Pengembalian',  'i-back',  true],
            ['/petugas/pengembalian/cetak', 'Cetak laporan', 'i-print', false],
        ];
    } elseif (strpos($namaRole, 'siswa') !== false || strpos($namaRole, 'peminjam') !== false || strpos($path, '/peminjam') === 0) {
        $peran = 'Peminjam';
        $menu = [
            ['/',                             'Beranda',             'i-home', true],
            ['/peminjam/riwayat-peminjaman',  'Riwayat peminjaman',  'i-clock', false],
            ['/peminjam/peminjam/ajukan',     'Ajukan pinjaman',     'i-swap',  false],
            ['/peminjam/pengembalian/ajukan', 'Ajukan pengembalian', 'i-back',  false],
        ];
    } else {
        $peran = 'Admin';
        $menu = [
            ['/',                    'Beranda',       'i-home',  true],
            ['/admin',               'Dashboard',     'i-grid',  true],
            ['/admin/kategori',      'Kategori',      'i-tag',   false],
            ['/admin/buku',          'Buku',          'i-book',  false],
            ['/admin/peminjaman',    'Peminjaman',    'i-swap',  false],
            ['/admin/pengembalian',  'Pengembalian',  'i-back',  false],
            ['/admin/log-aktifitas', 'Log aktivitas', 'i-clock', false],
            ['/admin/laporan',       'Cetak laporan', 'i-print', false],
        ];
    }

    if ($tamu) {
        $peran = 'Belum masuk';
    }

    // Pesan sukses / error dari redirect()->with(...)
    $pesanOk = null;
    $pesanErr = null;
    try {
        if (function_exists('session')) {
            $pesanOk  = session('success');
            $pesanErr = session('error');
        }
    } catch (\Throwable $e) {}
@endphp
<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tema.css') }}">
    <script>
        try { var t = localStorage.getItem('tema'); if (t) { document.documentElement.dataset.theme = t; document.documentElement.setAttribute('data-bs-theme', t); } } catch (e) {}
    </script>
</head>
<body>

<svg width="0" height="0" style="position:absolute" aria-hidden="true">
    <defs>
        <symbol id="i-home" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></symbol>
        <symbol id="i-book" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20V3H6.5A2.5 2.5 0 0 0 4 5.5z"/></symbol>
        <symbol id="i-swap" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M7 7h13l-3-3M17 17H4l3 3"/></symbol>
        <symbol id="i-back" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7L21 8M21 3v5h-5"/></symbol>
        <symbol id="i-clock" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></symbol>
        <symbol id="i-print" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V3h12v6M6 18H4v-7h16v7h-2M7 14h10v7H7z"/></symbol>
        <symbol id="i-grid" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></symbol>
        <symbol id="i-login" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1h-4M10 17l5-5-5-5M15 12H3"/></symbol>
        <symbol id="i-userplus" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8M19 8v6M22 11h-6"/></symbol>
        <symbol id="i-tag" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12V4a1 1 0 0 1 1-1h8l9 9-9 9z"/><circle cx="8" cy="8" r="1.2"/></symbol>
    </defs>
</svg>

<header class="topbar">
    <span>Peminjaman Buku</span>
    <button type="button" class="btn-ghost kecil theme-toggle">Ganti tema</button>
</header>

<div class="app">

    <aside class="sidebar">
        <div class="brand">
            <strong>Peminjaman<br>Buku</strong>
            <small>Perpustakaan sekolah</small>
        </div>

        <nav class="menu">
            @foreach ($menu as $m)
            @php
                $aktif = $m[3] ? ($path === $m[0]) : (strpos($path, $m[0]) === 0);
            @endphp
            <a href="{{ $m[0] }}" class="{{ $aktif ? 'aktif' : '' }}"><svg><use href="#{{ $m[2] }}"/></svg>{{ $m[1] }}</a>
            @endforeach
        </nav>

        <div class="side-bottom">
            <div class="user">
                <div class="avatar">{{ $inisial }}</div>
                <div><b>{{ $namaUser }}</b><small>{{ $peran }}</small></div>
            </div>
            <div class="side-actions">
                @if ($tamu)
                <button type="button" class="btn-ghost theme-toggle">Ganti tema</button>
                @else
                <form action="/logout" method="POST" style="flex:1; margin:0;">
                    @csrf
                    <button type="submit" class="btn-ghost" style="width:100%;">Keluar</button>
                </form>
                <button type="button" class="btn-ghost kecil theme-toggle">Tema</button>
                @endif
            </div>
        </div>
    </aside>

    <main class="main">
        <div class="isi">
            @if ($pesanOk)
                <div class="notif ok">{{ $pesanOk }}</div>
            @endif
            @if ($pesanErr)
                <div class="notif err">{{ $pesanErr }}</div>
            @endif
            @yield('content')
        </div>
    </main>
</div>

<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@yield('scripts')
<script>
    document.querySelectorAll('.theme-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var root = document.documentElement;
            var next = root.dataset.theme === 'dark' ? 'light' : 'dark';
            root.dataset.theme = next;
            root.setAttribute('data-bs-theme', next);
            try { localStorage.setItem('tema', next); } catch (e) {}
        });
    });
</script>
</body>
</html>