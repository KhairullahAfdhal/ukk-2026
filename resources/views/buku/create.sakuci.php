@extends ('layouts.app')

@section ('content')

<div class="container">
    <h1>Tambah Buku</h1>
    <form action="{{ route('buku.store') }}" method="POST">
        @csrf 
        <div class="form-group mb-3">
            <label for="nama_buku">NamaBuku</label>
            <input type="text" name="nama_buku" id="nama_buku" class="form-control" required>
            <label for="kode_buku">KodeBuku</label>
            <input type="text" name="kode_buku" id="kode_buku" class="form-control" required>
         </div>
         <button type="sumbit" class="btn btn-primary">Simpan</button>
</form>
@endsection
