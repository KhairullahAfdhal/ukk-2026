@extends('layouts.app')

@section('title', 'Beranda — Perpustakaan Digital')

@section('content')

{{-- Hero --}}
<section class="hero">
    <span class="hero-pill">Perpustakaan sekolah, kini di genggaman</span>

    <h1 class="hero-title">Baca buku apa saja,<br class="d-none d-md-inline"> kapan saja, di mana saja</h1>

    <p class="hero-lead">
        Ribuan buku siap dipinjam secara digital. Cari judul favoritmu dan mulai membaca hari ini.
    </p>

    

    <div class="shelf" aria-hidden="true">
        <span></span><span></span><span></span><span></span><span></span>
        <span></span><span></span><span></span><span></span><span></span>
    </div>
</section>

{{-- Statistik --}}
<section class="stats">
    <div class="row g-3">
        <div class="col-6 col-md-3 stat-item">
            <div class="stat-value">10.000+</div>
            <div class="stat-label">Judul buku</div>
        </div>
        <div class="col-6 col-md-3 stat-item">
            <div class="stat-value">2.500+</div>
            <div class="stat-label">Pembaca aktif</div>
        </div>
        <div class="col-6 col-md-3 stat-item">
            <div class="stat-value">24/7</div>
            <div class="stat-label">Akses kapan saja</div>
        </div>
        <div class="col-6 col-md-3 stat-item">
            <div class="stat-value">100%</div>
            <div class="stat-label">Peminjaman otomatis</div>
        </div>
    </div>
</section>

{{-- Katalog --}}
<section id="katalog-buku">
    <div class="section-head">
        <div>
           
        </div>
    </div>

   <section class="stats">
   <h4>Tentang Aplikasi</h4>
   <p>Aplikasi Perpustakaan Online adalah sebuah platform digital berbasis web yang dirancang untuk memudahkan pengelolaan, pencarian, dan peminjaman buku secara digital tanpa batasan waktu dan tempat. Sistem ini mengintegrasikan peran pustakawan dan pembaca ke dalam satu ekosistem yang efisien dan otomatis.</p>
   </section>
    </div>
</section>

@endsection