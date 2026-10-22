<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\LogAktifitas;

class PeminjamanController extends Controller
{
    private function catat(string $aktifitas): void
    {
        $user = User::current();

        LogAktifitas::create([
            'id_user'   => $user->id ?? null,
            'aktifitas' => $aktifitas,
        ]);
    }

    /** Tambahkan nama peminjam & nama buku ke tiap baris (2 query saja). */
    private function lengkapi($datap)
    {
        $users = [];
        foreach (User::all() as $u) {
            $users[$u->id] = $u->username;
        }

        $bukus = [];
        foreach (Buku::all() as $b) {
            $bukus[$b->id_buku] = $b->nama_buku;
        }

        foreach ($datap as $item) {
            $item->nama_peminjam = $users[$item->id_user] ?? '-';
            $item->nama_buku     = $bukus[$item->id_buku] ?? '-';
        }

        return $datap;
    }

    // ==========================================
    // ADMIN
    // ==========================================

    public function index(Request $request)
    {
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(5);
        $datap = $this->lengkapi($datap);

        return view('peminjaman.index', compact('datap'));
    }

    public function create(Request $request)
    {
        $buku  = Buku::all();
        $users = User::all();

        return view('peminjaman.create', compact('buku', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user'        => 'required|exists:users,id',
            'id_buku'        => 'required|exists:buku,id_buku',
            'jumlah'         => 'required',
            'tanggal_pinjam' => 'required',
        ]);

        Peminjaman::create([
            'id_user'         => $request->input('id_user'),
            'id_buku'         => $request->input('id_buku'),
            'jumlah'          => $request->input('jumlah'),
            'tanggal_pinjam'  => $request->input('tanggal_pinjam'),
            'tanggal_kembali' => $request->input('tanggal_kembali') ?: null,
            'status'          => $request->input('status') ?: 'Pending',
        ]);

        $this->catat('Admin menambahkan data peminjaman baru.');

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();

        if (! $peminjaman) {
            return redirect(route('peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $buku  = Buku::all();
        $users = User::all();

        return view('peminjaman.edit', compact('peminjaman', 'buku', 'users'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();

        if (! $peminjaman) {
            return redirect(route('peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $request->validate([
            'id_user'        => 'required|exists:users,id',
            'id_buku'        => 'required|exists:buku,id_buku',
            'jumlah'         => 'required',
            'tanggal_pinjam' => 'required',
        ]);

        $peminjaman->update([
            'id_user'         => $request->input('id_user'),
            'id_buku'         => $request->input('id_buku'),
            'jumlah'          => $request->input('jumlah'),
            'tanggal_pinjam'  => $request->input('tanggal_pinjam'),
            'tanggal_kembali' => $request->input('tanggal_kembali') ?: null,
            'status'          => $request->input('status') ?: $peminjaman->status,
        ]);

        $this->catat('Admin memperbarui data peminjaman (ID: ' . $id . ').');

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    public function delete(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();

        if ($peminjaman) {
            $peminjaman->delete();
            $this->catat('Admin menghapus data peminjaman (ID: ' . $id . ').');
        }

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil dihapus!');
    }

    // ==========================================
    // PEMINJAM / SISWA
    // ==========================================

    public function indexPeminjam(Request $request)
    {
        $currentUser = User::current();

        if (! $currentUser) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Peminjaman::where('id_user', $currentUser->id)
            ->orderBy('id_peminjaman', 'desc')
            ->paginate(10);

        $datap = $this->lengkapi($datap);

        return view('peminjam.index_peminjam', compact('datap'));
    }

    public function createPeminjam(Request $request)
    {
        $buku = Buku::all();

        return view('peminjam.create_peminjam', compact('buku'));
    }

    public function storePeminjam(Request $request)
    {
        $currentUser = User::current();

        if (! $currentUser) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'id_buku'        => 'required|exists:buku,id_buku',
            'jumlah'         => 'required',
            'tanggal_pinjam' => 'required',
        ]);

        Peminjaman::create([
            'id_user'         => $currentUser->id, // dikunci dari session
            'id_buku'         => $request->input('id_buku'),
            'jumlah'          => $request->input('jumlah'),
            'tanggal_pinjam'  => $request->input('tanggal_pinjam'),
            'tanggal_kembali' => $request->input('tanggal_kembali') ?: null,
            'status'          => 'Pending',
        ]);

        $this->catat('Peminjam ' . ($currentUser->username ?? 'Siswa') . ' mengajukan peminjaman buku.');

        return redirect(route('peminjam.riwayat'))->with('success', 'Pengajuan peminjaman berhasil dikirim!');
    }

    // ==========================================
    // PETUGAS
    // ==========================================

    public function indexPetugas(Request $request)
    {
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(10);
        $datap = $this->lengkapi($datap);

        return view('petugas.peminjaman', compact('datap'));
    }

    public function setujuiPetugas(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();

        if (! $peminjaman) {
            return redirect(route('petugas.peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $peminjaman->update(['status' => 'Disetujui']);

        $this->catat('Petugas menyetujui peminjaman (ID: ' . $id . ').');

        return redirect(route('petugas.peminjaman.index'))->with('success', 'Peminjaman berhasil disetujui!');
    }
}