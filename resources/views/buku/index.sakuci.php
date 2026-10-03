@extends('layouts.app')

@section('content')
<div class="container">
    <h1>DAFTAR BUKU</h1>
    <a href="{{ route('buku.create') }}" class="btn btn-primary mb-3 btn-sm">Tambah Buku</a>
    <table class="table table-bordered table-striped">
        <thead> 
            <tr>
                <th>NO</th>
                <th>Nama_buku</th>
                <th>Kode_buku</th>
                <th>Aksi</th>
            </tr>   
        </thead>
        <tbody>
            @php 
            $no = 1;
            @endphp
            @foreach ($buku as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_buku}}</td>
                <td>{{ $items->kode_buku }}</td>
                <td>
                    <a href="{{ route('buku.edit', ['id_buku' => $items->id_buku]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('buku.destroy', ['id_buku' => $items->id_buku]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</button>

                </td>
            </tr>     
            @endforeach
        </tbody>
    </table>
    
    {!! $buku
    ->links() !!}
</div>
@endsection