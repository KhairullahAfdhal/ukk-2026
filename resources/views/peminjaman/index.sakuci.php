@extends('layouts.app')

@section('content')
<div class="container">
    <h1>DAFTAR PEMINJAMAN</h1>
<form action="" method="GET" class="mb-4">
    <div class="row g-2">
        <div class="col-md-9">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Cari nama peminjam, nama buku, atau status..."
                value="{{ $_GET['search'] ?? '' }}"
            >
        </div>

        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i> Cari
            </button>

            <a href="{{ strtok($_SERVER['REQUEST_URI'], '?') }}"
               class="btn btn-outline-secondary">
                Reset
            </a>
        </div>
    </div>
</form>
    <a href="{{ route('peminjaman.create') }}" class="btn btn-primary mb-3 btn-sm">Tambah Peminjaman</a>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Nama Buku</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach ($datap as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_peminjam }}</td>
                <td>{{ $items->nama_buku }}</td>
                <td>{{ $items->jumlah }}</td>
                <td>{{ $items->tanggal_pinjam }}</td>
                <td>{{ $items->tanggal_kembali ?? '-' }}</td>
                <td>{{ $items->status }}</td>
                <td>
                    <a href="{{ route('peminjaman.edit', ['id' => $items->id_peminjaman]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('peminjaman.delete', ['id' => $items->id_peminjaman]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data peminjaman ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {!! $datap->links() !!}
</div>
@endsection