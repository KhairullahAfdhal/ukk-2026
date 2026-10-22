@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Catat Pengembalian</h1>
    <p><strong>Peminjam:</strong> {{ $peminjamName }} — <strong>Buku:</strong> {{ $bukuName }}</p>

    <form action="{{ route('pengembalian.update', ['id_peminjaman' => $peminjaman->id_peminjaman]) }}" method="POST">
        @csrf
        <label>Tanggal Pengembalian</label>
        <input type="date" name="tanggal_pengembalian" value="{{ substr($pengembalian->tanggal_pengembalian ?? '', 0, 10) }}" class="form-control mb-3" required>

        <label>Terlambat (hari)</label>
        <input type="number" name="terlambat_hari" min="0" value="{{ $pengembalian->terlambat_hari ?? 0 }}" class="form-control mb-3">

        <label>Denda</label>
        <input type="number" name="denda" min="0" value="{{ $pengembalian->denda ?? 0 }}" class="form-control mb-3">

        <label>Kondisi Barang</label>
        <input type="text" name="kondisi_barang" value="{{ $pengembalian->kondisi_barang ?? 'Baik' }}" class="form-control mb-3">

        <label>Catatan</label>
        <textarea name="catatan" class="form-control mb-3">{{ $pengembalian->catatan ?? '' }}</textarea>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection