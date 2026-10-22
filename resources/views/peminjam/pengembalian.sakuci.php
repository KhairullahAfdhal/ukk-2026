@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajukan Pengembalian</h1>

    @if (count($peminjamanAktif) === 0)
        <p>Tidak ada buku yang sedang dipinjam untuk dikembalikan.</p>
    @else
        <form action="{{ route('peminjam.pengembalian.store') }}" method="POST">
            @csrf
            <label>Pilih Peminjaman</label>
            <select name="id_peminjaman" class="form-select mb-3" required>
                <option value="">-- Pilih Buku yang Dikembalikan --</option>
                @foreach ($peminjamanAktif as $p)
                    <option value="{{ $p->id_peminjaman }}">{{ $p->nama_buku }} (dipinjam {{ $p->tanggal_pinjam }})</option>
                @endforeach
            </select>

            <label>Tanggal Pengembalian</label>
            <input type="date" name="tanggal_pengembalian" class="form-control mb-3" required>

            <label>Kondisi Barang</label>
            <input type="text" name="kondisi_barang" value="Baik" class="form-control mb-3">

            <label>Catatan</label>
            <textarea name="catatan" class="form-control mb-3"></textarea>

            <button type="submit" class="btn btn-primary">Ajukan</button>
        </form>
    @endif
</div>
@endsection