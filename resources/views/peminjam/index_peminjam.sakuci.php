@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Riwayat Peminjaman</h1>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Buku</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse ($datap as $items)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $items->nama_buku }}</td>
                <td>{{ $items->jumlah }}</td>
                <td>{{ $items->tanggal_pinjam }}</td>
                <td>{{ $items->tanggal_kembali ?? '-' }}</td>
                <td>{{ $items->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada riwayat peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {!! $datap->links() !!}
</div>
@endsection