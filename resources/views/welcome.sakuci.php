@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@php
    $pengguna = \App\Models\User::current();
    $namaUser = $pengguna ? $pengguna->username : 'Pengguna';

    $namaHari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $namaBulan = ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    $tanggalHariIni = $namaHari[(int) date('w')] . ', ' . date('j') . ' ' . $namaBulan[(int) date('n')] . ' ' . date('Y');

    // Warna sampul bergantian berdasarkan urutan
    $warnaSampul = ['#2F6F8F', '#8A3B2E', '#3E5C45', '#5B4A8A', '#6B5A2A'];
    $urutP = 0;
    $urutB = 0;
@endphp

<section class="hero">
    <p class="tanggal">{{ $tanggalHariIni }}</p>
    <h1>Mau baca apa hari ini, {{ $namaUser }}?</h1>

    <form class="search" action="/admin/buku" method="GET">
        <input type="search" name="q" placeholder="Cari judul atau kode buku">
        <button type="submit" class="btn-accent">Cari buku</button>
    </form>

</section>

<section class="ringkasan">
    <div><strong>{{ $sedangDipinjam }}</strong><span>Sedang dipinjam</span></div>
    <div><strong>{{ $jatuhTempo }}</strong><span>Jatuh tempo minggu ini</span></div>
    <div><strong class="denda">Rp {{ number_format($denda, 0, ',', '.') }}</strong><span>Denda berjalan</span></div>
</section>

<section class="seksi">
    <div class="seksi-head">
        <h2>Sedang dipinjam</h2>
        <a href="/admin/peminjaman">Lihat semua</a>
    </div>
    <div class="daftar">
        @forelse ($dipinjam as $p)
        <div class="item">
            <div class="mini-sampul" style="background: {{ $warnaSampul[$urutP++ % 5] }}"></div>
            <div class="item-info">
                <b>{{ $p->nama_buku }}</b>
                <small>Dipinjam {{ $p->tanggal_pinjam }}@if ($p->tanggal_kembali) &middot; kembali {{ $p->tanggal_kembali }}@endif</small>
            </div>
            <span class="lencana">{{ $p->status }}</span>
        </div>
        @empty
        <div class="item-kosong">Belum ada buku yang sedang dipinjam.</div>
        @endforelse
    </div>
</section>

<section class="seksi">
    <div class="seksi-head">
        <h2>Koleksi terbaru</h2>
        <a href="/admin/buku">Semua buku</a>
    </div>
    <div class="rak">
        @foreach ($bukuTerbaru as $b)
        @php
            $namaKategori = $b->kategori ? $b->kategori->nama_kategori : 'Tanpa kategori';
        @endphp
        <a class="buku" href="/admin/buku">
            <div class="sampul" style="background: {{ $warnaSampul[$urutB++ % 5] }}">
                <b>{{ $b->nama_buku }}</b>
                <span>{{ $b->kode_buku }}</span>
            </div>
            <h3>{{ $b->nama_buku }}</h3>
            <p>{{ $namaKategori }}</p>
        </a>
        @endforeach
    </div>
</section>
@endsection