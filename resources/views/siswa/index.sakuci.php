@extends('layouts.app')

@section('content')
<div class="container">
    <h1>DAFTAR BUKU</h1>
    <a href="{{ route('siswa.create') }}" class="btn btn-primary mb-3 btn-sm">Tambah Buku</a>
    <table class="table table-bordered table-striped">
        <thead> 
            <tr>
                <th>NO</th>
                <th>id_user</th>
                <th>nis</th>
                <th>nama</th>
                <th>kelas</th>
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
                <td>{{ $items->keterangan }}</td>
                <td>
                    <a href="{{ route('siswa.edit', ['id_siswa' => $items->id_siswa]) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('kategori.destroy', ['id_siswa' => $items->id_siswa]) }}" method="POST" style="display: inline-block;">
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