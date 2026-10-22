<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Buku;
use App\Models\LogAktifitas;

class PengembalianController extends Controller
{
    private function catat(string $aktifitas): void
    {
        $user = User::current();
        LogAktifitas::create([
            'id_user'   => $user->id ?? null,
            'aktifitas' => $aktifitas,
        ]);
    }

    private function lengkapi($datap)
    {
        $peminjamanList = [];
        foreach (Peminjaman::all() as $p) {
            $peminjamanList[$p->id_peminjaman] = $p;
        }

        $userList = [];
        foreach (User::all() as $u) {
            $userList[$u->id] = $u->username;
        }

        $bukuList = [];
        foreach (Buku::all() as $b) {
            $bukuList[$b->id_buku] = $b->nama_buku;
        }

        foreach ($datap as $item) {
            $p = $peminjamanList[$item->id_peminjaman] ?? null;
            $item->nama_peminjam = $p ? ($userList[$p->id_user] ?? '-') : '-';
            $item->nama_buku     = $p ? ($bukuList[$p->id_buku] ?? '-') : '-';
        }

        return $datap;
    }

    // ==========================================
    // ADMIN
    // ==========================================

    public function index(Request $request)
{
    $peminjaman = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(10);

    $pengembalianList = [];
    foreach (Pengembalian::all() as $p) {
        $pengembalianList[$p->id_peminjaman] = $p;
    }

    $userList = [];
    foreach (User::all() as $u) {
        $userList[$u->id] = $u->username;
    }

    $bukuList = [];
    foreach (Buku::all() as $b) {
        $bukuList[$b->id_buku] = $b->nama_buku;
    }

    foreach ($peminjaman as $item) {
        $item->nama_peminjam = $userList[$item->id_user] ?? '-';
        $item->nama_buku     = $bukuList[$item->id_buku] ?? '-';
        $item->pengembalian  = $pengembalianList[$item->id_peminjaman] ?? null;
    }

    return view('pengembalian.index', compact('peminjaman'));
}

public function edit(Request $request, $id_peminjaman)
{
    $peminjaman = Peminjaman::where('id_peminjaman', $id_peminjaman)->first();

    if (! $peminjaman) {
        return redirect(route('pengembalian.index'))->with('error', 'Data peminjaman tidak ditemukan.');
    }

    $user = User::find($peminjaman->id_user);
    $buku = Buku::find($peminjaman->id_buku);
    $pengembalian = Pengembalian::where('id_peminjaman', $id_peminjaman)->first();

    return view('pengembalian.edit', [
        'peminjaman'   => $peminjaman,
        'pengembalian' => $pengembalian,
        'peminjamName' => $user->username ?? ('User #' . $peminjaman->id_user),
        'bukuName'     => $buku->nama_buku ?? ('Buku #' . $peminjaman->id_buku),
    ]);
}

public function update(Request $request, $id_peminjaman)
{
    $data = $request->validate([
        'tanggal_pengembalian' => 'required|date',
        'terlambat_hari'       => 'nullable|integer|min:0',
        'denda'                => 'nullable|integer|min:0',
        'kondisi_barang'       => 'nullable|string|max:100',
        'catatan'              => 'nullable|string',
    ]);

    $data['terlambat_hari'] = $data['terlambat_hari'] ?? 0;
    $data['denda']          = $data['denda'] ?? 0;
    $data['kondisi_barang'] = $data['kondisi_barang'] ?? 'Baik';

    $pengembalian = Pengembalian::where('id_peminjaman', $id_peminjaman)->first();

    if ($pengembalian) {
        $pengembalian->update($data);
    } else {
        $data['id_peminjaman'] = $id_peminjaman;
        Pengembalian::create($data);
    }

    $peminjaman = Peminjaman::findOrFail($id_peminjaman);
    $peminjaman->update(['status' => 'Di Kembalikan']);

    $this->catat('Admin mencatat pengembalian buku (Peminjaman ID: ' . $id_peminjaman . ').');

    return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil disimpan.');
}

public function delete(Request $request, $id_peminjaman)
{
    $pengembalian = Pengembalian::where('id_peminjaman', $id_peminjaman)->first();

    if ($pengembalian) {
        $pengembalian->delete();
        $this->catat('Admin menghapus data pengembalian (Peminjaman ID: ' . $id_peminjaman . ').');
    }

    return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil dihapus.');
}

    // ==========================================
    // PEMINJAM / SISWA
    // ==========================================

    public function createPeminjam(Request $request)
    {
        $user = User::current();
        if (! $user) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $peminjamanAktif = Peminjaman::where('id_user', $user->id)
            ->whereIn('status', ['Dipinjam', 'Disetujui'])
            ->get();

        $bukuList = [];
        foreach (Buku::all() as $b) {
            $bukuList[$b->id_buku] = $b->nama_buku;
        }

        foreach ($peminjamanAktif as $p) {
            $p->nama_buku = $bukuList[$p->id_buku] ?? '-';
        }

        return view('peminjam.pengembalian', compact('user', 'peminjamanAktif'));
    }

    public function storePeminjam(Request $request)
    {
        $currentUser = User::current();
        if (! $currentUser) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $data = $request->validate([
            'id_peminjaman'        => 'required|integer',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang'       => 'nullable|string|max:100',
            'catatan'              => 'nullable|string',
        ]);

        $data['terlambat_hari'] = 0;
        $data['denda']          = 0;
        $data['kondisi_barang'] = $data['kondisi_barang'] ?? 'Baik';

        Pengembalian::create($data);

        $peminjaman = Peminjaman::findOrFail($data['id_peminjaman']);
        $peminjaman->update(['status' => 'Di Kembalikan']);

        $this->catat('Peminjam ' . ($currentUser->username ?? 'Siswa') . ' mengajukan pengembalian buku.');

        return redirect(route('peminjam.riwayat'))->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }

    // ==========================================
    // PETUGAS
    // ==========================================

    public function indexPetugas(Request $request)
    {
        $user = User::current();
        if (! $user) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Pengembalian::orderBy('id_pengembalian', 'desc')->paginate(10);
        $datap = $this->lengkapi($datap);

        return view('petugas.pantau_pengembalian', compact('datap'));
    }

    public function cetakPetugas(Request $request)
    {
        $user = User::current();
        if (! $user) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Pengembalian::orderBy('id_pengembalian', 'desc')->get();
        $datap = $this->lengkapi($datap);

        return view('petugas.cetak_laporan', compact('datap', 'user'));
    }
}