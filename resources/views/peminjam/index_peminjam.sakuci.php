@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Peminjaman</h1>

    <!-- Form Pencarian dan Reset yang Rapi -->
    <form action="" method="GET" style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
        <!-- Kotak Input Pencarian -->
        <input type="search" name="search" placeholder="Cari nama buku, jumlah, tanggal, atau status..." value="{{ request('search') }}" style="flex: 1; padding: 10px 15px; border-radius: 8px; border: 1px solid #333; background: #161b22; color: #fff;">
        
        <!-- Tombol Cari -->
        <button type="submit" class="btn-accent" style="padding: 10px 20px; background: #f0a500; color: #000; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">Cari</button>
        
        <!-- Tombol Reset -->
        <a href="{{ route('peminjam.riwayat') }}" style="padding: 10px 20px; background: transparent; color: white; border: 1px solid #444; border-radius: 8px; text-decoration: none; text-align: center; display: inline-block; white-space: nowrap;">Reset</a>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($datap as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_buku }}</td>
                <td>{{ $items->jumlah }}</td>
                <td>{{ $items->tanggal_pinjam }}</td>
                <td>{{ $items->tanggal_kembali ?? '-' }}</td>
                <td>{{ $items->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada riwayat peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {!! $datap->links() !!}
</div>
@endsection