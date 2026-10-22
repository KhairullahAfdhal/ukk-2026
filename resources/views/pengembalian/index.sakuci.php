@extends('layouts.app')

@section('content')
<div class="container">
    <h1>DAFTAR PENGEMBALIAN</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Buku</th>
                <th>Status</th>
                <th>Tgl Kembali</th>
                <th>Terlambat</th>
                <th>Denda</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($peminjaman as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_peminjam }}</td>
                <td>{{ $items->nama_buku }}</td>
                <td>{{ $items->status }}</td>
                <td>{{ $items->pengembalian->tanggal_pengembalian ?? '-' }}</td>
                <td>{{ $items->pengembalian->terlambat_hari ?? '-' }}</td>
                <td>{{ $items->pengembalian->denda ?? '-' }}</td>
                <td>{{ $items->pengembalian->kondisi_barang ?? '-' }}</td>
                <td>
                    <a href="{{ route('pengembalian.edit', ['id_peminjaman' => $items->id_peminjaman]) }}" class="btn btn-sm btn-warning">
                        {{ $items->pengembalian ? 'Edit' : 'Catat Kembali' }}
                    </a>
                    @if ($items->pengembalian)
                    <form action="{{ route('pengembalian.delete', ['id_peminjaman' => $items->id_peminjaman]) }}" method="POST" style="display: inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data pengembalian ini?')">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {!! $peminjaman->links() !!}
</div>
@endsection