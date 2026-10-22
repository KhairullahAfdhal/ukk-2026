@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Peminjaman</h1>
    <form action="{{ route('peminjaman.update', ['id' => $peminjaman->id_peminjaman]) }}" method="POST">
        @csrf
        <label>Peminjam</label>
        <select name="id_user" class="form-select mb-3" required>
            @foreach ($users as $u)
                <option value="{{ $u->id }}" {{ $peminjaman->id_user == $u->id ? 'selected' : '' }}>{{ $u->username }}</option>
            @endforeach
        </select>

        <label>Buku</label>
        <select name="id_buku" class="form-select mb-3" required>
            @foreach ($buku as $b)
                <option value="{{ $b->id_buku }}" {{ $peminjaman->id_buku == $b->id_buku ? 'selected' : '' }}>{{ $b->nama_buku }}</option>
            @endforeach
        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" value="{{ $peminjaman->jumlah }}" class="form-control mb-3" required>

        <label>Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" value="{{ substr($peminjaman->tanggal_pinjam, 0, 10) }}" class="form-control mb-3" required>

        <label>Tanggal Kembali</label>
        <input type="date" name="tanggal_kembali" value="{{ substr($peminjaman->tanggal_kembali ?? '', 0, 10) }}" class="form-control mb-3">

        <label>Status</label>
        <select name="status" class="form-select mb-3">
           @foreach (['Pending', 'Disetujui', 'Ditolak', 'Dipinjam', 'Di Kembalikan'] as $s)
            <option value="{{ $s }}" {{ $peminjaman->status == $s ? 'selected' : '' }}>{{ $s }}</option>
        @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection