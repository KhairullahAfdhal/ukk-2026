@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Pengembalian</h1>
    <form action="{{ route('pengembalian.store') }}" method="POST">
        @csrf
        <div class="form-group mb-3">
            <label>Peminjaman</label>
            <select name="id_peminjaman" class="form-select mb-3" required>
                <option value="">-- Pilih Peminjam --</option>
                @foreach ($peminjaman as $p)
                    <option value="{{ $p->id_peminjaman }}">
                        {{ $userList[$p->id_user] ?? '-' }} — {{ $bukuList[$p->id_buku] ?? '-' }}
                    </option>
                @endforeach
            </select>

            <label>Tanggal Pengembalian</label>
            <input type="date" name="tanggal_pengembalian" class="form-control mb-3" required>

            <label>Terlambat (hari)</label>
            <input type="number" name="terlambat_hari" min="0" value="0" class="form-control mb-3">

            <label>Denda</label>
            <input type="number" name="denda" min="0" value="0" class="form-control mb-3">

            <label>Kondisi Barang</label>
            <input type="text" name="kondisi_barang" value="Baik" class="form-control mb-3">

            <label>Catatan</label>
            <textarea name="catatan" class="form-control mb-3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection