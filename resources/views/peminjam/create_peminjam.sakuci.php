@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Ajukan Peminjaman Buku</h1>
    <form action="{{ route('peminjam.storePeminjam') }}" method="POST">
        @csrf
        <label>Buku</label>
        <select name="id_buku" class="form-select mb-3" required>
            <option value="">-- Pilih Buku --</option>
            @foreach ($buku as $b)
                <option value="{{ $b->id_buku }}">{{ $b->nama_buku }}</option>
            @endforeach
        </select>

        <label>Jumlah</label>
        <input type="number" name="jumlah" min="1" value="1" class="form-control mb-3" required>

        <label>Tanggal Pinjam</label>
        <input type="date" name="tanggal_pinjam" class="form-control mb-3" required>

        <button type="submit" class="btn btn-primary">Ajukan</button>
    </form>
</div>
@endsection