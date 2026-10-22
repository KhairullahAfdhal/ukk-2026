@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Peminjaman</h1>
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Peminjam</label>
            <select name="id_user" class="form-select mb-3" required>
                <option value="">-- Pilih Peminjam --</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ old('id_user') == $u->id ? 'selected' : '' }}>{{ $u->username }}</option>
                @endforeach
            </select>

            <label>Buku</label>
            <select name="id_buku" class="form-select mb-3" required>
                <option value="">-- Pilih Buku --</option>
                @foreach ($buku as $b)
                    <option value="{{ $b->id_buku }}" {{ old('id_buku') == $b->id_buku ? 'selected' : '' }}>{{ $b->nama_buku }}</option>
                @endforeach
            </select>

            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1" value="1" class="form-control mb-3" required>

            <label>Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" class="form-control mb-3" required>

            <label>Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" class="form-control mb-3">

            <label>Status</label>
            <select name="status" class="form-select mb-3">
            <option value="Pending">Pending</option>
            <option value="Disetujui">Disetujui</option>
            <option value="Ditolak">Ditolak</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Di Kembalikan">Di Kembalikan</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection