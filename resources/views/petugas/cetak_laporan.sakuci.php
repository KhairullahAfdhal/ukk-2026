@extends('layouts.print')

@section('title')Laporan Peminjaman & Pengembalian@endsection

@section('content')
<div class="laporan-header">
    <h1>Laporan Peminjaman & Pengembalian Buku</h1>
    <p>{{ config('app.name') }}</p>
    <p>
        Periode: {{ $dari ?: 'Awal' }} s/d {{ $sampai ?: 'Sekarang' }}
        &middot; Dicetak oleh: {{ $dicetakOleh }}
        &middot; Tanggal cetak: {{ date('d-m-Y H:i') }}
    </p>
</div>

<form method="GET" class="print-toolbar">
    <label>Dari: <input type="date" name="dari" value="{{ $dari }}"></label>
    <label>Sampai: <input type="date" name="sampai" value="{{ $sampai }}"></label>
    <button type="submit">Terapkan Filter</button>
</form>

<table>
    <thead>
        <tr>
            <th>No</th><th>Peminjam</th><th>Buku</th><th>Jumlah</th>
            <th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Denda</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse ($rows as $r)
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $r->nama_peminjam }}</td>
            <td>{{ $r->nama_buku }}</td>
            <td>{{ $r->jumlah }}</td>
            <td>{{ $r->tanggal_pinjam }}</td>
            <td>{{ $r->tanggal_kembali ?? '-' }}</td>
            <td>{{ $r->status }}</td>
            <td>{{ number_format($r->denda, 0, ',', '.') }}</td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;">Tidak ada data pada periode ini.</td></tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td colspan="7" style="text-align:right;">Total Denda</td>
            <td>Rp {{ number_format($totalDenda, 0, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>

<div class="laporan-footer">
    <div class="ttd">
        <p>Mengetahui,</p>
        <div class="garis"></div>
        <p>{{ $dicetakOleh }}</p>
    </div>
</div>
@endsection