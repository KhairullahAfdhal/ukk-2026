@extends ('layouts.app')
@section ('content')

<div class="container">
    <h1>Tambah Siswa</h1>
        <form action="{{ route('admin.siswa.store') }}" method="POST">
                @csrf
                        <div class="form-group mb-3">
                                    <label for="nama">Nama Siswa</label>
                                                <input type="text" class="form-control" id="nama" name="nama" required>
                                                             <label for="nis">NIS</label>
                                                                         <input type="text" class="form-control" id="nis" name="nis" required>
                                                                                      <label for="kelas">Kelas</label>
                                                                                                  <input type="text" class="form-control" id="kelas" name="kelas" required>
                                                                                                          </div>
                                                                                                                  <button type="submit" class="btn btn-primary">Simpan</button>
                                                                                                                      </form>
                                                                                                                      </div>
@endsection