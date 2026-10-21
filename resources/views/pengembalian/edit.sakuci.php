@extends('layouts.app')

@section('content')
<form action="{{ route('pengembalian.update', ['id_pengembalian' => $pengembalian->id_pengembalian]) }}" method="post" class="d-flex flex-column form-horizontal">
    @csrf
    @method('PUT')
    <label>Nama</label>
    <input type="text" name="nama_pengembalian" value="{{ $kategori->nama_pengembalian }}" class="form-control mb-3" required>
    <label>Keterangan</label>    
    <input type="text" name="keterangan" value="{{ $kategori->keterangan }}" class="form-control mb-3" required>
    <label>Kode Kategori</label>    
    <input type="text" name="kode_kategori" value="{{ $kategori->kode_kategori }}" class="form-control mb-3" required>


    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection