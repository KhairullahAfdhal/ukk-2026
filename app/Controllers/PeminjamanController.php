<?php

namespace App\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\LogAktifitas;
use Sakuci\Http\Request;

class PeminjamanController
{
    // ==========================================
    // BAGIAN ADMIN: Kelola Semua Peminjaman
    // ==========================================

    public function index(Request $request)
    {
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(4);
        return view('peminjaman.index', compact('datap'));
    }

    public function create(Request $request)
    {
        $buku = Buku::all();
        $users = User::all();
        return view('peminjaman.create', compact('buku', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        
        if (!isset($data['status']) || empty($data['status'])) {
            $data['status'] = 'Pending';
        }

        Peminjaman::create($data);

        // Pencatatan Log Aktivitas Admin
        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Admin menambahkan data peminjaman baru.'
        ]);

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil ditambahkan!');
    }

    public function edit(Request $request, $id)
    {
        $data = Peminjaman::where('id_peminjaman', $id)->first();
        if (!$data) {
            return redirect(route('peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $alat = Alat::all();
        $users = User::all();
        return view('peminjaman.edit', compact('data', 'alat', 'users'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();
        if (!$peminjaman) {
            return redirect(route('peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $input = $request->all();

        if (empty($input['id_alat'])) {
            $input['id_alat'] = $peminjaman->id_alat;
        }
        if (empty($input['id_user'])) {
            $input['id_user'] = $peminjaman->id_user;
        }

        $peminjaman->update($input);

        // Pencatatan Log Aktivitas Admin
        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Admin memperbarui data transaksi peminjaman (ID: ' . $id . ').'
        ]);

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil diperbarui!');
    }

    public function delete(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();
        if ($peminjaman) {
            $peminjaman->delete();

            // Pencatatan Log Aktivitas Admin
            $currentUser = User::current();
            LogAktifitas::create([
                'id_user'   => $currentUser->id ?? null,
                'aktifitas' => 'Admin menghapus data peminjaman (ID: ' . $id . ').'
            ]);
        }

        return redirect(route('peminjaman.index'))->with('success', 'Data peminjaman berhasil dihapus!');
    }


    // ==========================================
    // BAGIAN PEMINJAM / SISWA: Pengajuan Mandiri
    // ==========================================

    public function indexPeminjam(Request $request)
    {
        $currentUser = User::current();
        
        if (!$currentUser) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $datap = Peminjaman::where('id_user', $currentUser->id)
                           ->orderBy('id_peminjaman', 'desc')
                           ->paginate(10);

        return view('peminjam.index_peminjam', compact('datap'));
    }

    public function createPeminjam(Request $request)
    {
        $alat = Alat::all();
        return view('peminjam.create_peminjam', compact('alat'));
    }

    public function storePeminjam(Request $request)
    {
        $currentUser = User::current();
        
        if (!$currentUser) {
            return redirect(route('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        $data = $request->all();
        
        // Memastikan id_user dikunci menggunakan data session login (mencegah manipulasi input)
        $data['id_user'] = $currentUser->id;
        $data['status'] = 'Pending';

        Peminjaman::create($data);

        // Pencatatan Log Aktivitas Siswa
        LogAktifitas::create([
            'id_user'   => $currentUser->id,
            'aktifitas' => 'Peminjam ' . ($currentUser->username ?? 'Siswa') . ' mengajukan permohonan peminjaman alat baru.'
        ]);

        // DIUBAH KE rute riwayat yang sesuai web.php
        return redirect(route('peminjam.riwayat'))->with('success', 'Pengajuan peminjaman berhasil dikirim dan masuk ke daftar riwayat!');
    }


    // ==========================================
    // BAGIAN PETUGAS: Verifikasi & Persetujuan
    // ==========================================

    public function indexPetugas(Request $request)
    {
        $datap = Peminjaman::orderBy('id_peminjaman', 'desc')->paginate(10);
        
        foreach ($datap as $item) {
            $item->data_alat = Alat::where('id_alat', $item->id_alat)->first();
            $item->data_user = User::where('id', $item->id_user)->first();
        }

        return view('petugas.peminjaman', compact('datap'));
    }

    public function setujuiPetugas(Request $request, $id)
    {
        $peminjaman = Peminjaman::where('id_peminjaman', $id)->first();

        if (!$peminjaman) {
            return redirect(route('petugas.peminjaman.index'))->with('error', 'Data peminjaman tidak ditemukan.');
        }

        $peminjaman->update([
            'status' => 'Disetujui'
        ]);

        // Pencatatan Log Aktivitas Petugas
        $currentUser = User::current();
        LogAktifitas::create([
            'id_user'   => $currentUser->id ?? null,
            'aktifitas' => 'Petugas menyetujui transaksi peminjaman (ID: ' . $id . ').'
        ]);

        return redirect(route('petugas.peminjaman.index'))->with('success', 'Peminjaman alat berhasil disetujui!');
    }
}