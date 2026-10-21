@extends('layouts.app')

@section('content')
<form action="{{ route('buku.update', ['id_buku' => $buku->id_buku]) }}" method="post" class="d-flex flex-column form-horizontal">
    @csrf
    @method('PUT')
    <label>NamaBuku</label>
    <input type="text" name="nama_buku" value="{{ $buku->nama_buku }}" class="form-control mb-3" required>
    <label>KodeBuku</label>    
    <input type="text" name="kode_buku" value="{{ $buku->kode_buku }}" class="form-control mb-3" required>
    <label>Kategori</label>
    <select name="id_kategori" class="form-select mb-3" required>
    <option value="">-- Pilih Kategori --</option>
    @foreach ($kategori as $item)
        <option
            value="{{ $item->id_kategori }}"
            {{ $buku->id_kategori == $item->id_kategori ? 'selected' : '' }}
        >
            {{ $item->nama_kategori }}
        </option>
    @endforeach
</select>

    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection