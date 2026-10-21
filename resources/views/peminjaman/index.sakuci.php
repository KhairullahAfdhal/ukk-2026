@extends('layouts.app')

@section('content')
<div class="container">
    <h1>DAFTAR PEMINJAMAN</h1>
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
                <th>Aksi</th>
            </tr>   
        </thead>
        <tbody>
            @php 
            $no = 1;
            @endphp
            @foreach ($data as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_peminjaman}}</td>
                <td>{{ $items->keterangan }}</td>
                <td>{{ $items->kode_kategori }}</td>
                <td>
                    <a href="{{ route('peminjaman.edit', ['id_peminjaman' => $items->id_peminjaman]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('peminjaman.destroy', ['id_peminjaman' => $items->id_peminjaman]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</button>

                </td>
            </tr>     
            @endforeach
        </tbody>
    </table>
    
    {!! $data->links() !!}
</div>
@endsection