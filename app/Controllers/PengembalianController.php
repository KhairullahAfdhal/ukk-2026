<?php

namespace App\Controllers;

use Sakuci\Controller;
use Sakuci\Http\Request;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use App\Models\LogAktifitas;

class PengembalianController extends Controller
{
    // =========================================================================
    // BAGIAN 1: KHUSUS ADMIN (FULL AKSES KELOLA)
    // =========================================================================

    public function index(Request $request)
    {
        $datap = Pengembalian::orderBy('id_pengembalian', 'desc')->paginate(10);
        $peminjaman = Peminjaman::all();
        $users = User::all();
        $alats = Alat::all();

        $peminjamanList = [];
        foreach ($peminjaman as $p) {
            $pid = $p->id_peminjaman ?? $p->id;
            $peminjamanList[$pid] = $p;
        }

        $userList = [];
        foreach ($users as $u) {
            $id = $u->id ?? $u->id_user;
            $userList[$id] = $u->username ?? $u->nama ?? "User #{$id}";
        }

        $alatList = [];
        foreach ($alats as $a) {
            $id = $a->id_alat ?? $a->id;
            $alatList[$id] = $a->nama_alat ?? "Alat #{$id}";
        }

        return view('pengembalian.index', compact('datap', 'peminjamanList', 'userList', 'alatList'));
    }

    public function create(Request $request)
    {
        $peminjaman = Peminjaman::where('status', '!=', 'Dikembalikan')->get();
        $users = User::all();
        $alats = Alat::all();

        $userList = [];
        foreach ($users as $u) {
            $id = $u->id ?? $u->id_user;
            $userList[$id] = $u->username ?? $u->nama ?? "User #{$id}";
        }

        $alatList = [];
        foreach ($alats as $a) {
            $id = $a->id_alat ?? $a->id;
            $alatList[$id] = $a->nama_alat ?? "Alat #{$id}";
        }

        return view('pengembalian.create', compact('peminjaman', 'userList', 'alatList'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_peminjaman'        => 'required|integer',
            'tanggal_pengembalian' => 'required|date',
            'terlambat_hari'       => 'nullable|integer|min:0',
            'denda'                => 'nullable|integer|min:0',
            'kondisi_barang'       => 'nullable|string|max:100',
            'catatan'              => 'nullable|string',
        ]);

        $validatedData['terlambat_hari'] = $validatedData['terlambat_hari'] ?? 0;
        $validatedData['denda']          = $validatedData['denda'] ?? 0;
        $validatedData['kondisi_barang'] = $validatedData['kondisi_barang'] ?? 'Baik';

        Pengembalian::create($validatedData);

        $peminjaman = Peminjaman::findOrFail($validatedData['id_peminjaman']);
        $peminjaman->update(['status' => 'Dikembalikan']);

        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Admin mencatat pengembalian alat (Peminjaman ID: ' . $validatedData['id_peminjaman'] . ').'
        ]);

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil ditambahkan.');
    }

    public function edit(Request $request, $id_pengembalian)
    {
        $datap = Pengembalian::findOrFail($id_pengembalian);
        $peminjaman = Peminjaman::find($datap->id_peminjaman);

        $peminjamName = '-';
        $alatName = '-';

        if ($peminjaman) {
            $user = User::find($peminjaman->id_user);
            $alat = Alat::find($peminjaman->id_alat);
            if (!$alat) {
                $alat = Alat::where('id_alat', $peminjaman->id_alat)->first();
            }

            $peminjamName = $user->username ?? $user->nama ?? ("User #" . $peminjaman->id_user);
            $alatName = $alat->nama_alat ?? ("Alat #" . $peminjaman->id_alat);
        }

        return view('pengembalian.edit', compact('datap', 'peminjaman', 'peminjamName', 'alatName'));
    }

    public function update(Request $request, $id_pengembalian)
    {
        $validatedData = $request->validate([
            'id_peminjaman'        => 'required|integer',
            'tanggal_pengembalian' => 'required|date',
            'terlambat_hari'       => 'nullable|integer|min:0',
            'denda'                => 'nullable|integer|min:0',
            'kondisi_barang'       => 'nullable|string|max:100',
            'catatan'              => 'nullable|string',
        ]);

        $pengembalian = Pengembalian::findOrFail($id_pengembalian);
        $pengembalian->update($validatedData);

        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Admin memperbarui data pengembalian (ID: ' . $id_pengembalian . ').'
        ]);

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil diubah.');
    }

    public function delete(Request $request, $id_pengembalian)
    {
        $pengembalian = Pengembalian::findOrFail($id_pengembalian);
        $pengembalian->delete();

        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Admin menghapus data pengembalian (ID: ' . $id_pengembalian . ').'
        ]);

        return redirect(route('pengembalian.index'))->with('success', 'Data pengembalian berhasil dihapus.');
    }


    // =========================================================================
    // BAGIAN 2: KHUSUS ROLE PEMINJAM (SISWA)
    // =========================================================================

    public function createPeminjam(Request $request)
    {
        $user = User::current();
        if (!$user) return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');

        $id_user = $user->id ?? $user->id_user;
        $peminjamanAktif = Peminjaman::where('id_user', $id_user)
                                     ->whereIn('status', ['Dipinjam', 'Disetujui', 'dipinjam', 'disetujui'])
                                     ->get();

        foreach ($peminjamanAktif as $p) {
            $p->alat = Alat::where('id_alat', $p->id_alat)->first();
        }

        return view('peminjam.pengembalian', compact('user', 'peminjamanAktif'));
    }

    public function storePeminjam(Request $request)
    {
        $currentUser = User::current();
        if (!$currentUser) return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');

        $validatedData = $request->validate([
            'id_peminjaman'        => 'required|integer',
            'tanggal_pengembalian' => 'required|date',
            'kondisi_barang'       => 'nullable|string|max:100',
            'catatan'              => 'nullable|string',
        ]);

        $validatedData['terlambat_hari'] = 0;
        $validatedData['denda']          = 0;
        $validatedData['kondisi_barang'] = $validatedData['kondisi_barang'] ?? 'Baik';

        Pengembalian::create($validatedData);

        $peminjaman = Peminjaman::findOrFail($validatedData['id_peminjaman']);
        $peminjaman->update(['status' => 'Dikembalikan']);

        LogAktifitas::create([
            'id_user'   => $currentUser->id,
            'aktifitas' => 'Peminjam ' . ($currentUser->username ?? 'Siswa') . ' mengajukan pengembalian alat.'
        ]);

        return redirect(route('peminjam.riwayat'))->with('success', 'Pengajuan pengembalian berhasil dikirim.');
    }


    // =========================================================================
    // BAGIAN 3: KHUSUS ROLE PETUGAS (PANTAU & CETAK LAPORAN)
    // =========================================================================

    public function indexPetugas(Request $request)
    {
        $user = User::current();
        if (!$user) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Pengembalian::orderBy('id_pengembalian', 'desc')->paginate(10);
        
        $peminjaman = Peminjaman::all();
        $users = User::all();
        $alats = Alat::all();

        $peminjamanList = [];
        foreach ($peminjaman as $p) {
            $pid = $p->id_peminjaman ?? $p->id;
            $peminjamanList[$pid] = $p;
        }

        $userList = [];
        foreach ($users as $u) {
            $id = $u->id ?? $u->id_user;
            $userList[$id] = $u->username ?? $u->nama ?? "User #{$id}";
        }

        $alatList = [];
        foreach ($alats as $a) {
            $id = $a->id_alat ?? $a->id;
            $alatList[$id] = $a->nama_alat ?? "Alat #{$id}";
        }

        return view('petugas.pantau_pengembalian', compact('datap', 'peminjamanList', 'userList', 'alatList'));
    }

    public function cetakPetugas(Request $request)
    {
        $user = User::current();
        if (!$user) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Pengembalian::orderBy('id_pengembalian', 'desc')->get();
        
        $peminjaman = Peminjaman::all();
        $users = User::all();
        $alats = Alat::all();

        $peminjamanList = [];
        foreach ($peminjaman as $p) {
            $pid = $p->id_peminjaman ?? $p->id;
            $peminjamanList[$pid] = $p;
        }

        $userList = [];
        foreach ($users as $u) {
            $id = $u->id ?? $u->id_user;
            $userList[$id] = $u->username ?? $u->nama ?? "User #{$id}";
        }

        $alatList = [];
        foreach ($alats as $a) {
            $id = $a->id_alat ?? $a->id;
            $alatList[$id] = $a->nama_alat ?? "Alat #{$id}";
        }

       return view('petugas.cetak_laporan', compact('datap', 'peminjamanList', 'userList', 'alatList', 'user'));
    }
}