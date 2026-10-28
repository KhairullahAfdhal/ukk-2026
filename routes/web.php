<?php

use App\Controllers\Core\AuthController;
use App\Controllers\Core\DashboardController;
use App\Controllers\Core\DatabaseController;
use App\Controllers\Core\DocsController;
use App\Controllers\Core\RoleController;
use App\Controllers\Core\UserController;
use App\Controllers\KategoriController;
use App\Controllers\BukuController;
use App\Controllers\PeminjamanController;
use App\Controllers\PengembalianController;
use App\Controllers\LogAktifitasController;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\Pengembalian;
use Sakuci\Route;

Route::get('/', function () {
    $buku = [];
    $namaBuku = [];
    foreach (Buku::all() as $b) {
        $buku[] = $b;
        $namaBuku[$b->id_buku] = $b->nama_buku;
    }

    $dipinjam = [];
    $jatuhTempo = 0;
    $batas = date('Y-m-d', strtotime('+7 days'));
    foreach (Peminjaman::orderBy('tanggal_pinjam', 'desc')->get() as $p) {
        if (in_array($p->status, ['Dipinjam', 'Disetujui'])) {
            $p->nama_buku = $namaBuku[$p->id_buku] ?? '-';
            $dipinjam[] = $p;
            if ($p->tanggal_kembali && $p->tanggal_kembali <= $batas) {
                $jatuhTempo++;
            }
        }
    }

    $denda = 0;
    foreach (Pengembalian::all() as $r) {
        $denda += (int) $r->denda;
    }

    return view('welcome', [
        'sedangDipinjam' => count($dipinjam),
        'jatuhTempo'     => $jatuhTempo,
        'denda'          => $denda,
        'dipinjam'       => $dipinjam,
        'bukuTerbaru'    => array_slice(array_reverse($buku), 0, 5),
    ]);
})->name('home');

Route::get('/docs', [DocsController::class, 'index'])->name('docs');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.attempt')->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// ==========================================
// ROLE: ADMIN
// ==========================================
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function () {
    Route::get('/', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('/roles', [RoleController::class, 'store'])->name('admin.roles.store');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');

    Route::get('/database/export', [DatabaseController::class, 'export'])->name('admin.database.export');
    
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori/store', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id_kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id_kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id_kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');

    // ROUTE ALAT (Disetujui untuk POST pada Hapus & Update sesuai Controller)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
    Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
    Route::post('/buku/store', [BukuController::class, 'store'])->name('buku.store');
    Route::get('/buku/{id_buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::post('/buku/{id_buku}/delete', [BukuController::class, 'delete'])->name('buku.delete');
    Route::put('/buku/{id_buku}', [BukuController::class, 'update'])->name('buku.update');
    Route::delete('/buku/{id_buku}', [BukuController::class, 'destroy'])->name('buku.destroy');

    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::post('/peminjaman/{id}/update', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::post('/peminjaman/{id}/delete', [PeminjamanController::class, 'delete'])->name('peminjaman.delete');

    Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
    Route::get('/pengembalian/{id_peminjaman}/edit', [PengembalianController::class, 'edit'])->name('pengembalian.edit');
    Route::post('/pengembalian/{id_peminjaman}/update', [PengembalianController::class, 'update'])->name('pengembalian.update');
    Route::post('/pengembalian/{id_peminjaman}/delete', [PengembalianController::class, 'delete'])->name('pengembalian.delete');

    Route::get('/log-aktifitas', [LogAktifitasController::class, 'index'])->name('admin.log.index');
    Route::get('/laporan', [PengembalianController::class, 'cetak'])->name('admin.laporan.cetak');
});

// ==========================================
// ROLE: PEMINJAM 
// ==========================================
Route::group(['prefix' => 'peminjam', 'middleware' => 'siswa'], function () {
    Route::get('/', [DashboardController::class, 'peminjam'])->name('peminjam.dashboard');
    Route::get('/daftar-alat', [AlatController::class, 'daftarAlatPeminjam'])->name('peminjam.alat');
    Route::get('/peminjam', [PeminjamanController::class, 'indexPeminjam'])->name('peminjam.indexPeminjam');
    Route::get('/peminjam/ajukan', [PeminjamanController::class, 'createPeminjam'])->name('peminjam.createPeminjam');
    Route::post('/peminjam/ajukan', [PeminjamanController::class, 'storePeminjam'])->name('peminjam.storePeminjam');
    Route::get('/riwayat-peminjaman', [PeminjamanController::class, 'indexPeminjam'])->name('peminjam.riwayat');
    Route::get('/pengembalian/ajukan', [PengembalianController::class, 'createPeminjam'])->name('peminjam.pengembalian.create');
    Route::post('/pengembalian/store', [PengembalianController::class, 'storePeminjam'])->name('peminjam.pengembalian.store');
});

// ==========================================
// ROLE: PETUGAS
// ==========================================
Route::group(['prefix' => 'petugas', 'middleware' => 'petugas'], function () {
    Route::get('/', [DashboardController::class, 'petugas'])->name('petugas.dashboard');
    Route::get('/peminjaman', [PeminjamanController::class, 'indexPetugas'])->name('petugas.peminjaman.index');
    Route::post('/peminjaman/{id}/setujui', [PeminjamanController::class, 'setujuiPetugas'])->name('petugas.peminjaman.setujui');
    Route::get('/pengembalian', [PengembalianController::class, 'indexPetugas'])->name('petugas.pengembalian.index');
    Route::get('/pengembalian/cetak', [PengembalianController::class, 'cetak'])->name('petugas.pengembalian.cetak');
});