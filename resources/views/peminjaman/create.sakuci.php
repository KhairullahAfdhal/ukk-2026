@extends ('layouts.app')

@section ('content')

<div class="container">
    <h1>Tambah Kategori</h1>
    <form action="{{ route('peminjaman.store') }}" method="POST">
        @csrf 
        <div class="form-group mb-3">
            <label for="nama_peminjaman">Nama</label>
            <input type="text" name="nama_peminjaman" id="nama_peminjaman" class="form-control" required>
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control" required>
            <label for="kode_kategori">Kode_kategori</label>
            <input type="text" name="kode_kategori" id="kode_kategori" class="form-control" required>
         </div>
         <button type="sumbit" class="btn btn-primary">Simpan</button>
</form>
@endsection
